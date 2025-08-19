<?php
namespace App\Http\Controllers\Org;

use App\Exports\ReadingsExport;
use App\Exports\ReadingsHistoryExport;
use App\Http\Controllers\Controller;
use App\Models\FixedCostConfig;
use App\Models\Location;
use App\Models\Member;
use App\Models\Org;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Models\Reading;
use App\Models\Section;
use App\Models\Service;
use App\Models\TierConfig;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ReadingController extends Controller
{
    protected $_param;

    public function __construct()
    {
        $this->middleware('auth');
        // No accedas a parámetros de ruta aquí. Hazlo en los métodos que lo requieran.
    }

    /**
     * Show the application dashboard.
     * @param int $org_id
     * @return \Illuminate\Contracts\Support\Renderable
     */
public function index($org_id, Request $request)
{
    $org = Org::find($org_id);
    if (! $org) {
        return redirect()->back()->with('error', 'Organización no encontrada.');
    }

    $locations = DB::table('locations')
        ->where('org_id', $org_id)
        ->orderBy('order_by', 'ASC')
        ->get();

    // Obtener los sectores para el selector
    $sectores = $locations;

    $sector = $request->input('sector');
    $search = $request->input('search');
    $year   = $request->input('year');
    $month  = $request->input('month');

    // Si no se especifica año/mes, usar el actual
    if (!$year || !$month) {
        $year = date('Y');
        $month = date('m');
    }
    if ($month && strlen($month) == 1) {
        $month = '0' . $month;
    }

    $currentPeriod = "$year-$month";
    
    // Calcular el período anterior
    $previousMonth = $month - 1;
    $previousYear = $year;
    if ($previousMonth <= 0) {
        $previousMonth = 12;
        $previousYear = $year - 1;
    }
    $previousMonth = str_pad($previousMonth, 2, '0', STR_PAD_LEFT);
    $previousPeriod = "$previousYear-$previousMonth";

    // Obtener todos los servicios con sus datos de miembros
    $query = DB::table('services')
        ->join('members', 'services.member_id', '=', 'members.id')
        ->leftJoin('locations', 'services.locality_id', '=', 'locations.id')
        ->where('services.org_id', $org_id);

    if ($sector && $sector != '0') {
        $query->where('services.locality_id', $sector);
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('members.rut', 'like', "%{$search}%")
              ->orWhere('members.full_name', 'like', "%{$search}%");
        });
    }

    $readings = $query->select(
            'services.id as service_id',
            'services.nro',
            'services.member_id',
            'members.id as member_id',
            'members.rut',
            'members.full_name',
            'locations.name as location_name'
        )
        ->orderBy('services.nro')
        ->get();

    // Obtener IDs de servicios para las subconsultas
    $serviceIds = $readings->pluck('service_id')->toArray();
    
    if (!empty($serviceIds)) {
        // Obtener lecturas anteriores
        $previousReadings = DB::table('readings')
            ->whereIn('service_id', $serviceIds)
            ->where('period', $previousPeriod)
            ->pluck('current_reading', 'service_id');

        // Obtener lecturas actuales
        $currentReadings = DB::table('readings')
            ->whereIn('service_id', $serviceIds)
            ->where('period', $currentPeriod)
            ->get()
            ->keyBy('service_id');

        // Agregar los datos a cada reading
        foreach ($readings as $reading) {
            $reading->previous_reading = $previousReadings[$reading->service_id] ?? 0;
            
            $currentReading = $currentReadings[$reading->service_id] ?? null;
            if ($currentReading) {
                $reading->id = $currentReading->id;
                $reading->current_reading = $currentReading->current_reading;
                $reading->cm3 = $currentReading->cm3;
                $reading->total = $currentReading->total;
                $reading->corte_reposicion = $currentReading->corte_reposicion;
                $reading->other = $currentReading->other;
                $reading->invoice_type = $currentReading->invoice_type;
            } else {
                $reading->id = null;
                $reading->current_reading = '';
                $reading->cm3 = 0;
                $reading->total = 0;
                $reading->corte_reposicion = 0;
                $reading->other = 0;
                $reading->invoice_type = 'boleta';
            }
            
            $reading->period = $currentPeriod;
            $reading->org_id = $org_id;
        }
    }

    // Obtener los sectores para el selector
    $sectores = DB::table('locations')
        ->where('org_id', $org->id)
        ->orderBy('order_by', 'ASC')
        ->get();

    // Calcular estadísticas de progreso
    $totalServicios = $readings->count();
    $lecturasIngresadas = $readings->filter(function($reading) {
        return $reading->id !== null && $reading->current_reading > 0;
    })->count();
    
    // Calcular estadísticas por tipo de documento
    $lecturasConDatos = $readings->filter(function($reading) {
        return $reading->id !== null && $reading->current_reading > 0;
    });
    
    $boletasPorProcesar = $lecturasConDatos->filter(function($reading) {
        return $reading->invoice_type === 'boleta';
    })->count();
    
    $facturasPorProcesar = $lecturasConDatos->filter(function($reading) {
        return $reading->invoice_type === 'factura';
    })->count();
    
    $progresoStats = [
        'total' => $totalServicios,
        'ingresadas' => $lecturasIngresadas,
        'pendientes' => $totalServicios - $lecturasIngresadas,
        'porcentaje' => $totalServicios > 0 ? round(($lecturasIngresadas / $totalServicios) * 100, 1) : 0,
        'boletas' => $boletasPorProcesar,
        'facturas' => $facturasPorProcesar
    ];

    return view('orgs.readings.index', compact('org', 'readings', 'locations', 'sectores', 'progresoStats'));
}

    public function history($org_id, Request $request)
    {
        $org = Org::find($org_id);
        if (! $org) {
            return redirect()->back()->with('error', 'Organización no encontrada.');
        }

        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');
        $sector     = $request->input('sector');
        $search     = $request->input('search');

        if ($start_date) {
            $start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->format('Y-m');
        }
        if ($end_date) {
            $end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $end_date)->format('Y-m');
        }

        $readings = Reading::join('services', 'readings.service_id', 'services.id')
            ->join('members', 'services.member_id', 'members.id')
            ->where('readings.org_id', $org_id)
            ->when($start_date && $end_date, function ($q) use ($start_date, $end_date) {
                $q->where('readings.period', '>=', $start_date)
                    ->where('readings.period', '<=', $end_date);
            })
            ->when($sector, function ($q) use ($sector) {
                $q->where('services.locality_id', $sector);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('members.rut', $search)
                    ->orWhere('members.full_name', 'like', '%' . $search . '%');
            })
            ->select('readings.*', 'services.nro', 'members.rut', 'members.full_name', 'services.sector as location_name')
            ->orderBy('period', 'desc')->get();

        $locations = Location::where('org_id', $org->id)->orderby('order_by', 'ASC')->get();

        return view('orgs.readings.history', compact('org', 'readings', 'locations'));
    }

    public function current_reading_update($org_id, Request $request)
    {
        // Agregar logging para debugging
        Log::info('Datos recibidos en current_reading_update:', [
            'all_data' => $request->all(),
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method(),
            'org_id' => $org_id
        ]);

        try {
            $request->validate([
                'reading_id'      => 'nullable|numeric',
                'service_id'      => 'nullable|numeric',
                'current_reading' => 'required|numeric|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación en current_reading_update:', [
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);
            if ($request->ajax()) {
                return response()->json(['error' => 'Error de validación: ' . json_encode($e->errors())], 422);
            }
            throw $e;
        }

        try {
            $org = Org::find($org_id);
            if (! $org) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Organización no encontrada.'], 404);
                }
                return redirect()->back()->with('error', 'Organización no encontrada.');
            }

            // Si existe reading_id, es una actualización
            if ($request->reading_id) {
                $reading = Reading::findOrFail($request->reading_id);
                $this->updateReading($org, $reading, $request->only(['current_reading']));
            } 
            // Si no existe reading_id pero sí service_id, es una nueva lectura
            else if ($request->service_id) {
                $reading = $this->createNewReading($org_id, $request->service_id, $request->current_reading);
                $this->updateReading($org, $reading, $request->only(['current_reading']));
            }
            else {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Faltan datos para procesar la lectura.'], 400);
                }
                return redirect()->back()->with('error', 'Faltan datos para procesar la lectura.');
            }

            if ($request->ajax()) {
                return response()->json(['success' => 'Lectura actualizada correctamente']);
            }
            return redirect()->back()->with('success', 'Lectura actualizada correctamente');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Error al actualizar lectura: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('danger', 'Error al actualizar lectura: ' . $e->getMessage());
        }
    }

    public function update($org_id, Request $request)
    {
        $request->validate([
            'reading_id'      => 'required|numeric',
            'current_reading' => 'required|numeric|min:0',
            'multas_vencidas' => 'nullable|numeric|min:0',
        ]);

        try {
            $org     = Org::find($org_id);
            if (! $org) {
                return redirect()->back()->with('error', 'Organización no encontrada.');
            }
            $reading = Reading::findOrFail($request->reading_id);

            $this->updateReading($org, $reading, $request->all());

            return redirect()->back()->with('success', 'Actualización de lectura correcta');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Error al actualizar la lectura: ' . $e->getMessage());
        }
    }

    private function updateReading($org, $reading, $data)
    {
        $tier       = TierConfig::where('org_id', $org->id)->OrderBy('id', 'ASC')->get();
        $configCost = FixedCostConfig::where('org_id', $org->id)->first();

        $reading->previous_reading = $data['previous_reading'] ?? $reading->previous_reading;
        $reading->current_reading  = $data['current_reading'];

        $reading->cm3 = max(0, $reading->current_reading - $reading->previous_reading);

        $service              = Service::findOrFail($reading->service_id);
        $cargo_fijo           = $configCost->fixed_charge_penalty;
        $subsidio             = $service->meter_plan; // 0 o 1
        $porcentaje_subsidio  = $service->percentage / 100;
        $consumo_agua_potable = 0;
        $subsidioDescuento    = 0;
        $cm3                  = $reading->cm3;
        $consumoNormal        = 0;

        $tramos = [];
        foreach ($tier as $t) {
            $tramos[] = [
                'hasta'  => $t->range_to,
                'precio' => $t->value,
            ];
        }
        if (empty($tramos) || end($tramos)['hasta'] < PHP_INT_MAX) {
            $tramos[] = [
                'hasta'  => PHP_INT_MAX,
                'precio' => end($tramos)['precio'] ?? 0,
            ];
        }

        $anterior = 0;
        $restante = $cm3;

        for ($i = 0; $i < count($tramos) && $restante > 0; $i++) {
            $limite = $tramos[$i]['hasta'];
            $precio = $tramos[$i]['precio'];

            $cantidad = min($restante, $limite - $anterior);

            if ($i === 0 && $subsidio != 0) {
                $cantidadSubvencionada = min($configCost->max_covered_m3, $cantidad);
                $cantidadNormal        = $cantidad - $cantidadSubvencionada;
                $precioConSubsidio     = $precio * (1 - $porcentaje_subsidio); // esto es el 0.4 o 0.6? es lo que se descuenta o el total, deverisa ser el descuento, osea el 0.4 el que se muestra
                $consumo_agua_potable += $cantidadSubvencionada * $precioConSubsidio;
                $consumo_agua_potable += $cantidadNormal * $precio;
                $consumoNormal += $cantidad * $precio;
                $subsidioDescuento += $cantidadSubvencionada * ($precio - $precioConSubsidio);
            } else {
                $consumo_agua_potable += $cantidad * $precio;
                $consumoNormal += $cantidad * $precio;
            }

            $restante -= $cantidad;
            $anterior = $limite;
        }

        $reading->vc_water = $consumoNormal;
        $reading->v_subs   = $subsidioDescuento;

        // Manejar las multas vencidas (800 o 1600)
        $multas_vencidas = 0;
        if (isset($data['cargo_mora'])) {
            $multas_vencidas = max($multas_vencidas, $configCost->late_fee_penalty);
        }
        if (isset($data['cargo_vencido'])) {
            $multas_vencidas = max($multas_vencidas, $configCost->expired_penalty);
        }
        $reading->multas_vencidas = $multas_vencidas;

        $reading->corte_reposicion = isset($data['cargo_corte_reposicion']) ?
        ($configCost->replacement_penalty) : 0;
        $reading->other = $data['other'] ?? ($reading->other !== null ? $reading->other : 0);

        $subtotal_consumo_mes  = $consumo_agua_potable + $cargo_fijo;
        $reading->total_mounth = $subtotal_consumo_mes;
        $subTotal              = $subtotal_consumo_mes + $reading->multas_vencidas + $reading->corte_reposicion + $reading->other + ($reading->s_previous ?? 0);
        $reading->sub_total    = $subTotal;

        if ($reading->invoice_type && $reading->invoice_type != "boleta") {
            $iva            = $subTotal * 0.19;
            $reading->total = $subTotal + $iva;
        } else {
            $reading->total = $subTotal;
        }

        $reading->save();
    }

