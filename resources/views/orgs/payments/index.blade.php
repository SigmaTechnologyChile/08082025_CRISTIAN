
@extends('layouts.nice', ['active' => 'orgs.payments.index', 'title' => 'Ingresar un Pago'])
@push('styles')
<style>
    @page {
        size: landscape;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        .print-title, .table-responsive, .table-responsive * {
            visibility: visible !important;
        }
        .print-title {
            position: fixed;
            top: 20px;
            left: 0;
            width: 100vw;
            text-align: center;
            font-size: 2em;
            font-weight: bold;
            color: #222;
            background: #fff;
            z-index: 10001;
            font-family: Arial, Helvetica, sans-serif;
        }
        .table-responsive {
            position: fixed;
            left: 0;
            top: 80px;
            width: 100vw;
            height: calc(100vh - 100px);
            background: #fff;
            z-index: 9999;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }
        .table {
            width: 95vw !important;
            font-size: 0.95em !important;
            margin: auto;
            border-collapse: collapse !important;
            font-family: Arial, Helvetica, sans-serif;
            background: #fff !important;
        }
        .table th, .table td {
            white-space: normal !important;
            padding: 6px 10px !important;
            border: 1px solid #888 !important;
            font-size: 0.95em !important;
            color: #222 !important;
        }
        .table th {
            background: #e6f0ff !important;
            font-weight: bold !important;
            border-bottom: 2px solid #4a90e2 !important;
        }
        .table tr:nth-child(even) td {
            background: #f7fafd !important;
        }
        .table tr:nth-child(odd) td {
            background: #fff !important;
        }
        /* Ocultar todo lo demás en impresión */
        .row.g-3, .btn, .alert, .pagetitle, .card-footer, .modal, .modal-backdrop, nav, .breadcrumb {
            display: none !important;
        }
    }
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
        }
    }

    /* Estilos adicionales para la tabla */
    .table-responsive {
        overflow-x: unset;
        width: 100%;
    }
    .table th, .table td {
        vertical-align: middle;
        white-space: nowrap;
    }
    
    /* Estilos para los períodos pendientes */
    .period-badges {
        max-width: 150px;
    }
    
    .period-badge {
        font-size: 0.7em;
        margin: 1px;
        white-space: nowrap;
    }
    
    .table td.periods-col {
        white-space: normal !important;
        max-width: 150px;
    }
    /* Estilos para impresión */
    @media print {
        body * {
            visibility: hidden;
        }
        .table-responsive, .table-responsive * {
            visibility: visible !important;
        }
        .table-responsive {
            position: absolute;
            left: 0;
            top: 0;
            width: 100vw;
            background: #fff;
            z-index: 9999;
        }
        .table {
            width: 100% !important;
            font-size: 1em !important;
        }
        .table th, .table td {
            white-space: normal !important;
            padding: 8px !important;
        }
        /* Ocultar botones y filtros en impresión */
        .row.g-3, .btn, .alert, .pagetitle, .card-footer, .modal, .modal-backdrop {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="pagetitle print-title" style="display:none;">{{$org->name}} - Pagos Pendientes</div>
<div class="pagetitle">
    <h1>{{$org->name}}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
            <li class="breadcrumb-item"><a href="{{route('orgs.dashboard', $org->id)}}">{{$org->name}}</a></li>
            <li class="breadcrumb-item active">Pagos</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
    <div class="card top-selling overflow-auto">
        <div class="card-body pt-2">
            <form method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    <!-- Sectores -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold d-block text-center">Sectores</label>
                        <select name="sector" class="form-select rounded-3 py-2">
                            @if($locations->isNotEmpty())
                                <option value="">Todos</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('sector', request()->sector) == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">No hay sectores disponibles</option>
                            @endif
                        </select>
                    </div>

                    <!-- Buscador -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold d-block text-center">Buscar</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-white ps-3">
                                <i class="bi bi-search fs-5 text-primary"></i>
                            </span>
                            <input type="text" name="search" class="form-control rounded-end-3 py-2"
                                placeholder="Buscar por nombre, apellido, Rut"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <!-- Botón Filtrar -->
                    <div class="col-md-auto d-flex align-items-center">
                        <button type="submit" class="btn btn-primary pulse-btn p-1 px-2 rounded-2 enhanced-btn">
                            <i class="bi bi-funnel-fill me-2"></i>Filtrar
                        </button>
                    </div>
@php
    $disabled = false;
@endphp
                    <!-- Botón Imprimir -->
                    <div class="col-md-auto d-flex align-items-center ms-2">
                        <button type="button" id="printBtn" class="btn btn-danger pulse-btn p-1 px-2 rounded-2 enhanced-btn" style="background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%); border: none;">
                            <i class="bi bi-printer me-2"></i>Imprimir
                        </button>
                    </div>
                </div>
            </form>

            <!-- Información sobre los datos mostrados -->
            <div class="alert alert-info mt-3" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Registros mostrados:</strong> Todos los clientes con pagos pendientes de cualquier período/mes.
                Los períodos se muestran ordenados del más antiguo al más reciente.<br>
                <span style="color:#0a58ca;font-weight:500;">La impresión se genera en formato horizontal (landscape) por defecto para mejor visualización de la tabla.</span>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center">
                                N° Cliente
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
                            </th>
                            <th scope="col" class="text-center">
                                RUT/RUN
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'rut', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'rut', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
                            </th>
                            <th scope="col" class="text-center">
                                Nombres
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'first_name', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'first_name', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
                            </th>
                            <th scope="col" class="text-center">
                                Apellidos
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'last_name', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'last_name', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
                            </th>
                            <th scope="col" class="text-center">
                                Servicios
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'qrx_serv', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'qrx_serv', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
                            </th>
                            <th scope="col" class="text-center">
                                Períodos Pendientes
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'pending_periods', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'pending_periods', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
                            </th>
                            <th scope="col" class="text-center">
                                Estado
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_amount', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_amount', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
                            </th>
                            <th scope="col" class="text-center">
                                TOTAL ($)
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_amount', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_amount', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
                            </th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($members as $member)
                        <tr data-id="{{ $member->id }}">
                            <td class="text-center">{{ $member->id }}</td>
                            <td class="text-center">
                                <a href="{{route('orgs.members.edit', [$org->id, $member->id])}}">{{ $member->rut }}</a>
                            </td>
                            <td class="text-center">{{ $member->first_name }}</td>
                            <td class="text-center">{{ $member->last_name }}</td>
                            <td class="text-center">{{ $member->qrx_serv }}</td>
                            <td class="text-center periods-col">
                                @if($member->pending_periods)
                                    @php
                                        $periods = explode(', ', $member->pending_periods);
                                        $periodCount = count($periods);
                                    @endphp
                                    
                                    <div class="period-badges">
                                        @if($periodCount <= 3)
                                            <!-- Mostrar todos los períodos si son 3 o menos -->
                                            @foreach($periods as $period)
                                                <span class="badge bg-warning text-dark period-badge mb-1 me-1">{{ $period }}</span>
                                            @endforeach
                                        @else
                                            <!-- Mostrar primeros 2 períodos + indicador de más -->
                                            @for($i = 0; $i < 2; $i++)
                                                <span class="badge bg-warning text-dark period-badge mb-1 me-1">{{ $periods[$i] }}</span>
                                            @endfor
                                            <span class="badge bg-danger period-badge mb-1" 
                                                  title="{{ implode(', ', $periods) }}">
                                                +{{ $periodCount - 2 }} más
                                            </span>
                                        @endif
                                        
                                        <br><small class="text-muted">{{ $periodCount }} período(s)</small>
                                    </div>
                                @else
                                    <small class="text-muted">N/A</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($member->total_amount == 0)
                                    <span class="badge bg-success">Sin deudas</span>
                                @else
                                    <span class="badge bg-warning">Pendiente de pago</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="text-success fw-bold">@money($member->total_amount)</span>
                            </td>
                            <td class="text-center">
                                <a href="{{route('orgs.payments.services', [$org->id, $member->rut])}}"
                                    class="btn btn-sm btn-danger">Pagar</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Paginación eliminada -->
        </div>
    </div>
</section>

<!-- Modal para Nuevo Miembro -->
<div class="modal fade" id="newMemberModal" tabindex="-1" aria-labelledby="newMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="newMemberModalLabel">Nuevo Inventario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newMemberForm" action="" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción del Artículo</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="qxt" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="qxt" name="qxt" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="order_date" class="form-label">Fecha último Pedido</label>
                                <input type="date" class="form-control" id="order_date" name="order_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Valor</label>
                                <input type="number" class="form-control" id="amount" name="amount" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Estado</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="Disponible">Disponible</option>
                                    <option value="En uso">En uso</option>
                                    <option value="En mantenimiento">En mantenimiento</option>
                                    <option value="Dado de baja">Dado de baja</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="location" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="location" name="location">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="responsible" class="form-label">Nombre del responsable</label>
                                <input type="text" class="form-control" id="responsible" name="responsible">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="low_date" class="form-label">Fecha de Traslado o Baja</label>
                                <input type="date" class="form-control" id="low_date" name="low_date">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="observations" class="form-label">Observaciones (Opcional)</label>
                                <textarea class="form-control" id="observations" name="observations" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Añadir Inventario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edición de Lectura -->
<div class="modal fade" id="editReadingModal" tabindex="-1" aria-labelledby="editReadingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReadingModalLabel">Editar Lectura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editReadingForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="current_reading" class="form-label">Lectura Actual</label>
                        <input type="number" class="form-control" id="current_reading" name="current_reading" required>
                    </div>
                    <input type="hidden" id="reading_id" name="reading_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" form="editReadingForm" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para imprimir la vista filtrada
    document.getElementById('printBtn').addEventListener('click', function() {
        window.print();
    });

    // ...existing code...
    // Función para manejar la edición de lecturas
    function editReading(readingId, orgId) {
        document.getElementById('reading_id').value = readingId;
        document.getElementById('editReadingForm').action = `/orgs/${orgId}/payments/update/${readingId}`;
        document.getElementById('current_reading').value = '';
        document.getElementById('current_reading').focus();
    }

    document.getElementById('editReadingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const readingId = document.getElementById('reading_id').value;
        const orgId = '{{ $org->id }}';
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editReadingModal')).hide();
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = `
                    <strong>¡Éxito!</strong> Lectura actualizada correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.card-body').prepend(alert);
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar la lectura');
        });
    });

    // ...existing code...
    const urlParams = new URLSearchParams(window.location.search);
    const fromPayment = sessionStorage.getItem('payment_completed');
    if (fromPayment === 'true') {
        sessionStorage.removeItem('payment_completed');
        const successAlert = document.createElement('div');
        successAlert.className = 'alert alert-success alert-dismissible fade show';
        successAlert.innerHTML = `
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>¡Pago procesado exitosamente!</strong> La tabla se ha actualizado automáticamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.pagetitle').after(successAlert);
        setTimeout(() => {
            if (successAlert.parentNode) {
                successAlert.remove();
            }
        }, 5000);
    }

    document.querySelectorAll('[onclick*="showServices"]').forEach(button => {
        button.addEventListener('click', function() {
            sessionStorage.setItem('payment_in_progress', 'true');
        });
    });

    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function showServices(orgId, rut) {
    sessionStorage.setItem('payment_in_progress', 'true');
    window.location.href = `/orgs/${orgId}/payments/services/${rut}`;
}
</script>
@endsection
