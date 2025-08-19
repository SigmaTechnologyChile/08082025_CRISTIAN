<?php
    namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use App\Models\Categoria;
use App\Models\Movimiento; // HABILITADO: tabla recreada exitosamente
use App\Models\ConfiguracionInicial;
use App\Models\ConfiguracionCuentasIniciales;
use App\Models\Banco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// ...código existente...

class ContableController extends Controller
{
    /**
     * Conciliación automática de movimientos con extractos
     */
    public function conciliarMovimientos(Request $request, $id)
    {
        $movimientos = \App\Models\Movimiento::where('org_id', $id)->get();
        $extractos = \DB::table('extractos')->where('org_id', $id)->get();

        $conciliados = 0;
        $pendientes = 0;
        $diferencias = [];

        foreach ($movimientos as $mov) {
            $fechaMov = \Carbon\Carbon::parse((string)$mov->fecha)->format('Y-m-d');
            $match = $extractos->first(function($ext) use ($mov, $fechaMov) {
                return (
                    $ext->fecha == $fechaMov &&
                    (float)$ext->monto == (float)$mov->monto &&
                    ($ext->nro_dcto == $mov->nro_dcto || $ext->numero_documento == $mov->numero_documento)
                );
            });
            if ($match) {
                $mov->conciliado = true;
                $mov->save();
                $conciliados++;
            } else {
                $pendientes++;
                $diferencias[] = [
                    'id' => $mov->id,
                    'fecha' => $fechaMov,
                    'monto' => $mov->monto,
                    'nro_dcto' => $mov->nro_dcto,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'conciliados' => $conciliados,
            'pendientes' => $pendientes,
            'diferencias' => $diferencias,
        ]);
    }
    /**
     * Exportar balance a PDF
     */
    public function exportarPDF($id)
    {
        // Usar una librería como dompdf si está instalada, aquí ejemplo básico
            $movimientos = Movimiento::where('org_id', $id)
                ->with('categoria')
                ->orderBy('fecha', 'desc')
                ->get();
        $html = view('orgs.contable.balance_pdf', compact('movimientos'))->render();
        // Si tienes dompdf:
        // $pdf = \PDF::loadHTML($html);
        // return $pdf->download('balance_org_' . $id . '.pdf');
        // Temporal:
        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Exportar balance a CSV
     */
    public function exportarCSV($id)
    {
        $movimientos = Movimiento::where('org_id', $id)->orderBy('fecha', 'desc')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="balance_org_' . $id . '.csv"',
        ];
        $callback = function() use ($movimientos) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Fecha', 'Descripción', 'Monto', 'Tipo', 'Cuenta']);
            foreach ($movimientos as $m) {
                fputcsv($handle, [
                    $m->fecha,
                    $m->descripcion,
                    $m->monto,
                    $m->tipo,
                    $m->cuenta_id,
                ]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar balance a Excel (XLSX)
     */
    public function exportarExcelBalance($id)
    {
        // Requiere Laravel Excel, aquí ejemplo básico CSV
        return $this->exportarCSV($id);
    }

    /**
     * Descargar extracto bancario
     */
    public function descargarExtracto($id)
    {
        // Ejemplo: descargar extracto como CSV
        $extractos = DB::table('extractos')->where('org_id', $id)->orderBy('fecha', 'desc')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="extracto_org_' . $id . '.csv"',
        ];
        $callback = function() use ($extractos) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Fecha', 'Detalle', 'Monto', 'Cuenta']);
            foreach ($extractos as $e) {
                fputcsv($handle, [
                    $e->fecha,
                    $e->detalle,
                    $e->monto,
                    $e->cuenta_id,
                ]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Historial de cambios (auditoría)
     */
    public function historialCambios($id)
    {
        // Ejemplo: obtener historial de cambios de movimientos
        $historial = DB::table('auditorias_movimientos')->where('org_id', $id)->orderBy('fecha', 'desc')->get();
        return view('orgs.contable.historial', compact('historial'));
    }
// ...existing code...
    public function balanceCompleto($id)
    {
        $resumen = $this->getResumenSaldos($id);

        $movimientos = Movimiento::where('org_id', $id)->orderBy('fecha', 'desc')->get();
        $contableIngresos = \App\Models\ContableIngreso::whereHas('order', function($q) use ($id) {
            $q->where('org_id', $id);
        })->get();

        // Unificar ingresos de movimientos y contable_ingresos
        $ingresos = collect();
        foreach ($movimientos as $m) {
            if ($m->monto > 0) {
                $ingresos->push([
                    'fecha' => $m->fecha,
                    'monto' => $m->monto,
                    'categoria' => $m->categoria,
                    'detalle' => $m->detalle ?? '',
                ]);
            }
        }
        foreach ($contableIngresos as $ci) {
            $ingresos->push([
                'fecha' => $ci->fecha,
                'monto' => $ci->monto,
                'categoria' => $ci->categoria,
                'detalle' => $ci->detalle ?? '',
            ]);
        }

        // Agrupar por categoría
        $categoriasIngresos = [
            'Cuotas de Incorporación' => $ingresos->where('categoria', 'Cuotas de Incorporación')->sum('monto'),
            'Consumo de Agua' => $ingresos->where('categoria', 'agua')->sum('monto'),
            'Cargo Fijo' => $ingresos->where('categoria', 'cargo_fijo')->sum('monto'),
            'Multas y Recargos' => $ingresos->where('categoria', 'multas')->sum('monto'),
            'Otros Ingresos' => $ingresos->where('categoria', 'otros')->sum('monto'),
        ];

        $datosIngresos = [
            'labels' => array_keys($categoriasIngresos),
            'data' => array_values($categoriasIngresos),
        ];

        // Flujo mensual
        $flujoLabels = [];
        $flujoIngresos = [];
        $agrupados = $ingresos->groupBy(function($m) {
            return \Carbon\Carbon::parse($m['fecha'])->format('Y-m');
        });
        foreach ($agrupados as $mes => $items) {
            $flujoLabels[] = $mes;
            $flujoIngresos[] = collect($items)->sum('monto');
        }

        // Si no hay movimientos ni saldos, todos los indicadores serán null
        $hayDatos = $movimientos->count() > 0 && isset($resumen['totalSaldoActual']) && isset($resumen['totalEgresos']) && isset($resumen['saldoFinal']) && isset($resumen['totalSaldoInicial']) && isset($resumen['totalIngresos']);

        $categoriasIngresos = [
            'Cuotas de Incorporación' => $movimientos->where('categoria', 'Cuotas de Incorporación')->sum('monto'),
            'Consumo de Agua' => $movimientos->where('categoria', 'Consumo de Agua')->sum('monto'),
            'Otros Ingresos' => $movimientos->where('categoria', 'Otros Ingresos')->sum('monto'),
            'Multas y Recargos' => $movimientos->where('categoria', 'Multas y Recargos')->sum('monto'),
        ];
        $categoriasEgresos = [
            'Giros y Transferencias' => $movimientos->where('categoria', 'Giros y Transferencias')->sum('monto'),
            'Gastos Operacionales' => $movimientos->where('categoria', 'Gastos Operacionales')->sum('monto'),
            'Gastos Administrativos' => $movimientos->where('categoria', 'Gastos Administrativos')->sum('monto'),
            'Mantenimiento' => $movimientos->where('categoria', 'Mantenimiento')->sum('monto'),
        ];
        $datosIngresos = [
            'labels' => array_keys($categoriasIngresos),
            'data' => array_values($categoriasIngresos),
        ];
        $datosEgresos = [
            'labels' => array_keys($categoriasEgresos),
            'data' => array_values($categoriasEgresos),
        ];

        // Indicadores solo si hay datos reales
        $ratioLiquidez = ($hayDatos && $resumen['totalSaldoActual'] && $resumen['totalEgresos']) ?
            ($resumen['totalSaldoActual'] > 0 && $resumen['totalEgresos'] > 0 ? $resumen['totalSaldoActual'] / $resumen['totalEgresos'] : null)
            : null;
        $roe = ($hayDatos && $resumen['saldoFinal'] && $resumen['totalSaldoInicial']) ?
            ($resumen['saldoFinal'] > 0 && $resumen['totalSaldoInicial'] > 0 ? ($resumen['saldoFinal'] / $resumen['totalSaldoInicial']) * 100 : null)
            : null;
        $ratioEndeudamiento = ($hayDatos && $resumen['totalEgresos'] && $resumen['totalSaldoActual']) ?
            ($resumen['totalEgresos'] > 0 && $resumen['totalSaldoActual'] > 0 ? ($resumen['totalEgresos'] / $resumen['totalSaldoActual']) * 100 : null)
            : null;
        $margenOperacional = ($hayDatos && $resumen['totalIngresos'] && $resumen['totalEgresos']) ?
            ($resumen['totalIngresos'] > 0 ? (($resumen['totalIngresos'] - $resumen['totalEgresos']) / $resumen['totalIngresos']) * 100 : null)
            : null;

        // Flujo mensual
        $flujoLabels = [];
        $flujoIngresos = [];
        $flujoEgresos = [];
        $agrupados = $movimientos->groupBy(function($m) {
            return Carbon::parse($m->fecha)->format('Y-m');
        });
        foreach ($agrupados as $mes => $items) {
            $flujoLabels[] = $mes;
            $flujoIngresos[] = $items->where('monto', '>', 0)->sum('monto');
            $flujoEgresos[] = $items->where('monto', '<', 0)->sum('monto');
        }

        // Conciliación bancaria
        $conciliacionLabels = ['Caja General', 'Cta. Cte. 1', 'Cta. Ahorro'];
        $conciliacionRegistrado = [
            $resumen['cuentaCajaGeneralOperativa']->saldo_actual ?? 0,
            $resumen['cuentaCorriente1Operativa']->saldo_actual ?? 0,
            $resumen['cuentaAhorroOperativa']->saldo_actual ?? 0,
        ];
        $conciliacionBancario = [
            $resumen['cuentaCajaGeneral']->saldo_inicial ?? 0,
            $resumen['cuentaCorriente1']->saldo_inicial ?? 0,
            $resumen['cuentaAhorro']->saldo_inicial ?? 0,
        ];

        $cuentas = Cuenta::where('org_id', $id)->get();
        $categorias = Categoria::all();
        return view('orgs.contable.balance_completo', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
            'categoriasIngresos' => $categoriasIngresos,
            'categoriasEgresos' => $categoriasEgresos,
            'datosIngresos' => $datosIngresos,
            'datosEgresos' => $datosEgresos,
            'ratioLiquidez' => $ratioLiquidez,
            'roe' => $roe,
            'ratioEndeudamiento' => $ratioEndeudamiento,
            'margenOperacional' => $margenOperacional,
            'flujoLabels' => $flujoLabels,
            'flujoIngresos' => $flujoIngresos,
            'flujoEgresos' => $flujoEgresos,
            'conciliacionLabels' => $conciliacionLabels,
            'conciliacionRegistrado' => $conciliacionRegistrado,
            'conciliacionBancario' => $conciliacionBancario,
            'cuentas' => $cuentas,
            'categorias' => $categorias,
        ], $resumen));
    }
    /**
     * Exporta los movimientos a Excel para la organización.
     */
    public function exportarExcel($id)
    {
    // TEST DE LOG DIRECTO Y LARAVEL
    file_put_contents(storage_path('logs/laravel.log'), "Test directo PHP\n", FILE_APPEND);
    \Log::info('Test de log Laravel desde exportarExcel');
    // HABILITADO: tabla movimientos recreada exitosamente
        $movimientos = \App\Models\Movimiento::where('org_id', $id)
            ->orderBy('fecha', 'desc')->get();
        
        // $movimientos = collect(); // Array vacío temporal - YA NO NECESARIO

        // Crear el contenido CSV manualmente (puedes cambiar a Laravel Excel si está instalado)
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="movimientos_org_' . $id . '.csv"',
        ];

        $callback = function() use ($movimientos) {
            $handle = fopen('php://output', 'w');
            // Encabezados
            fputcsv($handle, ['Fecha', 'Descripción', 'Total Consumo', 'Cuotas Incorporación', 'Otros Ingresos', 'Giros']);
            foreach ($movimientos as $m) {
                fputcsv($handle, [
                    $m->fecha,
                    $m->descripcion,
                    $m->total_consumo,
                    $m->cuotas_incorporacion,
                    $m->otros_ingresos,
                    $m->giros,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        return $this->storeNuevoFlujo($request);
    }

    /**
     * NUEVO FLUJO - Configuración inicial con copia automática a tabla operativa
     */
    public function storeNuevoFlujo(Request $request)
    {
        try {
            \Log::info('=== NUEVO FLUJO - STORE INICIADO ===');
            \Log::info('Timestamp: ' . now());
            \Log::info('Data recibida:', $request->all());
            \Log::info('Headers:', $request->headers->all());
            \Log::info('Method: ' . $request->method());
            \Log::info('URL: ' . $request->url());
            
            // DEBUG específico del orgId
            $orgIdFromRequest = $request->input('orgId');
            \Log::info('DEBUG orgId raw: ' . $orgIdFromRequest);
            \Log::info('DEBUG orgId type: ' . gettype($orgIdFromRequest));
            \Log::info('DEBUG orgId isEmpty: ' . (empty($orgIdFromRequest) ? 'YES' : 'NO'));
            \Log::info('DEBUG orgId isNull: ' . (is_null($orgIdFromRequest) ? 'YES' : 'NO'));
            
            // Debugging de todos los campos importantes
            \Log::info('🔍 Todos los inputs:', [
                'orgId' => $request->input('orgId'),
                'saldo_caja_general' => $request->input('saldo_caja_general'),
                'responsable' => $request->input('responsable'),
                '_token' => $request->input('_token') ? 'PRESENTE' : 'AUSENTE'
            ]);
            
            // Debug específico del orgId
            \Log::info('orgId recibido:', [
                'value' => $request->input('orgId'),
                'type' => gettype($request->input('orgId')),
                'is_null' => is_null($request->input('orgId')),
                'is_empty' => empty($request->input('orgId'))
            ]);
            
            // Obtener orgId ANTES de validación (como en método antiguo)
            $orgId = $request->input('orgId');
            \Log::info('DEBUG - orgId obtenido ANTES de validación: ' . $orgId);
            
            // Validación básica (SIN validar orgId aquí, como en método antiguo)
            $request->validate([
                'saldo_caja' => 'required|numeric|min:0',
                'saldo_cc1' => 'nullable|numeric|min:0',
                'saldo_cc2' => 'nullable|numeric|min:0', 
                'saldo_ahorro' => 'nullable|numeric|min:0',
                'responsable' => 'required|string|max:100',
                'banco_cc1' => 'nullable|integer|exists:bancos,id',
                'banco_cc2' => 'nullable|integer|exists:bancos,id',
                'banco_ahorro' => 'nullable|integer|exists:bancos,id',
                'numero_cc1' => 'nullable|string|max:50',
                'numero_cc2' => 'nullable|string|max:50',
                'numero_ahorro' => 'nullable|string|max:50',
            ]);
            
            // Validar orgId manualmente después (más control)
            if (!$orgId || !is_numeric($orgId)) {
                \Log::error('ERROR - orgId inválido: ' . $orgId);
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ID de organización inválido',
                    'debug' => ['orgId_received' => $orgId, 'type' => gettype($orgId)]
                ], 400);
            }
            
            $orgId = intval($orgId); // Convertir a entero
            \Log::info('DEBUG - orgId convertido: ' . $orgId);
            
            if ($orgId <= 0) {
                \Log::error('ERROR: orgId inválido después de conversión: ' . $orgId);
                return response()->json([
                    'success' => false,
                    'message' => 'ID de organización inválido'
                ], 400);
            }
            
            \Log::info("🏢 Procesando para organización: {$orgId}");

            // Array para rastrear cuentas procesadas
            $cuentasProcesadas = [];

            // 1. CAJA GENERAL
            $this->procesarConfiguracionInicial([
                'org_id' => $orgId,
                'tipo_cuenta' => 'caja_general',
                'saldo_inicial' => $request->saldo_caja,
                'responsable' => $request->responsable,
                'banco_id' => null, // Caja general no tiene banco
                'numero_cuenta' => null,
                'observaciones' => 'Caja general - Efectivo'
            ], $cuentasProcesadas);

            // 2. CUENTA CORRIENTE 1
            if ($request->saldo_cc1 > 0 || $request->banco_cc1) {
                $this->procesarConfiguracionInicial([
                    'org_id' => $orgId,
                    'tipo_cuenta' => 'cuenta_corriente_1',
                    'saldo_inicial' => $request->saldo_cc1 ?? 0,
                    'responsable' => $request->responsable,
                    'banco_id' => $request->banco_cc1,
                    'numero_cuenta' => $request->numero_cc1,
                    'observaciones' => 'Cuenta corriente principal'
                ], $cuentasProcesadas);
            }

            // 3. CUENTA CORRIENTE 2
            if ($request->saldo_cc2 > 0 || $request->banco_cc2) {
                $this->procesarConfiguracionInicial([
                    'org_id' => $orgId,
                    'tipo_cuenta' => 'cuenta_corriente_2',
                    'saldo_inicial' => $request->saldo_cc2 ?? 0,
                    'responsable' => $request->responsable,
                    'banco_id' => $request->banco_cc2,
                    'numero_cuenta' => $request->numero_cc2,
                    'observaciones' => 'Cuenta corriente secundaria'
                ], $cuentasProcesadas);
            }

            // 4. CUENTA AHORRO
            if ($request->saldo_ahorro > 0 || $request->banco_ahorro) {
                $this->procesarConfiguracionInicial([
                    'org_id' => $orgId,
                    'tipo_cuenta' => 'cuenta_ahorro',
                    'saldo_inicial' => $request->saldo_ahorro ?? 0,
                    'responsable' => $request->responsable,
                    'banco_id' => $request->banco_ahorro,
                    'numero_cuenta' => $request->numero_ahorro,
                    'observaciones' => 'Cuenta de ahorro principal'
                ], $cuentasProcesadas);
            }

            \Log::info('✅ PROCESO COMPLETADO');
            \Log::info('📊 Resumen:', [
                'total_cuentas' => count($cuentasProcesadas),
                'cuentas' => $cuentasProcesadas,
                'timestamp' => now()
            ]);

            // Redirección con mensaje de éxito para formulario web
            return redirect()->route('cuentas_iniciales.show', $orgId)
                ->with('success', 'Configuración de cuentas guardada exitosamente')
                ->with('cuentas_procesadas', count($cuentasProcesadas));

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('❌ Errores de validación:', $e->errors());
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            \Log::error('❌ Error en store:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error al guardar la configuración: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Procesa la configuración inicial de una cuenta y la copia a la tabla operativa
     */
    private function procesarConfiguracionInicial($datos, &$cuentasProcesadas)
    {
        \Log::info("🔄 Procesando: {$datos['tipo_cuenta']}");

        // Obtener nombre del banco si existe
        $nombreBanco = null;
        if ($datos['banco_id']) {
            $banco = \App\Models\Banco::find($datos['banco_id']);
            $nombreBanco = $banco ? $banco->nombre : null;
        }

        // 1. Guardar/actualizar en configuracion_cuentas_iniciales
        $configuracion = ConfiguracionCuentasIniciales::updateOrCreate(
            [
                'org_id' => $datos['org_id'],
                'tipo_cuenta' => $datos['tipo_cuenta']
            ],
            [
                'saldo_inicial' => $datos['saldo_inicial'],
                'responsable' => $datos['responsable'],
                'banco_id' => $datos['banco_id'],
                'nombre_banco' => $nombreBanco, // ← NUEVO CAMPO
                'numero_cuenta' => $datos['numero_cuenta'],
                'observaciones' => $datos['observaciones'],
                'copiado_a_cuentas' => false // Resetear flag
            ]
        );

        \Log::info("💾 Configuración inicial guardada ID: {$configuracion->id}");

        // 2. Copiar automáticamente a tabla operativa (cuentas)
        $this->copiarACuentasOperativas($configuracion);

        // 3. Marcar como copiado
        $configuracion->update(['copiado_a_cuentas' => true]);

        // 4. Agregar al tracking
        $cuentasProcesadas[] = [
            'tipo' => 'transferencia', // Siempre debe ser un valor permitido
            'subtipo' => isset($datos['subtipo']) ? $datos['subtipo'] : null,
            'saldo' => $datos['saldo_inicial'],
            'banco_id' => $datos['banco_id'],
            'numero_cuenta' => $datos['numero_cuenta'],
            'configuracion_id' => $configuracion->id
        ];

        \Log::info("✅ {$datos['tipo_cuenta']} procesada completamente");
    }

    /**
     * Copia la configuración inicial a la tabla operativa (cuentas)
     */
    private function copiarACuentasOperativas($configuracion)
    {
        $nombreCuenta = $this->generarNombreCuenta($configuracion->tipo_cuenta);
        
        // Obtener nombre del banco si existe
        $nombreBanco = null;
        if ($configuracion->banco_id) {
            $banco = \App\Models\Banco::find($configuracion->banco_id);
            $nombreBanco = $banco ? $banco->nombre : null;
        }
        
        $cuenta = Cuenta::updateOrCreate(
            [
                'org_id' => $configuracion->org_id,
                'tipo' => $configuracion->tipo_cuenta
            ],
            [
                'nombre' => $nombreCuenta,
                'saldo_actual' => $configuracion->saldo_inicial,
                'banco_id' => $configuracion->banco_id,
                'nombre_banco' => $nombreBanco, // ← NUEVO CAMPO
                'numero_cuenta' => $configuracion->numero_cuenta,
                'responsable' => $configuracion->responsable
            ]
        );

        \Log::info("🔗 Cuenta operativa actualizada ID: {$cuenta->id}, Nombre: {$nombreCuenta}, Banco: {$nombreBanco}");
        
        return $cuenta;
    }

    /**
     * Genera nombres descriptivos para las cuentas
     */
    private function generarNombreCuenta($tipoCuenta)
    {
        $nombres = [
            'caja_general' => 'Caja General',
            'cuenta_corriente_1' => 'Cuenta Corriente Principal',
            'cuenta_ahorro' => 'Cuenta de Ahorro Principal',
            'cuenta_corriente_2' => 'Cuenta Corriente Secundaria',
            'cuenta_corriente_3' => 'Cuenta Corriente Terciaria'
        ];

        return $nombres[$tipoCuenta] ?? ucfirst(str_replace('_', ' ', $tipoCuenta));
    }

    /**
     * MÉTODO ANTIGUO - mantenido como backup
     */
    public function storeAntiguo(Request $request)
    {
        try {
            // LOG: Test inicial
            \Log::info('=== MÉTODO STORE INICIADO ===');
            \Log::info('Timestamp: ' . now());
            \Log::info('Request method: ' . $request->method());
            \Log::info('Request headers: ', $request->headers->all());
            \Log::info('Request input count: ' . count($request->all()));
            
            // Helper function para limpiar valores undefined/null/empty
            $cleanValue = function($value) {
                if ($value === 'undefined' || $value === 'null' || $value === '' || $value === null) {
                    return null;
                }
                return $value;
            };
            
            $request->validate([
            'saldo_caja_general' => 'required|numeric',
            'banco_caja_general' => 'nullable|string',
            'numero_caja_general' => 'nullable|string',
            'responsable_caja_general' => 'nullable|string',
            'tipo_cuenta_caja_general' => 'nullable|string',
            'observaciones_caja_general' => 'nullable|string',

            'saldo_cta_corriente_1' => 'required|numeric',
            'banco_cta_corriente_1' => 'nullable|string',
            'numero_cta_corriente_1' => 'nullable|string',
            'responsable_cta_corriente_1' => 'nullable|string',
            'tipo_cuenta_cta_corriente_1' => 'nullable|string',
            'observaciones_cta_corriente_1' => 'nullable|string',

            'saldo_cuenta_ahorro' => 'required|numeric',
            'banco_cuenta_ahorro' => 'nullable|string',
            'numero_cuenta_ahorro' => 'nullable|string',
            'responsable_cuenta_ahorro' => 'nullable|string',
            'tipo_cuenta_cuenta_ahorro' => 'nullable|string',
            'observaciones_cuenta_ahorro' => 'nullable|string',
            
            // Validación para cuentas dinámicas
            'cuentas_dinamicas' => 'nullable|json',
        ]);

        $orgId = $request->input('orgId');
        
        // NUEVO: Manejar actualización solo de bancos
        if ($request->has('solo_bancos') && $request->solo_bancos === 'true') {
            \Log::info('🏦 MODO: Actualización solo de bancos');
            return $this->actualizarSoloBancos($request, $orgId);
        }
        
        // LOG: Debug de datos recibidos
        \Log::info('=== DATOS RECIBIDOS EN STORE ===');
        \Log::info('orgId: ' . $orgId);
        \Log::info('🔍 BANCOS RECIBIDOS:');
        \Log::info('  - banco_cta_corriente_1: ' . ($request->banco_cta_corriente_1 ?? 'NULL'));
        \Log::info('  - banco_cuenta_ahorro: ' . ($request->banco_cuenta_ahorro ?? 'NULL'));
        \Log::info('🔍 SALDOS RECIBIDOS:');
        \Log::info('  - saldo_caja_general: ' . ($request->saldo_caja_general ?? 'NULL'));
        \Log::info('  - saldo_cta_corriente_1: ' . ($request->saldo_cta_corriente_1 ?? 'NULL'));
        \Log::info('  - saldo_cuenta_ahorro: ' . ($request->saldo_cuenta_ahorro ?? 'NULL'));
        \Log::info('cuentas_dinamicas: ' . $request->input('cuentas_dinamicas'));
        \Log::info('Todos los parámetros:', $request->all());
        
        // DEBUG ADICIONAL: Escribir a archivo temporal
        $debugInfo = [
            'timestamp' => now(),
            'orgId' => $orgId,
            'cuentas_dinamicas_raw' => $request->input('cuentas_dinamicas'),
            'has_cuentas_dinamicas' => $request->has('cuentas_dinamicas'),
            'all_request' => $request->all()
        ];
        file_put_contents(storage_path('debug_cuentas.json'), json_encode($debugInfo, JSON_PRETTY_PRINT));
        
        $cuentasGuardadas = []; // Array para tracking de cuentas guardadas
        
        // Caja General
        $cuentaCajaGeneral = Cuenta::where('tipo', 'caja_general')->where('org_id', $orgId)->first();
        if (!$cuentaCajaGeneral) {
            // Crear la cuenta si no existe
            $cuentaCajaGeneral = Cuenta::create([
                'tipo' => 'caja_general',
                'nombre' => 'Caja General',
                'org_id' => $orgId,
                'saldo_actual' => $request->saldo_caja_general,
                'banco_id' => null, // Caja general no tiene banco
                'numero_cuenta' => null, // Caja general no tiene número de cuenta
            ]);
        } else {
            $cuentaCajaGeneral->saldo_actual = $request->saldo_caja_general;
            $cuentaCajaGeneral->save();
        }
        
        $configuracion = ConfiguracionInicial::updateOrCreate(
            [
                'org_id' => $orgId,
                'cuenta_id' => $cuentaCajaGeneral->id,
            ],
            [
                'saldo_inicial' => $request->saldo_caja_general,
                'responsable' => $cleanValue($request->responsable_caja_general),
                'banco' => 'Efectivo', // Mostrar "Efectivo" para caja general
                'numero_cuenta' => null,
                'tipo_cuenta' => $cleanValue($request->tipo_cuenta_caja_general),
                'observaciones' => $cleanValue($request->observaciones_caja_general),
            ]
        );
        \Log::info('✅ CAJA GENERAL GUARDADA - ID: ' . $configuracion->id . ', Saldo: ' . $request->saldo_caja_general);
        $cuentasGuardadas[] = ['tipo' => 'caja_general', 'saldo' => $request->saldo_caja_general];
        
        // Buscar banco por código para obtener el ID y nombre
        $bancoId = null;
        $bancoNombre = null;
        \Log::info('🔍 BANCO CORRIENTE 1 - Código recibido: ' . $request->banco_cta_corriente_1);
        if ($request->banco_cta_corriente_1) {
            // Buscar el banco por código para obtener el ID
            $banco = \App\Models\Banco::where('codigo', $request->banco_cta_corriente_1)->first();
            if (!$banco) {
                // Si no existe, crear el banco con código y nombre predeterminado
                $bancosEstaticos = [
                    'banco_estado' => 'Banco Estado',
                    'banco_chile' => 'Banco de Chile',
                    'banco_bci' => 'BCI',
                    'banco_santander' => 'Santander',
                    'banco_itau' => 'Itaú',
                    'banco_scotiabank' => 'Scotiabank',
                    'banco_bice' => 'BICE',
                    'banco_security' => 'Security',
                    'banco_falabella' => 'Falabella',
                ];
                
                if (isset($bancosEstaticos[$request->banco_cta_corriente_1])) {
                    $banco = \App\Models\Banco::create([
                        'codigo' => $request->banco_cta_corriente_1,
                        'nombre' => $bancosEstaticos[$request->banco_cta_corriente_1]
                    ]);
                }
            }
            
            if ($banco) {
                $bancoId = $banco->id;
                $bancoNombre = $banco->nombre;
                \Log::info('✅ BANCO ENCONTRADO - ID: ' . $bancoId . ', Nombre: ' . $bancoNombre);
            } else {
                \Log::warning('⚠️ BANCO NO ENCONTRADO para código: ' . $request->banco_cta_corriente_1);
            }
        } else {
            \Log::info('ℹ️ NO SE ENVIÓ CÓDIGO DE BANCO para cuenta corriente 1');
        }
        
        $cuentaCorriente1 = Cuenta::where('tipo', 'cuenta_corriente_1')->where('org_id', $orgId)->first();
        if (!$cuentaCorriente1) {
            // Crear la cuenta si no existe
            $cuentaCorriente1 = Cuenta::create([
                'tipo' => 'cuenta_corriente_1',
                'nombre' => 'Cuenta Corriente Principal',
                'org_id' => $orgId,
                'saldo_actual' => $request->saldo_cta_corriente_1,
                'banco_id' => $bancoId,
                'numero_cuenta' => $request->numero_cta_corriente_1,
            ]);
        } else {
            $cuentaCorriente1->saldo_actual = $request->saldo_cta_corriente_1;
            $cuentaCorriente1->banco_id = $bancoId;
            $cuentaCorriente1->numero_cuenta = $request->numero_cta_corriente_1;
            $cuentaCorriente1->save();
        }
        
        $configuracion = ConfiguracionInicial::updateOrCreate(
            [
                'org_id' => $orgId,
                'cuenta_id' => $cuentaCorriente1->id,
            ],
            [
                'saldo_inicial' => $request->saldo_cta_corriente_1,
                'responsable' => $cleanValue($request->responsable_cta_corriente_1),
                'banco' => $bancoNombre, // Guardar el nombre del banco en lugar del código
                'numero_cuenta' => $cleanValue($request->numero_cta_corriente_1),
                'tipo_cuenta' => $cleanValue($request->tipo_cuenta_cta_corriente_1),
                'observaciones' => $cleanValue($request->observaciones_cta_corriente_1),
            ]
        );
        \Log::info('💾 GUARDANDO EN configuracion_cuentas_iniciales - Banco: ' . ($bancoNombre ?? 'NULL') . ', Número cuenta: ' . $request->numero_cta_corriente_1);
        \Log::info('✅ CUENTA CORRIENTE 1 GUARDADA - ID: ' . $configuracion->id . ', Saldo: ' . $request->saldo_cta_corriente_1);
        $cuentasGuardadas[] = ['tipo' => 'cuenta_corriente_1', 'saldo' => $request->saldo_cta_corriente_1];
        
        // Cuenta de Ahorro
        $bancoIdAhorro = null;
        $bancoNombreAhorro = null;
        \Log::info('🔍 BANCO AHORRO - Código recibido: ' . $request->banco_cuenta_ahorro);
        if ($request->banco_cuenta_ahorro) {
            // Buscar el banco por código para obtener el ID
            $banco = \App\Models\Banco::where('codigo', $request->banco_cuenta_ahorro)->first();
            if (!$banco) {
                // Si no existe, crear el banco con código y nombre predeterminado
                $bancosEstaticos = [
                    'banco_estado' => 'Banco Estado',
                    'banco_chile' => 'Banco de Chile',
                    'banco_bci' => 'BCI',
                    'banco_santander' => 'Santander',
                    'banco_itau' => 'Itaú',
                    'banco_scotiabank' => 'Scotiabank',
                    'banco_bice' => 'BICE',
                    'banco_security' => 'Security',
                    'banco_falabella' => 'Falabella',
                ];
                
                if (isset($bancosEstaticos[$request->banco_cuenta_ahorro])) {
                    $banco = \App\Models\Banco::create([
                        'codigo' => $request->banco_cuenta_ahorro,
                        'nombre' => $bancosEstaticos[$request->banco_cuenta_ahorro]
                    ]);
                }
            }
            
            if ($banco) {
                $bancoIdAhorro = $banco->id;
                $bancoNombreAhorro = $banco->nombre;
                \Log::info('✅ BANCO AHORRO ENCONTRADO - ID: ' . $bancoIdAhorro . ', Nombre: ' . $bancoNombreAhorro);
            } else {
                \Log::warning('⚠️ BANCO AHORRO NO ENCONTRADO para código: ' . $request->banco_cuenta_ahorro);
            }
        } else {
            \Log::info('ℹ️ NO SE ENVIÓ CÓDIGO DE BANCO para cuenta de ahorro');
        }
        
        $cuentaAhorro = Cuenta::where('tipo', 'cuenta_ahorro')->where('org_id', $orgId)->first();
        if (!$cuentaAhorro) {
            // Crear la cuenta si no existe
            $cuentaAhorro = Cuenta::create([
                'tipo' => 'cuenta_ahorro',
                'nombre' => 'Cuenta de Ahorro Principal',
                'org_id' => $orgId,
                'saldo_actual' => $request->saldo_cuenta_ahorro,
                'banco_id' => $bancoIdAhorro,
                'numero_cuenta' => $request->numero_cuenta_ahorro,
            ]);
        } else {
            $cuentaAhorro->saldo_actual = $request->saldo_cuenta_ahorro;
            $cuentaAhorro->banco_id = $bancoIdAhorro;
            $cuentaAhorro->numero_cuenta = $request->numero_cuenta_ahorro;
            $cuentaAhorro->save();
        }
        
        $configuracion = ConfiguracionInicial::updateOrCreate(
            [
                'org_id' => $orgId,
                'cuenta_id' => $cuentaAhorro->id,
            ],
            [
                'saldo_inicial' => $request->saldo_cuenta_ahorro,
                'responsable' => $cleanValue($request->responsable_cuenta_ahorro),
                'banco' => $bancoNombreAhorro, // Guardar el nombre del banco en lugar del código
                'numero_cuenta' => $cleanValue($request->numero_cuenta_ahorro),
                'tipo_cuenta' => $cleanValue($request->tipo_cuenta_cuenta_ahorro),
                'observaciones' => $cleanValue($request->observaciones_cuenta_ahorro),
            ]
        );
        \Log::info('💾 GUARDANDO AHORRO EN configuracion_cuentas_iniciales - Banco: ' . ($bancoNombreAhorro ?? 'NULL') . ', Número cuenta: ' . $request->numero_cuenta_ahorro);
        \Log::info('✅ CUENTA AHORRO GUARDADA - ID: ' . $configuracion->id . ', Saldo: ' . $request->saldo_cuenta_ahorro);
        $cuentasGuardadas[] = ['tipo' => 'cuenta_ahorro', 'saldo' => $request->saldo_cuenta_ahorro];

        // NUEVO: Procesar cuentas dinámicas
        $cuentasDinamicasGuardadas = 0;
        $debugCuentasDinamicas = [];
        
        try {
            if ($request->has('cuentas_dinamicas')) {
                $cuentasDinamicasRaw = $request->input('cuentas_dinamicas');
                $debugCuentasDinamicas['raw_data'] = $cuentasDinamicasRaw;
                $debugCuentasDinamicas['is_empty'] = empty($cuentasDinamicasRaw);
                
                $cuentasDinamicas = json_decode($cuentasDinamicasRaw, true);
                $debugCuentasDinamicas['decoded_data'] = $cuentasDinamicas;
                $debugCuentasDinamicas['json_error'] = json_last_error();
                
                \Log::info('Cuentas dinámicas decodificadas:', $cuentasDinamicas);
                
                if (is_array($cuentasDinamicas) && count($cuentasDinamicas) > 0) {
                    $debugCuentasDinamicas['count'] = count($cuentasDinamicas);
                    
                    foreach ($cuentasDinamicas as $index => $cuentaDinamica) {
                        \Log::info('Procesando cuenta dinámica #' . ($index + 1) . ':', $cuentaDinamica);
                        
                        // Usar el tipo específico (ej: cuenta_corriente_2, cuenta_ahorro_3)
                        $tipoEspecifico = $cuentaDinamica['tipo'];
                        
                        // Limpiar valores undefined o null
                        $banco = $cuentaDinamica['banco'] ?? '';
                        if ($banco === 'undefined' || $banco === 'null') {
                            $banco = '';
                        }
                        
                        $numero = $cuentaDinamica['numero'] ?? '';
                        if ($numero === 'undefined' || $numero === 'null') {
                            $numero = '';
                        }
                        
                        $responsable = $cuentaDinamica['responsable'] ?? '';
                        if ($responsable === 'undefined' || $responsable === 'null') {
                            $responsable = '';
                        }
                        
                        $saldo = floatval($cuentaDinamica['saldo'] ?? 0);
                        
                // Buscar banco por código si se proporciona
                $bancoId = null;
                $bancoNombreDinamico = null;
                if (!empty($banco)) {
                    $bancoObj = \App\Models\Banco::where('codigo', $banco)->first();
                    if (!$bancoObj) {
                        // Si no existe, crear el banco con código y nombre predeterminado
                        $bancosEstaticos = [
                            'banco_estado' => 'Banco Estado',
                            'banco_chile' => 'Banco de Chile',
                            'banco_bci' => 'BCI',
                            'banco_santander' => 'Santander',
                            'banco_itau' => 'Itaú',
                            'banco_scotiabank' => 'Scotiabank',
                            'banco_bice' => 'BICE',
                            'banco_security' => 'Security',
                            'banco_falabella' => 'Falabella',
                        ];
                        
                        if (isset($bancosEstaticos[$banco])) {
                            $bancoObj = \App\Models\Banco::create([
                                'codigo' => $banco,
                                'nombre' => $bancosEstaticos[$banco]
                            ]);
                        }
                    }
                    
                    if ($bancoObj) {
                        $bancoId = $bancoObj->id;
                        $bancoNombreDinamico = $bancoObj->nombre;
                        \Log::info('📍 BANCO ENCONTRADO para cuenta dinámica: Código ' . $banco . ' = ID ' . $bancoId . ' = ' . $bancoNombreDinamico);
                    } else {
                        \Log::warning('⚠️ BANCO NO ENCONTRADO para cuenta dinámica: ' . $banco);
                    }
                }                        $nombreCuenta = !empty($banco) ? $banco : $tipoEspecifico;
                        
                        // Buscar si ya existe esta cuenta específica para esta organización
                        $cuenta = Cuenta::where([
                            'tipo' => $tipoEspecifico,
                            'org_id' => $orgId
                        ])->first();
                        
                        if (!$cuenta) {
                            // Crear nueva cuenta con tipo específico
                            $cuenta = Cuenta::create([
                                'tipo' => $tipoEspecifico,
                                'nombre' => $nombreCuenta,
                                'banco_id' => $bancoId, // Usar banco_id en lugar de banco
                                'numero_cuenta' => $numero,
                                'org_id' => $orgId,
                                'saldo_actual' => $saldo,
                            ]);
                            \Log::info('✅ NUEVA CUENTA DINÁMICA CREADA: ' . $tipoEspecifico . ' (ID: ' . $cuenta->id . ') con banco_id: ' . $bancoId);
                            $debugCuentasDinamicas['accounts_created'][] = $tipoEspecifico;
                        } else {
                            // Actualizar cuenta existente
                            $cuenta->update([
                                'banco_id' => $bancoId, // Usar banco_id en lugar de banco
                                'numero_cuenta' => $numero,
                                'saldo_actual' => $saldo,
                                'nombre' => $nombreCuenta,
                            ]);
                            \Log::info('✅ CUENTA DINÁMICA ACTUALIZADA: ' . $tipoEspecifico . ' (ID: ' . $cuenta->id . ')');
                            $debugCuentasDinamicas['accounts_updated'][] = $tipoEspecifico;
                        }
                        
                        // Verificar si ya tiene configuración inicial
                        $configuracionExistente = ConfiguracionInicial::where([
                            'org_id' => $orgId,
                            'cuenta_id' => $cuenta->id
                        ])->first();
                        
                        if ($configuracionExistente) {
                            \Log::info('Actualizando configuración inicial existente para: ' . $tipoEspecifico);
                            // Actualizar configuración existente
                            $configuracionExistente->update([
                                'saldo_inicial' => $saldo,
                                'responsable' => $cleanValue($responsable),
                                'banco' => $bancoNombreDinamico ?? null, // Guardar el nombre del banco en lugar del código
                                'numero_cuenta' => $cleanValue($numero),
                            ]);
                            $configuracionId = $configuracionExistente->id;
                            $debugCuentasDinamicas['configs_updated'][] = $tipoEspecifico;
                        } else {
                            // Crear nueva configuración inicial
                            $configuracion = ConfiguracionInicial::create([
                                'org_id' => $orgId,
                                'cuenta_id' => $cuenta->id,
                                'saldo_inicial' => $saldo,
                                'responsable' => $cleanValue($responsable),
                                'banco' => $bancoNombreDinamico ?? null, // Guardar el nombre del banco en lugar del código
                                'numero_cuenta' => $cleanValue($numero),
                                'tipo_cuenta' => null,
                                'observaciones' => 'Cuenta dinámica: ' . $tipoEspecifico,
                            ]);
                            $configuracionId = $configuracion->id;
                            \Log::info('✅ NUEVA CONFIGURACIÓN INICIAL CREADA para: ' . $tipoEspecifico);
                            $debugCuentasDinamicas['configs_created'][] = $tipoEspecifico;
                        }
                        
                        \Log::info('✅ CUENTA DINÁMICA COMPLETAMENTE GUARDADA: ' . $tipoEspecifico . ' - Config ID: ' . $configuracionId . ', Saldo: ' . $saldo);
                        $cuentasGuardadas[] = ['tipo' => $tipoEspecifico, 'saldo' => $saldo];
                        $cuentasDinamicasGuardadas++;
                    }
                } else {
                    $debugCuentasDinamicas['error'] = 'No es array válido o está vacío';
                    \Log::warning('cuentas_dinamicas no es un array válido o está vacío');
                }
            } else {
                $debugCuentasDinamicas['error'] = 'No se encontró el parámetro cuentas_dinamicas';
                \Log::info('No se encontraron cuentas dinámicas en la request');
            }
        } catch (\Exception $e) {
            $debugCuentasDinamicas['exception'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            \Log::error('Error procesando cuentas dinámicas: ' . $e->getMessage());
        }
        
        // Escribir debug adicional
        file_put_contents(storage_path('debug_cuentas_dinamicas.json'), json_encode($debugCuentasDinamicas, JSON_PRETTY_PRINT));
        
        // LOG FINAL: Resumen de lo guardado
        \Log::info('=== RESUMEN FINAL DE GUARDADO ===');
        \Log::info('Total cuentas básicas guardadas: 3 (caja_general, cuenta_corriente_1, cuenta_ahorro)');
        \Log::info('Total cuentas dinámicas guardadas: ' . $cuentasDinamicasGuardadas);
        \Log::info('Todas las cuentas guardadas:', $cuentasGuardadas);
        \Log::info('=== FIN DE PROCESAMIENTO ===');

        // Verificar si es una petición AJAX
        if ($request->ajax()) {
            $totalCuentasGuardadas = count($cuentasGuardadas);
            $mensaje = "✅ Configuración guardada exitosamente: {$totalCuentasGuardadas} cuentas procesadas";
            if ($cuentasDinamicasGuardadas > 0) {
                $mensaje .= " (incluyendo {$cuentasDinamicasGuardadas} cuentas dinámicas)";
            }
            
            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'debug_info' => [
                    'total_cuentas_guardadas' => $totalCuentasGuardadas,
                    'cuentas_dinamicas_guardadas' => $cuentasDinamicasGuardadas,
                    'cuentas_detalle' => $cuentasGuardadas,
                    'timestamp' => now()->format('Y-m-d H:i:s'),
                    'cuentas_dinamicas_debug' => $debugCuentasDinamicas,
                    'org_id' => $orgId
                ],
                'redirect' => route('cuentas_iniciales.show', ['id' => $request->orgId])
            ]);
        }

        $totalCuentasGuardadas = count($cuentasGuardadas);
        $mensaje = "Configuración guardada exitosamente: {$totalCuentasGuardadas} cuentas procesadas";
        if ($cuentasDinamicasGuardadas > 0) {
            $mensaje .= " (incluyendo {$cuentasDinamicasGuardadas} cuentas dinámicas)";
        }
        
        return redirect()->route('cuentas_iniciales.show', ['id' => $request->orgId])->with('success', $mensaje);
        
        } catch (\Exception $e) {
            \Log::error('❌ ERROR CRÍTICO EN STORE: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Error line: ' . $e->getLine());
            \Log::error('Error file: ' . $e->getFile());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno del servidor: ' . $e->getMessage(),
                    'error_details' => [
                        'message' => $e->getMessage(),
                        'line' => $e->getLine(),
                        'file' => basename($e->getFile())
                    ]
                ], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'Error interno del servidor: ' . $e->getMessage()]);
        }
    }


    // Método index eliminado - funcionalidad movida a mostrarLibroCaja


    public function mostrarLibroCaja($id)
    {
        $movimientos = \App\Models\Movimiento::where('org_id', $id)
            ->orderBy('fecha', 'desc')
            ->get();
        $resumen = $this->getResumenSaldos($id);
        $bancos = \App\Models\Banco::all();

        // Calcular totales para la vista tabular
        $saldoCajaGeneral = $resumen['cuentaCajaGeneralOperativa']->saldo_actual ?? 0;
        $saldoCuentaCorriente1 = $resumen['cuentaCorriente1Operativa']->saldo_actual ?? 0;
        $saldoCuentaCorriente2 = $resumen['cuentaCorriente2Operativa']->saldo_actual ?? 0;
        $saldoCuentaAhorro = $resumen['cuentaAhorroOperativa']->saldo_actual ?? 0;
        $saldoTotal = $saldoCajaGeneral + $saldoCuentaCorriente1 + $saldoCuentaCorriente2 + $saldoCuentaAhorro;

        $totalIngresos = $movimientos->where('tipo', 'ingreso')->sum('monto');
        $totalEgresos = $movimientos->where('tipo', 'egreso')->sum('monto');
        $saldoFinal = $saldoTotal + $totalIngresos - $totalEgresos;

        // Detectar si el usuario es CRC
        $usuarioCRC = (auth()->user()->rol ?? '') === 'crc';

        return view('orgs.contable.libro-caja', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
            'bancos' => $bancos,
            'saldoCajaGeneral' => $saldoCajaGeneral,
            'saldoCuentaCorriente1' => $saldoCuentaCorriente1,
            'saldoCuentaCorriente2' => $saldoCuentaCorriente2,
            'saldoCuentaAhorro' => $saldoCuentaAhorro,
            'saldoTotal' => $saldoTotal,
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'saldoFinal' => $saldoFinal,
            'usuarioCRC' => $usuarioCRC,
        ], $resumen));
    }

    /**
     * Centraliza la obtención de saldos iniciales y objetos de cuentas para el partial y las vistas
     */
    private function getResumenSaldos($orgId)
    {
        // Usar directamente la tabla de cuentas operativas (nuevo flujo)
        $cuentaCajaGeneral = Cuenta::where('org_id', $orgId)->where('tipo', 'caja_general')->first();
        $cuentaCorriente1 = Cuenta::where('org_id', $orgId)->where('tipo', 'cuenta_corriente_1')->first();
        $cuentaAhorro = Cuenta::where('org_id', $orgId)->where('tipo', 'cuenta_ahorro')->first();

        // Obtener configuraciones iniciales para saldos históricos
        $configCaja = ConfiguracionCuentasIniciales::where('org_id', $orgId)->where('tipo_cuenta', 'caja_general')->first();
        $configCorriente = ConfiguracionCuentasIniciales::where('org_id', $orgId)->where('tipo_cuenta', 'cuenta_corriente_1')->first();
        $configAhorro = ConfiguracionCuentasIniciales::where('org_id', $orgId)->where('tipo_cuenta', 'cuenta_ahorro')->first();

        // Saldos iniciales (desde configuración)
        $saldosIniciales = [
            'Caja General' => $configCaja->saldo_inicial ?? null,
            'Cuenta Corriente 1' => $configCorriente->saldo_inicial ?? null,
            'Cuenta de Ahorro' => $configAhorro->saldo_inicial ?? null,
        ];

        // Saldos actuales (desde tabla operativa)
        $cuentaCajaGeneralActual = $cuentaCajaGeneral ? $cuentaCajaGeneral->saldo_actual : null;
        $cuentaCorriente1Actual = $cuentaCorriente1 ? $cuentaCorriente1->saldo_actual : null;
        $cuentaAhorroActual = $cuentaAhorro ? $cuentaAhorro->saldo_actual : null;

        // Suma total inicial y actual
        $totalSaldoInicial = array_sum(array_filter($saldosIniciales, fn($v) => $v !== null));
        $totalSaldoActual = ($cuentaCajaGeneralActual ?? 0) + ($cuentaCorriente1Actual ?? 0) + ($cuentaAhorroActual ?? 0);
        $saldosIniciales['Saldo Total'] = $totalSaldoInicial + $totalSaldoActual;

        // Si no hay saldos ni movimientos, retornar null en totales
        $noDatos = ($totalSaldoInicial === 0 && $totalSaldoActual === 0);
        $totalIngresos = $noDatos ? null : 0;
        $totalEgresos = $noDatos ? null : 0;
        $saldoFinal = $noDatos ? null : ($saldosIniciales['Saldo Total'] + $totalIngresos - $totalEgresos);

        return [
            'saldosIniciales' => $saldosIniciales,
            'cuentaCajaGeneral' => $configCaja, // Configuración inicial para compatibilidad
            'cuentaCorriente1' => $configCorriente,
            'cuentaAhorro' => $configAhorro,
            'cuentaCajaGeneralOperativa' => $cuentaCajaGeneral, // Cuentas operativas
            'cuentaCorriente1Operativa' => $cuentaCorriente1,
            'cuentaAhorroOperativa' => $cuentaAhorro,
            'totalSaldoInicial' => $noDatos ? null : $totalSaldoInicial,
            'totalSaldoActual' => $noDatos ? null : $totalSaldoActual,
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'saldoFinal' => $saldoFinal,
        ];
    }

    /**
     * Muestra el balance general de la organización
     */
    public function balance($id)
    {
        $resumen = $this->getResumenSaldos($id);
        $movimientos = Movimiento::where('org_id', $id)->orderBy('fecha', 'desc')->get();

        if ($movimientos) {
            // Procesar totales por categoría de ingresos
            $categoriasIngresos = [
                'Cuotas de Incorporación' => $movimientos->where('categoria', 'Cuotas de Incorporación')->sum('monto'),
                'Consumo de Agua' => $movimientos->where('categoria', 'Consumo de Agua')->sum('monto'),
                'Otros Ingresos' => $movimientos->where('categoria', 'Otros Ingresos')->sum('monto'),
                'Multas y Recargos' => $movimientos->where('categoria', 'Multas y Recargos')->sum('monto'),
            ];

            // Procesar totales por categoría de egresos
            $categoriasEgresos = [
                'Giros y Transferencias' => $movimientos->where('categoria', 'Giros y Transferencias')->sum('monto'),
                'Gastos Operacionales' => $movimientos->where('categoria', 'Gastos Operacionales')->sum('monto'),
                'Gastos Administrativos' => $movimientos->where('categoria', 'Gastos Administrativos')->sum('monto'),
                'Mantenimiento' => $movimientos->where('categoria', 'Mantenimiento')->sum('monto'),
            ];

            // Datos para gráficos
            $datosIngresos = [
                'labels' => array_keys($categoriasIngresos),
                'data' => array_values($categoriasIngresos),
            ];
            $datosEgresos = [
                'labels' => array_keys($categoriasEgresos),
                'data' => array_values($categoriasEgresos),
            ];

            // Ratios y análisis financiero
            $ratioLiquidez = $resumen['totalSaldoActual'] > 0 ? $resumen['totalSaldoActual'] / ($resumen['totalEgresos'] ?: 1) : 1.5;
            $roe = $resumen['saldoFinal'] > 0 ? ($resumen['saldoFinal'] / ($resumen['totalSaldoInicial'] ?: 1)) * 100 : 12.5;
            $ratioEndeudamiento = $resumen['totalEgresos'] > 0 ? ($resumen['totalEgresos'] / ($resumen['totalSaldoActual'] ?: 1)) * 100 : 35;
            $margenOperacional = $resumen['totalIngresos'] > 0 ? (($resumen['totalIngresos'] - $resumen['totalEgresos']) / $resumen['totalIngresos']) * 100 : 18.3;

            // Últimos movimientos (máx 10)
            $ultimosMovimientos = $movimientos->sortByDesc('fecha')->take(10);
        } else {
            $categoriasIngresos = $categoriasEgresos = $datosIngresos = $datosEgresos = [];
            $ratioLiquidez = $roe = $ratioEndeudamiento = $margenOperacional = 0;
            $ultimosMovimientos = collect();
        }

        $cuentas = Cuenta::where('org_id', $id)->get();
        $categorias = Categoria::all();
    return view('orgs.contable.balance', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
            'categoriasIngresos' => $categoriasIngresos,
            'categoriasEgresos' => $categoriasEgresos,
            'datosIngresos' => $datosIngresos,
            'datosEgresos' => $datosEgresos,
            'ratioLiquidez' => $ratioLiquidez,
            'roe' => $roe,
            'ratioEndeudamiento' => $ratioEndeudamiento,
            'margenOperacional' => $margenOperacional,
            'ultimosMovimientos' => $ultimosMovimientos,
            'cuentas' => $cuentas,
            'categorias' => $categorias,
        ], $resumen));
    }