public function dte($id, $readingId)
{
    Log::info('Recibiendo solicitud para DTE con ID org: ' . $id . ' y readingId: ' . $readingId);

    // Obtener organización
    $org = DB::table('orgs')->where('id', $id)->first();
    if (!$org) {
        abort(404, 'Organización no encontrada');
    }

    // Verificar si es una nueva lectura (formato: new-{service_id})
    if (str_starts_with($readingId, 'new-')) {
        $serviceId = str_replace('new-', '', $readingId);
        return $this->handleNewReadingDte($id, $serviceId);
    }

    // Obtener lectura con validación de organización
    $reading = DB::table('readings')
        ->where('id', $readingId)
        ->where('org_id', $id)
        ->first();

    if (!$reading) {
        abort(404, 'Lectura no encontrada o no pertenece a esta organización');
    }

    // Obtener servicio asociado
    $service = DB::table('services')
        ->where('id', $reading->service_id)
        ->first();

    if (!$service) {
        abort(404, 'Servicio no encontrado');
    }

    // Obtener miembro asociado
    $member = DB::table('members')
        ->where('id', $service->member_id)
        ->first();

    if (!$member) {
        abort(404, 'Miembro no encontrado');
    }

    // Añadir datos de miembro y servicio al objeto lectura
    $reading->member = $member;
    $reading->service = $service;

    // Obtener configuración de tramos
    $tier = DB::table('tier_config')
        ->where('org_id', $id)
        ->orderBy('id', 'ASC')
        ->get();


    if ($tier->isEmpty()) {
        Log::error("No se encontraron secciones para la organización con ID: {$id}");
        abort(404, 'No se encontraron secciones.');
    }

    // Obtener configuración de costos fijos
    $configCost = DB::table('fixed_costs_config')
        ->where('org_id', $org->id)
        ->first();

    if (!$configCost) {
        Log::error("No se encontró configuración de costos fijos para la organización con ID: {$org->id}");
        abort(404, 'Configuración de costos fijos no encontrada.');
    }

    // Obtener lectura anterior
    $readingAnterior = DB::table('readings')
        ->where('member_id', $member->id)
        ->where('service_id', $service->id)
        ->where('period', '<', $reading->period)
        ->orderBy('period', 'desc')
        ->first();

    // Cálculo de tramos (igual que antes)
    $consumo = $reading->cm3;
    $detalle_sections = [];
    $consumo_restante = $consumo;
    $anterior = 0;

     foreach ($tier as $index => $tierConfig) {
            if ($consumo_restante <= 0) {
                // Si no queda consumo, este tramo tendrá 0 m3
                $tierConfig->section            = $tierConfig->range_from . " Hasta " . $tierConfig->range_to;
                $tierConfig->m3                 = 0;
                $tierConfig->precio             = $tierConfig->value;
                $tierConfig->total              = 0;
                $tierConfig->total_sin_subsidio = 0;
                $tierConfig->subsidio_aplicado  = 0;
            } else {
                $limite_tramo = $tierConfig->range_to;

                // Si es el último tramo, asignar todo el consumo restante
                $es_ultimo_tramo = ($index == count($tier) - 1);

                if ($es_ultimo_tramo) {
                    // En el último tramo, asignar todo el consumo restante
                    $m3_en_este_tramo = $consumo_restante;
                } else {
                    // En tramos intermedios, calcular la capacidad del tramo
                    $capacidad_tramo  = $limite_tramo - $anterior;
                    $m3_en_este_tramo = min($capacidad_tramo, $consumo_restante);
                }

                $tierConfig->section = $tierConfig->range_from . " Hasta " . $tierConfig->range_to;
                $tierConfig->m3      = $m3_en_este_tramo;
                $tierConfig->precio  = $tierConfig->value;

                // SIEMPRE mostrar el precio completo sin subsidio en la tabla de tramos
                $tierConfig->total = $m3_en_este_tramo * $tierConfig->value;

                // Reducir el consumo restante
                $consumo_restante -= $m3_en_este_tramo;
                $anterior = $limite_tramo;

                Log::info("Tramo {$tierConfig->range_from}-{$tierConfig->range_to}: {$m3_en_este_tramo} m3, Total sin subsidio: {$tierConfig->total}, Restante: {$consumo_restante}");
            }

            $detalle_sections[] = $tierConfig;
        }

        // Calcular el total de todos los tramos (sin subsidio aplicado)


    // Cálculos de montos (igual que antes)
    $total_tramos_sin_subsidio = array_sum(array_column($detalle_sections, 'total'));
    $cargo_fijo = $configCost->fixed_charge_penalty;
    $consumo_agua_potable = $total_tramos_sin_subsidio;
    $subsidio_descuento = $reading->v_subs ?? 0;
    $subtotal_consumo = $consumo_agua_potable + $cargo_fijo - $subsidio_descuento;

    $subtotal_con_cargos = $subtotal_consumo +
        ($reading->multas_vencidas ?? 0) +
        ($reading->corte_reposicion ?? 0) +
        ($reading->other ?? 0) +
        ($reading->s_previous ?? 0);

    // Determinar tipo de documento
    $routeName = Route::currentRouteName();
    $docType = str_contains($routeName, 'factura') ? 'factura' : 'boleta';

    if ($docType === 'factura') {
        $iva = $subtotal_con_cargos * 0.19;
        $total_con_iva = $subtotal_con_cargos + $iva;
        return view('orgs.factura', compact('reading', 'org', 'detalle_sections', 'tier', 'configCost', 'subtotal_consumo', 'subtotal_con_cargos', 'iva', 'total_con_iva', 'consumo_agua_potable', 'subsidio_descuento', 'readingAnterior'));
    } else {
        return view('orgs.boleta', compact('reading', 'org', 'detalle_sections', 'tier', 'configCost', 'subtotal_consumo',  'consumo_agua_potable', 'subsidio_descuento', 'readingAnterior'));
    }
}

    private function handleNewReadingDte($orgId, $serviceId)
    {
        Log::info('Manejando nueva lectura DTE para service_id: ' . $serviceId);

        // Obtener organización
        $org = DB::table('orgs')->where('id', $orgId)->first();
        if (!$org) {
            abort(404, 'Organización no encontrada');
        }

        // Obtener servicio
        $service = DB::table('services')->where('id', $serviceId)->first();
        if (!$service) {
            abort(404, 'Servicio no encontrado');
        }

        // Obtener miembro
        $member = DB::table('members')->where('id', $service->member_id)->first();
        if (!$member) {
            abort(404, 'Miembro no encontrado');
        }

        // Crear objeto lectura temporal para nueva lectura
        $currentPeriod = date('Y-m');
        
        // Buscar lectura anterior
        $previousMonth = date('m') - 1;
        $previousYear = date('Y');
        if ($previousMonth <= 0) {
            $previousMonth = 12;
            $previousYear = date('Y') - 1;
        }
        $previousMonth = str_pad($previousMonth, 2, '0', STR_PAD_LEFT);
        $previousPeriod = "$previousYear-$previousMonth";

        $previousReading = DB::table('readings')
            ->where('service_id', $serviceId)
            ->where('period', $previousPeriod)
            ->first();

        $previous_reading_value = $previousReading ? $previousReading->current_reading : 0;

        // Crear objeto lectura temporal
        $reading = (object) [
            'id' => null,
            'org_id' => $orgId,
            'service_id' => $serviceId,
            'member_id' => $member->id,
            'period' => $currentPeriod,
            'previous_reading' => $previous_reading_value,
            'current_reading' => $previous_reading_value, // Valor por defecto
            'cm3' => 0,
            'invoice_type' => 'boleta',
            'total' => 0,
            'vc_water' => 0,
            'v_subs' => 0,
            'multas_vencidas' => 0,
            'corte_reposicion' => 0,
            'other' => 0,
            's_previous' => 0,
            'member' => $member,
            'service' => $service
        ];

        // Obtener configuración de tramos
        $tier = DB::table('tier_config')
            ->where('org_id', $orgId)
            ->orderBy('id', 'ASC')
            ->get();

        if ($tier->isEmpty()) {
            Log::error("No se encontraron secciones para la organización con ID: {$orgId}");
            abort(404, 'No se encontraron secciones.');
        }

        // Obtener configuración de costos fijos
        $configCost = DB::table('fixed_costs_config')
            ->where('org_id', $orgId)
            ->first();

        if (!$configCost) {
            Log::error("No se encontró configuración de costos fijos para la organización con ID: {$orgId}");
            abort(404, 'Configuración de costos fijos no encontrada.');
        }

        // Buscar lectura anterior para mostrar
        $readingAnterior = DB::table('readings')
            ->where('service_id', $serviceId)
            ->where('period', '<', $currentPeriod)
            ->orderBy('period', 'desc')
            ->first();

        // Crear detalle de secciones vacío para nueva lectura
        $detalle_sections = [];
        foreach ($tier as $tierConfig) {
            $tierConfig->section = $tierConfig->range_from . " Hasta " . $tierConfig->range_to;
            $tierConfig->m3 = 0;
            $tierConfig->precio = $tierConfig->value;
            $tierConfig->total = 0;
            $detalle_sections[] = $tierConfig;
        }

        // Cálculos básicos para nueva lectura
        $cargo_fijo = $configCost->fixed_charge_penalty;
        $consumo_agua_potable = 0;
        $subsidio_descuento = 0;
        $subtotal_consumo = $cargo_fijo;

        // Determinar tipo de documento
        $routeName = Route::currentRouteName();
        $docType = str_contains($routeName, 'factura') ? 'factura' : 'boleta';

        if ($docType === 'factura') {
            $iva = 0;
            $total_con_iva = $subtotal_consumo;
            return view('orgs.factura', compact('reading', 'org', 'detalle_sections', 'tier', 'configCost', 'subtotal_consumo', 'subtotal_consumo', 'iva', 'total_con_iva', 'consumo_agua_potable', 'subsidio_descuento', 'readingAnterior'));
        } else {
            return view('orgs.boleta', compact('reading', 'org', 'detalle_sections', 'tier', 'configCost', 'subtotal_consumo', 'consumo_agua_potable', 'subsidio_descuento', 'readingAnterior'));
        }
    }

    public function multiBoletaPrint($id, $readingId)
    {
        Log::info('Recibiendo solicitud para DTE con ID org: ' . $id . ' y readingId: ' . $readingId);

        $org              = Org::findOrFail($id);
        $reading          = Reading::findOrFail($readingId);
        $reading->member  = Member::findOrFail($reading->member_id);
        $reading->service = Service::findOrFail($reading->service_id);
        $tier             = TierConfig::where('org_id', $id)->OrderBy('id', 'ASC')->get();
        $configCost       = FixedCostConfig::where('org_id', $org->id)->first();

        $readingAnterior = Reading::where('member_id', $reading->member_id)
            ->where('service_id', $reading->service_id)
            ->where('period', '<', $reading->period)
            ->orderBy('period', 'desc')
            ->first();

        if ($tier->isEmpty()) {
            Log::error("No se encontraron secciones para la organización con ID: {$id}");
            abort(404, 'No se encontraron secciones.');
        }

        if (! $configCost) {
            \Log::error("No se encontró configuración de costos fijos para la organización con ID: {$org->id}");
            abort(404, 'Configuración de costos fijos no encontrada.');
        }

        // Obtener datos del servicio para el subsidio
        $service             = Service::findOrFail($reading->service_id);
        $subsidio            = $service->meter_plan; // 0 o 1
        $porcentaje_subsidio = $service->percentage / 100;

        // Asegurándonos de que el consumo es mayor que 0
        $consumo = $reading->cm3;
        Log::info("Consumo inicial: " . $consumo);

        $detalle_sections = [];
        $consumo_restante = $consumo;
        $anterior         = 0;

        foreach ($tier as $index => $tierConfig) {
            if ($consumo_restante <= 0) {
                // Si no queda consumo, este tramo tendrá 0 m3
                $tierConfig->section            = $tierConfig->range_from . " Hasta " . $tierConfig->range_to;
                $tierConfig->m3                 = 0;
                $tierConfig->precio             = $tierConfig->value;
                $tierConfig->total              = 0;
                $tierConfig->total_sin_subsidio = 0;
                $tierConfig->subsidio_aplicado  = 0;
            } else {
                $limite_tramo = $tierConfig->range_to;

                // Si es el último tramo, asignar todo el consumo restante
                $es_ultimo_tramo = ($index == count($tier) - 1);

                if ($es_ultimo_tramo) {
                    // En el último tramo, asignar todo el consumo restante
                    $m3_en_este_tramo = $consumo_restante;
                } else {
                    // En tramos intermedios, calcular la capacidad del tramo
                    $capacidad_tramo  = $limite_tramo - $anterior;
                    $m3_en_este_tramo = min($capacidad_tramo, $consumo_restante);
                }

                $tierConfig->section = $tierConfig->range_from . " Hasta " . $tierConfig->range_to;
                $tierConfig->m3      = $m3_en_este_tramo;
                $tierConfig->precio  = $tierConfig->value;

                // SIEMPRE mostrar el precio completo sin subsidio en la tabla de tramos
                $tierConfig->total = $m3_en_este_tramo * $tierConfig->value;

                // Reducir el consumo restante
                $consumo_restante -= $m3_en_este_tramo;
                $anterior = $limite_tramo;

                Log::info("Tramo {$tierConfig->range_from}-{$tierConfig->range_to}: {$m3_en_este_tramo} m3, Total sin subsidio: {$tierConfig->total}, Restante: {$consumo_restante}");
            }

            $detalle_sections[] = $tierConfig;
        }

        // Calcular el total de todos los tramos (sin subsidio aplicado)
        $total_tramos_sin_subsidio = array_sum(array_column($detalle_sections, 'total'));

        Log::info("Total de tramos sin subsidio: {$total_tramos_sin_subsidio}");
        Log::info("vc_water de reading (con subsidio aplicado): {$reading->vc_water}");
        Log::info("v_subs de reading (subsidio a descontar): {$reading->v_subs}");

        // Valores fijos
        $cargo_fijo           = $configCost->fixed_charge_penalty;
        $consumo_agua_potable = $total_tramos_sin_subsidio; // Usar el total de tramos sin subsidio
        $subsidio_descuento   = $reading->v_subs ?? 0;      // Subsidio ya calculado

        $subtotal_consumo = $consumo_agua_potable + $cargo_fijo - $subsidio_descuento;

        // Verificando el subtotal
        Log::info("Consumo agua potable (tramos sin subsidio): " . $consumo_agua_potable);
        Log::info("Cargo fijo: " . $cargo_fijo);
        Log::info("Subsidio a descontar: " . $subsidio_descuento);
        Log::info("Subtotal de consumo (después de restar subsidio): " . $subtotal_consumo);

        $subtotal_con_cargos = $subtotal_consumo +
            ($reading->multas_vencidas ?? 0) +
            ($reading->corte_reposicion ?? 0) +
            ($reading->other ?? 0) +
            ($reading->s_previous ?? 0);

        // Definir el IVA solo si el tipo de documento es factura
        $iva           = 0;
        $total_con_iva = $subtotal_con_cargos;


$routeName = Route::currentRouteName();
Log::info('Ruta actual detectada: ' . $routeName);

if ($routeName === 'orgs.multiBoletaPrint') {
    $docType = 'boleta';
} elseif ($routeName === 'orgs.multiFacturaPrint') {
    $docType = 'factura';
    $iva = $subtotal_con_cargos * 0.19;
    $total_con_iva = $subtotal_con_cargos + $iva;
} else {
    $docType = 'boleta'; // Por defecto
}

        Log::info('Tipo de documento seleccionado: ' . $docType);
        Log::info("IVA Calculado: {$iva}");
        Log::info("Total con IVA: {$total_con_iva}");

        switch (strtolower($docType)) {
            case 'boleta':
                Log::info('Entrando a la vista de Boleta');
                return view('orgs.multiBoletaPrint', compact('reading', 'org', 'detalle_sections', 'tier', 'configCost', 'subtotal_consumo', 'total_con_iva', 'consumo_agua_potable', 'subsidio_descuento', 'readingAnterior'));

            case 'factura':
                Log::info('Entrando a la vista de Factura');
                return view('orgs.multiFacturaPrint', compact('reading', 'org', 'detalle_sections', 'tier', 'configCost', 'subtotal_consumo', 'subtotal_con_cargos', 'iva', 'total_con_iva', 'consumo_agua_potable', 'subsidio_descuento', 'readingAnterior'));

            default:
                abort(404, 'Tipo de documento no reconocido: ' . $docType);
        }
    }

    /*Export Excel*/
    public function export()
    {
        return Excel::download(new ReadingsExport, 'Reading-' . date('Ymdhis') . '.xlsx');
    }

    /**
     * Guarda lecturas enviadas por el frontend (POST /guardar-lecturas)
     * Espera un array 'lecturas' en el body (JSON)
     * Cada lectura debe tener: numero, rut, cliente, sector, lectura, period
     * Busca el service_id por numero y rut, y guarda o actualiza la lectura para ese periodo
     * Devuelve JSON con éxito o error
     */
    public function store(Request $request)
    {
        $lecturas = $request->input('lecturas');
        Log::info('Lecturas recibidas para guardar:', $lecturas);
        if (!is_array($lecturas) || empty($lecturas)) {
            Log::warning('No se recibieron lecturas válidas');
            return response()->json(['message' => 'No se recibieron lecturas válidas'], 400);
        }
        $errores = [];
        $guardadas = 0;
        DB::beginTransaction();
        try {
            foreach ($lecturas as $lectura) {
                Log::info('Procesando lectura:', $lectura);
                // Validar datos mínimos
                if (empty($lectura['numero']) || empty($lectura['rut']) || empty($lectura['lectura']) || empty($lectura['period'])) {
                    $errores[] = 'Faltan datos en una lectura';
                    Log::warning('Lectura con datos faltantes:', $lectura);
                    continue;
                }
                    // Buscar el servicio por nro y rut
                    $servicio = Service::where('nro', $lectura['numero'])
                        ->with('member')
                        ->first();
                    
                Log::info('Buscando servicio con número: ' . $lectura['numero']);
                Log::info('Resultado búsqueda servicio:', ['servicio_encontrado' => $servicio ? 'SI' : 'NO']);
                
                if ($servicio && $servicio->member) {
                    Log::info('Datos del member asociado:', [
                        'member_id' => $servicio->member->id,
                        'rut_member' => $servicio->member->rut,
                        'rut_enviado' => $lectura['rut']
                    ]);
                }
                if (!$servicio || !$servicio->member || $servicio->member->rut !== $lectura['rut']) {
                    $errores[] = 'No se encontró el servicio para el número ' . $lectura['numero'] . ' y rut ' . $lectura['rut'];
                    Log::warning('No se encontró el servicio para:', ['numero' => $lectura['numero'], 'rut' => $lectura['rut']]);
                    continue;
                }
                // Validar que existan los campos necesarios en el modelo
                $service_id = $servicio->getAttribute('id');
                $org_id = $servicio->getAttribute('org_id');
                $member_id = $servicio->getAttribute('member_id');
                Log::info('IDs detectados:', ['service_id' => $service_id, 'org_id' => $org_id, 'member_id' => $member_id]);
                if (!$service_id || !$org_id || !$member_id) {
                    $errores[] = 'El modelo Service no tiene los campos requeridos (id, org_id, member_id)';
                    Log::warning('Faltan campos en Service:', ['service' => $servicio]);
                    continue;
                }
                // Buscar o crear la lectura para ese periodo y servicio
                $reading = Reading::firstOrNew([
                    'service_id' => $service_id,
                    'period' => $lectura['period'],
                ]);
                $reading->org_id = $org_id;
                $reading->member_id = $member_id;
                $reading->service_id = $service_id;
                $reading->period = $lectura['period'];
                $reading->current_reading = $lectura['lectura'];
                
                Log::info('Datos asignados a reading:', [
                    'service_id' => $reading->service_id,
                    'period' => $reading->period,
                    'current_reading' => $reading->current_reading,
                    'org_id' => $reading->org_id,
                    'member_id' => $reading->member_id
                ]);
                // Asignar locality_id desde el servicio relacionado
                $reading->locality_id = $servicio->getAttribute('locality_id');
                // Buscar lectura anterior para previous_reading
                $prev = Reading::where('service_id', $service_id)
                    ->where('period', '<', $lectura['period'])
                    ->orderBy('period', 'desc')
                    ->first();
                $reading->previous_reading = $prev && isset($prev->current_reading) ? $prev->current_reading : 0;
                $reading->cm3 = max(0, (float)$reading->current_reading - (float)$reading->previous_reading);
                $reading->save();
                    // Recalcular valores usando updateReading
                        $this->updateReading(Org::find($servicio->org_id), $reading, [
                        'current_reading' => $reading->current_reading,
                        'previous_reading' => $reading->previous_reading
                    ]);
                $guardadas++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar lecturas: ' . $e->getMessage());
            return response()->json(['message' => 'Error al guardar lecturas: ' . $e->getMessage()], 500);
        }
        if ($guardadas === 0) {
            Log::warning('No se guardó ninguna lectura', ['errores' => $errores]);
            return response()->json(['message' => 'No se guardó ninguna lectura', 'errores' => $errores], 400);
        }
        Log::info('Lecturas guardadas correctamente', ['guardadas' => $guardadas]);
        return response()->json([
            'message' => 'Lecturas guardadas correctamente',
            'guardadas' => $guardadas,
            'errores' => $errores
        ]);
    }

    private function createNewReading($org_id, $service_id, $current_reading)
    {
        // Obtener el servicio para acceder a member_id y locality_id
        $service = Service::findOrFail($service_id);
        
        // Calcular el período actual
        $currentPeriod = date('Y-m');
        
        // Buscar la lectura anterior (del período anterior)
        $previousMonth = date('m') - 1;
        $previousYear = date('Y');
        if ($previousMonth <= 0) {
            $previousMonth = 12;
            $previousYear = date('Y') - 1;
        }
        $previousMonth = str_pad($previousMonth, 2, '0', STR_PAD_LEFT);
        $previousPeriod = "$previousYear-$previousMonth";
        
        $previousReading = Reading::where('service_id', $service_id)
            ->where('period', $previousPeriod)
            ->first();
        
        $previous_reading_value = $previousReading ? $previousReading->current_reading : 0;
        
        // Crear la nueva lectura
        $reading = new Reading();
        $reading->org_id = $org_id;
        $reading->service_id = $service_id;
        $reading->member_id = $service->member_id;
        $reading->locality_id = $service->locality_id;
        $reading->period = $currentPeriod;
        $reading->previous_reading = $previous_reading_value;
        $reading->current_reading = $current_reading;
        $reading->cm3 = max(0, $current_reading - $previous_reading_value);
        $reading->invoice_type = 'boleta';
        $reading->total = 0;
        $reading->vc_water = 0;
        $reading->v_subs = 0;
        $reading->multas_vencidas = 0;
        $reading->corte_reposicion = 0;
        $reading->other = 0;
        $reading->s_previous = 0;
        $reading->total_mounth = 0;
        $reading->sub_total = 0;
        $reading->save();
        
        return $reading;
    }

    public function exportHistory($id)
    {
        // Si tu exportador requiere argumentos, pásalos aquí
        return Excel::download(new ReadingsHistoryExport($id), 'Readings-History-' . date('Ymdhis') . '.xlsx');
    }

}
