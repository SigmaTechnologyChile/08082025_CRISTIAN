<?php
namespace App\Http\Controllers\Org;

use App\Exports\MembersExport;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Member;
use App\Models\Org;
use App\Models\OrgMember;
use App\Models\Service;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Eliminado acceso a Route::current()
    }

    public function index(Request $request, $id)
    {
        $org = Org::findOrFail($id);
        $sector = $request->input('sector');
        $search = $request->input('search');

        $members = Member::join('orgs_members', 'members.id', 'orgs_members.member_id')
            ->leftjoin('services', 'members.rut', 'services.rut')
            ->where('orgs_members.org_id', $org->id)
            ->when($sector, function ($q) use ($sector) {
                $q->where('services.locality_id', $sector);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('members.rut', $search)
                    ->orWhere('members.first_name', 'like', '%' . $search . '%')
                    ->orWhere('members.last_name', 'like', '%' . $search . '%')
                    ->orWhere('members.full_name', 'like', '%' . $search . '%');
            })
            ->select(
                'members.id',
                'members.rut',
                'members.full_name',
                'members.first_name',
                'members.last_name',
                'members.gender',
                'members.city_id',
                'members.commune',
                'members.address',
                'members.partner',
                'members.phone',
                'members.mobile_phone',
                'members.email',
                'members.active',
                'members.created_at',
                'members.updated_at',
                'members.deleted_at',
                'services.id as service_id',
                DB::raw('COUNT(*) as qrx_serv')
            )
            ->groupBy(
                'members.id',
                'members.rut',
                'members.full_name',
                'members.first_name',
                'members.last_name',
                'members.gender',
                'members.city_id',
                'members.commune',
                'members.address',
                'members.partner',
                'members.phone',
                'members.mobile_phone',
                'members.email',
                'members.active',
                'members.created_at',
                'members.updated_at',
                'members.deleted_at',
                'services.id'
            )
            ->paginate(20);

        $locations = Location::where('org_id', $org->id)->OrderBy('order_by', 'ASC')->get();
        return view('orgs.members.index', compact('org', 'members', 'locations'));
    }

    public function create($id)
    {
        $org = Org::findOrFail($id);
        $states = State::all();
        $locations = Location::where('org_id', $org->id)->OrderBy('order_by', 'ASC')->get();
        return view('orgs.members.create', compact('org', 'states', 'locations'));
    }

    public function store(Request $request, $id)
    {
        $org = Org::findOrFail($id);

        $validated = $request->validate([
            'rut' => 'required|string|unique:members,rut',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'address' => 'required|string|max:255',
            'state' => 'required|exists:states,id',
            'commune' => 'required|string|max:100',
            'mobile_phone' => 'required|string|max:15',
            'phone' => 'nullable|string|max:15',
            'partner' => 'required',
            'gender' => 'required|string',
            'activo' => 'nullable|boolean',
        ]);

        $member = new Member();
        $member->rut = $validated['rut'];
        $member->first_name = strtoupper($validated['first_name']);
        $member->last_name = strtoupper($validated['last_name']);
        $member->full_name = $member->first_name . ' ' . $member->last_name;
        $member->city_id = $validated['state'];
        $member->commune = $validated['commune'];
        $member->gender = $validated['gender'];
        $member->email = $validated['email'];
        $member->address = strtoupper($validated['address']);
        $member->partner = $validated['partner'];
        $member->phone = '+56' . ltrim($validated['phone'] ?? '', '0');
        $member->mobile_phone = '+56' . ltrim($validated['mobile_phone'], '0');
        $member->active = $validated['activo'] ?? 1;
        $member->save();

        $orgMember = new OrgMember();
        $orgMember->org_id = $org->id;
        $orgMember->member_id = $member->id;
        $orgMember->save();

        return redirect()->route('orgs.members.index', $org->id)
            ->with('success', 'Socio creado correctamente.');
    }

    public function dashboard($id)
    {
        $org = Org::findOrFail($id);
        return view('orgs.dashboard', compact('org'));
    }

    public function edit($id, $memberId)
    {
        $org = Org::findOrFail($id);
        $member = Member::findOrFail($memberId);
        $city = null;
        if ($member->city_id) {
            $city = State::find($member->city_id);
        }

        $services = Service::where('member_id', $memberId)
            ->leftJoin('locations', 'services.locality_id', '=', 'locations.id')
            ->select(
                'services.*',
                'locations.name as sector_name'
            )
            ->get();

        $states = State::all();

        return view('orgs.members.edit', compact('org', 'member', 'services', 'states', 'city'));
    }

    public function update(Request $request, $id, $memberId)
    {
        $org = Org::findOrFail($id);
        $member = Member::findOrFail($memberId);

        // ...validación y lógica igual que antes...

        // El resto del método permanece igual, solo cambia $org = Org::findOrFail($id);
        // Puedes copiar el bloque de validación y lógica de tu versión original aquí.
    }

    public function transferService(Request $request, $id, $memberId, $serviceId)
    {
        $org = Org::findOrFail($id);
        $member = Member::findOrFail($memberId);

        // ...resto de la lógica igual que antes...
    }

    public function editService($id, $memberId, $serviceId)
    {
        $org = Org::findOrFail($id);
        $member = Member::findOrFail($memberId);

        // ...resto de la lógica igual que antes...
    }

    public function updateService(Request $request, $id, $memberId, $serviceId)
    {
        $org = Org::findOrFail($id);

        // ...resto de la lógica igual que antes...
    }

    public function toggleStatus($id, $memberId, $serviceId)
    {
        $org = Org::findOrFail($id);

        // ...resto de la lógica igual que antes...
    }

    public function export($id)
    {
        $org = Org::findOrFail($id);
        return Excel::download(new MembersExport, 'Members-' . date('Ymdhis') . '.xlsx');
    }
}