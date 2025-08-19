@extends('layouts.nice', ['active' => 'orgs.services.index', 'title' => 'Lista de Servicios'])

@section('content')
    <div class="pagetitle">
        <h1>Lista de Servicios para la Organización: {{ $org->name }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orgs.index') }}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orgs.dashboard', $org->id) }}">{{ $org->name }}</a></li>
                <li class="breadcrumb-item active">Lista de Servicios</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card top-selling overflow-auto">
            <div class="card-body pt-2">
                <!-- Filtros -->
                <form action="" method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <!-- Sector -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Sector</label>
                            <select name="sector" id="sector" class="form-select rounded-3">
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

                        <!-- N° Servicio -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">N° ID Servicio</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control" name="nro" id="nro" value="{{ request()->nro }}">
                            </div>
                        </div>

                        <!-- Buscador único por Nombre / RUT -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Nombre / RUT</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" name="search" id="search" placeholder="Nombre / RUT" value="{{ request()->search }}">
                            </div>
                        </div>

                        <!-- Botón Filtrar -->
                        <div class="col-md-auto d-flex align-items-center">
                            <button type="submit" class="btn btn-primary pulse-btn p-1 px-2 rounded-2">
                                <i class="bi bi-funnel-fill me-2"></i>Filtrar
                            </button>
                        </div>
                        <!-- Botón Exportar -->
                        <div class="col-md-auto d-flex align-items-center ms-2">
                            <a
                                href="{{ route('orgs.services.export', ['id' => $org->id] + request()->only(['sector','nro','search','sort','order'])) }}"
                                class="btn btn-primary pulse-btn p-1 px-2 rounded-2 enhanced-btn"
                                id="exportBtn"
                                target="_blank"
                                tabindex="0">
                                <i class="bi bi-box-arrow-right me-2"></i>Exportar
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla de Servicios -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
    <th scope="col" class="text-center">
        ID Servicio
        <a href="?sort=nro&order=asc" class="text-decoration-none ms-1"><i class="bi bi-arrow-up-short"></i></a>
        <a href="?sort=nro&order=desc" class="text-decoration-none"><i class="bi bi-arrow-down-short"></i></a>
    </th>
    <th scope="col" class="text-center">
        Miembro
        <a href="?sort=member_name&order=asc" class="text-decoration-none ms-1"><i class="bi bi-arrow-up-short"></i></a>
        <a href="?sort=member_name&order=desc" class="text-decoration-none"><i class="bi bi-arrow-down-short"></i></a>
    </th>
    <th scope="col" class="text-center">
        Sector
        <a href="?sort=location_name&order=asc" class="text-decoration-none ms-1"><i class="bi bi-arrow-up-short"></i></a>
        <a href="?sort=location_name&order=desc" class="text-decoration-none"><i class="bi bi-arrow-down-short"></i></a>
    </th>
    <th scope="col" class="text-center">
        N° Medidor
        <a href="?sort=meter_number&order=asc" class="text-decoration-none ms-1"><i class="bi bi-arrow-up-short"></i></a>
        <a href="?sort=meter_number&order=desc" class="text-decoration-none"><i class="bi bi-arrow-down-short"></i></a>
    </th>
    <th scope="col" class="text-center">
        Boleta/Factura
        <a href="?sort=invoice_type&order=asc" class="text-decoration-none ms-1"><i class="bi bi-arrow-up-short"></i></a>
        <a href="?sort=invoice_type&order=desc" class="text-decoration-none"><i class="bi bi-arrow-down-short"></i></a>
    </th>
    <th scope="col" class="text-center">
        MIDEPLAN
        <a href="?sort=meter_plan&order=asc" class="text-decoration-none ms-1"><i class="bi bi-arrow-up-short"></i></a>
        <a href="?sort=meter_plan&order=desc" class="text-decoration-none"><i class="bi bi-arrow-down-short"></i></a>
    </th>
    <th scope="col" class="text-center">
        Porcentaje
        <a href="?sort=percentage&order=asc" class="text-decoration-none ms-1"><i class="bi bi-arrow-up-short"></i></a>
        <a href="?sort=percentage&order=desc" class="text-decoration-none"><i class="bi bi-arrow-down-short"></i></a>
    </th>
    <th scope="col" class="text-center">
        Diámetro
        <a href="?sort=diameter&order=asc" class="text-decoration-none ms-1"><i class="bi bi-arrow-up-short"></i></a>
        <a href="?sort=diameter&order=desc" class="text-decoration-none"><i class="bi bi-arrow-down-short"></i></a>
    </th>
</tr>
</thead>
<tbody>
    @if($services->isNotEmpty())
        @foreach($services as $service)
            <tr>
                <td class="text-center">{{ $service->nro ?? 'N/D' }}</td>
                <td class="text-center">{{ $service->member_name ?? 'N/D' }}</td>
                <td class="text-center">{{ $service->location_name }}</td>
                <td class="text-center">{{ $service->meter_number ?? 'N/D' }}</td>
                <td class="text-center">{{ $service->invoice_type ?? 'N/D' }}</td>
                <td class="text-center">{{ $service->meter_plan ?? 'N/D' }}</td>
                <td class="text-center">{{ $service->percentage ?? 'N/D' }}</td>
                <td class="text-center">{{ $service->diameter ?? 'N/D' }}</td>
            </tr>
        @endforeach
                            @else
                                <tr>
                                    <td colspan="9">No hay servicios disponibles para esta organización.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Sin paginación -->
            </div>
        </div>
    </section>
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

@push('scripts')

    <!-- Incluyo SheetJS y el script de exportación justo antes de </body> para máxima compatibilidad -->
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.onclick = function(e) {
                e.preventDefault();
                try {
                    // Selecciona la tabla específica de servicios usando clase/table-responsive
                    var table = document.querySelector('.table-responsive table');
                    if (!table) {
                        console.error('No se encontró la tabla de servicios para exportar.');
                        alert('No se encontró ninguna tabla para exportar.');
                        return;
                    }
                    // Clona la tabla para exportar solo las filas visibles
                    var clone = table.cloneNode(true);
                    // Elimina las filas ocultas (por filtro, display:none)
                    Array.from(clone.querySelectorAll('tbody tr')).forEach(function(row) {
                        if (row.style.display === 'none') {
                            row.parentNode.removeChild(row);
                        }
                    });
                    var wb = XLSX.utils.table_to_book(clone, {sheet: 'Servicios'});
                    XLSX.writeFile(wb, 'servicios_exportados.xlsx');
                } catch (err) {
                    console.error('Error al exportar:', err);
                    alert('Error al exportar: ' + err.message);
                }
            }
        }
    });
    </script>
@endpush
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.onclick = function(e) {
                e.preventDefault();
                try {
                    // Selecciona la tabla específica de servicios usando clase/table-responsive
                    var table = document.querySelector('.table-responsive table');
                    if (!table) {
                        console.error('No se encontró la tabla de servicios para exportar.');
                        alert('No se encontró ninguna tabla para exportar.');
                        return;
                    }
                    var wb = XLSX.utils.table_to_book(table, {sheet: 'Servicios'});
                    XLSX.writeFile(wb, 'servicios_exportados.xlsx');
                } catch (err) {
                    console.error('Error al exportar:', err);
                    alert('Error al exportar: ' + err.message);
                }
            }
        }
    });
    </script>
@endpush
