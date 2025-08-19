<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Org;
use App\Models\Inventary;
use App\Models\InventoryCategory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventaryExport;

class InventaryController extends Controller
{
    protected $_param;
    public $org;
    
    public function __construct()
    {
        $this->middleware('auth');
        // El parámetro 'id' debe obtenerse dentro de los métodos, no en el constructor
        $this->org = null;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request,$id)
    {
        $org = Org::find($id);

        
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $search = $request->input('search');
        
        if (!$org) {
            return redirect()->route('orgs.index')->with('error', 'Organización no encontrada.');
        }

        $inventories = Inventary::with('category')  
            ->where('org_id', $org->id)
            ->when($start_date && $end_date, function($q) use ($start_date, $end_date) {
                $q->whereDate('order_date', '>=', $start_date)
                  ->whereDate('order_date', '<=', $end_date);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('description', 'like', '%'.$search.'%')
                    ->orWhere('responsible', 'like', '%' . $search . '%');
            })
            ->orderBy('order_date', 'desc') 
            ->paginate(20); 
            
        $totalQuantity = $inventories->sum('qxt'); 
        $totalAmount = $inventories->sum('amount'); 

        return view('orgs.inventories.index',compact('org','inventories'));
    }
    
    public function create($id)
    {
           \Log::info('ID create: ' . $id);
        
        $org = Org::find($id);
        if (!$org) {
            return redirect()->route('orgs.index')->with('error', 'Organización no encontrada.');
        }
        $categories = InventoryCategory::where('org_id', $org->id)->get();
        return view('orgs.inventories.create', compact('org', 'categories'));
    }
    
    public function store(Request $request, $orgId)
    {
    
        $validated = $request->validate([
            'description' => 'required|string',
            'qxt' => 'required|numeric',
            'order_date' => 'required|date',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'location' => 'nullable|string',
            'responsible' => 'nullable|string',
            'low_date' => 'nullable|date',
            'observations' => 'nullable|string',
            'category_id' => 'required|exists:inventories_categories,id',
        ]);
    
        $inventary = new Inventary();
        $inventary->org_id = $orgId;
        $inventary->description = $validated['description'];
        $inventary->qxt = $validated['qxt'];
        $inventary->order_date = $validated['order_date'];
        $inventary->amount = $validated['amount'];
        $inventary->status = $validated['status'];
        $inventary->location = $validated['location'] ?? null;
        $inventary->responsible = $validated['responsible'] ?? null;
        $inventary->low_date = $validated['low_date'] ?? null;
        $inventary->category_id = $validated['category_id'];
        $inventary->observations = $validated['observations'] ?? 'Sin observaciones';
    
        $inventary->save();
        
        return redirect()->route('orgs.inventories.index', $this->org->id)
                         ->with('success', 'Inventario creado exitosamente.');
    }

    public function edit($id, $inventoryId)
    {
        
    $org = Org::findOrFail($id);
    $inventary = Inventary::where('org_id', $org->id)->findOrFail($inventoryId);
    $categories = InventoryCategory::where('org_id', $org->id)->get();

    return view('orgs.inventories.edit', [
        'org' => $org,
        'inventary' => $inventary,
        'categories' => $categories,
        'title' => 'Editar Inventario'
    ]);

    
    }
    
    public function update(Request $request, $org_id, $inventoryId)
    {
        // Validar los datos de la solicitud
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'qxt' => 'required|integer|min:1',
            'order_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string',
            'location' => 'nullable|string|max:255',
            'responsible' => 'nullable|string|max:255',
            'low_date' => 'nullable|date',
            'category_id' => 'required|exists:inventories_categories,id', // Validar que la categoría exista en la tabla correcta
            'observations' => 'nullable|string',
        ]);
    
        // Buscar inventario por ID y organización
        $inventary = Inventary::where('org_id', $org_id)->findOrFail($inventoryId);
    
        // Asignar valores
        $inventary->description = $validated['description'];
        $inventary->qxt = $validated['qxt'];
        $inventary->order_date = $validated['order_date'];
        $inventary->amount = $validated['amount'];
        $inventary->status = $validated['status'];
        $inventary->location = $validated['location'] ?? null;
        $inventary->responsible = $validated['responsible'] ?? null;
        $inventary->low_date = $validated['low_date'] ?? null;
        $inventary->observations = $validated['observations'] ?? 'Sin observaciones';
        $inventary->category_id = $validated['category_id'];  // Guardar la categoría seleccionada
    
        // Guardar cambios
        $inventary->save();
    
        return redirect()->route('orgs.inventories.index', $org_id)
                         ->with('success', 'Inventario actualizado correctamente.');
    }
    public function export() 
    {
        return Excel::download(new InventaryExport, 'Inventario-'.date('Ymdhis').'.xlsx');
    }


}
