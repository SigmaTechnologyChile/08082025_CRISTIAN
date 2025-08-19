@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giros y Depósitos - Sistema Financiero</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/contable/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
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
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .giros-depositos-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .form-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            border: 2px solid #e9ecef;
        }
        
        .form-section h3 {
            margin-bottom: 20px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
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
            border-color: #17a2b8;
            box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
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
        
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
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
        
        .saldo-info {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #17a2b8;
        }
        
        .saldo-cuenta {
            font-weight: 600;
            color: #17a2b8;
            margin-bottom: 8px;
        }
        
        .giro-section {
            border-left: 4px solid #ffc107;
            background: #fff9e6;
        }
        
        .deposito-section {
            border-left: 4px solid #17a2b8;
            background: #e7f3ff;
        }
        
        .transferencia-info {
            background: #d1ecf1;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #bee5eb;
        }
        
        @media (max-width: 768px) {
            .giros-depositos-grid {
                grid-template-columns: 1fr;
            }
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
            <span>Giros y Depósitos</span>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h1><i class="bi bi-arrow-left-right"></i> Giros y Depósitos</h1>
                    <p>Gestión de transferencias entre cuentas</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Dashboard
                </a>
            </div>

            <!-- Información de Saldos -->
            <div class="saldo-info">
                <h4><i class="bi bi-wallet2"></i> Saldos Disponibles por Cuenta</h4>
                <div class="giros-depositos-grid">
                    <div>
                        <div class="saldo-cuenta">Caja General: <span id="saldoCajaGeneral">$0</span></div>
                        <div class="saldo-cuenta">Cuenta Corriente 1: <span id="saldoCuentaCorriente1">$0</span></div>
                    </div>
                    <div>
                        <div class="saldo-cuenta">Cuenta Corriente 2: <span id="saldoCuentaCorriente2">$0</span></div>
                        <div class="saldo-cuenta">Total Sistema: <span id="saldoTotal">$0</span></div>
                    </div>
                </div>
            </div>

            <!-- Formularios de Giros y Depósitos -->
            <div class="giros-depositos-grid">
                <!-- Sección Giros -->
                <div class="form-section giro-section">
                    <h3><i class="bi bi-send"></i> Registrar Giro</h3>
                    <p><em>Transferir dinero desde cuenta bancaria a Caja General</em></p>
                    
                    <form id="girosForm" method="POST" action="{{ route('giros.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="fecha_giro">
                                <i class="bi bi-calendar3"></i> Fecha
                            </label>
                            <input type="date" id="fecha_giro" name="fecha" required value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label for="monto_giro">
                                <i class="bi bi-currency-dollar"></i> Monto a Girar
                            </label>
                            <input type="number" id="monto_giro" name="monto" step="0.01" placeholder="0.00" required>
                        </div>

                        <div class="form-group">
                            <label for="cuenta_origen_giro">
                                <i class="bi bi-bank"></i> Cuenta Origen
                            </label>
                            <select id="cuenta_origen_giro" name="cuenta_origen" required>
                                <option value="">-- Selecciona cuenta origen --</option>
                                <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
                                <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="detalle_giro">
                                <i class="bi bi-card-text"></i> Detalle del Giro
                            </label>
                            <textarea id="detalle_giro" name="detalle" rows="3" placeholder="Describe el motivo del giro" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning" style="width: 100%;">
                            <i class="bi bi-send"></i> Registrar Giro
                        </button>
                    </form>
                </div>

                <!-- Sección Depósitos -->
                <div class="form-section deposito-section">
                    <h3><i class="bi bi-bank"></i> Registrar Depósito</h3>
                    <p><em>Transferir dinero desde Caja General a cuenta bancaria</em></p>
                    
                    <form id="depositosForm" method="POST" action="{{ route('depositos.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="fecha_deposito">
                                <i class="bi bi-calendar3"></i> Fecha
                            </label>
                            <input type="date" id="fecha_deposito" name="fecha" required value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label for="monto_deposito">
                                <i class="bi bi-currency-dollar"></i> Monto a Depositar
                            </label>
                            <input type="number" id="monto_deposito" name="monto" step="0.01" placeholder="0.00" required>
                        </div>

                        <div class="form-group">
                            <label for="cuenta_destino_deposito">
                                <i class="bi bi-bank2"></i> Cuenta Destino
                            </label>
                            <select id="cuenta_destino_deposito" name="cuenta_destino" required>
                                <option value="">-- Selecciona cuenta destino --</option>
                                <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
                                <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="detalle_deposito">
                                <i class="bi bi-card-text"></i> Detalle del Depósito
                            </label>
                            <textarea id="detalle_deposito" name="detalle" rows="3" placeholder="Describe el motivo del depósito" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-info" style="width: 100%;">
                            <i class="bi bi-bank"></i> Registrar Depósito
                        </button>
                    </form>
                </div>
            </div>

            <!-- Información de Transferencias -->
            <div class="transferencia-info">
                <h4><i class="bi bi-info-circle"></i> Información sobre Transferencias</h4>
                <ul>
                    <li><strong>Giro:</strong> Retira dinero de una cuenta bancaria y lo transfiere a Caja General</li>
                    <li><strong>Depósito:</strong> Transfiere dinero de Caja General a una cuenta bancaria</li>
                    <li>Ambas operaciones actualizan automáticamente los saldos del sistema</li>
                    <li>Las transferencias se reflejan inmediatamente en el Libro de Caja y Balance</li>
                </ul>
            </div>
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
            giros: 'finanzas_giros',
            depositos: 'finanzas_depositos',
            saldos: 'finanzas_saldos',
            estadisticas: 'finanzas_estadisticas'
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            cargarSaldos();
        });

        function setupEventListeners() {
            const girosForm = document.getElementById('girosForm');
            girosForm.addEventListener('submit', handleGiroSubmit);

            const depositosForm = document.getElementById('depositosForm');
            depositosForm.addEventListener('submit', handleDepositoSubmit);

            // Validar saldos al cambiar montos
            document.getElementById('monto_giro').addEventListener('input', validarGiro);
            document.getElementById('monto_deposito').addEventListener('input', validarDeposito);
            
            document.getElementById('cuenta_origen_giro').addEventListener('change', validarGiro);
        }

        function cargarSaldos() {
            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{"caja_general": 0, "cuenta_corriente_1": 0, "cuenta_corriente_2": 0}');
            
            document.getElementById('saldoCajaGeneral').textContent = formatearMoneda(saldos.caja_general);
            document.getElementById('saldoCuentaCorriente1').textContent = formatearMoneda(saldos.cuenta_corriente_1);
            document.getElementById('saldoCuentaCorriente2').textContent = formatearMoneda(saldos.cuenta_corriente_2);
            
            const saldoTotal = saldos.caja_general + saldos.cuenta_corriente_1 + saldos.cuenta_corriente_2;
            document.getElementById('saldoTotal').textContent = formatearMoneda(saldoTotal);
        }

        function validarGiro() {
            const cuenta = document.getElementById('cuenta_origen_giro').value;
            const monto = parseFloat(document.getElementById('monto_giro').value);
            
            if (!cuenta || isNaN(monto)) return;

            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{}');
            const saldoDisponible = saldos[cuenta] || 0;
            
            if (monto > saldoDisponible) {
                mostrarNotificacion(`Saldo insuficiente. Disponible: ${formatearMoneda(saldoDisponible)}`, 'error');
            }
        }

        function validarDeposito() {
            const monto = parseFloat(document.getElementById('monto_deposito').value);
            
            if (isNaN(monto)) return;

            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{}');
            const saldoCaja = saldos.caja_general || 0;
            
            if (monto > saldoCaja) {
                mostrarNotificacion(`Saldo insuficiente en Caja General. Disponible: ${formatearMoneda(saldoCaja)}`, 'error');
            }
        }

        function handleGiroSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const giro = {
                id: Date.now(),
                tipo: 'giro',
                fecha: formData.get('fecha'),
                cuenta_origen: formData.get('cuenta_origen'),
                cuenta_destino: 'caja_general',
                detalle: formData.get('detalle'),
                monto: parseFloat(formData.get('monto')),
                timestamp: new Date().toISOString()
            };

            // Validaciones
            if (isNaN(giro.monto) || giro.monto <= 0) {
                mostrarNotificacion('Ingrese un monto válido y positivo', 'error');
                return;
            }

            // Validar saldo suficiente
            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{}');
            const saldoDisponible = saldos[giro.cuenta_origen] || 0;
            
            if (saldoDisponible < giro.monto) {
                mostrarNotificacion('Saldo insuficiente en la cuenta seleccionada', 'error');
                return;
            }

            // Guardar giro y actualizar saldos
            guardarGiro(giro);

            // Enviar al servidor Laravel
            enviarGiroAlServidor(formData);
        }

        function handleDepositoSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const deposito = {
                id: Date.now(),
                tipo: 'deposito',
                fecha: formData.get('fecha'),
                cuenta_origen: 'caja_general',
                cuenta_destino: formData.get('cuenta_destino'),
                detalle: formData.get('detalle'),
                monto: parseFloat(formData.get('monto')),
                timestamp: new Date().toISOString()
            };

            // Validaciones
            if (isNaN(deposito.monto) || deposito.monto <= 0) {
                mostrarNotificacion('Ingrese un monto válido y positivo', 'error');
                return;
            }

            // Validar saldo suficiente en Caja General
            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{}');
            const saldoCaja = saldos.caja_general || 0;
            
            if (saldoCaja < deposito.monto) {
                mostrarNotificacion('Saldo insuficiente en Caja General', 'error');
                return;
            }

            // Guardar depósito y actualizar saldos
            guardarDeposito(deposito);

            // Enviar al servidor Laravel
            enviarDepositoAlServidor(formData);
        }

        function guardarGiro(giro) {
            // Obtener giros existentes
            const giros = JSON.parse(localStorage.getItem(STORAGE_KEYS.giros) || '[]');
            giros.push(giro);
            localStorage.setItem(STORAGE_KEYS.giros, JSON.stringify(giros));

            // Actualizar saldos: restar de origen, sumar a destino
            actualizarSaldos(giro.cuenta_origen, -giro.monto);
            actualizarSaldos(giro.cuenta_destino, giro.monto);

            // Notificar a otros módulos
            window.dispatchEvent(new CustomEvent('giroRegistrado', { detail: giro }));

            mostrarNotificacion('Giro registrado exitosamente', 'success');
            document.getElementById('girosForm').reset();
            cargarSaldos();
        }

        function guardarDeposito(deposito) {
            // Obtener depósitos existentes
            const depositos = JSON.parse(localStorage.getItem(STORAGE_KEYS.depositos) || '[]');
            depositos.push(deposito);
            localStorage.setItem(STORAGE_KEYS.depositos, JSON.stringify(depositos));

            // Actualizar saldos: restar de origen, sumar a destino
            actualizarSaldos(deposito.cuenta_origen, -deposito.monto);
            actualizarSaldos(deposito.cuenta_destino, deposito.monto);

            // Notificar a otros módulos
            window.dispatchEvent(new CustomEvent('depositoRegistrado', { detail: deposito }));

            mostrarNotificacion('Depósito registrado exitosamente', 'success');
            document.getElementById('depositosForm').reset();
            cargarSaldos();
        }

        function actualizarSaldos(cuenta, monto) {
            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{"caja_general": 0, "cuenta_corriente_1": 0, "cuenta_corriente_2": 0}');
            saldos[cuenta] = (saldos[cuenta] || 0) + monto;
            localStorage.setItem(STORAGE_KEYS.saldos, JSON.stringify(saldos));
        }

        function enviarGiroAlServidor(formData) {
            fetch('{{ route("giros.store") }}', {
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
                    mostrarNotificacion('Giro guardado en el servidor exitosamente', 'success');
                } else {
                    mostrarNotificacion('Error al guardar giro en el servidor: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión con el servidor para giros', 'error');
            });
        }

        function enviarDepositoAlServidor(formData) {
            fetch('{{ route("depositos.store") }}', {
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
                    mostrarNotificacion('Depósito guardado en el servidor exitosamente', 'success');
                } else {
                    mostrarNotificacion('Error al guardar depósito en el servidor: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión con el servidor para depósitos', 'error');
            });
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

        // Escuchar eventos de otros módulos
        window.addEventListener('ingresoRegistrado', cargarSaldos);
        window.addEventListener('egresoRegistrado', cargarSaldos);
    </script>
</body>
</html>
@endsection
