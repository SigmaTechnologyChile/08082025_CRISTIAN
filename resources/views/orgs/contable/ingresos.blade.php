@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Ingresos - Sistema Financiero</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/contable/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .container {
            max-width: 1200px;
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }
        
        .full-width {
            grid-column: span 2;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
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
    </style>
</head>

<body>
    <div id="notification" class="notification"></div>

    <div class="container">
        <!-- Navegación -->
        <div class="nav-breadcrumb">
            <a href="{{ route('dashboard') }}">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <span> / </span>
            <span>Registro de Ingresos</span>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h1><i class="bi bi-cash-coin"></i> Registro de Ingresos</h1>
                    <p>Gestión de ingresos del sistema financiero</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Dashboard
                </a>
            </div>

            <!-- Formulario de Registro -->
            <form id="ingresosForm" method="POST" action="{{ route('ingresos.store') }}">
                @csrf
                <div class="form-grid">
                    <!-- Columna 1 -->
                    <div>
                        <div class="form-group">
                            <label for="fecha">
                                <i class="bi bi-calendar3"></i> Fecha
                            </label>
                            <input type="date" id="fecha" name="fecha" required value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label for="nro_dcto">
                                <i class="bi bi-receipt"></i> N° Comprobante
                            </label>
                            <input type="text" id="nro_dcto" name="nro_dcto" placeholder="Ingrese número de comprobante" required>
                        </div>

                        <div class="form-group">
                            <label for="categoria">
                                <i class="bi bi-tags"></i> Categoría de Ingreso
                            </label>
                            <select id="categoria" name="categoria" required>
                                <option value="">-- Selecciona una categoría --</option>
                                <option value="venta_agua">Venta de Agua (Total Consumo)</option>
                                <option value="cuotas_incorporacion">Cuotas de Incorporación</option>
                                <option value="venta_medidores">Venta de Medidores (Otros Ingresos)</option>
                                <option value="trabajos_domicilio">Trabajos en Domicilio (Otros Ingresos)</option>
                                <option value="subsidios">Subsidios (Otros Ingresos)</option>
                                <option value="otros_aportes">Otros Aportes (Otros Ingresos)</option>
                                <option value="multas_inasistencia">Multas Inasistencia (Otros Ingresos)</option>
                                <option value="otras_multas">Otras Multas (Otros Ingresos)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cuenta_destino">
                                <i class="bi bi-bank"></i> Cuenta Destino
                            </label>
                            <select id="cuenta_destino" name="cuenta_destino" required>
                                <option value="">-- Selecciona una cuenta --</option>
                                <option value="caja_general">Caja General</option>
                                <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
                                <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
                            </select>
                        </div>
                    </div>

                    <!-- Columna 2 -->
                    <div>
                        <div class="form-group">
                            <label for="monto">
                                <i class="bi bi-currency-dollar"></i> Monto
                            </label>
                            <input type="number" id="monto" name="monto" step="0.01" placeholder="0.00" required>
                        </div>

                        <div class="form-group">
                            <label for="detalle">
                                <i class="bi bi-card-text"></i> Descripción
                            </label>
                            <textarea id="detalle" name="detalle" rows="8" placeholder="Descripción del ingreso" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="btn-group full-width">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="bi bi-save"></i> Registrar Ingreso
                    </button>
                    <button type="button" class="btn btn-info" id="imprimirComprobanteBtn" style="flex: 1;">
                        <i class="bi bi-printer"></i> Imprimir Comprobante
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>

        <!-- Enlaces Rápidos -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3><i class="bi bi-lightning"></i> Acciones Rápidas</h3>
            </div>
            <div class="btn-group">
                <a href="{{ route('libro-caja.index') }}" class="btn btn-info">
                    <i class="bi bi-journal-bookmark"></i> Ver Libro de Caja
                </a>
                <a href="{{ route('balance.index') }}" class="btn btn-info">
                    <i class="bi bi-bar-chart"></i> Ver Balance
                </a>
                <a href="{{ route('movimientos.index') }}" class="btn btn-info">
                    <i class="bi bi-list-check"></i> Ver Movimientos
                </a>
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
            saldos: 'finanzas_saldos',
            estadisticas: 'finanzas_estadisticas'
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            generarNumeroComprobante();
            setupEventListeners();
        });

        function setupEventListeners() {
            const form = document.getElementById('ingresosForm');
            form.addEventListener('submit', handleSubmit);

            const imprimirBtn = document.getElementById('imprimirComprobanteBtn');
            imprimirBtn.addEventListener('click', generarComprobante);

            // Auto-actualizar número de comprobante
            document.getElementById('categoria').addEventListener('change', generarNumeroComprobante);
        }

        function handleSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const ingreso = {
                id: Date.now(),
                fecha: formData.get('fecha'),
                nro_dcto: formData.get('nro_dcto'),
                categoria: formData.get('categoria'),
                cuenta_destino: formData.get('cuenta_destino'),
                detalle: formData.get('detalle'),
                monto: parseFloat(formData.get('monto')),
                timestamp: new Date().toISOString()
            };

            // Validaciones
            if (isNaN(ingreso.monto) || ingreso.monto <= 0) {
                mostrarNotificacion('Ingrese un monto válido y positivo', 'error');
                return;
            }

            // Guardar en localStorage para comunicación entre módulos
            guardarIngreso(ingreso);

            // Enviar al servidor Laravel
            enviarAlServidor(formData);
        }

        function guardarIngreso(ingreso) {
            // Obtener ingresos existentes
            const ingresos = JSON.parse(localStorage.getItem(STORAGE_KEYS.ingresos) || '[]');
            ingresos.push(ingreso);
            localStorage.setItem(STORAGE_KEYS.ingresos, JSON.stringify(ingresos));

            // Actualizar saldos
            actualizarSaldos(ingreso.cuenta_destino, ingreso.monto);

            // Actualizar estadísticas
            actualizarEstadisticas();

            // Notificar a otros módulos
            window.dispatchEvent(new CustomEvent('ingresoRegistrado', { detail: ingreso }));

            mostrarNotificacion('Ingreso registrado exitosamente en el sistema local', 'success');
        }

        function actualizarSaldos(cuenta, monto) {
            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{"caja_general": 0, "cuenta_corriente_1": 0, "cuenta_corriente_2": 0}');
            saldos[cuenta] = (saldos[cuenta] || 0) + monto;
            localStorage.setItem(STORAGE_KEYS.saldos, JSON.stringify(saldos));
        }

        function actualizarEstadisticas() {
            const ingresos = JSON.parse(localStorage.getItem(STORAGE_KEYS.ingresos) || '[]');
            const egresos = JSON.parse(localStorage.getItem('finanzas_egresos') || '[]');
            
            const totalIngresos = ingresos.reduce((sum, item) => sum + item.monto, 0);
            const totalEgresos = egresos.reduce((sum, item) => sum + item.monto, 0);
            const saldoFinal = totalIngresos - totalEgresos;

            const estadisticas = {
                totalIngresos,
                totalEgresos,
                saldoFinal,
                ultimaActualizacion: new Date().toISOString()
            };

            localStorage.setItem(STORAGE_KEYS.estadisticas, JSON.stringify(estadisticas));
        }

        function enviarAlServidor(formData) {
            fetch('{{ route("ingresos.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion('Ingreso guardado en el servidor exitosamente', 'success');
                    document.getElementById('ingresosForm').reset();
                    generarNumeroComprobante();
                } else {
                    mostrarNotificacion('Error al guardar en el servidor: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión con el servidor', 'error');
            });
        }

        function generarNumeroComprobante() {
            const contador = parseInt(localStorage.getItem('contador_comprobantes') || '1');
            document.getElementById('nro_dcto').value = `ING-${String(contador).padStart(6, '0')}`;
            localStorage.setItem('contador_comprobantes', contador + 1);
        }

        function generarComprobante() {
            const form = document.getElementById('ingresosForm');
            const formData = new FormData(form);
            
            if (!formData.get('monto') || !formData.get('detalle')) {
                mostrarNotificacion('Complete el formulario antes de generar el comprobante', 'error');
                return;
            }

            // Generar PDF del comprobante
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Comprobante de Ingreso</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; border-bottom: 2px solid #28a745; padding-bottom: 10px; }
                        .content { margin: 20px 0; }
                        .row { display: flex; justify-content: space-between; margin: 10px 0; }
                        .label { font-weight: bold; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>COMPROBANTE DE INGRESO</h2>
                        <p>N° ${formData.get('nro_dcto')}</p>
                    </div>
                    <div class="content">
                        <div class="row">
                            <span class="label">Fecha:</span>
                            <span>${formData.get('fecha')}</span>
                        </div>
                        <div class="row">
                            <span class="label">Categoría:</span>
                            <span>${formData.get('categoria')}</span>
                        </div>
                        <div class="row">
                            <span class="label">Cuenta:</span>
                            <span>${formData.get('cuenta_destino')}</span>
                        </div>
                        <div class="row">
                            <span class="label">Monto:</span>
                            <span>${formatearMoneda(parseFloat(formData.get('monto')))}</span>
                        </div>
                        <div class="row">
                            <span class="label">Descripción:</span>
                            <span>${formData.get('detalle')}</span>
                        </div>
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(() => window.close(), 1000);
                        }
                    </script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        function formatearMoneda(valor) {
            return new Intl.NumberFormat('es-CL', {
                style: 'currency',
                currency: 'CLP'
            }).format(valor || 0);
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

        // Escuchar cambios de otros módulos
        window.addEventListener('storage', function(e) {
            if (e.key === STORAGE_KEYS.ingresos) {
                console.log('Datos de ingresos actualizados desde otro módulo');
            }
        });
    </script>
</body>
</html>
@endsection
