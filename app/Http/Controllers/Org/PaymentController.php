<?php

namespace App\Http\Controllers\Org;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Org;
use App\Models\Member;
use App\Models\Service;
use App\Models\Reading;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsHistoryExport;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
    protected $_param;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        $id = \Route::current()?->parameter('id');
        if ($id) {
            $this->org = Org::find($id);
        }
    }

    /**
     * Show the application dashboard.
     * @param int $org_id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $org_id)
    {
        $org = $this->org;

        $year = $request->input('year');
        $month = $request->input('month');
        $sector = $request->input('sector');
        $search = $request->input('search');

        if (!$org) {
            return redirect()->route('orgs.index')->with('error', 'Organización no encontrada.');
        }


        // Orden dinámico por columna y sentido
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');

        $members = Reading::join('members', 'readings.member_id', 'members.id')
            ->join('services', 'services.id', 'readings.service_id')
            ->where('services.org_id', $org->id)
            ->where('readings.payment_status', 0)
            ->when($year && $year !== '', function($q) use ($year) {
                $q->where('readings.period','like',$year.'%');
            })
            ->when($month && $month !== '', function($q) use ($month) {
                $q->where('readings.period','like','%'.$month);
            })
            ->when($sector, function($q) use ($sector) {
                $q->where('services.locality_id',$sector);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('members.first_name', 'like', "%{$search}%")
                      ->orWhere('members.last_name', 'like', "%{$search}%")
                      ->orWhere('members.rut', 'like', "%{$search}%")
                      ->orWhere('members.address', 'like', "%{$search}%");
                });
            })
            ->select(
                'members.id',
                'members.rut',
                'members.first_name',
                'members.last_name',
                'members.address',
                'members.phone',
                'members.email',
                DB::raw('COUNT(readings.id) as qrx_serv'),
                DB::raw('COUNT(DISTINCT readings.id) as qrx_readings'),
                DB::raw('SUM(readings.total) as total_amount'),
                DB::raw('GROUP_CONCAT(DISTINCT readings.period ORDER BY readings.period SEPARATOR ", ") as pending_periods')
            )
            ->groupBy('members.id',
                'members.rut',
                'members.first_name',
                'members.last_name',
                'members.address',
                'members.phone',
                'members.email'
            )
            ->orderBy($sort, $order)
            ->get();

        $locations = Location::where('org_id', $org->id)->get();
        return view('orgs.payments.index', compact('org', 'members', 'locations'));
    }

     // Mostrar el formulario de pago
    public function create($org_id)
    {
        // Obtener la organización, servicios, etc.
        $org = Org::findOrFail($org_id);
        $services = Service::all();
        return view('orgs.payments.create', compact('org', 'services'));
    }

    // Procesar el pago
    /*public function store(Request $request, $org_id)
    {
        $org = Org::findOrFail($org_id);
        $user = Member::where('rut', $request->input('dni'))->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        $payment_method_id = $request->input('payment_method_id');
        if($payment_method_id == 1 OR $payment_method_id == 2 OR $payment_method_id == 3){
            $payment_status = 1;
        }else{
            $payment_status= 0;
        }

        // Crear la nueva orden
        $order = new Order();
        $order->order_code = Str::upper(Str::random(9));
        $order->org_id = $org_id;
        $order->dni = $request->input('dni');
        $order->name = $user->first_name . ' ' . $user->last_name;
        $order->email = $user->email;
        $order->phone = $user->phone;
        $order->status = 1; // Estado de la orden (pendiente)
        $order->payment_method_id = $payment_method_id;
        $order->payment_status = $payment_status;
        $order->save();

        $order_id = $order->id;
        $docs = $request->input('doc');
        $services = $request->input('services');

        //dd($services);
        $qty=0;
        $sum=0;
        foreach($services as $key=>$service){
            $service_id = $service;
            $service = Service::find($service_id);
            $reading = Reading::where('service_id',$service_id)->where('payment_status',0)->orderby('period','DESC')->first();

            $qty++;
            $order_items = new OrderItem;
            $order_items->order_id = $order_id;
            $order_items->org_id = $reading->org_id;
            $order_items->member_id = $reading->member_id;
            $order_items->service_id = $reading->service_id;
            $order_items->reading_id  = $reading->id;
            $order_items->locality_id = $service->locality_id;
            $order_items->folio=$reading->folio;
            $order_items->type_dte = ($service->invoice_type=='factura' ? 'Factura' :'Boleta');
            $order_items->price=$reading->total_mounth;
            $order_items->total=$reading->total;
            $order_items->status=1;
            $order_items->payment_method_id = $payment_method_id;
            $order_items->description="Pago de servicio nro <b>".Str::padLeft($service->nro,5,0)."</b> , Periodo <b>".$reading->period."</b>, lectura <b>".$reading->id."</b>";
            $order_items->payment_status = $payment_status;
            $order_items->save();
            $sumTotal=+$reading->total;

            $reading->payment_status = $payment_status;
            $reading->save();

            // --- Registro automático en contabilidad ---
            $conceptos_map = [
                'vcf_water'        => ['categoria' => 'agua', 'grupo' => 'servicio'],
                'vc_water'         => ['categoria' => 'agua', 'grupo' => 'servicio'],
                'vs_consumption'   => ['categoria' => 'agua', 'grupo' => 'servicio'],
                'vcf_alca'         => ['categoria' => 'cargo_fijo', 'grupo' => 'servicio'],
                'vc_alca'          => ['categoria' => 'cargo_fijo', 'grupo' => 'servicio'],
                'vcfa_serv'        => ['categoria' => 'cargo_fijo', 'grupo' => 'servicio'],
                'vca_serv'         => ['categoria' => 'cargo_fijo', 'grupo' => 'servicio'],
                'v_subs'           => ['categoria' => 'subsidio', 'grupo' => 'descuento'],
                'vc_social'        => ['categoria' => 'subsidio', 'grupo' => 'descuento'],
                'multas_vencidas'  => ['categoria' => 'multas', 'grupo' => 'penalización'],
                'other'            => ['categoria' => 'otros', 'grupo' => 'otros'],
                'corte_reposicion' => ['categoria' => 'corte_reposicion', 'grupo' => 'penalización'],
                'v_admin'          => ['categoria' => 'administrativo', 'grupo' => 'servicio'],
                's_previous'       => ['categoria' => 'saldo_anterior', 'grupo' => 'otros'],
                'tax'              => ['categoria' => 'iva', 'grupo' => 'impuesto'],
            ];

            foreach ($conceptos_map as $campo => $info) {
                $monto = $reading->$campo ?? 0;
                if ($monto > 0) {
                    \App\Models\ContableIngreso::create([
                        'order_id'   => $order->id,
                        'reading_id' => $reading->id,
                        'fecha'      => now(),
                        'monto'      => $monto,
                        'categoria'  => $info['categoria'],
                        'tipo'       => ($info['categoria'] == 'subsidio' ? 'descuento' : 'ingreso'),
                        'grupo'      => $info['grupo'],
                        'detalle'    => $info['categoria'] . ' - ' . $monto,
                        'usuario_id' => $user->id,
                    ]);
                }
            }
        }

        $orderU = Order::findOrFail($order_id);
        $orderU->qty = $qty;
        $orderU->total = $sumTotal;
        $orderU->save();

        // Redirigir a la página de detalles de la orden
        return redirect()->route('checkout-order-code', [$order->order_code]);
    }*/

    public function update(Request $request, $id, $reading_id)
    {
        $request->validate([
        'current_reading' => 'required|numeric|min:0'
        ]);

            // Se utiliza el parámetro $id en lugar de $org_id

            $reading = Reading::findOrFail($reading_id);
            $reading->current_reading = $request->current_reading;
            $reading->payment_status = 1; // Pagado

            // Actualizar montos desde el request si están presentes
            $campos_montos = [
                'vcf_water', 'vc_water', 'vs_consumption', 'vcf_alca', 'vc_alca', 'vcfa_serv', 'vca_serv',
                'v_subs', 'vc_social', 'multas_vencidas', 'other', 'corte_reposicion', 'v_admin',
                's_previous', 'tax', 'total_mounth', 'total', 'sub_total'
            ];
            foreach ($campos_montos as $campo) {
                if ($request->has($campo)) {
                    $reading->$campo = $request->input($campo);
                }
            }
            $reading->save();

            // --- Registro automático en contabilidad ---
            $conceptos_map = [
                'vcf_water'        => ['categoria' => 'agua', 'grupo' => 'servicio'],
                'vc_water'         => ['categoria' => 'agua', 'grupo' => 'servicio'],
                'vs_consumption'   => ['categoria' => 'agua', 'grupo' => 'servicio'],
                'vcf_alca'         => ['categoria' => 'cargo_fijo', 'grupo' => 'servicio'],
                'vc_alca'          => ['categoria' => 'cargo_fijo', 'grupo' => 'servicio'],
                'vcfa_serv'        => ['categoria' => 'cargo_fijo', 'grupo' => 'servicio'],
                'vca_serv'         => ['categoria' => 'cargo_fijo', 'grupo' => 'servicio'],
                'v_subs'           => ['categoria' => 'subsidio', 'grupo' => 'descuento'],
                'vc_social'        => ['categoria' => 'subsidio', 'grupo' => 'descuento'],
                'multas_vencidas'  => ['categoria' => 'multas', 'grupo' => 'penalización'],
                'other'            => ['categoria' => 'otros', 'grupo' => 'otros'],
                'corte_reposicion' => ['categoria' => 'corte_reposicion', 'grupo' => 'penalización'],
                'v_admin'          => ['categoria' => 'administrativo', 'grupo' => 'servicio'],
                's_previous'       => ['categoria' => 'saldo_anterior', 'grupo' => 'otros'],
                'tax'              => ['categoria' => 'iva', 'grupo' => 'impuesto'],
            ];

            $user = null;
            if (auth()->check()) {
                $user = auth()->user();
            } else if ($reading->member_id) {
                $user = \App\Models\Member::find($reading->member_id);
            }

            foreach ($conceptos_map as $campo => $info) {
                $monto = $reading->$campo ?? 0;
                if ($monto > 0) {
                    \App\Models\ContableIngreso::create([
                        'order_id'   => null,
                        'reading_id' => $reading->id,
                        'fecha'      => now(),
                        'monto'      => $monto,
                        'categoria'  => $info['categoria'],
                        'tipo'       => ($info['categoria'] == 'subsidio' ? 'descuento' : 'ingreso'),
                        'grupo'      => $info['grupo'],
                        'detalle'    => $info['categoria'] . ' - ' . $monto,
                        'usuario_id' => $user ? $user->id : null,
                    ]);
                }
            }

        return response()->json([
            'success' => true,
            'new_reading' => $reading->current_reading
            ]);
    }

    public function showServices($orgId, $rut)
    {
       // dd($rut);
        // Obtener la organización usando Query Builder
        $org = $this->org;

        if (!$org) {
            abort(404, 'Organization not found');
        }
        $member = Member::where('rut',$rut)->first();
        if (!$member) {
            abort(404, 'Member not found');
        }

        // Información básica del miembro
        \Log::info('PaymentController - Procesando servicios para', [
            'rut' => $rut,
            'member_id' => $member->id
        ]);

        // Buscar una posible orden asociada al miembro y la organización
        $order = Order::where('dni', $member->rut)->first();

        // Si no hay una orden, crear un código de orden aleatorio
        $order_code = $order ? $order->order_code : Str::upper(Str::random(9));

        $members = Member::where('rut',$rut)->first();

        $services = Reading::join('services','services.id','readings.service_id')
                        ->where('readings.member_id', $members->id)
                        ->where('readings.org_id', $orgId) // Filtrar por organización
                        ->where('services.org_id', $orgId) // Asegurar que el servicio también pertenezca a la org
                        ->where('readings.payment_status', 0)
                        ->where('readings.total', '>', 0)
                        ->select(
                            'readings.id as reading_id',
                            'readings.service_id',
                            'services.nro',
                            'services.sector',
                            'readings.total as total_sum', // Total individual de cada lectura
                            'readings.payment_status',
                            'readings.period',
                            'readings.folio'
                        )
                        ->orderBy('readings.service_id', 'DESC')
                        ->orderBy('readings.period', 'DESC') // Mostrar primero los meses más recientes
                        ->get();

        \Log::info('PaymentController - Servicios encontrados', [
            'count' => $services->count(),
            'member_id' => $members->id
        ]);

        // Pasar la organización, los servicios y el RUT a la vista
        return view('orgs.payments.services', compact('order_code','org', 'member','services', 'rut'));
    }

    public function history(Request $request, $org_id)
    {
        $org = $this->org;

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $sector = $request->input('sector');
        $search = $request->input('search');

        if (!$org) {
            return redirect()->route('orgs.index')->with('error', 'Organización no encontrada.');
        }

        $order_items = OrderItem::join('orders','order_items.order_id','orders.id')
            ->join('readings', 'order_items.reading_id', 'readings.id')
            ->join('members', 'order_items.member_id', 'members.id')
            ->join('services', 'order_items.service_id', 'services.id')
            ->where('readings.org_id', $org_id)
            ->when($start_date && $end_date, function($q) use ($start_date, $end_date) {
                $q->whereDate('order_items.created_at', '>=', $start_date)
                  ->whereDate('order_items.created_at', '<=', $end_date);
            })
            ->when($sector, function($q) use ($sector) {
                    $q->where('services.locality_id',$sector);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    // Especificamos de qué tabla debe venir cada columna
                    $q->where('members.first_name', 'like', "%{$search}%")
                      ->orWhere('members.last_name', 'like', "%{$search}%")
                      ->orWhere('members.rut', 'like', "%{$search}%")
                      ->orWhere('members.address', 'like', "%{$search}%");
                });
            })
            ->select('order_items.*','orders.order_code')
            ->orderby('order_items.id','DESC')
            ->paginate(20)
                 ->withQueryString();

        foreach ($order_items as $item) {
            $item->member = Member::find($item->member_id);
            $item->service = Service::find($item->service_id);
            $item->reading = Reading::find($item->reading_id);
            $item->location = Location::find($item->locality_id);
            $item->payment_method = PaymentMethod::find($item->payment_method_id);
        }
        $locations = Location::where('org_id', $org->id)->get();

        return view('orgs.payments.history', compact('org', 'order_items','locations'));
    }

    public function debugReadingStatus($orgId, $rut)
    {
        $member = Member::where('rut', $rut)->first();
        
        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        $pendingReadings = Reading::where('member_id', $member->id)
            ->where('org_id', $orgId)
            ->where('payment_status', 0)
            ->where('total', '>', 0)
            ->get();

        $paidReadings = Reading::where('member_id', $member->id)
            ->where('org_id', $orgId)
            ->where('payment_status', 1)
            ->get();

        return response()->json([
            'member' => [
                'id' => $member->id,
                'name' => $member->first_name . ' ' . $member->last_name,
                'rut' => $member->rut
            ],
            'pending_readings_count' => $pendingReadings->count(),
            'paid_readings_count' => $paidReadings->count(),
            'pending_readings' => $pendingReadings->map(function($reading) {
                return [
                    'id' => $reading->id,
                    'period' => $reading->period,
                    'total' => $reading->total,
                    'payment_status' => $reading->payment_status
                ];
            }),
            'recent_paid_readings' => $paidReadings->sortByDesc('updated_at')->take(5)->map(function($reading) {
                return [
                    'id' => $reading->id,
                    'period' => $reading->period,
                    'total' => $reading->total,
                    'payment_status' => $reading->payment_status,
                    'updated_at' => $reading->updated_at
                ];
            })
        ]);
    }

    public function debugValuesComparison($orgId)
    {
        // Obtener una lectura de ejemplo
        $reading = Reading::where('payment_status', 0)
            ->where('total', '>', 0)
            ->where('org_id', $orgId)
            ->first();
            
        if (!$reading) {
            return response()->json(['error' => 'No readings found']);
        }

        // Verificar cómo se ve el valor en diferentes contextos
        $data = [
            'reading_data' => [
                'id' => $reading->id,
                'total' => $reading->total,
                'total_type' => gettype($reading->total),
                'total_raw' => $reading->getOriginal('total'),
                'period' => $reading->period
            ],
            'formatted_for_view' => [
                'formatted_clp' => number_format($reading->total, 0, ',', '.'),
                'money_helper' => '$' . number_format($reading->total, 0, ',', '.')
            ]
        ];

        return response()->json($data);
    }

    public function debugOrderValues($orgId, $orderCode)
    {
        $order = Order::where('order_code', $orderCode)->first();
        
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $items = OrderItem::where('order_id', $order->id)->get();
        
        $debug_data = [
            'order_info' => [
                'order_code' => $order->order_code,
                'total' => $order->total,
                'qty' => $order->qty
            ],
            'items_from_order' => $items->map(function($item) {
                return [
                    'id' => $item->id,
                    'reading_id' => $item->reading_id,
                    'price' => $item->price,
                    'total' => $item->total,
                    'type_dte' => $item->type_dte
                ];
            }),
            'readings_original_values' => $items->map(function($item) {
                $reading = Reading::find($item->reading_id);
                return [
                    'reading_id' => $item->reading_id,
                    'original_total' => $reading ? $reading->total : 'N/A',
                    'saved_total' => $item->total,
                    'matches' => $reading ? ($reading->total == $item->total) : false
                ];
            })
        ];

        return response()->json($debug_data);
    }

    public function export()
    {
        return Excel::download(new PaymentsExport, 'pagos-'.date('Ymdhis').'.xlsx');
    }

    public function exportHistory($id)
    {
        return Excel::download(new PaymentsHistoryExport, 'pagos-History-' . date('Ymdhis') . '.xlsx');
    }
}
