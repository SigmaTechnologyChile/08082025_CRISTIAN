<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Org;
use App\Models\Service;
use App\Models\Member;
use App\Models\Location;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ServiceExport;

class ServiceController extends Controller
{
    /**
     * Devuelve los servicios y miembros de un sector (para poblar el selector Servicios dinámicamente)
     */
    public function porSector($sectorId)
    {
        $servicios = DB::table('services as s')
            ->join('members as m', 's.member_id', '=', 'm.id')
            ->where('s.locality_id', $sectorId)
            ->select('s.nro as numero', 'm.rut', 'm.full_name')
            ->orderBy('m.full_name')
            ->get();

        return response()->json($servicios);
    }
    protected $_param;
    public $org;
    
    public function __construct()
    {
        $this->middleware('auth');
        // Solo buscar la organización si la ruta tiene el parámetro 'id' y es numérico
        $route = Route::current();
        $id = null;
        if ($route) {
            $id = $route->parameter('id');
        }
        $this->org = (is_numeric($id) && $id > 0) ? Org::find($id) : null;
    }
    
    public function index()
    {
        $org = $this->org;
        $locations = Location::all();

        // Parámetros de ordenamiento
        $sort = request()->get('sort', 'nro');
        $order = request()->get('order', 'asc');

        // Validar columnas permitidas para ordenar
        $allowedSorts = [
            'nro', 'member_name', 'location_name', 'meter_number', 'invoice_type', 'meter_plan', 'percentage', 'diameter'
        ];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'nro';
        }
        if (!in_array(strtolower($order), ['asc', 'desc'])) {
            $order = 'asc';
        }

        $services = DB::table('services')
            ->join('members', 'services.member_id', '=', 'members.id')
            ->join('locations', 'services.locality_id', '=', 'locations.id')
            ->when($this->org, function ($query) {
                return $query->where('services.org_id', $this->org->id);
            })
            ->when(request('sector'), function ($query) {
                return $query->where('services.locality_id', request('sector'));
            })
            ->when(request('nro'), function ($query) {
                return $query->where('services.nro', 'like', '%' . request('nro') . '%');
            })
            ->when(request('search'), function ($query) {
                $search = request('search');
                return $query->where(function($q) use ($search) {
                    $q->where('members.full_name', 'like', "%$search%")
                      ->orWhere('members.rut', 'like', "%$search%");
                });
            })
            ->select(
                'services.*',
                'members.full_name as member_name',
                'members.rut as member_rut',
                'locations.name as location_name'
            )
            // Ordenamiento dinámico
            ->orderBy($sort, $order)
            ->get();

        return view('orgs.services.index', compact('org', 'services', 'locations'));
    }
    
    public function create()
    {
        return view('orgs.services.create');
    }
    
   
    public function createForMember($orgId, $memberId)
    {
        $org = $this->org;
        $locations = $this->org ? Location::where('org_id', $this->org->id)->get() : collect();
        $member = Member::findOrFail($memberId);
         return view('orgs.services.create', compact('org', 'member','locations'));
    }
    
    public function storeForMember(Request $request, $orgId, $memberId)
    {
       
        $validator = Validator::make($request->all(), [
            'locality_id' => 'required|integer',
            'meter_plan' => 'required|in:si,no',
            'meter_type' => 'required|in:analogico,digital',
            'meter_number' => 'required|string|max:20',
            'invoice_type' => 'required|in:boleta,factura',
            'diameter' => 'required|in:1/2,3/4',
            'partner' => 'nullable|in:socio,cliente',
            'observations' => 'nullable|string|max:1000',
            'state' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:100'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // Lógica personalizada para validar 'percentage' solo cuando 'meter_plan' es 'si'
        if ($request->meter_plan == 'si') {
            // Si 'meter_plan' es 'si', validar que 'percentage' sea requerido
            $validator->addRules(['percentage' => 'required|numeric|min:0|max:100']);
    
            // Comprobar si la validación falla nuevamente después de agregar la validación para 'percentage'
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } else {
            // Si 'meter_plan' es 'no', podemos establecer 'percentage' a null (o 0 si lo prefieres)
            $request->merge(['percentage' => null]);
        }
        

        $member = Member::findOrFail($memberId);
        
        $service = new Service();
        $service->member_id = $memberId;
        $service->org_id = $orgId;
        $service->locality_id = $request->input('locality_id');
        $service->meter_plan = $request->input('meter_plan');
        $service->percentage = $request->input('percentage', 0);
        $service->meter_type = $request->input('meter_type');
        $service->meter_number = $request->input('meter_number');
        $service->invoice_type = $request->input('invoice_type');
        $service->diameter = $request->input('diameter');
        //$service->partner = $request->input('partner', 'cliente');
        $service->observations = $request->input('observations');
        $service->rut = $member->rut;
        $service->address = $member->address;
        $service->state = $request->input('state', '');
        $service->commune = $request->input('commune', '');
        //$service->serv = $request->input('serv', 1); 
        $maxNro = Service::where('org_id',$orgId)->max('nro');
        $service->nro = $maxNro + 1;

        while (Service::where('org_id',$orgId)->where('nro', $service->nro)->exists()) {
        $service->nro++;
        }
        //$service->serv = $request->serv ?: 1;
        $service->active = 1;
        $service->save();

        return redirect()->route('orgs.members.index', ['id' => $orgId])
            ->with('success', 'Servicio creado exitosamente');
    }
    
    public function export($id) 
    {
        $filters = [
            'org_id' => $id,
            'sector' => request('sector'),
            'nro' => request('nro'),
            'search' => request('search'),
            'sort' => request('sort', 'nro'),
            'order' => request('order', 'asc'),
        ];
        return Excel::download(new ServiceExport($filters), 'Servicios-'.date('Ymdhis').'.xlsx');
    }
}
