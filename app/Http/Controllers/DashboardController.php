<?php

namespace App\Http\Controllers;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Org; // Asegúrate de importar el modelo Org

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd(Auth::user());

        if(Auth::user()->is_manager == 1) {
            $currentuserid = Auth::user()->id;
            $orgs = Org::where('user_id', $currentuserid)
                      ->orderBy('active', 'DESC')
                      ->paginate(20);

            return view('dashboard-manager', compact('orgs'));
        } else {
            return view('dashboard');
        }
    }

    /**
     * Muestra el dashboard de mis organizaciones
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
public function misOrganizaciones()
{
    $user = Auth::user();
    // Si es SuperAdmin, usar orgId de la sesión si existe
    if ($user->isSuperAdmin()) {
        $orgId = session('orgId', $user->org_id);
        $org = \App\Models\Org::find($orgId);
    } else {
        $member = Member::where('rut', $user->rut)->first();
        $org = $member?->orgs()->first();
    }
    return view('dashboardmisorganizaciones', compact('org'));
}
}
