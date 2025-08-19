@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Egresos - Sistema Financiero</title>
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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
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
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
        
        .saldo-info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        
        .saldo-cuenta {
            font-weight: 600;
            color: #dc3545;
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
            <span>Registro de Egresos</span>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h1><i class="bi bi-credit-card"></i> Registro de Egresos</h1>
                    <p>Gestión de egresos del sistema financiero</p>
                </div>
                <a href="{{ route('dashboard', ['id' => $orgId]) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Dashboard
                </a>
            </div>

            <!-- Información de Saldos -->
            <div class="saldo-info">
                <h4><i class="bi bi-wallet2"></i> Saldos Disponibles</h4>
                <div class="form-grid">
                    <div>
                        <span class="saldo-cuenta">Caja General:</span>
                        <span id="saldoCajaGeneral">$0</span>
                    </div>
                    <div>
                        <span class="saldo-cuenta">Cuenta Corriente 1:</span>
                        <span id="saldoCuentaCorriente1">$0</span>
                    </div>
                    <div>
                        <span class="saldo-cuenta">Cuenta Corriente 2:</span>
                        <span id="saldoCuentaCorriente2">$0</span>
                    </div>
                </div>
            </div>

            <!-- Formulario de Registro -->
            <form id="egresosForm" method="POST" action="{{ route('egresos.store', ['id' => $orgId]) }}">
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
                                <i class="bi bi-receipt"></i> N° Boleta/Factura
                            </label>
                            <input type="text" id="nro_dcto" name="nro_dcto" placeholder="Ingrese número de comprobante" required>
                        </div>

                        <div class="form-group">
                            <label for="categoria">
                                <i class="bi bi-tags"></i> Categoría de Egreso
                            </label>
                            <select id="categoria" name="categoria" required>
                                <option value="">-- Selecciona una categoría --</option>
                                <option value="energia_electrica">Energía Eléctrica</option>
                                <option value="sueldos">Sueldos/Leyes Sociales</option>
                                <option value="mantencion">Mantención</option>
                                <option value="materiales_red">Materiales e Insumos de Red</option>
                                <option value="insumos_oficina">Insumos de Oficina</option>
                                <option value="trabajos_domicilio">Trabajos en Domicilio</option>
                                <option value="mejoramiento">Mejoramiento</option>
                                <option value="viaticos">Viáticos</option>
                                <option value="otras_cuentas">Otras Cuentas</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cuenta_origen">
                                <i class="bi bi-bank"></i> Cuenta Origen
                            </label>
                            <select id="cuenta_origen" name="cuenta_origen" required>
                                <option value="">-- Selecciona una cuenta --</option>
                                <option value="caja_general">Caja General</option>
                                <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
                                <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="monto">
                                <i class="bi bi-currency-dollar"></i> Monto
                            </label>
                            <input type="number" id="monto" name="monto" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>

                    <!-- Columna 2 -->
                    <div>
                        <div class="form-group">
                            <label for="razon_social">
                                <i class="bi bi-building"></i> Razón Social del Proveedor
                            </label>
                            <input type="text" id="razon_social" name="razon_social" placeholder="Nombre o razón social del proveedor" required>
                        </div>

                        <div class="form-group">
                            <label for="rut_proveedor">
                                <i class="bi bi-card-text"></i> RUT Proveedor
                            </label>
                            <input type="text" id="rut_proveedor" name="rut_proveedor" placeholder="11.111.111-1" pattern="[0-9]{1,2}\.[0-9]{3}\.[0-9]{3}-[0-9Kk]{1}">
                        </div>

                        <div class="form-group">
                            <label for="descripcion">
                                <i class="bi bi-card-text"></i> Descripción
                            </label>
                            <textarea id="descripcion" name="descripcion" rows="8" placeholder="Descripción detallada del egreso" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="btn-group full-width">
                    <button type="submit" class="btn btn-danger" style="flex: 1;">
                        <i class="bi bi-save"></i> Registrar Egreso
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
                <a href="{{ route('libro-caja.index', ['id' => $orgId]) }}" class="btn btn-info">
                    <i class="bi bi-journal-bookmark"></i> Ver Libro de Caja
                </a>
                <a href="{{ route('balance.index', ['id' => $orgId]) }}" class="btn btn-info">
                    <i class="bi bi-bar-chart"></i> Ver Balance
                </a>
                <a href="{{ route('movimientos.index', ['id' => $orgId]) }}" class="btn btn-info">
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
            egresos: 'finanzas_egresos',
            saldos: 'finanzas_saldos',
            estadisticas: 'finanzas_estadisticas'
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            generarNumeroComprobante();
            setupEventListeners();
            cargarSaldos();
        });

        function setupEventListeners() {
            const form = document.getElementById('egresosForm');
            form.addEventListener('submit', handleSubmit);

            const imprimirBtn = document.getElementById('imprimirComprobanteBtn');
            imprimirBtn.addEventListener('click', generarComprobante);

            const cuentaSelect = document.getElementById('cuenta_origen');
            cuentaSelect.addEventListener('change', mostrarSaldoCuenta);

            // Auto-actualizar número de comprobante
            document.getElementById('categoria').addEventListener('change', generarNumeroComprobante);
        }

        function cargarSaldos() {
            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{"caja_general": 0, "cuenta_corriente_1": 0, "cuenta_corriente_2": 0}');
            
            document.getElementById('saldoCajaGeneral').textContent = formatearMoneda(saldos.caja_general);
            document.getElementById('saldoCuentaCorriente1').textContent = formatearMoneda(saldos.cuenta_corriente_1);
            document.getElementById('saldoCuentaCorriente2').textContent = formatearMoneda(saldos.cuenta_corriente_2);
        }

        function mostrarSaldoCuenta() {
            const cuenta = document.getElementById('cuenta_origen').value;
            if (!cuenta) return;

            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{}');
            const saldo = saldos[cuenta] || 0;
            
            const cuentaNombre = {
                'caja_general': 'Caja General',
                'cuenta_corriente_1': 'Cuenta Corriente 1',
                'cuenta_corriente_2': 'Cuenta Corriente 2'
            };

            mostrarNotificacion(`Saldo disponible en ${cuentaNombre[cuenta]}: ${formatearMoneda(saldo)}`, 'info');
        }

        function handleSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const egreso = {
                id: Date.now(),
                fecha: formData.get('fecha'),
                nro_dcto: formData.get('nro_dcto'),
                categoria: formData.get('categoria'),
                cuenta_origen: formData.get('cuenta_origen'),
                razon_social: formData.get('razon_social'),
                rut_proveedor: formData.get('rut_proveedor'),
                descripcion: formData.get('descripcion'),
                monto: parseFloat(formData.get('monto')),
                timestamp: new Date().toISOString()
            };

            // Validaciones
            if (isNaN(egreso.monto) || egreso.monto <= 0) {
                mostrarNotificacion('Ingrese un monto válido y positivo', 'error');
                return;
            }

            // Validar saldo suficiente
            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{}');
            const saldoDisponible = saldos[egreso.cuenta_origen] || 0;
            
            if (saldoDisponible < egreso.monto) {
                mostrarNotificacion('Saldo insuficiente en la cuenta seleccionada', 'error');
                return;
            }

            // Validar RUT si se proporciona
            if (egreso.rut_proveedor && !validarRut(egreso.rut_proveedor)) {
                mostrarNotificacion('El RUT debe tener el formato correcto (ej: 11.111.111-1)', 'error');
                return;
            }

            // Guardar en localStorage para comunicación entre módulos
            guardarEgreso(egreso);

            // Enviar al servidor Laravel
            enviarAlServidor(formData);
        }

        function guardarEgreso(egreso) {
            // Obtener egresos existentes
            const egresos = JSON.parse(localStorage.getItem(STORAGE_KEYS.egresos) || '[]');
            egresos.push(egreso);
            localStorage.setItem(STORAGE_KEYS.egresos, JSON.stringify(egresos));

            // Actualizar saldos (restar monto)
            actualizarSaldos(egreso.cuenta_origen, -egreso.monto);

            // Actualizar estadísticas
            actualizarEstadisticas();

            // Notificar a otros módulos
            window.dispatchEvent(new CustomEvent('egresoRegistrado', { detail: egreso }));

            mostrarNotificacion('Egreso registrado exitosamente en el sistema local', 'success');
            
            // Actualizar saldos en pantalla
            cargarSaldos();
        }

        function actualizarSaldos(cuenta, monto) {
            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{"caja_general": 0, "cuenta_corriente_1": 0, "cuenta_corriente_2": 0}');
            saldos[cuenta] = (saldos[cuenta] || 0) + monto;
            localStorage.setItem(STORAGE_KEYS.saldos, JSON.stringify(saldos));
        }

        function actualizarEstadisticas() {
            const ingresos = JSON.parse(localStorage.getItem('finanzas_ingresos') || '[]');
            const egresos = JSON.parse(localStorage.getItem(STORAGE_KEYS.egresos) || '[]');
            
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
            fetch('{{ route("egresos.store", ['id' => $orgId]) }}', {
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
                    mostrarNotificacion('Egreso guardado en el servidor exitosamente', 'success');
                    document.getElementById('egresosForm').reset();
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
            const contador = parseInt(localStorage.getItem('contador_comprobantes_egresos') || '1');
            document.getElementById('nro_dcto').value = `EGR-${String(contador).padStart(6, '0')}`;
            localStorage.setItem('contador_comprobantes_egresos', contador + 1);
        }

        function generarComprobante() {
            const form = document.getElementById('egresosForm');
            const formData = new FormData(form);
            
            if (!formData.get('monto') || !formData.get('descripcion')) {
                mostrarNotificacion('Complete el formulario antes de generar el comprobante', 'error');
                return;
            }

            // Generar PDF del comprobante
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Comprobante de Egreso</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; border-bottom: 2px solid #dc3545; padding-bottom: 10px; }
                        .content { margin: 20px 0; }
                        .row { display: flex; justify-content: space-between; margin: 10px 0; }
                        .label { font-weight: bold; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>COMPROBANTE DE EGRESO</h2>
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
                            <span>${formData.get('cuenta_origen')}</span>
                        </div>
                        <div class="row">
                            <span class="label">Proveedor:</span>
                            <span>${formData.get('razon_social')}</span>
                        </div>
                        <div class="row">
                            <span class="label">RUT:</span>
                            <span>${formData.get('rut_proveedor')}</span>
                        </div>
                        <div class="row">
                            <span class="label">Monto:</span>
                            <span>${formatearMoneda(parseFloat(formData.get('monto')))}</span>
                        </div>
                        <div class="row">
                            <span class="label">Descripción:</span>
                            <span>${formData.get('descripcion')}</span>
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

        function validarRut(rut) {
            // Eliminar puntos y guión
            const rutLimpio = rut.replace(/[.-]/g, '');
            
            // Verificar que tenga al menos 8 caracteres
            if (rutLimpio.length < 8) return false;
            
            // Separar número y dígito verificador
            const numero = rutLimpio.slice(0, -1);
            const dv = rutLimpio.slice(-1).toUpperCase();
            
            // Calcular dígito verificador
            let suma = 0;
            let multiplicador = 2;
            
            for (let i = numero.length - 1; i >= 0; i--) {
                suma += parseInt(numero[i]) * multiplicador;
                multiplicador = multiplicador === 7 ? 2 : multiplicador + 1;
            }
            
            const resto = suma % 11;
            const dvCalculado = resto === 0 ? '0' : resto === 1 ? 'K' : (11 - resto).toString();
            
            return dv === dvCalculado;
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
            if (e.key === STORAGE_KEYS.saldos) {
                cargarSaldos();
            }
        });

        // Escuchar eventos de ingresos para actualizar saldos
        window.addEventListener('ingresoRegistrado', function(e) {
            cargarSaldos();
        });
    </script>
</body>
</html>
@endsection