    /**
     * Muestra la conciliación bancaria
     */
    public function conciliacionBancaria($id)
    {
        $resumen = $this->getResumenSaldos($id);
        $movimientos = \App\Models\Movimiento::where('org_id', $id)
            ->orderBy('fecha', 'desc')->get();

        // Obtener extractos bancarios desde la base de datos (tabla extractos)
        $extractos = \DB::table('extractos')
            ->where('org_id', $id)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('orgs.contable.conciliacion_bancaria', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
            'extractos' => $extractos,
        ], $resumen));
    }

    /**
     * Muestra los movimientos contables
     */
    public function movimientos($id)
    {
        // HABILITADO: tabla movimientos recreada exitosamente
        $movimientos = \App\Models\Movimiento::where('org_id', $id)
            ->orderBy('fecha', 'desc')->get();
        // $movimientos = collect(); // Empty collection - YA NO NECESARIO
        $resumen = $this->getResumenSaldos($id);

        return view('orgs.contable.movimientos', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
        ], $resumen));
    }

    /**
     * Muestra el informe por rubro
     */
    public function informePorRubro($id)
    {
        // Obtener movimientos de la organización
        $movimientos = Movimiento::where('org_id', $id)->orderBy('fecha', 'desc')->get();

        // Procesar ingresos y egresos por rubro
        $rubrosIngresos = [];
        $rubrosEgresos = [];
        $ingresosTotales = 0;
        $egresosTotales = 0;

        foreach ($movimientos as $mov) {
            // Usar el campo 'tipo' del movimiento para identificar ingresos/egresos
            $tipoRaw = $mov->tipo ?? ($mov->monto >= 0 ? 'ingreso' : 'egreso');
            $tipo = strtolower(trim($tipoRaw));
            $isIngreso = ($tipo === 'ingreso');
            $isEgreso = ($tipo === 'egreso');
            // Si el movimiento tiene una relación con Categoria, usar su nombre
            if ($mov->categoria instanceof \App\Models\Categoria) {
                $rubro = $mov->categoria->nombre;
            } elseif (is_string($mov->categoria)) {
                $rubro = $mov->categoria;
            } elseif (!empty($mov->rubro)) {
                $rubro = $mov->rubro;
            } else {
                $rubro = 'Sin categoría';
            }
            $rubro = (string)$rubro;
            if ($isIngreso) {
                if (!isset($rubrosIngresos[$rubro])) {
                    $rubrosIngresos[$rubro] = [
                        'nombre' => $rubro,
                        'total' => 0,
                        'movimientos' => []
                    ];
                }
                $rubrosIngresos[$rubro]['total'] += abs($mov->monto);
                $rubrosIngresos[$rubro]['movimientos'][] = $mov;
                $ingresosTotales += abs($mov->monto);
            } elseif ($isEgreso) {
                if (!isset($rubrosEgresos[$rubro])) {
                    $rubrosEgresos[$rubro] = [
                        'nombre' => $rubro,
                        'total' => 0,
                        'movimientos' => []
                    ];
                }
                $rubrosEgresos[$rubro]['total'] += abs($mov->monto);
                $rubrosEgresos[$rubro]['movimientos'][] = $mov;
                $egresosTotales += abs($mov->monto);
            }
        }

        // Ordenar por total
        $rubrosIngresos = collect($rubrosIngresos)->sortByDesc('total')->values()->all();
        $rubrosEgresos = collect($rubrosEgresos)->sortByDesc('total')->values()->all();

        // Calcular operaciones vs administración (ejemplo: 70% operaciones, 30% administración)
        $operacionesTotal = round($ingresosTotales * 0.7);
        $administracionTotal = $ingresosTotales - $operacionesTotal;

        $resumen = $this->getResumenSaldos($id);

        return view('orgs.contable.informe_por_rubro', array_merge([
            'orgId' => $id,
            'rubrosIngresos' => $rubrosIngresos,
            'rubrosEgresos' => $rubrosEgresos,
            'operacionesTotal' => $operacionesTotal,
            'administracionTotal' => $administracionTotal,
            'ingresosTotales' => $ingresosTotales,
            'egresosTotales' => $egresosTotales,
        ], $resumen));
    }

    /**
     * Muestra el registro de ingresos y egresos
     */
    public function registroIngresosEgresos($id)
    {
        // $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($id) {
        //     $q->where('org_id', $id);
        // })->orderBy('fecha', 'desc')->get();
        $movimientos = collect(); // Empty collection - table was deleted
        $resumen = $this->getResumenSaldos($id);
        $categoriasIngresos = \App\Models\Categoria::where('tipo', 'ingreso')->orderBy('nombre')->get();
        $categoriasEgresos = \App\Models\Categoria::where('tipo', 'egreso')->orderBy('nombre')->get();
        
        // Obtener cuentas operativas desde la tabla cuentas
        $cuentas = \App\Models\Cuenta::where('org_id', $id)
            ->orderBy('tipo')
            ->get();
        
        // Obtener configuraciones iniciales con nombres de banco (usando nueva tabla) - LEGACY
        $configuracionesIniciales = ConfiguracionCuentasIniciales::where('org_id', $id)
            ->whereNotNull('banco_id')
            ->with('banco')
            ->orderBy('created_at')
            ->get();

        return view('orgs.contable.registro_ingresos_egresos', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
            'categoriasIngresos' => $categoriasIngresos,
            'categoriasEgresos' => $categoriasEgresos,
            'cuentas' => $cuentas, // Nueva variable para los selects
            'configuracionesIniciales' => $configuracionesIniciales, // Mantener para compatibilidad
        ], $resumen));
    }

    /**
     * Muestra las cuentas iniciales
     */
    public function cuentasIniciales($id)
    {
        // Usar las nuevas tablas del flujo
        $configuraciones = ConfiguracionCuentasIniciales::where('org_id', $id)->get();
        $cuentasIniciales = \App\Models\Cuenta::where('org_id', $id)->get();
        $bancos = \App\Models\Banco::orderBy('nombre')->get();
        $resumen = $this->getResumenSaldos($id);

        return view('orgs.contable.cuentas_iniciales_modal', array_merge([
            'orgId' => $id,
            'cuentasIniciales' => $cuentasIniciales,
            'configuraciones' => $configuraciones,
            'bancos' => $bancos,
            'active' => 'cuentas-iniciales',
        ], $resumen));
    }

    /**
     * Muestra una cuenta inicial específica
     */
    public function mostrarCuentaInicial($id, $cuenta)
    {
        $configuracion = ConfiguracionCuentasIniciales::with('banco')->where('org_id', $id)->where('id', $cuenta)->first();
        $bancos = \App\Models\Banco::orderBy('nombre')->get();
        $resumen = $this->getResumenSaldos($id);

        return view('orgs.contable.cuentas_iniciales_modal', array_merge([
            'orgId' => $id,
            'configuracion' => $configuracion,
            'bancos' => $bancos,
        ], $resumen));
    }

    /**
     * Muestra la configuración de cuentas
     */
    public function configuracionCuentas($id)
    {
        $configuraciones = ConfiguracionCuentasIniciales::with('banco')->where('org_id', $id)->get();
        $cuentas = \App\Models\Cuenta::where('org_id', $id)->get();
        $bancos = \App\Models\Banco::orderBy('nombre')->get();
        $resumen = $this->getResumenSaldos($id);

        return view('orgs.contable.cuentas_iniciales_modal', array_merge([
            'orgId' => $id,
            'cuentas' => $cuentas,
            'configuraciones' => $configuraciones,
            'bancos' => $bancos,
        ], $resumen));
    }

    /**
     * Obtiene los datos de las cuentas iniciales por AJAX
     */
    public function obtenerDatosCuentasIniciales($id)
    {
        try {
            $configuraciones = ConfiguracionCuentasIniciales::where('org_id', $id)->get();
            
            return response()->json([
                'success' => true,
                'cuentas' => $configuraciones->map(function($config) {
                    return [
                        'tipo' => $config->tipo,
                        'saldo_inicial' => $config->saldo_inicial,
                        'saldo_actual' => $config->saldo_actual,
                        'banco' => $config->banco,
                        'numero_cuenta' => $config->numero_cuenta,
                        'responsable' => $config->responsable,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guarda las cuentas iniciales desde el modal
     */
    public function guardarCuentasIniciales(Request $request, $id)
    {
        try {
            \DB::beginTransaction();

            // Validar datos básicos - añadimos cuenta corriente 2
            $request->validate([
                'saldo_caja_general' => 'required|integer|min:0',
                'saldo_cta_corriente_1' => 'required|integer|min:0',
                'saldo_cta_corriente_2' => 'nullable|integer|min:0',
                'saldo_cuenta_ahorro' => 'required|integer|min:0',
                'responsable' => 'nullable|string|max:255',
            ]);

            // Usar los nombres exactos de los campos del formulario
            $cuentasParaGuardar = [
                [
                    'tipo_cuenta' => 'caja_general',
                    'saldo' => $request->saldo_caja_general,
                    'banco_id' => 'EFECTIVO', // Caja General es efectivo
                    'nombre_banco' => 'CAJA', // Nombre específico para caja
                    'numero' => '99',   // Identificador específico para cuenta caja
                    'responsable' => $request->responsable ?? '',
                ],
                [
                    'tipo_cuenta' => 'cuenta_corriente_1',
                    'saldo' => $request->saldo_cta_corriente_1,
                    'banco_id' => $request->banco_cta_corriente_1 ?: null,
                    'nombre_banco' => $this->obtenerNombreBanco($request->banco_cta_corriente_1),
                    'numero' => $request->numero_cta_corriente_1 ?? '',
                    'responsable' => $request->responsable ?? '',
                ],
                [
                    'tipo_cuenta' => 'cuenta_corriente_2',
                    'saldo' => $request->saldo_cta_corriente_2 ?? 0,
                    'banco_id' => $request->banco_cta_corriente_2 ?: null,
                    'nombre_banco' => $this->obtenerNombreBanco($request->banco_cta_corriente_2),
                    'numero' => $request->numero_cta_corriente_2 ?? '',
                    'responsable' => $request->responsable ?? '',
                ],
                [
                    'tipo_cuenta' => 'cuenta_ahorro',
                    'saldo' => $request->saldo_cuenta_ahorro,
                    'banco_id' => $request->banco_cuenta_ahorro ?: null,
                    'nombre_banco' => $this->obtenerNombreBanco($request->banco_cuenta_ahorro),
                    'numero' => $request->numero_cuenta_ahorro ?? '',
                    'responsable' => $request->responsable ?? '',
                ],
            ];

            foreach ($cuentasParaGuardar as $cuentaData) {
                ConfiguracionCuentasIniciales::create([
                    'org_id' => $id,
                    'tipo_cuenta' => $cuentaData['tipo_cuenta'],
                    'saldo_inicial' => $cuentaData['saldo'],
                    'banco_id' => $cuentaData['banco_id'],
                    'nombre_banco' => $cuentaData['nombre_banco'],
                    'numero_cuenta' => $cuentaData['numero'],
                    'responsable' => $cuentaData['responsable'],
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cuentas iniciales guardadas correctamente',
                'debug_info' => [
                    'total_cuentas_guardadas' => count($cuentasParaGuardar),
                    'timestamp' => now()->toDateTimeString(),
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar las cuentas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene el nombre del banco por su ID
     */
    private function obtenerNombreBanco($bancoId)
    {
        if (!$bancoId) {
            return null;
        }
        
        $banco = Banco::find($bancoId);
        return $banco ? $banco->nombre : null;
    }

    /**
     * Muestra el libro caja en formato tabular
     */
    public function libroCajaTabular($id)
    {
        // Obtener todas las cuentas de la organización
        $cuentas = \App\Models\Cuenta::where('org_id', $id)
            ->orderBy('nombre')
            ->get();
        
        // Obtener categorías globales agrupadas por tipo y grupo
        $categoriasIngresos = \App\Models\Categoria::where('tipo', 'ingreso')
            ->get()
            ->groupBy('grupo');
            
        $categoriasEgresos = \App\Models\Categoria::where('tipo', 'egreso')
            ->get()
            ->groupBy('grupo');
        
        // Mapear las categorías a las columnas de la vista
        $columnasIngresos = [
            'Total Consumo' => $categoriasIngresos->get('Total Consumo', collect())->first(),
            'Cuotas Incorporación' => $categoriasIngresos->get('Cuotas Incorporación', collect())->first(),
            'Otros Ingresos' => $categoriasIngresos->get('Otros Ingresos', collect())->first(),
            'Giros' => null, // Los giros son transferencias, no categorías
        ];
        
        $columnasEgresos = [
            'ENERGÍA ELÉCTRICA' => $categoriasEgresos->get('ENERGÍA ELÉCTRICA', collect())->first(),
            'SUELDOS/LEYES SOCIALES' => $categoriasEgresos->get('SUELDOS/LEYES SOCIALES', collect())->first(),
            'OTROS GASTOS DE OPERACIÓN' => $categoriasEgresos->get('OTROS GASTOS DE OPERACIÓN', collect())->first(),
            'GASTOS MANTENCION' => $categoriasEgresos->get('GASTOS MANTENCION', collect())->first(),
            'GASTOS ADMINISTRACION' => $categoriasEgresos->get('GASTOS ADMINISTRACION', collect())->first(),
            'GASTOS MEJORAMIENTO' => $categoriasEgresos->get('GASTOS MEJORAMIENTO', collect())->first(),
            'OTROS EGRESOS' => $categoriasEgresos->get('OTROS EGRESOS', collect())->first(),
            'DEPÓSITOS' => null, // Los depósitos son transferencias, no categorías
        ];
        
        // Obtener movimientos para mostrar en la tabla
        $movimientos = \App\Models\Movimiento::with(['categoria', 'cuentaOrigen', 'cuentaDestino'])
            ->where(function($q) use ($id) {
                $q->whereHas('cuentaOrigen', function($sq) use ($id) {
                    $sq->where('org_id', $id);
                })->orWhereHas('cuentaDestino', function($sq) use ($id) {
                    $sq->where('org_id', $id);
                })->orWhere('org_id', $id);
            })
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(function($movimiento) use ($columnasIngresos, $columnasEgresos) {
                try {
                    $mapeado = $this->mapearMovimientoTabular($movimiento, $columnasIngresos, $columnasEgresos);
                    // Asegurar que todas las propiedades requeridas existen
                    $props = [
                        'id', 'fecha', 'descripcion', 'tipo', 'monto',
                        'total_consumo', 'cuotas_incorporacion', 'otros_ingresos', 'giros',
                        'energia_electrica', 'sueldos_leyes', 'otros_gastos_operacion',
                        'gastos_mantencion', 'gastos_administrativos', 'gastos_mejoramiento',
                        'otros_egresos', 'depositos'
                    ];
                    foreach ($props as $prop) {
                        if (!property_exists($mapeado, $prop)) {
                            $mapeado->$prop = 0;
                        }
                    }
                    // Agregar grupo desde la relación categoria si existe
                    $mapeado->grupo = $movimiento->categoria->grupo ?? null;
                    return $mapeado;
                } catch (\Exception $e) {
                    // Log para debugging
                    \Log::error('Error mapeando movimiento ID: ' . $movimiento->id, [
                        'categoria' => $movimiento->categoria,
                        'categoria_id' => $movimiento->categoria_id ?? 'no definido',
                        'tipo' => $movimiento->tipo,
                        'error' => $e->getMessage()
                    ]);
                    // Retornar un objeto básico para evitar que falle completamente
                    return (object) [
                        'id' => $movimiento->id,
                        'fecha' => $movimiento->fecha,
                        'descripcion' => $movimiento->descripcion,
                        'tipo' => $movimiento->tipo,
                        'monto' => $movimiento->monto,
                        'grupo' => $movimiento->categoria->grupo ?? null,
                        'total_consumo' => 0,
                        'cuotas_incorporacion' => 0,
                        'otros_ingresos' => 0,
                        'giros' => 0,
                        'energia_electrica' => 0,
                        'sueldos_leyes' => 0,
                        'otros_gastos_operacion' => 0,
                        'gastos_mantencion' => 0,
                        'gastos_administrativos' => 0,
                        'gastos_mejoramiento' => 0,
                        'otros_egresos' => 0,
                        'depositos' => 0,
                    ];
                }
            });
        
        // Obtener saldos reales de las cuentas para el encabezado
        $cuentaCajaGeneral = $cuentas->where('tipo', 'caja_general')->first();
        $cuentaCorriente1 = $cuentas->where('tipo', 'cuenta_corriente_1')->first();
        $cuentaCorriente2 = $cuentas->where('tipo', 'cuenta_corriente_2')->first();
        $cuentaAhorro = $cuentas->where('tipo', 'cuenta_ahorro')->first();
        
        $saldoCajaGeneral = $cuentaCajaGeneral ? $cuentaCajaGeneral->saldo_actual : 0;
        $saldoCuentaCorriente1 = $cuentaCorriente1 ? $cuentaCorriente1->saldo_actual : 0;
        $saldoCuentaCorriente2 = $cuentaCorriente2 ? $cuentaCorriente2->saldo_actual : 0;
        $saldoCuentaAhorro = $cuentaAhorro ? $cuentaAhorro->saldo_actual : 0;
        $saldoTotal = $saldoCajaGeneral + $saldoCuentaCorriente1 + $saldoCuentaCorriente2 + $saldoCuentaAhorro;
        
        // Calcular totales desde los movimientos filtrados para los totalizadores
        // Sumar todos los movimientos de tipo ingreso, sin importar la cuenta
        $totalIngresos = $movimientos->filter(function($m) {
            return isset($m->tipo) && $m->tipo === 'ingreso';
        })->sum(function($m) {
            // Sumar solo el campo principal, evitando duplicidad
            if (isset($m->total_consumo) && $m->total_consumo > 0) return $m->total_consumo;
            if (isset($m->cuotas_incorporacion) && $m->cuotas_incorporacion > 0) return $m->cuotas_incorporacion;
            if (isset($m->otros_ingresos) && $m->otros_ingresos > 0) return $m->otros_ingresos;
            if (isset($m->giros) && $m->giros > 0) return $m->giros;
            return $m->monto ?? 0;
        });

        // Sumar todos los movimientos de tipo egreso, sin importar la cuenta
        $totalEgresos = $movimientos->filter(function($m) {
            return isset($m->tipo) && $m->tipo === 'egreso';
        })->sum(function($m) {
            return ($m->energia_electrica ?? 0)
                + ($m->sueldos_leyes ?? 0)
                + ($m->otros_gastos_operacion ?? 0)
                + ($m->gastos_mantencion ?? 0)
                + ($m->gastos_administracion ?? 0)
                + ($m->gastos_mejoramiento ?? 0)
                + ($m->otros_egresos ?? 0)
                + ($m->depositos ?? 0)
                + ($m->monto ?? 0); // Considera monto directo si corresponde
        });

        // Saldo Final = saldo inicial + ingresos - egresos
        // Obtener fecha de inicio del filtro (por defecto primer día del mes actual)
    $fechaDesde = request('fecha_desde') ?? date('Y-m-01');
    $fechaHasta = request('fecha_hasta') ?? date('Y-m-d');
        // Calcular saldo inicial: suma de saldos de todas las cuentas al inicio del período
        // Saldo inicial: suma de todos los ingresos menos egresos anteriores a la fechaDesde
        $saldoInicial = \App\Models\Movimiento::where('org_id', $id)
            ->where('fecha', '<', $fechaDesde)
            ->whereIn('tipo', ['ingreso', 'egreso'])
            ->get()
            ->reduce(function($saldo, $mov) {
                if ($mov->tipo === 'ingreso') {
                    return $saldo + ($mov->monto ?? 0);
                } else {
                    return $saldo - ($mov->monto ?? 0);
                }
            }, 0);

        // Total ingresos del período
        $totalIngresos = \App\Models\Movimiento::where('org_id', $id)
            ->where('fecha', '>=', $fechaDesde)
            ->where('fecha', '<=', $fechaHasta)
            ->where('tipo', 'ingreso')
            ->sum('monto');

        // Total egresos del período
        $totalEgresos = \App\Models\Movimiento::where('org_id', $id)
            ->where('fecha', '>=', $fechaDesde)
            ->where('fecha', '<=', $fechaHasta)
            ->where('tipo', 'egreso')
            ->sum('monto');

        // Saldo final solo del período filtrado
        $saldoFinalReal = $saldoInicial + $totalIngresos - $totalEgresos;
        
        // Debug para verificar los valores
        \Log::info('Saldos y totales obtenidos:', [
            'saldoCajaGeneral' => $saldoCajaGeneral,
            'saldoCuentaCorriente1' => $saldoCuentaCorriente1,
            'saldoCuentaCorriente2' => $saldoCuentaCorriente2,
            'saldoCuentaAhorro' => $saldoCuentaAhorro,
            'saldoTotal' => $saldoTotal,
            'totalCuentas' => $cuentas->count(),
            'totalMovimientos' => $movimientos->count(),
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'saldoFinalReal' => $saldoFinalReal
        ]);
        
        
        $resumen = $this->getResumenSaldos($id);

        $view = view('orgs.contable.libro_caja_tabular', array_merge($resumen, [
            'orgId' => $id,
            'id' => $id,
            'movimientos' => $movimientos,
            'cuentas' => $cuentas,
            'columnasIngresos' => $columnasIngresos,
            'columnasEgresos' => $columnasEgresos,
            'mostrarLibroCaja' => true,
            'saldoCajaGeneral' => $saldoCajaGeneral,
            'saldoCuentaCorriente1' => $saldoCuentaCorriente1,
            'saldoCuentaCorriente2' => $saldoCuentaCorriente2,
            'saldoCuentaAhorro' => $saldoCuentaAhorro,
            'saldoTotal' => $saldoTotal,
            // Variables para compatibilidad con la vista (objetos cuenta)
            'cuentaCajaGeneral' => $cuentaCajaGeneral,
            'cuentaCorriente1' => $cuentaCorriente1,
            'cuentaCorriente2' => $cuentaCorriente2,
            // Sobrescribir totales con valores reales calculados (ESTOS DEBEN IR AL FINAL)
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'saldoFinal' => $saldoFinalReal,
        ]));
        
        // Inyectar script para actualizar saldos
        $content = $view->render();
        $script = "
        <script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {
                // Inyectar saldos reales desde la base de datos
                localStorage.setItem('saldoCajaGeneral', {$saldoCajaGeneral});
                localStorage.setItem('saldoCuentaCorriente1', {$saldoCuentaCorriente1});
                localStorage.setItem('saldoCuentaCorriente2', {$saldoCuentaCorriente2});
                localStorage.setItem('saldoCuentaAhorro', {$saldoCuentaAhorro});
                
                // Actualizar los campos inmediatamente después de que se cargue la página
                setTimeout(function() {
                    if (document.getElementById('saldoCajaGeneral')) {
                        document.getElementById('saldoCajaGeneral').value = formatCurrency({$saldoCajaGeneral});
                    }
                    if (document.getElementById('saldoCuentaCorriente1')) {
                        document.getElementById('saldoCuentaCorriente1').value = formatCurrency({$saldoCuentaCorriente1});
                    }
                    if (document.getElementById('saldoCuentaCorriente2')) {
                        document.getElementById('saldoCuentaCorriente2').value = formatCurrency({$saldoCuentaCorriente2});
                    }
                    if (document.getElementById('saldoCuentaAhorro')) {
                        document.getElementById('saldoCuentaAhorro').value = formatCurrency({$saldoCuentaAhorro});
                    }
                    if (document.getElementById('saldoTotal')) {
                        document.getElementById('saldoTotal').value = formatCurrency({$saldoTotal});
                    }
                }, 500);
            });
        </script>";
        
        // Inyectar el script antes del cierre del body
        $content = str_replace('</body>', $script . '</body>', $content);
        
        return response($content);
    }

    /**
     * API endpoint para obtener saldos actuales de las cuentas
     */
    public function obtenerSaldosActuales($id)
    {
        $cuentas = \App\Models\Cuenta::where('org_id', $id)->get();
        
        $cuentaCajaGeneral = $cuentas->where('tipo', 'caja_general')->first();
        $cuentaCorriente1 = $cuentas->where('tipo', 'cuenta_corriente')->first();
        $cuentaCorriente2 = $cuentas->where('tipo', 'cuenta_corriente')->skip(1)->first();
        $cuentaAhorro = $cuentas->where('tipo', 'cuenta_ahorro')->first();
        
        $saldos = [
            'saldoCajaGeneral' => $cuentaCajaGeneral ? $cuentaCajaGeneral->saldo_actual : 0,
            'saldoCuentaCorriente1' => $cuentaCorriente1 ? $cuentaCorriente1->saldo_actual : 0,
            'saldoCuentaCorriente2' => $cuentaCorriente2 ? $cuentaCorriente2->saldo_actual : 0,
            'saldoCuentaAhorro' => $cuentaAhorro ? $cuentaAhorro->saldo_actual : 0,
        ];
        
        $saldos['saldoTotal'] = array_sum($saldos);
        
        return response()->json($saldos);
    }

    /**
     * Mapea un movimiento a las columnas específicas del libro caja tabular
     * 
     * Mapeo por categorías:
     * - Ingresos: Usa categoria->grupo ('Total Consumo', 'Cuotas Incorporación', 'Otros Ingresos')
     * - Egresos: Usa categoria->grupo ('ENERGÍA ELÉCTRICA', 'SUELDOS/LEYES SOCIALES', etc.)
     * - Transferencias: Usa tipo/subtipo (giros, depósitos)
     */
    private function mapearMovimientoTabular($movimiento, $columnasIngresos = [], $columnasEgresos = [])
    {
        // Crear un objeto para retornar con la estructura de la tabla
        // Inicializar objeto dinámico con todas las columnas de la vista en cero
        $columnasVista = [
            'Total Consumo',
            'Cuotas Incorporación',
            'Otros Ingresos',
            'Giros',
            'TOTAL INGRESOS',
            'Energía Eléctrica',
            'Sueldos y Leyes Sociales',
            'Otras Ctas. (Agua, Int. Cel.)',
            'Mantención y reparaciones Instalaciones',
            'Insumos y Materiales (Oficina)',
            'Materiales e Insumos (Red)',
            'Viáticos / Seguros / Movilización',
            'Gastos por Trabajos en domicilio',
            'Mejoramiento / Inversiones',
        ];
        $item = (object) [
            'id' => $movimiento->id,
            'fecha' => $movimiento->fecha,
            'descripcion' => $movimiento->descripcion,
            'tipo' => $movimiento->tipo,
            'monto' => $movimiento->monto,
        ];
        // Función para normalizar nombres (sin tildes, espacios, mayúsculas)
        $normalizar = function($texto) {
            $texto = strtolower($texto);
            $texto = str_replace(['á','é','í','ó','ú','ñ'], ['a','e','i','o','u','n'], $texto);
            $texto = preg_replace('/\s+/', '', $texto);
            return $texto;
        };
        $mapaColumnas = [];
        foreach ($columnasVista as $col) {
            $item->{$col} = 0;
            $mapaColumnas[$normalizar($col)] = $col;
        }
        // Mapeo extra para grupos comunes de egresos
        $mapeoGruposEgresos = [
            'energiaelectrica' => 'Energía Eléctrica',
            'sueldosyleyessociales' => 'Sueldos y Leyes Sociales',
            'gastosdeoperacion' => 'Gastos de Operación',
            'gastosdemantencion' => 'Gastos de Mantención',
            'gastosdeadministracion' => 'Gastos de Administración',
            'gastosdemejoramiento' => 'Gastos de Mejoramiento',
            'otrosgastos' => 'Otros Gastos',
            'depositos' => 'Depósitos',
        ];

        $monto = $movimiento->monto;

        // Mapear según el tipo y subtipo del movimiento
        if ($movimiento->tipo === 'transferencia') {
            // Asegurar que la relación categoria esté cargada
            $categoria = null;
            if (is_object($movimiento->categoria) && isset($movimiento->categoria->grupo)) {
                $categoria = $movimiento->categoria;
            } elseif (isset($movimiento->categoria_id)) {
                $categoria = \App\Models\Categoria::find($movimiento->categoria_id);
            }
            $grupo = $categoria->grupo ?? $movimiento->grupo ?? null;
            $tipoCat = $categoria->tipo ?? null;
            // Solo si tipo es 'Transferencia' y grupo es correcto
            if ($tipoCat === 'Transferencia' && $grupo === 'Giros') {
                $item->giros = $monto;
                $item->grupo = 'Giros';
            } elseif ($tipoCat === 'Transferencia' && $grupo === 'Depósitos') {
                $item->depositos = $monto;
                $item->grupo = 'Depósitos';
            } else {
                // Si no hay grupo definido, mantener lógica anterior
                if ($movimiento->cuenta_origen_id && !$movimiento->cuenta_destino_id) {
                    $item->giros = $monto;
                    $item->grupo = 'Giros';
                } elseif (!$movimiento->cuenta_origen_id && $movimiento->cuenta_destino_id) {
                    $item->depositos = $monto;
                    $item->grupo = 'Depósitos';
                }
            }
            // Asignar también a las columnas estándar para la vista
            $item->giros = $item->giros ?? 0;
            $item->depositos = $item->depositos ?? 0;
        } elseif ($movimiento->tipo === 'ingreso') {
            // Mapear ingresos según la categoría y su grupo
            $grupo = null;
            
            // Verificar si categoria es una relación (objeto) o un campo (string/ID)
            if (is_object($movimiento->categoria) && isset($movimiento->categoria->grupo)) {
                $grupo = $movimiento->categoria->grupo;
            } elseif (isset($movimiento->categoria_id)) {
                // Si hay categoria_id, buscar la categoría
                $categoria = \App\Models\Categoria::find($movimiento->categoria_id);
                $grupo = $categoria ? $categoria->grupo : null;
            } elseif (is_string($movimiento->categoria)) {
                // Si categoria es un string, podría ser el nombre del grupo directamente
                $grupo = $movimiento->categoria;
            }
            
            if ($grupo) {
                switch ($grupo) {
                    case 'Total Consumo':
                    case 'TOTAL CONSUMO':
                        $item->total_consumo = $monto;
                        break;
                    case 'Cuotas Incorporación':
                    case 'CUOTAS INCORPORACION':
                        $item->cuotas_incorporacion = $monto;
                        break;
                    case 'Otros Ingresos':
                    case 'OTROS INGRESOS':
                        $item->otros_ingresos = $monto;
                        break;
                    case 'Giros':
                        $item->giros = $monto;
                        break;
                    default:
                        // Si el grupo no coincide, lo asignamos a otros_ingresos
                        $item->otros_ingresos = $monto;
                        break;
                }
            } else {
                $item->otros_ingresos = $monto;
            }
        } elseif ($movimiento->tipo === 'egreso') {
            // Asignar el monto estrictamente a la columna cuyo nombre coincide con el grupo
            $grupo = null;
            if (is_object($movimiento->categoria) && isset($movimiento->categoria->grupo)) {
                $grupo = $movimiento->categoria->grupo;
            } elseif (isset($movimiento->categoria_id)) {
                $categoria = \App\Models\Categoria::find($movimiento->categoria_id);
                $grupo = $categoria ? $categoria->grupo : null;
            } elseif (is_string($movimiento->categoria)) {
                $grupo = $movimiento->categoria;
            }
            if ($grupo) {
                $grupoNorm = $normalizar($grupo);
                // Si el grupo normalizado está en el mapeo extra, usar el nombre de columna mapeado
                if (isset($mapeoGruposEgresos[$grupoNorm])) {
                    $columnaVista = $mapeoGruposEgresos[$grupoNorm];
                    $item->{$columnaVista} = $monto;
                } elseif (isset($mapaColumnas[$grupoNorm])) {
                    $columnaVista = $mapaColumnas[$grupoNorm];
                    $item->{$columnaVista} = $monto;
                }
            }
        }

        return $item;
    }

    /**
     * Muestra la vista de giros y depósitos
     */
    public function girosDepositos($id)
    {
        // Obtener movimientos de transferencias (giros y depósitos)
        $movimientos = \App\Models\Movimiento::where('org_id', $id)
            ->where('tipo', 'transferencia')
            ->orderBy('fecha', 'desc')->get();
        
        $resumen = $this->getResumenSaldos($id);
        $configuracionesIniciales = ConfiguracionCuentasIniciales::where('org_id', $id)->get();
           $cuentas = \App\Models\Cuenta::where('org_id', $id)->orderBy('tipo')->get();

        return view('orgs.contable.giros_depositos', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
            'configuracionesIniciales' => $configuracionesIniciales,
               'cuentas' => $cuentas,
        ], $resumen));
    }

    /**
     * Muestra la vista nice (dashboard personalizado)
     */
    public function nice($id)
    {
        $resumen = $this->getResumenSaldos($id);
        // Obtener los últimos 10 movimientos para el dashboard
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($id) {
            $q->where('org_id', $id);
        })->orderBy('fecha', 'desc')->limit(10)->get();
        $configuraciones = ConfiguracionCuentasIniciales::with('banco')->where('org_id', $id)->get();

        return view('orgs.contable.nice', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
            'configuraciones' => $configuraciones,
            'active' => 'dashboard',
        ], $resumen));
    }

    /**
     * Verifica el saldo disponible de una cuenta
     */
    public function verificarSaldoCuenta(Request $request)
    {
        try {
            $cuentaId = $request->input('cuenta_id');
            $montoEgreso = $request->input('monto', 0);
            
            // Buscar la cuenta
            $cuenta = \App\Models\Cuenta::find($cuentaId);
            
            if (!$cuenta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cuenta no encontrada'
                ], 404);
            }

            $saldoActual = $cuenta->saldo_actual ?? 0;
            $saldoInsuficiente = $saldoActual < $montoEgreso;
            
            return response()->json([
                'success' => true,
                'saldo_actual' => $saldoActual,
                'monto_egreso' => $montoEgreso,
                'saldo_insuficiente' => $saldoInsuficiente,
                'saldo_resultante' => $saldoActual - $montoEgreso,
                'cuenta_nombre' => $cuenta->nombre
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar saldo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa un registro de ingreso
     */
    public function procesarIngreso(Request $request)
    {
        try {
            $request->validate([
                'org_id' => 'required|integer',
                'cuenta_destino' => 'required|exists:cuentas,id',
                'categoria' => 'required|exists:categorias,id',
                'monto' => 'required|numeric|min:0.01',
                'descripcion' => 'required|string|max:500',
                'fecha' => 'required|date',
                'numero_comprobante' => 'required|string|max:50'
            ]);

            \DB::beginTransaction();

            // Buscar la cuenta destino
            $cuentaDestino = \App\Models\Cuenta::find($request->cuenta_destino);
            if (!$cuentaDestino) {
                throw new \Exception('Cuenta destino no encontrada');
            }

            // Buscar la categoría para obtener el grupo
            $categoria = \App\Models\Categoria::find($request->categoria);
            if (!$categoria) {
                throw new \Exception('Categoría no encontrada');
            }

            // Crear el movimiento de ingreso (lógica original)
            $movimiento = \App\Models\Movimiento::create([
                'org_id' => $request->org_id,
                'tipo' => 'ingreso',
                'cuenta_destino_id' => $request->cuenta_destino,
                'categoria_id' => $request->categoria,
                'grupo' => $categoria->grupo, // Agregar el grupo desde la categoría
                'monto' => $request->monto,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'numero_documento' => $request->numero_comprobante, // Corregido: numero_documento en lugar de nro_dcto
            ]);

            // El mapeo tabular se hace en la vista, no necesario aquí
            // $movimiento = $this->mapearMovimientoTabular($movimiento);
            // $movimiento->save();

            // Actualizar saldo de la cuenta destino (SUMAR el ingreso)
            $cuentaDestino->saldo_actual += $request->monto;
            $cuentaDestino->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ingreso registrado exitosamente',
                'data' => [
                    'movimiento_id' => $movimiento->id,
                    'nuevo_saldo' => $cuentaDestino->saldo_actual,
                    'cuenta_nombre' => $cuentaDestino->nombre
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar ingreso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa un registro de egreso
     */
    public function procesarEgreso(Request $request)
    {
        try {
            $request->validate([
                'cuenta_origen' => 'required|exists:cuentas,id',
                'categoria' => 'required|exists:categorias,id',
                'monto' => 'required|numeric|min:0.01',
                'descripcion' => 'required|string|max:500',
                'fecha' => 'required|date',
                'numero_comprobante' => 'required|string|max:50',
                'razon_social' => 'nullable|string|max:200',
                'rut_proveedor' => 'nullable|string|max:20'
            ]);
            $cuentaOrigen = \App\Models\Cuenta::find($request->cuenta_origen);
            if (!$cuentaOrigen) {
                throw new \Exception('Cuenta origen no encontrada');
            }
            if ($cuentaOrigen->saldo_actual < $request->monto) {
                throw new \Exception("Saldo insuficiente. Saldo actual: $" . number_format((float)$cuentaOrigen->saldo_actual, 2));
            }

            // Buscar la categoría para obtener el grupo
            $categoria = \App\Models\Categoria::find($request->categoria);
            if (!$categoria) {
                throw new \Exception('Categoría no encontrada');
            }

            // Crear el movimiento de egreso (solo una vez)
            $movimiento = \App\Models\Movimiento::create([
                'org_id' => $request->org_id,
                'tipo' => 'egreso',
                'cuenta_origen_id' => $request->cuenta_origen,
                'categoria_id' => $request->categoria,
                'grupo' => $categoria->grupo,
                'monto' => $request->monto,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'numero_documento' => $request->numero_comprobante,
                'proveedor' => $request->razon_social,
                'rut_proveedor' => $request->rut_proveedor,
            ]);

            // El mapeo tabular se hace en la vista, no necesario aquí
            // $movimiento = $this->mapearMovimientoTabular($movimiento);
            // $movimiento->save();

            // Actualizar saldo de la cuenta origen (RESTAR el egreso)
            $cuentaOrigen->saldo_actual -= $request->monto;
            $cuentaOrigen->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Egreso registrado exitosamente',
                'data' => [
                    'movimiento_id' => $movimiento->id,
                    'nuevo_saldo' => $cuentaOrigen->saldo_actual,
                    'cuenta_nombre' => $cuentaOrigen->nombre
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar egreso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa un giro (transferencia saliente)
     */
    public function procesarGiro(Request $request)
    {
    \Log::info('Test de log manual: procesarGiro ejecutado');
    \Log::info('procesarGiro request', $request->all());
    try {
            $request->validate([
                'cuenta_origen' => 'required|exists:cuentas,id',
                'monto' => 'required|numeric|min:0.01',
                'descripcion' => 'required|string|max:500',
                'fecha' => 'required|date',
                // 'numero_comprobante' => 'required|numeric|min:1' // Eliminada para correlativo automático
            ]);

            \DB::beginTransaction();

            // Buscar la cuenta origen
            $cuentaOrigen = \App\Models\Cuenta::find($request->cuenta_origen);
            if (!$cuentaOrigen) {
                throw new \Exception('Cuenta origen no encontrada');
            }

            // Verificar saldo suficiente
            if ($cuentaOrigen->saldo_actual < $request->monto) {
                throw new \Exception("Saldo insuficiente para el giro. Saldo actual: $" . number_format((float)$cuentaOrigen->saldo_actual, 2));
            }

            // Buscar la cuenta destino (Caja General)
            $cuentaCajaGeneral = \App\Models\Cuenta::where('org_id', $request->org_id ?? auth()->user()->org_id)
                ->where('tipo', 'caja_general')->first();
            if (!$cuentaCajaGeneral) {
                throw new \Exception('Cuenta Caja General no encontrada');
            }

            // Crear el movimiento de giro (transferencia interna)
            // Buscar la categoría 'Giros'
            $categoriaGiro = \App\Models\Categoria::where('nombre', 'Giros')->where('tipo', 'Transferencia')->first();
            $movimiento = \App\Models\Movimiento::create([
                'org_id' => $request->org_id ?? auth()->user()->org_id,
                'tipo' => 'transferencia',
                'subtipo' => 'giro',
                'cuenta_origen_id' => $request->cuenta_origen,
                'cuenta_destino_id' => $cuentaCajaGeneral->id,
                'categoria_id' => $categoriaGiro ? $categoriaGiro->id : null,
                'grupo' => $request->grupo ?? ($categoriaGiro ? $categoriaGiro->grupo : null),
                'monto' => $request->monto,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'nro_dcto' => $request->numero_comprobante,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Actualizar saldo de la cuenta origen (RESTAR el giro)
            $cuentaOrigen->saldo_actual -= $request->monto;
            $cuentaOrigen->save();
            // Actualizar saldo de la caja general (SUMAR el giro)
            $cuentaCajaGeneral->saldo_actual += $request->monto;
            $cuentaCajaGeneral->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Giro procesado exitosamente',
                'data' => [
                    'movimiento_id' => $movimiento->id,
                    'nuevo_saldo' => $cuentaOrigen->saldo_actual,
                    'cuenta_nombre' => $cuentaOrigen->nombre
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error en procesarGiro', [
                'exception' => $e,
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar giro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa un depósito (transferencia entrante)
     */
    public function procesarDeposito(Request $request)
    {
        try {
            $request->validate([
                'cuenta_destino' => 'required|exists:cuentas,id',
                'monto' => 'required|numeric|min:0.01',
                'descripcion' => 'required|string|max:500',
                'fecha' => 'required|date',
                // 'numero_comprobante' => 'required|numeric|min:1' // Eliminada para correlativo automático
            ]);

            \DB::beginTransaction();

            // Buscar la cuenta origen (Caja General)
            $cuentaCajaGeneral = \App\Models\Cuenta::where('org_id', $request->org_id ?? auth()->user()->org_id)
                ->where('tipo', 'caja_general')->first();
            if (!$cuentaCajaGeneral) {
                throw new \Exception('Cuenta Caja General no encontrada');
            }
            // Buscar la cuenta destino
            $cuentaDestino = \App\Models\Cuenta::find($request->cuenta_destino);
            if (!$cuentaDestino) {
                throw new \Exception('Cuenta destino no encontrada');
            }
            if ($cuentaDestino->tipo === 'caja_general') {
                throw new \Exception('No se permite transferir hacia la cuenta caja_general');
            }
            // Crear el movimiento de depósito (transferencia interna)
            // Buscar la categoría 'Depósitos'
            $categoriaDeposito = \App\Models\Categoria::where('nombre', 'Depósitos')->where('tipo', 'Transferencia')->first();
            $movimiento = \App\Models\Movimiento::create([
                'org_id' => $request->org_id ?? auth()->user()->org_id,
                'tipo' => 'transferencia',
                'subtipo' => 'deposito',
                'cuenta_origen_id' => $cuentaCajaGeneral->id,
                'cuenta_destino_id' => $cuentaDestino->id,
                'categoria_id' => $categoriaDeposito ? $categoriaDeposito->id : null,
                'grupo' => $request->grupo ?? ($categoriaDeposito ? $categoriaDeposito->grupo : null),
                'monto' => $request->monto,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'nro_dcto' => $request->numero_comprobante,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Aplicar mapeo tabular automático (columna depósitos)
            $this->mapearMovimientoTabular($movimiento); // No reasignar ni llamar save() si no retorna modelo

            // Actualizar saldo de la caja general (RESTAR el depósito)
            $cuentaCajaGeneral->saldo_actual -= $request->monto;
            $cuentaCajaGeneral->save();
            // Actualizar saldo de la cuenta destino (SUMAR el depósito)
            $cuentaDestino->saldo_actual += $request->monto;
            $cuentaDestino->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Depósito procesado exitosamente',
                'data' => [
                    'movimiento_id' => $movimiento->id,
                    'nuevo_saldo' => $cuentaDestino->saldo_actual,
                    'cuenta_nombre' => $cuentaDestino->nombre
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar depósito: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Actualizar solo los bancos de las cuentas existentes
     */
    private function actualizarSoloBancos($request, $orgId)
    {
        try {
            \Log::info('🏦 Iniciando actualización solo de bancos para org: ' . $orgId);
            
            $bancosActualizados = 0;
            
            // Función auxiliar para limpiar valores
            $cleanValue = function($value) {
                if ($value === null || $value === 'undefined' || $value === '') {
                    return null;
                }
                return trim($value);
            };
            
            // Actualizar banco cuenta corriente 1
            if ($request->has('banco_cta_corriente_1') && $request->banco_cta_corriente_1) {
                $bancoCorr1 = $this->procesarBanco($request->banco_cta_corriente_1);
                
                $cuentaCorriente1 = Cuenta::where('tipo', 'cuenta_corriente_1')->where('org_id', $orgId)->first();
                if ($cuentaCorriente1) {
                    // Actualizar cuenta
                    $cuentaCorriente1->banco_id = $bancoCorr1['id'];
                    $cuentaCorriente1->save();
                    
                    // Actualizar configuracion_cuentas_iniciales (nueva tabla)
                    ConfiguracionCuentasIniciales::where('org_id', $orgId)
                        ->where('tipo', 'cuenta_corriente_1')
                        ->update(['banco_id' => $bancoCorr1['id']]);
                    
                    $bancosActualizados++;
                    \Log::info('✅ Banco actualizado para cuenta corriente 1: ' . $bancoCorr1['nombre']);
                }
            }
            
            // Actualizar banco cuenta ahorro
            if ($request->has('banco_cuenta_ahorro') && $request->banco_cuenta_ahorro) {
                $bancoAhorro = $this->procesarBanco($request->banco_cuenta_ahorro);
                
                $cuentaAhorro = Cuenta::where('tipo', 'cuenta_ahorro')->where('org_id', $orgId)->first();
                if ($cuentaAhorro) {
                    // Actualizar cuenta
                    $cuentaAhorro->banco_id = $bancoAhorro['id'];
                    $cuentaAhorro->save();
                    
                    // Actualizar configuracion_cuentas_iniciales (nueva tabla)
                    ConfiguracionCuentasIniciales::where('org_id', $orgId)
                        ->where('tipo', 'cuenta_ahorro')
                        ->update(['banco_id' => $bancoAhorro['id']]);
                    
                    $bancosActualizados++;
                    \Log::info('✅ Banco actualizado para cuenta ahorro: ' . $bancoAhorro['nombre']);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "✅ Bancos actualizados exitosamente: {$bancosActualizados} cuentas",
                'bancos_actualizados' => $bancosActualizados
            ]);
            
        } catch (\Exception $e) {
            \Log::error('❌ Error actualizando bancos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error actualizando bancos: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Procesar información del banco (código y nombre)
     */
    private function procesarBanco($codigoBanco)
    {
        // Buscar el banco por código
        $banco = \App\Models\Banco::where('codigo', $codigoBanco)->first();
        
        if (!$banco) {
            // Si no existe, crear el banco
            $bancosEstaticos = [
                'banco_estado' => 'Banco Estado',
                'banco_chile' => 'Banco de Chile',
                'banco_bci' => 'BCI',
                'banco_santander' => 'Santander',
                'banco_itau' => 'Itaú',
                'banco_scotiabank' => 'Scotiabank',
                'banco_bice' => 'BICE',
                'banco_security' => 'Security',
                'banco_falabella' => 'Falabella',
            ];
            
            $nombreBanco = $bancosEstaticos[$codigoBanco] ?? $codigoBanco;
            
            $banco = \App\Models\Banco::create([
                'codigo' => $codigoBanco,
                'nombre' => $nombreBanco
            ]);
        }
        
        return [
            'id' => $banco->id,
            'nombre' => $banco->nombre,
            'codigo' => $banco->codigo
        ];
    }

    /**
     * Obtiene las categorías dinámicamente para eliminar hardcodeo
     */
    public function getCategorias()
    {
        try {
            $categorias = \App\Models\Categoria::select('id', 'nombre', 'grupo', 'tipo')
                ->orderBy('tipo')
                ->orderBy('grupo')
                ->orderBy('nombre')
                ->get();

            $categoriasOrganizadas = [
                'ingresos' => [],
                'egresos' => []
            ];

            foreach ($categorias as $categoria) {
                $tipo = $categoria->tipo === 'ingreso' ? 'ingresos' : 'egresos';
                
                if (!isset($categoriasOrganizadas[$tipo][$categoria->grupo])) {
                    $categoriasOrganizadas[$tipo][$categoria->grupo] = [];
                }
                
                $categoriasOrganizadas[$tipo][$categoria->grupo][] = [
                    'id' => $categoria->id,
                    'nombre' => $categoria->nombre,
                    'grupo' => $categoria->grupo
                ];
            }

            return response()->json([
                'categorias' => $categoriasOrganizadas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener categorías: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene los bancos dinámicamente
     */
    public function getBancos()
    {
        try {
            $bancos = \App\Models\Banco::select('id', 'nombre', 'codigo')
                ->orderBy('nombre')
                ->get();

            return response()->json([
                'success' => true,
                'bancos' => $bancos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener bancos: ' . $e->getMessage()
            ], 500);
        }
    }
}