<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Org;
use App\Models\FixedCostConfig;
use App\Models\TierConfig;
use App\Exports\ConfigsExport;



class SectionController extends Controller
{
    protected $_param;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        // El parámetro 'id' debe obtenerse dentro de los métodos, no en el constructor
        $this->org = null;
    }


    public function index(Request $request)
    {
        $id = $request->route('id');
        $org = Org::find($id);
        $fixedCostConfig = FixedCostConfig::firstOrCreate(
            ['org_id' => $org ? $org->id : null],
            [
                'fixed_charge_penalty' => 0,
                'replacement_penalty' => 0,
                'late_fee_penalty' => 0,
                'expired_penalty' => 0,
                'max_covered_m3' => 0,
            ]
        );

    $tiers = TierConfig::where('org_id', $org->id)->get();
        return view('orgs.sections.index', compact('org', 'fixedCostConfig', 'tiers'));
    }

    public function create()
    {
        $org = $this->org;
        $fixedCostConfig = FixedCostConfig::where('org_id', $org->id)->first();
        // $tier->org_id = $orgId;
        return view('orgs.sections.create', compact('org', 'fixedCostConfig'));
    }

    public function storeTier(Request $request, $orgId)
    {
        $validated = $request->validate([
            'tier_name' => 'required|string',
            'range_from' => 'required|numeric',
            'range_to' => 'required|numeric|gt:range_from',
            'value' => 'required|numeric',
        ]);

        $tier = new TierConfig();
        $tier->org_id = $orgId;
        $tier->tier_name = $request->input('tier_name');
        $tier->range_from = $request->input('range_from');
        $tier->range_to = $request->input('range_to');
        $tier->value = $request->input('value');
        $tier->save();

        return redirect()->route('orgs.sections.index', $orgId)->with('success', 'Tramo creado correctamente');
    }

    // Guarda o actualiza valores en fixed_cost_config
    public function storeFixedCost(Request $request, $orgId)
    {
        $validated = $request->validate([
            'fixed_charge_penalty' => 'required|numeric',
            'replacement_penalty' => 'required|numeric',
            'late_fee_penalty' => 'required|numeric',
            'expired_penalty' => 'required|numeric',
            'max_covered_m3' => 'required|numeric',
        ]);

        $fixedCost = FixedCostConfig::firstOrNew(['org_id' => $orgId]);

        $fixedCost->fixed_charge_penalty = $request->input('fixed_charge_penalty');
        $fixedCost->replacement_penalty = $request->input('replacement_penalty');
        $fixedCost->late_fee_penalty = $request->input('late_fee_penalty');
        $fixedCost->expired_penalty = $request->input('expired_penalty');
        $fixedCost->max_covered_m3 = $request->input('max_covered_m3');
        $fixedCost->save();

        return redirect()->route('orgs.sections.index', $orgId)->with('success', 'Configuración actualizada correctamente');
    }


    public function edit($orgId, $tramoId)
    {
        $org = Org::findOrFail($orgId);
        $tier = TierConfig::where('org_id', $orgId)->findOrFail($tramoId);
        return view('orgs.sections.edit', compact('org', 'tier'));
    }

    public function update(Request $request, $orgId, $tramoId)
    {
        // dd($request->all());
        $tier = TierConfig::where('org_id', $orgId)->where('id', $tramoId)->firstOrFail();
        $tier->update($request->only([
            'tier_name',
            'range_from',
            'range_to',
            'value'
        ]));

        return redirect()->route('orgs.sections.edit', [$orgId, $tramoId])
            ->with('success', 'Tramo actualizado');
    }

    public function destroy($orgId, $tramoId)
    {
        $tier = TierConfig::where('org_id', $orgId)->where('id', $tramoId)->firstOrFail();
        $tier->delete();

        return redirect()->route('orgs.sections.index', $orgId)
            ->with('success', 'Tramo eliminado correctamente.');
    }


    /*Export Excel*/

    public function export($id)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new ConfigsExport($id),
            'Configuraciones-' . date('YmdHis') . '.xlsx'
        );
    }
}
