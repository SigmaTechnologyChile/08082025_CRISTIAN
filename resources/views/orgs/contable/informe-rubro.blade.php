@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe por Rubro - Sistema Financiero</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/contable/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #e83e8c 0%, #d63384 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .filtros-rubro {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .filtros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .form-group {
            margin-bottom: 0;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #e83e8c;
            box-shadow: 0 0 0 3px rgba(232, 62, 140, 0.1);
        }
        
        .resumen-rubros {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .rubro-card {
            background: linear-gradient(135deg, #fff5f8 0%, #f8f9fa 100%);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border-left: 4px solid #e83e8c;
            transition: transform 0.3s ease;
        }
        
        .rubro-card:hover {
            transform: translateY(-5px);
        }
        
        .rubro-card h4 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 16px;
        }
        
        .rubro-card .valor {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .rubro-card .porcentaje {
            font-size: 14px;
            color: #666;
            font-weight: 600;
        }
        
        .rubro-ingresos {
            border-left-color: #28a745;
        }
        
        .rubro-egresos {
            border-left-color: #dc3545;
        }
        
        .rubro-servicios {
            border-left-color: #17a2b8;
        }
        
        .rubro-productos {
            border-left-color: #ffc107;
        }
        
        .rubro-gastos {
            border-left-color: #fd7e14;
        }
        
        .rubro-admin {
            border-left-color: #6f42c1;
        }
        
        .valor-positivo {
            color: #28a745;
        }
        
        .valor-negativo {
            color: #dc3545;
        }
        
        .valor-neutral {
            color: #6c757d;
        }
        
        .graficos-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .grafico-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
        }
        
        .grafico-section h4 {
            margin: 0 0 20px 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        .tabla-rubros {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .tabla-rubros th {
            background: linear-gradient(135deg, #e83e8c 0%, #d63384 100%);
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }
        
        .tabla-rubros td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }
        
        .tabla-rubros tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .tabla-rubros tbody tr:nth-child(even) {
            background-color: #fdfdfd;
        }
        
        .categoria-header {
            background: #e9ecef;
            font-weight: bold;
            color: #495057;
        }
        
        .subcategoria-row {
            padding-left: 20px;
        }
        
        .detalle-movimientos {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .detalle-movimientos h4 {
            margin: 0 0 20px 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .movimientos-rubro-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .movimientos-rubro-table th {
            background: linear-gradient(135deg, #e83e8c 0%, #d63384 100%);
            color: white;
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
        }
        
        .movimientos-rubro-table td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            font-size: 13px;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            justify-content: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #e83e8c 0%, #d63384 100%);
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            display: none;
        }
        
        .notification.success { background: #28a745; }
        .notification.error { background: #dc3545; }
        .notification.info { background: #17a2b8; }
        
        .nav-breadcrumb {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .nav-breadcrumb a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .nav-breadcrumb a:hover {
            text-decoration: underline;
        }
        
        .sin-datos {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        .btn-rubro {
            background: none;
            border: none;
            color: #e83e8c;
            text-decoration: underline;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        
        .btn-rubro:hover {
            color: #d63384;
        }
        
        @media (max-width: 768px) {
            .graficos-container {
                grid-template-columns: 1fr;
            }
            
            .resumen-rubros {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filtros-grid {
                grid-template-columns: 1fr;
            }
            
            .tabla-rubros,
            .movimientos-rubro-table {
                font-size: 12px;
            }
            
            .tabla-rubros th,
            .tabla-rubros td,
            .movimientos-rubro-table th,
            .movimientos-rubro-table td {
                padding: 8px 6px;
            }
        }
    </style>
</head>

<body>
    <div id="notification" class="notification"></div>

    <div class="container">
        <!-- Navegación -->
        <div class="nav-breadcrumb">
            <a href="{{ route('dashboard', ['id' => $orgId]) }}">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <span> / </span>
            <span>Informe por Rubro</span>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h1><i class="bi bi-pie-chart"></i> Informe por Rubro</h1>
                    <p>Análisis categórico de ingresos y egresos</p>
                </div>
                <a href="{{ route('dashboard', ['id' => $orgId]) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Dashboard
                </a>
            </div>

            <!-- Filtros -->
            <div class="filtros-rubro">
                <h4><i class="bi bi-funnel"></i> Filtros de Análisis</h4>
                <div class="filtros-grid">
                    <div class="form-group">
                        <label for="fechaDesdeRubro">
                            <i class="bi bi-calendar3"></i> Fecha Desde
                        </label>
                        <input type="date" id="fechaDesdeRubro">
                    </div>
                    
                    <div class="form-group">
                        <label for="fechaHastaRubro">
                            <i class="bi bi-calendar3"></i> Fecha Hasta
                        </label>
                        <input type="date" id="fechaHastaRubro">
                    </div>
                    
                    <div class="form-group">
                        <label for="tipoAnalisis">
                            <i class="bi bi-filter"></i> Tipo de Análisis
                        </label>
                        <select id="tipoAnalisis">
                            <option value="todos">Todos los Rubros</option>
                            <option value="ingresos">Solo Ingresos</option>
                            <option value="egresos">Solo Egresos</option>
                            <option value="comparativo">Comparativo</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="agruparPor">
                            <i class="bi bi-collection"></i> Agrupar Por
                        </label>
                        <select id="agruparPor">
                            <option value="categoria">Categoría</option>
                            <option value="subcategoria">Subcategoría</option>
                            <option value="mes">Mes</option>
                            <option value="semana">Semana</option>
                        </select>
                    </div>
                    
                    <button id="btnGenerarInforme" class="btn btn-primary">
                        <i class="bi bi-bar-chart-line"></i> Generar Informe
                    </button>
                </div>
            </div>

            <!-- Resumen de Rubros -->
            <div class="resumen-rubros" id="resumenRubros">
                <!-- Los cards se generarán dinámicamente -->
            </div>

            <!-- Gráficos -->
            <div class="graficos-container">
                <div class="grafico-section">
                    <h4><i class="bi bi-pie-chart"></i> Distribución por Categoría</h4>
                    <div class="chart-container">
                        <canvas id="distribucionChart"></canvas>
                    </div>
                </div>

                <div class="grafico-section">
                    <h4><i class="bi bi-bar-chart"></i> Comparativo Mensual</h4>
                    <div class="chart-container">
                        <canvas id="comparativoChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tabla Detallada por Rubros -->
            <div class="detalle-movimientos">
                <h4><i class="bi bi-table"></i> Detalle por Categorías</h4>
                <div class="table-responsive">
                    <table class="tabla-rubros">
                        <thead>
                            <tr>
                                <th>Categoría</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th>Promedio</th>
                                <th>Porcentaje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaRubros">
                            <tr class="sin-datos">
                                <td colspan="6">
                                    <i class="bi bi-inbox" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                                    Genere un informe para ver los datos
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Movimientos Detallados -->
            <div class="detalle-movimientos" id="detalleMovimientosSection" style="display: none;">
                <h4><i class="bi bi-list-ul"></i> Movimientos Detallados - <span id="categoriaSeleccionada"></span></h4>
                <div class="table-responsive">
                    <table class="movimientos-rubro-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Detalle</th>
                                <th>Subcategoría</th>
                                <th>Monto</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody id="tablaMovimientosRubro">
                        </tbody>
                    </table>
                </div>
                <button id="btnCerrarDetalle" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cerrar Detalle
                </button>
            </div>
        </div>

        <!-- Enlaces Rápidos -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3><i class="bi bi-lightning"></i> Acciones Rápidas</h3>
            </div>
            <div class="btn-group">
                <a href="{{ route('ingresos.index', ['id' => $orgId]) }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Registrar Ingreso
                </a>
                <a href="{{ route('egresos.index', ['id' => $orgId]) }}" class="btn btn-info">
                    <i class="bi bi-dash-circle"></i> Registrar Egreso
                </a>
                <button id="btnExportarInforme" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Exportar Informe
                </button>
                <button id="btnImprimirInforme" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>
    </div>

    <script>
        // Configuración CSRF para Laravel
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };

        // Storage para comunicación entre módulos
        const STORAGE_KEYS = {
            ingresos: 'finanzas_ingresos',
            egresos: 'finanzas_egresos'
        };

        // Categorías predefinidas
        const CATEGORIAS = {
            ingresos: {
                'Ventas': ['Productos', 'Servicios', 'Comisiones'],
                'Servicios Profesionales': ['Consultoría', 'Capacitación', 'Asesoría'],
                'Otros Ingresos': ['Intereses', 'Arriendo', 'Varios']
            },
            egresos: {
                'Gastos Operacionales': ['Arriendo', 'Servicios Básicos', 'Comunicaciones'],
                'Gastos Administrativos': ['Sueldos', 'Beneficios', 'Oficina'],
                'Gastos Comerciales': ['Marketing', 'Publicidad', 'Ventas'],
                'Otros Gastos': ['Impuestos', 'Intereses', 'Varios']
            }
        };

        let distribucionChart, comparativoChart;
        let datosInforme = {};

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            configurarFechasPorDefecto();
            inicializarGraficos();
        });

        function setupEventListeners() {
            document.getElementById('btnGenerarInforme').addEventListener('click', generarInforme);
            document.getElementById('btnExportarInforme').addEventListener('click', exportarInforme);
            document.getElementById('btnImprimirInforme').addEventListener('click', imprimirInforme);
            document.getElementById('btnCerrarDetalle').addEventListener('click', cerrarDetalle);
            
            // Escuchar eventos de otros módulos
            window.addEventListener('ingresoRegistrado', () => generarInforme());
            window.addEventListener('egresoRegistrado', () => generarInforme());
        }

        function configurarFechasPorDefecto() {
            const hoy = new Date();
            const primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
            
            document.getElementById('fechaDesdeRubro').value = primerDiaMes.toISOString().split('T')[0];
            document.getElementById('fechaHastaRubro').value = hoy.toISOString().split('T')[0];
        }

        function generarInforme() {
            const fechaDesde = document.getElementById('fechaDesdeRubro').value;
            const fechaHasta = document.getElementById('fechaHastaRubro').value;
            const tipoAnalisis = document.getElementById('tipoAnalisis').value;
            const agruparPor = document.getElementById('agruparPor').value;

            if (!fechaDesde || !fechaHasta) {
                mostrarNotificacion('Seleccione el rango de fechas', 'error');
                return;
            }

            // Cargar datos
            const ingresos = JSON.parse(localStorage.getItem(STORAGE_KEYS.ingresos) || '[]');
            const egresos = JSON.parse(localStorage.getItem(STORAGE_KEYS.egresos) || '[]');

            // Filtrar por fechas
            const ingresosFiltrados = filtrarPorFecha(ingresos, fechaDesde, fechaHasta);
            const egresosFiltrados = filtrarPorFecha(egresos, fechaDesde, fechaHasta);

            // Procesar datos según tipo de análisis
            procesarDatos(ingresosFiltrados, egresosFiltrados, tipoAnalisis, agruparPor);
            
            // Actualizar visualización
            actualizarResumen();
            actualizarTablas();
            actualizarGraficos();

            mostrarNotificacion('Informe generado exitosamente', 'success');
        }

        function filtrarPorFecha(movimientos, fechaDesde, fechaHasta) {
            return movimientos.filter(mov => {
                return mov.fecha >= fechaDesde && mov.fecha <= fechaHasta;
            });
        }

        function procesarDatos(ingresos, egresos, tipoAnalisis, agruparPor) {
            datosInforme = {
                ingresos: {},
                egresos: {},
                totales: {},
                movimientos: { ingresos, egresos }
            };

            // Procesar ingresos
            if (tipoAnalisis === 'todos' || tipoAnalisis === 'ingresos' || tipoAnalisis === 'comparativo') {
                ingresos.forEach(ingreso => {
                    const categoria = categorizarMovimiento(ingreso, 'ingreso');
                    const subcategoria = subcategorizarMovimiento(ingreso, 'ingreso');
                    
                    if (!datosInforme.ingresos[categoria]) {
                        datosInforme.ingresos[categoria] = {
                            total: 0,
                            cantidad: 0,
                            subcategorias: {},
                            movimientos: []
                        };
                    }

                    datosInforme.ingresos[categoria].total += ingreso.monto;
                    datosInforme.ingresos[categoria].cantidad++;
                    datosInforme.ingresos[categoria].movimientos.push({...ingreso, subcategoria});

                    if (!datosInforme.ingresos[categoria].subcategorias[subcategoria]) {
                        datosInforme.ingresos[categoria].subcategorias[subcategoria] = {
                            total: 0,
                            cantidad: 0
                        };
                    }
                    datosInforme.ingresos[categoria].subcategorias[subcategoria].total += ingreso.monto;
                    datosInforme.ingresos[categoria].subcategorias[subcategoria].cantidad++;
                });
            }

            // Procesar egresos
            if (tipoAnalisis === 'todos' || tipoAnalisis === 'egresos' || tipoAnalisis === 'comparativo') {
                egresos.forEach(egreso => {
                    const categoria = categorizarMovimiento(egreso, 'egreso');
                    const subcategoria = subcategorizarMovimiento(egreso, 'egreso');
                    
                    if (!datosInforme.egresos[categoria]) {
                        datosInforme.egresos[categoria] = {
                            total: 0,
                            cantidad: 0,
                            subcategorias: {},
                            movimientos: []
                        };
                    }

                    datosInforme.egresos[categoria].total += egreso.monto;
                    datosInforme.egresos[categoria].cantidad++;
                    datosInforme.egresos[categoria].movimientos.push({...egreso, subcategoria});

                    if (!datosInforme.egresos[categoria].subcategorias[subcategoria]) {
                        datosInforme.egresos[categoria].subcategorias[subcategoria] = {
                            total: 0,
                            cantidad: 0
                        };
                    }
                    datosInforme.egresos[categoria].subcategorias[subcategoria].total += egreso.monto;
                    datosInforme.egresos[categoria].subcategorias[subcategoria].cantidad++;
                });
            }

            // Calcular totales
            datosInforme.totales.ingresos = Object.values(datosInforme.ingresos).reduce((sum, cat) => sum + cat.total, 0);
            datosInforme.totales.egresos = Object.values(datosInforme.egresos).reduce((sum, cat) => sum + cat.total, 0);
            datosInforme.totales.neto = datosInforme.totales.ingresos - datosInforme.totales.egresos;
        }

        function categorizarMovimiento(movimiento, tipo) {
            // Categorización inteligente basada en palabras clave en el detalle
            const detalle = movimiento.detalle.toLowerCase();
            
            if (tipo === 'ingreso') {
                if (detalle.includes('venta') || detalle.includes('producto')) return 'Ventas';
                if (detalle.includes('servicio') || detalle.includes('consultoría')) return 'Servicios Profesionales';
                return 'Otros Ingresos';
            } else {
                if (detalle.includes('arriendo') || detalle.includes('luz') || detalle.includes('agua')) return 'Gastos Operacionales';
                if (detalle.includes('sueldo') || detalle.includes('oficina') || detalle.includes('administración')) return 'Gastos Administrativos';
                if (detalle.includes('marketing') || detalle.includes('publicidad') || detalle.includes('venta')) return 'Gastos Comerciales';
                return 'Otros Gastos';
            }
        }

        function subcategorizarMovimiento(movimiento, tipo) {
            const detalle = movimiento.detalle.toLowerCase();
            const categoria = categorizarMovimiento(movimiento, tipo);
            
            // Obtener subcategorías de la categoría
            const subcategorias = CATEGORIAS[tipo === 'ingreso' ? 'ingresos' : 'egresos'][categoria] || ['General'];
            
            // Buscar coincidencias
            for (const subcategoria of subcategorias) {
                if (detalle.includes(subcategoria.toLowerCase())) {
                    return subcategoria;
                }
            }
            
            return subcategorias[0] || 'General';
        }

        function actualizarResumen() {
            const container = document.getElementById('resumenRubros');
            let html = '';

            // Card de ingresos totales
            if (Object.keys(datosInforme.ingresos).length > 0) {
                const totalIngresos = datosInforme.totales.ingresos;
                const cantidadIngresos = Object.values(datosInforme.ingresos).reduce((sum, cat) => sum + cat.cantidad, 0);
                
                html += `
                    <div class="rubro-card rubro-ingresos">
                        <h4><i class="bi bi-plus-circle"></i> Total Ingresos</h4>
                        <div class="valor valor-positivo">${formatearMoneda(totalIngresos)}</div>
                        <div class="porcentaje">${cantidadIngresos} movimientos</div>
                    </div>
                `;
            }

            // Card de egresos totales
            if (Object.keys(datosInforme.egresos).length > 0) {
                const totalEgresos = datosInforme.totales.egresos;
                const cantidadEgresos = Object.values(datosInforme.egresos).reduce((sum, cat) => sum + cat.cantidad, 0);
                
                html += `
                    <div class="rubro-card rubro-egresos">
                        <h4><i class="bi bi-dash-circle"></i> Total Egresos</h4>
                        <div class="valor valor-negativo">${formatearMoneda(totalEgresos)}</div>
                        <div class="porcentaje">${cantidadEgresos} movimientos</div>
                    </div>
                `;
            }

            // Card de resultado neto
            const neto = datosInforme.totales.neto;
            html += `
                <div class="rubro-card">
                    <h4><i class="bi bi-calculator"></i> Resultado Neto</h4>
                    <div class="valor ${neto >= 0 ? 'valor-positivo' : 'valor-negativo'}">${formatearMoneda(neto)}</div>
                    <div class="porcentaje">${neto >= 0 ? 'Utilidad' : 'Pérdida'}</div>
                </div>
            `;

            // Cards individuales para las categorías principales
            const categoriasIngresos = Object.entries(datosInforme.ingresos)
                .sort((a, b) => b[1].total - a[1].total)
                .slice(0, 2);
            
            categoriasIngresos.forEach(([categoria, datos]) => {
                const porcentaje = datosInforme.totales.ingresos > 0 ? 
                    ((datos.total / datosInforme.totales.ingresos) * 100).toFixed(1) : 0;
                
                html += `
                    <div class="rubro-card rubro-servicios">
                        <h4><i class="bi bi-tag"></i> ${categoria}</h4>
                        <div class="valor valor-positivo">${formatearMoneda(datos.total)}</div>
                        <div class="porcentaje">${porcentaje}% del total</div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function actualizarTablas() {
            const tbody = document.getElementById('tablaRubros');
            let html = '';

            // Combinar ingresos y egresos para la tabla
            const todasCategorias = [];

            // Agregar ingresos
            Object.entries(datosInforme.ingresos).forEach(([categoria, datos]) => {
                const promedio = datos.cantidad > 0 ? datos.total / datos.cantidad : 0;
                const porcentaje = datosInforme.totales.ingresos > 0 ? 
                    ((datos.total / datosInforme.totales.ingresos) * 100).toFixed(1) : 0;
                
                todasCategorias.push({
                    categoria,
                    tipo: 'Ingreso',
                    cantidad: datos.cantidad,
                    total: datos.total,
                    promedio,
                    porcentaje,
                    clase: 'valor-positivo',
                    datos
                });
            });

            // Agregar egresos
            Object.entries(datosInforme.egresos).forEach(([categoria, datos]) => {
                const promedio = datos.cantidad > 0 ? datos.total / datos.cantidad : 0;
                const porcentaje = datosInforme.totales.egresos > 0 ? 
                    ((datos.total / datosInforme.totales.egresos) * 100).toFixed(1) : 0;
                
                todasCategorias.push({
                    categoria,
                    tipo: 'Egreso',
                    cantidad: datos.cantidad,
                    total: datos.total,
                    promedio,
                    porcentaje,
                    clase: 'valor-negativo',
                    datos
                });
            });

            // Ordenar por total descendente
            todasCategorias.sort((a, b) => b.total - a.total);

            todasCategorias.forEach(item => {
                html += `
                    <tr>
                        <td>
                            <strong>${item.categoria}</strong>
                            <br><small class="${item.clase}">${item.tipo}</small>
                        </td>
                        <td>${item.cantidad}</td>
                        <td class="${item.clase}">${formatearMoneda(item.total)}</td>
                        <td>${formatearMoneda(item.promedio)}</td>
                        <td>${item.porcentaje}%</td>
                        <td>
                            <button class="btn-rubro" onclick="verDetalleCategoria('${item.categoria}', '${item.tipo}')">
                                Ver Detalle
                            </button>
                        </td>
                    </tr>
                `;
            });

            if (todasCategorias.length === 0) {
                html = `
                    <tr class="sin-datos">
                        <td colspan="6">
                            <i class="bi bi-inbox" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                            No hay datos para mostrar en el período seleccionado
                        </td>
                    </tr>
                `;
            }

            tbody.innerHTML = html;
        }

        function verDetalleCategoria(categoria, tipo) {
            const tipoKey = tipo === 'Ingreso' ? 'ingresos' : 'egresos';
            const datos = datosInforme[tipoKey][categoria];
            
            if (!datos) return;

            document.getElementById('categoriaSeleccionada').textContent = `${categoria} (${tipo})`;
            
            const tbody = document.getElementById('tablaMovimientosRubro');
            let html = '';

            datos.movimientos.forEach(movimiento => {
                html += `
                    <tr>
                        <td>${formatearFecha(movimiento.fecha)}</td>
                        <td>${movimiento.detalle}</td>
                        <td>${movimiento.subcategoria || 'General'}</td>
                        <td class="${tipo === 'Ingreso' ? 'valor-positivo' : 'valor-negativo'}">
                            ${formatearMoneda(movimiento.monto)}
                        </td>
                        <td>${tipo}</td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            document.getElementById('detalleMovimientosSection').style.display = 'block';
            
            // Scroll al detalle
            document.getElementById('detalleMovimientosSection').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }

        function cerrarDetalle() {
            document.getElementById('detalleMovimientosSection').style.display = 'none';
        }

        function inicializarGraficos() {
            // Gráfico de distribución (pie chart)
            const ctx1 = document.getElementById('distribucionChart').getContext('2d');
            distribucionChart = new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            '#28a745', '#dc3545', '#17a2b8', '#ffc107', 
                            '#fd7e14', '#6f42c1', '#e83e8c', '#20c997'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Gráfico comparativo (bar chart)
            const ctx2 = document.getElementById('comparativoChart').getContext('2d');
            comparativoChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Ingresos',
                        data: [],
                        backgroundColor: '#28a745',
                        borderColor: '#1e7e34',
                        borderWidth: 1
                    }, {
                        label: 'Egresos',
                        data: [],
                        backgroundColor: '#dc3545',
                        borderColor: '#c82333',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatearMoneda(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function actualizarGraficos() {
            // Actualizar gráfico de distribución
            const etiquetas = [];
            const valores = [];
            
            Object.entries(datosInforme.ingresos).forEach(([categoria, datos]) => {
                etiquetas.push(`${categoria} (I)`);
                valores.push(datos.total);
            });
            
            Object.entries(datosInforme.egresos).forEach(([categoria, datos]) => {
                etiquetas.push(`${categoria} (E)`);
                valores.push(datos.total);
            });

            distribucionChart.data.labels = etiquetas;
            distribucionChart.data.datasets[0].data = valores;
            distribucionChart.update();

            // Actualizar gráfico comparativo
            const categoriasUnicas = new Set([
                ...Object.keys(datosInforme.ingresos),
                ...Object.keys(datosInforme.egresos)
            ]);

            const etiquetasComparativo = Array.from(categoriasUnicas);
            const ingresosData = etiquetasComparativo.map(cat => datosInforme.ingresos[cat]?.total || 0);
            const egresosData = etiquetasComparativo.map(cat => datosInforme.egresos[cat]?.total || 0);

            comparativoChart.data.labels = etiquetasComparativo;
            comparativoChart.data.datasets[0].data = ingresosData;
            comparativoChart.data.datasets[1].data = egresosData;
            comparativoChart.update();
        }

        function exportarInforme() {
            if (Object.keys(datosInforme).length === 0) {
                mostrarNotificacion('Genere un informe antes de exportar', 'error');
                return;
            }

            const datos = {
                fecha_generacion: new Date().toISOString(),
                periodo: {
                    desde: document.getElementById('fechaDesdeRubro').value,
                    hasta: document.getElementById('fechaHastaRubro').value
                },
                resumen: datosInforme.totales,
                categorias: {
                    ingresos: datosInforme.ingresos,
                    egresos: datosInforme.egresos
                }
            };

            const dataStr = JSON.stringify(datos, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            const url = URL.createObjectURL(dataBlob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `informe_rubros_${datos.periodo.desde}_${datos.periodo.hasta}.json`;
            link.click();
            URL.revokeObjectURL(url);

            mostrarNotificacion('Informe exportado exitosamente', 'success');
        }

        function imprimirInforme() {
            window.print();
        }

        function formatearMoneda(valor) {
            return new Intl.NumberFormat('es-CL', {
                style: 'currency',
                currency: 'CLP'
            }).format(valor || 0);
        }

        function formatearFecha(fecha) {
            return new Date(fecha).toLocaleDateString('es-CL');
        }

        function mostrarNotificacion(mensaje, tipo = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = mensaje;
            notification.className = `notification ${tipo}`;
            notification.style.display = 'block';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 4000);
        }

        // Escuchar cambios de localStorage de otros módulos
        window.addEventListener('storage', function(e) {
            if (Object.values(STORAGE_KEYS).includes(e.key)) {
                // Auto-regenerar informe si hay datos
                if (Object.keys(datosInforme).length > 0) {
                    generarInforme();
                }
            }
        });
    </script>
</body>
</html>
@endsection
