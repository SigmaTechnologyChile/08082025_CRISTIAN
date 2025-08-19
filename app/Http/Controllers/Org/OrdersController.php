<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\Reading;
use App\Models\Member;
use App\Models\Org;
use Illuminate\Support\Str;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    protected $_param;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, $org_id)
    {
        $validated = $request->validate([
            'services' => 'required|array',
            'services.*' => 'exists:readings,id',
            'payment_method_id' => 'required|string|in:1,2,3',
        ]);

        if (!$request->has('services') || empty($request->services)) {
            return redirect()->back()->with('error', 'Debes seleccionar al menos un servicio');
        }

        // Buscar la primera lectura seleccionada
        $firstReading = Reading::find($validated['services'][0]);
        if (!$firstReading) {
            return redirect()->back()->with('error', 'No se encontró la lectura seleccionada');
        }

        // Obtener el servicio desde la lectura
        $firstService = Service::find($firstReading->service_id);
        if (!$firstService) {
            return redirect()->back()->with('error', 'No se encontró el servicio asociado a la lectura');
        }

        $member = Member::find($firstReading->member_id);
        if (!$member) {
            return redirect()->back()->with('error', 'No se encontró el socio asociado a la lectura');
        }

        // Crear la orden principal
        $order = $this->createMainOrder($request, $member);

        // Procesar lecturas seleccionadas
        $this->processOrderItemsFromReadings($request, $order, $member);

        // Actualizar totales de la orden
        $this->updateOrderTotals($order);

        return redirect()->route('orgs.voucher.create', ['id' => $org_id, 'order_code' => $order->order_code]);
    }

    public function show($org_id, $order_code)
    {
        $org = Org::findOrFail($org_id);
        $order = Order::with('items')->where('order_code', $order_code)->firstOrFail();
        $items = $order->items;

        return view('orgs.orders.show', compact('org', 'order', 'items'));
    }

    private function createMainOrder(Request $request, Member $member): Order
    {
        $payment_method_id = $request->input('payment_method_id');
        $payment_status = in_array($payment_method_id, [1, 2, 3]) ? 1 : 0;

        $order = Order::create([
            'order_code' => Str::upper(Str::random(9)),
            'dni' => $member->rut,
            'name' => $member->full_name,
            'email' => $member->email,
            'phone' => $member->phone,
            'payment_method_id' => $payment_method_id,
        ]);

        return $order;
    }

    private function calculateItemTotal(Reading $reading): float
    {
        if ($reading->invoice_type == 'factura') {
            return $reading->total * 1.19; // Agrega IVA
        }
        return $reading->total;
    }

    private function updateOrderTotals(Order $order): void
    {
        $total = $order->items->sum('total');
        $order->total = $total;
        $order->save();
    }

    private function getPaymentMethodId(string $paymentMethod): int
    {
        $method = PaymentMethod::where('title', $paymentMethod)->first();
        return $method ? $method->id : 0;
    }

    private function processOrderItemsFromReadings(Request $request, Order $order, Member $member): void
    {
        $payment_method_id = $request->input('payment_method_id');
        $payment_status = in_array($payment_method_id, [1, 2, 3]) ? 1 : 0;

        $sumTotal = 0;
        $qty = 0;

        foreach ($request->input('services') as $readingId) {
            $reading = Reading::find($readingId);
            if (!$reading) continue;

            $qty++;
            $totalItem = $this->calculateItemTotal($reading);
            $service = Service::find($reading->service_id);

            OrderItem::create([
                'order_id' => $order->id,
                'org_id' => $reading->org_id,
                'member_id' => $reading->member_id,
                'service_id' => $reading->service_id,
                'reading_id' => $reading->id,
                'locality_id' => $reading->locality_id,
                'folio' => $reading->folio,
                'type_dte' => $reading->invoice_type == 'factura' ? 'Factura' : 'Boleta',
                'price' => $reading->total,
                'total' => $totalItem,
                'status' => 1,
                'payment_method_id' => $payment_method_id,
                'description' => $service ? ("Pago de servicio nro <b>" . str_pad($service->nro, 5, '0', STR_PAD_LEFT) . "</b>, Periodo <b>" . $reading->period . "</b>") : "Pago de servicio",
                'payment_status' => $payment_status
            ]);

            $sumTotal += $reading->total;
            $reading->payment_status = $payment_status;
            $reading->save();
        }

        // Actualizar la cantidad y el total en la orden
        $order->qty = $qty;
        $order->total = $sumTotal;
        $order->save();
    }
}