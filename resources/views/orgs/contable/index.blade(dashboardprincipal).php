@extends('layouts.nice', ['active'=>'dashboard','title'=>'Dashboard Contable'])

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Financiera - Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
        }
        
        .btn-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .btn-wrapper button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 20px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            min-height: 80px;
        }
        
        .btn-wrapper button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .btn-wrapper button i {
            font-size: 24px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid;
        }
        
        .stat-card.success { border-left-color: #28a745; }
        .stat-card.danger { border-left-color: #dc3545; }
        .stat-card.primary { border-left-color: #667eea; }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
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
    </style>
</head>

<body>
    <div id="notification" class="notification"></div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Sistema de Gestión Financiera</h1>
                <p>Panel de Control Principal</p>
            </div>

            <!-- Estadísticas Generales -->
            <div class="stats-grid">
                <div class="stat-card success">
                    <div class="stat-value" id="totalIngresosDisplay">$0</div>
                    <div class="stat-label">Total Ingresos</div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-value" id="totalEgresosDisplay">$0</div>
                    <div class="stat-label">Total Egresos</div>
                </div>
                <div class="stat-card primary">
                    <div class="stat-value" id="saldoFinalDisplay">$0</div>
                    <div class="stat-label">Saldo Final</div>
                </div>
            </div>

            <!-- Menú Principal -->
            <div class="btn-wrapper">
                <a href="{{ route('orgs.contable.ingresos', ['id' => auth()->user()->org_id]) }}" class="btn btn-primary">
                    <i class="bi bi-cash-coin"></i>
                    Registro de Ingresos
                </a>

                <a href="{{ route('orgs.contable.egresos', ['id' => auth()->user()->org_id]) }}" class="btn btn-primary">
                    <i class="bi bi-credit-card"></i>
                    Registro de Egresos
                </a>

                <a href="{{ route('orgs.contable.giros-depositos', ['id' => auth()->user()->org_id]) }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left-right"></i>
                    Giros y Depósitos
                </a>

                <a href="{{ route('orgs.contable.libro-caja', ['id' => auth()->user()->org_id]) }}" class="btn btn-primary">
                    <i class="bi bi-journal-bookmark"></i>
                    Libro de Caja Tabular
                </a>

                <a href="{{ route('orgs.contable.balance', ['id' => auth()->user()->org_id]) }}" class="btn btn-primary">
                    <i class="bi bi-bar-chart"></i>
                    Balance
                </a>

                <a href="{{ route('orgs.contable.conciliacion', ['id' => auth()->user()->org_id]) }}" class="btn btn-primary">
                    <i class="bi bi-bank"></i>
                    Conciliación Bancaria
                </a>

                <a href="{{ route('orgs.contable.informe-rubro', ['id' => auth()->user()->org_id]) }}" class="btn btn-primary">
                    <i class="bi bi-pie-chart"></i>
                    Informe por Rubro
                </a>

                <a href="{{ route('orgs.contable.movimientos', ['id' => auth()->user()->org_id]) }}" class="btn btn-primary">
                    <i class="bi bi-list-check"></i>
                    Movimientos
                </a>
            </div>
        </div>
    </div>

    <script>
        // Funciones para actualizar estadísticas del dashboard
        function actualizarEstadisticas() {
            fetch('{{ route("orgs.contable.dashboard.estadisticas", ["id" => auth()->user()->org_id]) }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalIngresosDisplay').textContent = formatearMoneda(data.totalIngresos);
                    document.getElementById('totalEgresosDisplay').textContent = formatearMoneda(data.totalEgresos);
                    document.getElementById('saldoFinalDisplay').textContent = formatearMoneda(data.saldoFinal);
                })
                .catch(error => console.error('Error:', error));
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
            }, 3000);
        }

        // Cargar estadísticas al iniciar
        document.addEventListener('DOMContentLoaded', function() {
            actualizarEstadisticas();
            
            // Actualizar cada 30 segundos
            setInterval(actualizarEstadisticas, 30000);
        });

        // Escuchar eventos de storage para sincronizar entre módulos
        window.addEventListener('storage', function(e) {
            if (e.key && e.key.includes('finanzas_')) {
                actualizarEstadisticas();
            }
        });
    </script>
</body>
</html>
@endsection
