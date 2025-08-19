@extends('layouts.nice', ['active'=>'orgs.members.index','title'=>'Miembros'])

@section('content')

    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Socios</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">

        <div class="card top-selling overflow-auto">
<div class="card-body pt-2">
                <form method="GET" id="filterForm">
                        <div class="row g-3 align-items-end">
                            <!-- Sectores -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Sectores</label>
                                <select name="sector" class="form-select rounded-3">
                                    @if($locations->isNotEmpty())
                                        <option value="">
                                            Todos
                                        </option>
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
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Buscar</label>
                                <div class="input-group input-group-sm search-input-group">
                                    <span class="input-group-text border-0 bg-white ps-4">
                                        <i class="bi bi-search fs-5 text-primary"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control rounded-end-3" placeholder="Buscar por nombre o apellido" value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Botón Filtrar -->
                            <!-- Botón Filtrar -->
                        <div class="col-md-auto d-flex align-items-center">
                        <button type="submit" class="btn btn-primary pulse-btn p-1 px-2 rounded-2">
                            <i class="bi bi-funnel-fill me-2"></i>Filtrar
                        </button>
                    </div>
                    <!-- Botón Exportar -->
                        <div class="col-md-auto d-flex align-items-center ms-2">
                        <a href="#" id="exportBtn" class="btn btn-primary pulse-btn p-1 px-2 rounded-2 enhanced-btn" tabindex="0">
                            <i class="bi bi-box-arrow-right me-2"></i>Exportar
                        </a>
                    </div>
                        </div>
                    </form>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
    <!-- Columnas eliminadas: ID, RUT, ID Servicio -->
    <th scope="col" class="text-center">RUT</th>
    <th scope="col" class="text-center">Nombre</th>
    <th scope="col" class="text-center">Dirección</th>
    <th scope="col" class="text-center">Acciones</th>
</tr>
</thead>
<tbody>
    @foreach($members as $member)
    <tr>
        <!-- Columnas eliminadas: ID, RUT, ID Servicio -->
        <td class="text-center"><a href="{{route('orgs.members.edit',[$org->id,$member->id])}}">{{ $member->rut }}</a></td>
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center">
                <div class="icon-box bg-light-primary rounded-circle me-2">
                    <i class="bi bi-person text-primary"></i>
                </div>
                <span class="fw-medium">{{ $member->first_name }} {{ $member->last_name }}</span>
            </div>
        </td>
        <td class="text-center">
            <small class="text-muted">
                {{ $member->address }}, {{ $member->commune }}, {{ $member->state }}
            </small>
        </td>
        <td class="text-center">@if($member->partner=='socio') <span class="badge bg-success"><i class="bi bi-file-earmark-person me-1"></i> {{ $member->partner }}</span> @else <span class="badge bg-info"><i class="ri-home-smile-2-line me-1"></i> {{ $member->partner }}</span> @endif</td>
        <td class="text-center">{{ $member->qrx_serv }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li class="dropdown-header text-start">
                                                <h6>Operación</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="{{ route('orgs.members.edit', [$org->id, $member->id]) }}">Ver/Editar</a></li>
                                            <!-- <li><a class="dropdown-item" href="#">Desactivar</a></li> -->
                                            <li><a class="dropdown-item" href="{{ route('orgs.services.createForMember', [$org->id, $member->id]) }}">Agregar servicio</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {!! $members->render('pagination::bootstrap-4') !!}
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
                    <form id="newMemberForm" action="{{ route('orgs.members.store', $org->id) }}" method="POST">
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
                                <h6 class="text-primary mb-3">Información de Servicios <span class="text-muted fs-6 fw-normal"></span></h6>
                                <div class="mb-3">
                                    <label for="sector" class="form-label">Sector</label>
                                    <input type="text" class="form-control" id="sector" name="sector">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="mide_plan" class="form-label">Mide Plan</label>
                                        <input type="text" class="form-control" id="mide_plan" name="mide_plan">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="porcentaje" class="form-label">Porcentaje</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="porcentaje" name="porcentaje" min="0" max="100">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tipo_medidor" class="form-label">Tipo Medidor</label>
                                        <input type="text" class="form-control" id="tipo_medidor" name="tipo_medidor">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="num_medidor" class="form-label">N° Medidor</label>
                                        <input type="text" class="form-control" id="num_medidor" name="num_medidor">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="propietario" class="form-label">Propietario</label>
                                    <input type="text" class="form-control" id="propietario" name="propietario">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="boleta_factura" class="form-label">Boleta/Factura</label>
                                        <select class="form-select" id="boleta_factura" name="boleta_factura">
                                            <option value="">Seleccionar...</option>
                                            <option value="boleta">Boleta</option>
                                            <option value="factura">Factura</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="diametro" class="form-label">Diámetro</label>
                                        <input type="text" class="form-control" id="diametro" name="diametro">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="cliente_socio" class="form-label">Cliente/Socio</label>
                                    <select class="form-select" id="cliente_socio" name="cliente_socio">
                                        <option value="">Seleccionar...</option>
                                        <option value="cliente">Cliente</option>
                                        <option value="socio">Socio</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones</label>
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

<!-- Incluyo SheetJS y el script de exportación justo antes de </body> para máxima compatibilidad -->
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
                var wb = XLSX.utils.table_to_book(table, {sheet: 'Socios'});
                XLSX.writeFile(wb, 'socios_exportados.xlsx');
            } catch (err) {
                alert('Error al exportar: ' + err.message);
            }
        }
    }
});
</script>

