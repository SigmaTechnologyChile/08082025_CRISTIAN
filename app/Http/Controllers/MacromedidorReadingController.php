<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MacromedidorReading;

class MacromedidorReadingController extends Controller
{
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }
        $data = $request->validate([
            'fecha' => 'required|date',
            'frecuencia' => 'required|string',
            'lectura_anterior_extraccion' => 'required|integer',
            'lectura_actual_extraccion' => 'required|integer',
            'lectura_anterior_entrega' => 'required|integer',
            'lectura_actual_entrega' => 'required|integer',
            'extraccion_total' => 'required|integer',
            'entrega_total' => 'required|integer',
            'perdidas_total' => 'required|integer',
            'porcentaje_perdidas' => 'required|string',
            'responsable' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);
        $registro = MacromedidorReading::create($data);
        return response()->json(['success' => true, 'registro' => $registro]);
    }

    public function index(Request $request, $orgId)
    {
        $query = MacromedidorReading::where('org_id', $orgId);
        $anio = $request->input('anio');
        $frecuencia = $request->input('frecuencia');
        if (!empty($anio)) {
            $query->whereYear('fecha', $anio);
        }
        if (!empty($frecuencia) && $frecuencia !== 'todos') {
            $query->where('frecuencia', $frecuencia);
        }
            // Obtener la última lectura del macromedidor para la organización
            $lecturas = $query->orderBy('fecha', 'desc')
                ->take(1)
                ->get();
            return response()->json($lecturas);
    }
}
