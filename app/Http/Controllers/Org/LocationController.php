<?php
namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Org;
use App\Models\Location;
use App\Models\Service;
use App\Models\State;
use App\Models\Province;
use App\Models\City;
use App\Models\Ruta;
use App\Exports\LocationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // No accedas a Route::current() aquí
    }

    // Endpoint para obtener clientes por sector (locality_id)
    public function clientesPorSector($locationId)
    {
        try {
            \Log::info("Buscando services para locality_id: " . $locationId);
            
            // Simplemente traer todos los services.id donde locality_id = locationId
            $servicios = DB::table('services')
                ->where('locality_id', $locationId)
                ->select('id as service_id')
                ->orderBy('id')
                ->get();
            
            \Log::info("Services encontrados: " . $servicios->count());
            
            return response()->json([
                'success' => true,
                'data' => $servicios,
                'total' => $servicios->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error en clientesPorSector: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index($id)
    {
        $org = Org::findOrFail($id);
        $locations = Location::join('cities','locations.city_id','cities.id')
                            ->join('provinces','cities.province_id','provinces.id')
                            ->join('states','provinces.state_id','states.id')
                            ->where('org_id', $org->id)
                            ->orderBy('order_by', 'asc')
                            ->select('locations.*','cities.name_city','provinces.name_province','states.name_state')
                            ->paginate(20);

        return view('orgs.locations.index', compact('org', 'locations'));
    }

    public function create($id)
    {
        $org = Org::findOrFail($id);
        $states = State::all();
        $provinces = Province::all();
        $cities = City::all();

        return view('orgs.locations.create', compact('org', 'states', 'provinces', 'cities'));
    }

    public function store(Request $request, $id)
    {
        $org = Org::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'city_id' => 'required',
        ]);
        $lastOrderBy = Location::where('org_id', $org->id)->max('order_by');
        $newOrderBy = $lastOrderBy ? $lastOrderBy + 1 : 1;

        $location = new Location();
        $location->org_id = $org->id;
        $location->city_id = $request->city_id;
        $location->name = $request->name;
        $location->active = 1;
        $location->order_by = $newOrderBy;
        $location->save();

        return redirect()->route('orgs.locations.index', ['id' => $org->id])
            ->with('success', 'Ubicación creada con éxito');
    }

    public function show($id, $location_id)
    {
        $org = Org::findOrFail($id);
        $location = Location::findOrFail($location_id);

        return view('orgs.locations.show', compact('org', 'location'));
    }

    public function edit($id, $locationId)
    {
        $org = Org::findOrFail($id);
        $location = Location::findOrFail($locationId);

        $states = State::all();
        $provinces = Province::all();
        $cities = City::all();

        return view('orgs.locations.edit', compact('org', 'location', 'states', 'provinces', 'cities'));
    }

    public function update(Request $request, $id, $locationId)
    {
        $org = Org::findOrFail($id);
        $location = Location::findOrFail($locationId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
        ]);

        $location->name = $request->input('name');
        $location->city_id = $request->input('city_id');
        $location->save();

        return redirect()->route('orgs.locations.index', $org->id)
            ->with('success', 'Sector actualizado correctamente');
    }

    public function getProvinces($stateId)
    {
        $provinces = Province::where('state_id', $stateId)->get();
        return response()->json($provinces);
    }

    public function getCities($provinceId)
    {
        $cities = City::where('province_id', $provinceId)->get();
        return response()->json($cities);
    }

    public function export()
    {
    $orgId = request('id');
    $search = request('search');
    return Excel::download(new LocationsExport($orgId, $search), 'Sectores-'.date('Ymdhis').'.xlsx');
    }

    public function panelControlRutas($id)
    {
        $org = Org::findOrFail($id);
        $locations = Location::where('org_id', $org->id)
            ->orderBy('order_by', 'asc')
            ->get();

        return view('orgs.locations.panelcontrolrutas_simple', compact('org', 'locations'));
    }

    // Método para guardar el orden de la ruta
    public function guardarOrdenRuta(Request $request, $orgId, $locationId)
    {
        try {
            $org = Org::findOrFail($orgId);
            $location = Location::findOrFail($locationId);
            
            $ordenData = $request->json()->all();
            
            if (!isset($ordenData['servicios']) || !is_array($ordenData['servicios'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de servicios inválidos'
                ], 400);
            }
            
            // Eliminar registros existentes para este sector
            Ruta::where('org_id', $orgId)
                 ->where('location_id', $locationId)
                 ->delete();
            
            // Insertar nuevo orden
            $rutasData = [];
            foreach ($ordenData['servicios'] as $servicio) {
                $rutasData[] = [
                    'org_id' => $orgId,
                    'location_id' => $locationId,
                    'service_id' => $servicio['service_id'],
                    'orden' => $servicio['orden'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            
            if (!empty($rutasData)) {
                Ruta::insert($rutasData);
            }
            
            \Log::info("Orden de ruta guardado para sector {$locationId}", $rutasData);
            
            return response()->json([
                'success' => true,
                'message' => 'Orden guardado correctamente',
                'total_servicios' => count($rutasData),
                'sector' => $location->name
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error al guardar orden de ruta: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener servicios con orden guardado
    public function obtenerServiciosConOrden($orgId, $locationId)
    {
        try {
            // Obtener servicios del sector con orden guardado
            $serviciosConOrden = DB::table('services')
                ->join('members', 'services.rut', '=', 'members.rut')
                ->leftJoin('rutas', function($join) use ($orgId, $locationId) {
                    $join->on('services.id', '=', 'rutas.service_id')
                         ->where('rutas.org_id', '=', $orgId)
                         ->where('rutas.location_id', '=', $locationId);
                })
                ->where('services.locality_id', $locationId)
                ->select(
                    'services.id as numero',
                    'services.rut',
                    DB::raw("CONCAT(members.first_name, ' ', members.last_name) as full_name"),
                    'rutas.orden'
                )
                ->orderBy('rutas.orden', 'ASC')
                ->orderBy('services.id', 'ASC') // Fallback para servicios sin orden
                ->get();

            // Ajustar para frontend: agregar array 'members' por servicio
            $serviciosConOrden = $serviciosConOrden->map(function($servicio) {
                return [
                    'numero' => $servicio->numero,
                    'rut' => $servicio->rut,
                    'full_name' => $servicio->full_name,
                    'orden' => $servicio->orden,
                    'members' => $servicio->full_name ? [ ['name' => $servicio->full_name] ] : [],
                ];
            });

            return response()->json($serviciosConOrden);
        } catch (\Exception $e) {
            \Log::error("Error al obtener servicios con orden: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}