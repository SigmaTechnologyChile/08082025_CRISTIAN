@extends('layouts.nice', ['active'=>'orgs.folios.histories','title'=>'Historial de Folios'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Folios</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card top-selling overflow-auto">
<div class="card-body pt-2">
                <form method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <!-- Rango de Fecha (Desde) -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Desde</label>
                            <input type="date" name="start_date" class="form-control rounded-3" value="{{ request('start_date') }}">
                        </div>
                        <!-- Rango de Fecha (Hasta) -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Hasta</label>
                            <input type="date" name="end_date" class="form-control rounded-3" value="{{ request('end_date') }}">
                        </div>

                        <!-- Buscador -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Buscar por nombre o RUT</label>
                            <div class="input-group input-group-sm search-input-group">
                                <span class="input-group-text border-0 bg-white ps-4">
                                    <i class="bi bi-search fs-5 text-primary"></i>
                                </span>
                                <input type="text" id="search" name="search" class="form-control rounded-end-3" placeholder="Buscar por tipo o mensaje" value="{{ request('search') }}">
                            </div>
                            <ul id="search-results" class="list-group position-absolute" style="width: 100%; display: none; z-index: 1000;">
                                <!-- Los resultados de la búsqueda se insertarán aquí -->
                            </ul>
                        </div>

                        <!-- Botón Filtrar -->
                        <div class="col-md-auto d-flex align-items-center">
                        <button type="submit" class="btn btn-primary pulse-btn p-1 px-2 rounded-2">
                            <i class="bi bi-funnel-fill me-2"></i>Filtrar
                        </button>
                    </div>
                        <div class="col-md-auto d-flex align-items-center ms-2">
                            <a href="#" id="exportBtn" class="btn btn-primary pulse-btn p-1 px-2 rounded-2">
                                <i class="bi bi-box-arrow-right me-2"></i>Exportar
                            </a>
@push('scripts')
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.onclick = function(e) {
            e.preventDefault();
            try {
                var table = document.querySelector('table');
                if (!table) {
                    alert('No se encontró ninguna tabla para exportar.');
                    return;
                }
                var wb = XLSX.utils.table_to_book(table, {sheet: 'Folios'});
                XLSX.writeFile(wb, 'folios_exportados.xlsx');
            } catch (err) {
                alert('Error al exportar: ' + err.message);
            }
        }
    }
});
</script>
@endpush
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Mensaje</th>
                                <th scope="col">codigoSii</th>
                                <th scope="col">fechaIngreso</th>
                                <th scope="col">fechaCaf</th>
                                <th scope="col">desde</th>
                                <th scope="col">hasta</th>
                                <th scope="col">fechaVencimiento</th>
                                <th scope="col">tipoDte</th>
                                <th scope="col">foliosDisponibles</th>
                                <th scope="col">ambiente</th>
                                <th scope="col">errors</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($folios as $member)
                            <tr>
                                <td>{{ $member->id }}</td>
                                <td>{{ $member->message }}</td>
                                <td>{{ $member->codigoSii }}</td>
                                <td>{{ $member->fechaIngreso }}</td>
                                <td>{{ $member->fechaCaf }}</td>
                                <td><span class="text-primary fw-bold">{{ $member->desde }}</span></td>
                                <td><span class="text-primary fw-bold">{{ $member->hasta }}</span></td>
                                <td>{{ $member->fechaVencimiento }}</td>
                                <td>{{ $member->tipoDte }}</td>
                                <td>{{ $member->foliosDisponibles }}</td>
                                <td>@if($member->ambiente==1) <span class="text-primary fw-bold">Productivo</span> @else <span class="text-warning fw-bold">Testing</span> @endif</td>
                                <td>{{ $member->errors }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {!! $folios->render('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </section>

    <!-- Modal para Nuevo Miembro -->
    <div class="modal fade" id="newMemberModal" tabindex="-1" aria-labelledby="newMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="newMemberModalLabel">Nuevo Socio</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newMemberForm" action="" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Columna izquierda - Datos personales -->
                            <div class="col-md-6 border-end pe-md-4">
                                <h6 class="text-primary mb-3">Datos Personales</h6>
                                <div class="mb-3">
                                    <label for="rut" class="form-label">RUT/RUN</label>
                                    <input type="text" class="form-control" id="rut" name="rut" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nombres" class="form-label">Nombres</label>
                                    <input type="text" class="form-control" id="nombres" name="first_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="apellidos" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control" id="apellidos" name="last_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="address" required>
                                </div>
                                <div class="mb-3">
                                    <label for="mobile_phone" class="form-label">Celular</label>
                                    <input type="tel" class="form-control" id="mobile_phone" name="mobile_phone">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono fijo</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                            </div>

                            <!-- Columna derecha - Servicios -->
                            <div class="col-md-6 ps-md-4">
                                <h6 class="text-primary mb-3">Información de Servicios <span class="text-muted fs-6 fw-normal">(Todos los campos son opcionales)</span></h6>
                                <div class="mb-3">
                                    <label for="sector" class="form-label">Sector (Opcional)</label>
                                    <input type="text" class="form-control" id="sector" name="sector">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="mide_plan" class="form-label">Mide Plan (Opcional)</label>
                                        <input type="text" class="form-control" id="mide_plan" name="mide_plan">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="porcentaje" class="form-label">Porcentaje (Opcional)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="porcentaje" name="porcentaje" min="0" max="100">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tipo_medidor" class="form-label">Tipo Medidor (Opcional)</label>
                                        <input type="text" class="form-control" id="tipo_medidor" name="tipo_medidor">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="num_medidor" class="form-label">N° Medidor (Opcional)</label>
                                        <input type="text" class="form-control" id="num_medidor" name="num_medidor">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="propietario" class="form-label">Propietario (Opcional)</label>
                                    <input type="text" class="form-control" id="propietario" name="propietario">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="boleta_factura" class="form-label">Boleta/Factura (Opcional)</label>
                                        <select class="form-select" id="boleta_factura" name="boleta_factura">
                                            <option value="">Seleccionar...</option>
                                            <option value="boleta">Boleta</option>
                                            <option value="factura">Factura</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="diametro" class="form-label">Diámetro (Opcional)</label>
                                        <input type="text" class="form-control" id="diametro" name="diametro">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="cliente_socio" class="form-label">Cliente/Socio (Opcional)</label>
                                    <select class="form-select" id="cliente_socio" name="cliente_socio">
                                        <option value="">Seleccionar...</option>
                                        <option value="cliente">Cliente</option>
                                        <option value="socio">Socio</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones (Opcional)</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Añadir cliente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .icon-box {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-light-primary {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
    }

    .table > :not(caption) > * > * {
        padding: 0.75rem 1rem;
    }

    .search-container {
        margin-bottom: 1.5rem;
    }

    @media (max-width: 767.98px) {
        .table-responsive {
            border: 0;
        }
    }

    .modal-header {
        border-radius: 0.375rem 0.375rem 0 0;
    }

    .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Estilos para el modal */
    .modal-header {
        border-bottom: 0;
    }

    .modal-content {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 0.5rem;
    }

    .form-control:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
    }

    /* Estilos para el formulario de dos columnas */
    @media (max-width: 767.98px) {
        .modal-body .border-end {
            border-right: none !important;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .modal-body .ps-md-4 {
            padding-left: 0 !important;
        }

        .modal-body .pe-md-4 {
            padding-right: 0 !important;
        }
    }

    .modal-xl {
        max-width: 1140px;
    }

    textarea.form-control {
        min-height: 100px;
    }

    .text-primary {
        color: var(--bs-primary) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('newMemberForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                alert('Formulario enviado correctamente');
                const modal = bootstrap.Modal.getInstance(document.getElementById('newMemberModal'));
                modal.hide();
            });
        }
    });
</script>
@endpush

