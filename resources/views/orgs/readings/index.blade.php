@extends('layouts.nice', ['active'=>'orgs.readings.index','title'=>'Ingreso de Lecturas'])

@push('css')
<style>
    left: 0;
    width: 100%;
    height: 100%;
    background-image:
        radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.15) 1px, transparent 1px),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
    opacity: 0.7;

    .enhanced-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15), 0 6px 6px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 20%, #0a58ca 60%, #084298 100%);
    }

    .enhanced-btn:active {
        transform: translateY(1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

    /* Estilos para los inputs y selects */
    .form-select, .form-control {
        height: 38px;
    }

    .input-group-text {
        height: 38px;
    }

    /* Estilos para la tabla */
    .table-responsive {
        overflow-x: auto;
    }

    .table th, .table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    /* Estilos para el modal de carga masiva */
    .file-upload {
        border: 2px dashed #4a6491;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        background-color: rgba(74, 100, 145, 0.05);
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-upload:hover {
        background-color: rgba(74, 100, 145, 0.1);
        transform: scale(1.01);
    }

    .file-upload i {
        font-size: 3rem;
        color: #4a6491;
        margin-bottom: 15px;
    }

    .preview-table {
        max-height: 300px;
        overflow-y: auto;
    }

    .preview-table th {
        background-color: #2c3e50;
        color: white;
        position: sticky;
        top: 0;
    }

    .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(90deg, #2c3e50, #4a6491);
        color: white;
    }

    .modal-title {
        font-weight: 600;
    }

    .modal-footer {
        background-color: #f8f9fa;
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }

    .step-indicator::before {
        content: "";
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #dee2e6;
        z-index: 0;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 1;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .step.active .step-number {
        background-color: #4a6491;
        color: white;
    }

    .step-label {
        font-size: 0.9rem;
        color: #6c757d;
        text-align: center;
    }

    .step.active .step-label {
        color: #2c3e50;
        font-weight: 600;
    }

    .success-icon {
        font-size: 5rem;
        color: #28a745;
        margin: 20px 0;
    }
    
    /* Estilos para el contador de progreso */
    .progress-mini {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 123, 255, 0.2);
        border-radius: 8px;
        padding: 8px 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .progress-mini:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .progress-mini .progress {
        height: 8px;
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        margin-bottom: 4px;
    }

    .progress-mini .stats-number {
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
    }

    .progress-mini .stats-label {
        font-size: 0.6rem;
        line-height: 1;
    }

    .progress-mini .progress-text {
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* Estilos para el modal de facturación */
    .invoice-option {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .invoice-option:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .invoice-option .card-body {
        transition: all 0.3s ease;
    }

    .invoice-option:hover .card-body {
        background-color: rgba(248, 249, 250, 0.8);
    }
    
    .progress {
        border-radius: 15px;
        background-color: #e9ecef;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .progress-bar {
        border-radius: 15px;
        position: relative;
        overflow: hidden;
    }
    
    .progress-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(to bottom, rgba(255,255,255,0.3), transparent);
        border-radius: 15px 15px 0 0;
    }
    
    .stats-number {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }
    
    .stats-label {
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endpush


@section('content')

    <div class="pagetitle">

      <h1>{{$org->name}}</h1>

      <nav>

        <ol class="breadcrumb">

          <li class="breadcrumb-item"><a href="/">Home</a></li>

          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>

          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>

          <li class="breadcrumb-item active">Lecturas</li>

        </ol>

      </nav>

    </div><!-- End Page Title -->



    <section class="section dashboard">

    <div class="card top-selling overflow-auto">

        <div class="card-body pt-2">

            <form method="GET" id="filterForm" action="{{ route('orgs.readings.index', $org->id) }}">
                <div class="row g-3 align-items-end">
                    <!-- Periodo Actual al margen izquierdo -->
                    <div class="col-md-2">
                        <label class="form-label fw-semibold d-block">Periodo Actual</label>
                        <div class="form-control text-center bg-light fw-bold" style="height:38px;">
                            {{ str_pad(date('m'), 2, '0', STR_PAD_LEFT) }}-{{ date('Y') }}
                        </div>
                    </div>
                    <!-- Selector de Sector -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sector</label>
                        <select class="form-select" id="sectorSelect" name="sector">
                            <option value="">Todos</option>
                            @foreach($sectores as $sector)
                                <option value="{{ $sector->id }}" {{ request('sector') == $sector->id ? 'selected' : '' }}>{{ $sector->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Buscador por nombre, apellido o RUT -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Buscar</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Nombre, Apellido o RUT">
                            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
                        </div>
                    </div>
                    <!-- Contador de Progreso Mini -->
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Progreso</label>
                        <div class="progress-mini">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" 
                                     role="progressbar" 
                                     style="width: {{ $progresoStats['porcentaje'] }}%; background: linear-gradient(90deg, #28a745, #20c997);" 
                                     aria-valuenow="{{ $progresoStats['porcentaje'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="progress-text text-primary">{{ $progresoStats['porcentaje'] }}%</small>
                                <small class="text-muted">{{ $progresoStats['ingresadas'] }}/{{ $progresoStats['total'] }}</small>
                            </div>
                        </div>
                    </div>
                    <!-- Botón Exportar -->
                    <div class="col-md-auto d-flex align-items-center ms-2">
                        <a href="#" id="exportBtn" class="btn btn-primary pulse-btn p-1 px-2 rounded-2 enhanced-btn disabled" tabindex="-1" aria-disabled="true">
                            <i class="bi bi-box-arrow-right me-2"></i>Exportar
                        </a>
                    </div>
                    <!-- Botón Facturar -->
                    <div class="col-md-auto d-flex align-items-center ms-2">
                        <button type="button" class="btn btn-danger pulse-btn p-1 px-2 rounded-2 enhanced-btn" data-bs-toggle="modal" data-bs-target="#invoiceModal">
                            <i class="fas fa-file-invoice me-2"></i>Facturar
                        </button>
                    </div>
                    <!-- Botón Ingreso Masivo -->
                    <div class="col-md-auto d-flex align-items-center ms-2">
                        <button type="button" class="btn btn-primary pulse-btn p-1 px-2 rounded-2 enhanced-btn" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fas fa-file-upload me-2"></i>Ingreso Masivo
                        </button>
                    </div>
                </div>

            </form>

            <div class="card-body">

                <div class="table-responsive mt-3">

                <table class="table table-bordered table-hover">

                    <thead class="table-light">

                        <tr>

                                <th scope="col" class="text-center">ID Lec.</th>

                                <th scope="col" class="text-center">Sector</th>

                                <th scope="col" class="text-center">Nro Serv.</th>

                                <th scope="col" class="text-center">RUT/RUN</th>

                                <th scope="col" class="text-center">Nombre/Apellido</th>

                                <th scope="col" class="text-center">Fecha</th>

                                <th scope="col" class="text-center">Lect. Anterior</th>

                                <th scope="col" class="text-center">Lect. Actual</th>

                                <th scope="col" class="text-center">Consumo <br/><small>(M³)</small></th>

                                <th scope="col" class="text-center">Total<br/><small>$</small></th>

                        </thead>
                        <tbody>
                            @foreach($readings as $reading)
                            <tr data-id="{{ $reading->id ?? 'new' }}" data-service-id="{{ $reading->service_id }}">
                                <td class="text-center">{{ $reading->id ?? '-' }}</td>
                                <td class="text-center">{{ $reading->location_name ?? 'N/A' }}</td>
                                <td class="text-center">{{ Illuminate\Support\Str::padLeft($reading->nro,5,0) }}</td>
                                <td class="text-center"><a href="{{route('orgs.members.edit',[$org->id,$reading->member_id])}}">{{ $reading->rut }}</a></td>
                                <td class="text-center">{{ $reading->full_name ?? 'N/A' }}</td>
                                <td class="text-center" style="white-space: nowrap;">{{ $reading->period }}</td>
                                <td class="text-center"><span class="text-warning fw-bold">{{ $reading->previous_reading ?? 0 }}</span></td>
                                <td class="text-center">
                                    @if($reading->cm3 == 0 || !$reading->id)
                                    <form method="POST" action="{{ route('orgs.readings.current_reading_update', $org->id) }}"
                                        class="current-reading-form" data-reading-id="{{ $reading->id }}" data-service-id="{{ $reading->service_id }}">
                                        @csrf
                                        @if($reading->id)
                                            <input type="hidden" name="reading_id" value="{{ $reading->id }}">
                                        @else
                                            <input type="hidden" name="service_id" value="{{ $reading->service_id }}">
                                        @endif
                                        <input type="number"
                                            name="current_reading"
                                            class="form-control form-control-sm current-reading-input mx-auto"
                                            value="{{ $reading->current_reading}}"
                                            style="width: 80px; display: block;"
                                            data-row-index="{{ $loop->index }}">
                                    </form>
                                    @else
                                    <input disabled readonly type="number"
                                        name="current_reading"
                                        class="form-control form-control-sm current-reading-input read-only mx-auto"
                                        value="{{ $reading->current_reading }}"
                                        style="width: 80px; display: block;"
                                        data-row-index="{{ $loop->index }}">
                                    @endif
                                </td>
                                <td class="text-center"><span class="text-primary fw-bold">{{ $reading->cm3 }}</span></td>
                                <td class="text-center" style="white-space: nowrap;">
                                    <span class="text-danger fw-bold">= @money($reading->total)</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <!-- Botón DTE -->
                                        @if($reading->invoice_type == "boleta")
                                            <a href="{{ route('orgs.readings.boleta', [$reading->org_id, $reading->id ?? 'new-' . $reading->service_id]) }}" class="btn btn-dark btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Ver Boleta">
                                                <i class="ri-file-2-line"></i> DTE
                                            </a>
                                        @else
                                            <a href="{{ route('orgs.readings.factura', [$reading->org_id, $reading->id ?? 'new-' . $reading->service_id]) }}" class="btn btn-dark btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Ver Factura">
                                                <i class="ri-file-2-line"></i> DTE
                                            </a>
                                        @endif
                                        <!-- Botón Editar -->
                                        <button class="btn btn-sm btn-success edit-btn"
                                                data-bs-id="{{ $reading->id ?? '-' }}"
                                                data-bs-current="{{ $reading->current_reading }}"
                                                data-bs-previous="{{ $reading->previous_reading ?? 0 }}"
                                                data-bs-corte_reposicion="{{ $reading->corte_reposicion ?? 0 }}"
                                                data-bs-other="{{ $reading->other ?? 0 }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editReadingModal"
                                                data-bs-placement="top"
                                                title="Editar">
                                            <i class="ri-edit-box-line"></i> Editar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </section>



    <!-- Modal de Edición de Lectura -->

    <div class="modal fade" id="editReadingModal" tabindex="-1" aria-labelledby="editReadingModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="editReadingModalLabel">Editar Lectura N° <span id="idReadingModal"></span></h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">

                    <form method="POST" action="{{ route('orgs.readings.update', $org->id) }}" id="editReadingForm">

                        @csrf

                        @method('POST')



                        <!-- Input para el ID de la lectura -->

                        <input type="hidden" id="reading_id" name="reading_id">



                        <div class="mb-3">

                            <label for="previous_reading" class="form-label">Lectura Anterior</label>

                            <input type="number" class="form-control" id="previous_reading" name="previous_reading" readonly>

                        </div>



                        <div class="mb-3">

                            <label for="current_reading" class="form-label">Lectura Actual</label>

                            <input type="number" class="form-control" id="current_reading" name="current_reading" required>

                        </div>
                        <div class="mb-3">

                            <div class="form-check mb-2">

                                <input

                                    class="form-check-input"

                                    type="checkbox"

                                    id="cargo_corte_reposicion"

                                    name="cargo_corte_reposicion"

                                  >

                                <label class="form-check-label" for="cargo_corte_reposicion">

                                    Cargo Corte Reposición

                                </label>

                            </div>

                        </div>
                        <div class="mb-3">

                            <label for="other" class="form-label">Otros Cargos</label>

                            <input type="number" class="form-control" id="other" name="other" >

                        </div>



                        <div class="mb-3">

                            <button type="submit" class="btn btn-success enhanced-btn">Guardar Cambios</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>



    <!-- Modal para carga masiva -->

    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="uploadModalLabel"><i class="fas fa-file-excel me-2"></i>Ingreso Masivo de Lecturas</h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">

        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header py-3 px-4" style="background: linear-gradient(90deg, #4a6491 0%, #2c3e50 100%); color: #fff;">
                    <h4 class="modal-title fw-bold d-flex align-items-center gap-2" id="uploadModalLabel">
                        <i class="fas fa-file-import fa-lg"></i> Ingreso Masivo de Lecturas
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4 pt-3">
                    <div class="step-indicator mb-4 px-2 py-3 rounded-3 bg-white shadow-sm d-flex flex-row align-items-center justify-content-center" style="position:relative;">
                        <div class="step text-center position-relative flex-grow-1" style="min-width:160px;">
                            <div class="step-number d-inline-flex align-items-center justify-content-center mx-auto mb-2" style="width:48px;height:48px;font-size:1.5rem;background:#0d6efd;color:#fff;border-radius:50%;box-shadow:0 2px 8px rgba(13,110,253,.15);font-weight:700;">1</div>
                            <div class="step-label fw-semibold" style="font-size:1rem;color:#0d6efd;">Cargar Archivo</div>
                        </div>
                        <div class="step-bar flex-grow-0" style="height:4px;width:60px;background:#dee2e6;margin:0 8px;"></div>
                        <div class="step text-center position-relative flex-grow-1" style="min-width:160px;">
                            <div class="step-number d-inline-flex align-items-center justify-content-center mx-auto mb-2" style="width:48px;height:48px;font-size:1.5rem;background:#6c757d;color:#fff;border-radius:50%;box-shadow:0 2px 8px rgba(108,117,125,.15);font-weight:700;">2</div>
                            <div class="step-label fw-semibold" style="font-size:1rem;color:#6c757d;">Verificar Datos</div>
                        </div>
                        <div class="step-bar flex-grow-0" style="height:4px;width:60px;background:#dee2e6;margin:0 8px;"></div>
                        <div class="step text-center position-relative flex-grow-1" style="min-width:160px;">
                            <div class="step-number d-inline-flex align-items-center justify-content-center mx-auto mb-2" style="width:48px;height:48px;font-size:1.5rem;background:#28a745;color:#fff;border-radius:50%;box-shadow:0 2px 8px rgba(40,167,69,.15);font-weight:700;">3</div>
                            <div class="step-label fw-semibold" style="font-size:1rem;color:#28a745;">Confirmar</div>
                        </div>
                    </div>

                    <div id="step1">
                        <div class="mb-4 p-3 rounded-3 bg-light border">
                            <h5 class="fw-semibold mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Instrucciones</h5>
                            <ul class="mb-0 ps-3">
                                <li>El archivo debe estar en formato <span class="fw-bold">Excel (.xlsx)</span></li>
                                <li>Debe contener <span class="fw-bold">únicamente las siguientes columnas</span> en este orden:<br>
                                    <span class="text-primary">numero (servicio), rut, lectura (actual), period (Mes/Año)</span>
                                </li>
                                <li><span class="fw-bold">numero</span>: Debe coincidir con el número de servicio registrado en el sistema (puede tener ceros a la izquierda).</li>
                                <li><span class="fw-bold">rut</span>: Sin puntos, solo guion antes del dígito verificador (ejemplo: 12345678-9).</li>
                                <li><span class="fw-bold">lectura</span>: Valor numérico correspondiente a la lectura actual.</li>
                                <li><span class="fw-bold">period</span>: Formato Año-Mes (ejemplo: 2025-07).</li>
                                <li>Se mostrará una vista previa con los primeros registros.</li>
                            </ul>
                        </div>
                        <div class="file-upload mb-4 p-4 rounded-3 border border-primary bg-white d-flex flex-column align-items-center justify-content-center" id="dropZone" style="min-height: 160px; cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <h5 class="mb-1 fw-bold">Arrastra tu archivo Excel aquí</h5>
                            <p class="text-muted mb-2">o haz clic para seleccionar</p>
                            <input type="file" id="fileInput" class="d-none" accept=".xlsx, .xls">
                        </div>
                        <div class="alert alert-warning d-flex align-items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <span>Ejemplo de formato requerido:</span>
                            <a href="{{ asset('plantilla_carga_lecturas_masiva.xlsx') }}" download class="btn btn-success btn-sm ms-3">
                                <i class="fas fa-file-download me-1"></i> Descargar modelo Excel
                            </a>
                        </div>
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Numero de Servicio</th>
                                        <th>RUT</th>
                                        <th>Lectura Actual</th>
                                        <th>Período</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>00001</td>
                                        <td>12345678-9</td>
                                        <td>1350</td>
                                        <td>2025-07</td>
                                    </tr>
                                    <tr>
                                        <td>00002</td>
                                        <td>98765432-1</td>
                                        <td>2150</td>
                                        <td>2025-07</td>
                                    </tr>
                                    <tr>
                                        <td>00003</td>
                                        <td>23456789-0</td>
                                        <td>3480</td>
                                        <td>2025-07</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="step2" class="d-none">
                        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                            <i class="fas fa-check-circle fa-lg"></i>
                            <span>Archivo cargado correctamente. Verifique los datos a continuación.</span>
                        </div>
                        <h5 class="fw-semibold mb-3"><i class="fas fa-eye text-success me-2"></i>Vista previa de archivos:</h5>
                        <div class="preview-table mb-4 rounded-3 border">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr class="table-primary">
                                        <th>RUT</th>
                                        <th>Nombre</th>
                                        <th>Lectura Actual</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>12345678-9</td>
                                        <td>Juan Ejemplo</td>
                                        <td>1350</td>
                                        <td><span class="badge bg-success">Válido</span></td>
                                    </tr>
                                    <tr>
                                        <td>98765432-1</td>
                                        <td>María Ejemplo</td>
                                        <td>2150</td>
                                        <td><span class="badge bg-success">Válido</span></td>
                                    </tr>
                                    <tr>
                                        <td>23456789-0</td>
                                        <td>Carlos Ejemplo</td>
                                        <td>3480</td>
                                        <td><span class="badge bg-success">Válido</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-info d-flex align-items-center gap-2">
                            <i class="fas fa-info-circle fa-lg"></i>
                            <span>Se han detectado 3 registros válidos listos para importar.</span>
                        </div>
                    </div>

                    <div id="step3" class="d-none text-center">
                        <i class="fas fa-check-circle success-icon mb-3"></i>
                        <h3 class="mb-3 fw-bold text-success">¡Importación completada con éxito!</h3>
                        <p class="fs-5">Se han registrado <span id="importedCount">0</span> lecturas correctamente.</p>
                        <div class="alert alert-success mt-4 d-inline-block text-start">
                            <i class="fas fa-clipboard-list me-2"></i> <span class="fw-semibold">Resumen:</span>
                            <ul class="mt-2 mb-0 ps-3">
                                <li>Total de registros en archivo: <span id="totalToImport">0</span></li>
                                <li>Registros importados: <span id="importedCount2">0</span></li>
                                <li>Registros con error: <span id="errorCount">0</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3 bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-primary enhanced-btn px-4 py-2" id="nextButton">
                        <i class="fas fa-arrow-right me-2"></i>Siguiente
                    </button>
                </div>
            </div>
        </div>
    </div
                        <div class="alert alert-success mb-4">

                            <i class="fas fa-check-circle me-2"></i>Archivo cargado correctamente. Verifique los datos a continuación.

                        </div>



                        <h6>Vista previa (primeros 3 registros):</h6>

                        <div class="preview-table mb-4">

                            <table class="table table-striped">

                                <thead>

                                    <tr class="table-primary">

                                        <th>RUT</th>

                                        <th>Nombre</th>

                                        <th>Lectura Actual</th>

                                        <th>Estado</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>

                                        <td>12.345.678-9</td>

                                        <td>Juan Pérez</td>

                                        <td>1,350</td>

                                        <td><span class="badge bg-success">Válido</span></td>

                                    </tr>

                                    <tr>

                                        <td>9.876.543-2</td>

                                        <td>María González</td>

                                        <td>2,150</td>

                                        <td><span class="badge bg-success">Válido</span></td>

                                    </tr>

                                    <tr>

                                        <td>23.456.789-0</td>

                                        <td>Carlos Soto</td>

                                        <td>3,480</td>

                                        <td><span class="badge bg-success">Válido</span></td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>



                        <div class="alert alert-info">

                            <i class="fas fa-info-circle me-2"></i>Se han detectado 3 registros válidos listos para importar.

                        </div>

                    </div>



                    <div id="step3" class="d-none text-center">

                        <i class="fas fa-check-circle success-icon"></i>

                        <h4 class="mb-3">¡Importación completada con éxito!</h4>

                        <p>Se han registrado 3 lecturas correctamente.</p>

                        <div class="alert alert-success mt-4">

                            <i class="fas fa-clipboard-list me-2"></i>Resumen:

                            <ul class="mt-2 mb-0">

                                <li>Registros totales: 3</li>

                                <li>Registros exitosos: 3</li>

                                <li>Registros con error: 0</li>

                            </ul>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-primary enhanced-btn" id="nextButton">Siguiente</button>

                </div>

            </div>

        </div>

    </div>

    <!-- Modal para Facturación -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header py-3 px-4" style="background: linear-gradient(90deg, #dc3545 0%, #b02a37 100%); color: #fff;">
                    <h4 class="modal-title fw-bold d-flex align-items-center gap-2" id="invoiceModalLabel">
                        <i class="fas fa-file-invoice fa-lg"></i> Proceso de Facturación
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4 pt-3">
                    <div class="mb-4">
                        <p class="text-muted mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Seleccione el tipo de documento que desea procesar. Solo se procesarán las lecturas que ya han sido ingresadas.
                        </p>
                    </div>

                    <div class="row g-3">
                        <!-- Opción Boletas -->
                        <div class="col-12">
                            <div class="card border-success h-100 invoice-option" data-type="boleta">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-receipt fa-3x text-success"></i>
                                    </div>
                                    <h5 class="card-title text-success fw-bold mb-2">Boletas</h5>
                                    <p class="card-text text-muted mb-3">Procesar documentos sin IVA</p>
                                    <div class="badge bg-success fs-6 px-3 py-2 mb-3">
                                        <i class="fas fa-file-alt me-2"></i>
                                        <span id="boletasCount">{{ $progresoStats['boletas'] ?? 0 }}</span> documentos
                                    </div>
                                    <button type="button" class="btn btn-success btn-lg w-100 fw-bold" onclick="procesarFacturacion('boleta')">
                                        <i class="fas fa-play me-2"></i>Procesar Boletas
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Opción Facturas -->
                        <div class="col-12">
                            <div class="card border-primary h-100 invoice-option" data-type="factura">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-file-invoice fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title text-primary fw-bold mb-2">Facturas</h5>
                                    <p class="card-text text-muted mb-3">Procesar documentos con IVA</p>
                                    <div class="badge bg-primary fs-6 px-3 py-2 mb-3">
                                        <i class="fas fa-file-invoice me-2"></i>
                                        <span id="facturasCount">{{ $progresoStats['facturas'] ?? 0 }}</span> documentos
                                    </div>
                                    <button type="button" class="btn btn-primary btn-lg w-100 fw-bold" onclick="procesarFacturacion('factura')">
                                        <i class="fas fa-play me-2"></i>Procesar Facturas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded-3">
                        <small class="text-muted">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Nota:</strong> El tipo de documento (boleta/factura) está definido en cada lectura. 
                            Solo se procesarán los documentos que coincidan con su selección.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



@section('js')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === FILTRO POR SECTOR ===
    const sectorSelect = document.getElementById('sectorSelect');
    if (sectorSelect) {
        sectorSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    // ===== CÓDIGO PARA LECTURAS =====

    const currentReadingInputs = document.querySelectorAll('.current-reading-input');



    currentReadingInputs.forEach(input => {

        input.addEventListener('focus', function() {

            this.dataset.originalValue = this.value;

        });



        input.addEventListener('keydown', function(e) {

            if (e.key === 'Enter') {

                e.preventDefault();



                const form = this.closest('form');

                const inputElement = this;



                if (this.value !== this.dataset.originalValue) {
                    // Debugging adicional
                    console.log('Valor actual del campo:', this.value);
                    console.log('Valor original:', this.dataset.originalValue);
                    console.log('Campo name:', this.name);
                    console.log('Campo válido:', this.value && this.value.trim() !== '');
                    
                    // Verificar que el campo tenga un valor válido
                    if (!this.value || this.value.trim() === '') {
                        alert('Por favor ingresa un valor válido para la lectura');
                        this.focus();
                        return;
                    }

                    // Mostrar indicador de carga
                    inputElement.style.backgroundColor = '#f8f9fa';
                    inputElement.style.color = '#6c757d';
                    inputElement.disabled = true;

                    

                    const xhr = new XMLHttpRequest();

                    xhr.open('POST', form.action, true);

                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));



                    // Manejar la respuesta

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    // Mostrar brevemente que fue exitoso
                                    inputElement.style.backgroundColor = '#d4edda';
                                    inputElement.style.color = '#155724';
                                    
                                    // Actualizar el contador de progreso inmediatamente
                                    actualizarContadorProgreso();
                                    
                                    // Recargar la página después de una breve pausa
                                    setTimeout(() => {
                                        location.reload();
                                    }, 500);
                                } else if (response.error) {
                                    // Mostrar error del servidor
                                    inputElement.style.backgroundColor = '#f8d7da';
                                    inputElement.style.color = '#721c24';
                                    inputElement.disabled = false;
                                    alert(response.error);
                                    
                                    setTimeout(() => {
                                        inputElement.style.backgroundColor = '';
                                        inputElement.style.color = '';
                                    }, 2000);
                                }
                            } catch (e) {
                                // Si no es JSON válido, tratar como error
                                inputElement.style.backgroundColor = '#f8d7da';
                                inputElement.style.color = '#721c24';
                                inputElement.disabled = false;
                                alert('Error al procesar la respuesta del servidor.');
                                
                                setTimeout(() => {
                                    inputElement.style.backgroundColor = '';
                                    inputElement.style.color = '';
                                }, 2000);
                            }
                        } else {
                            // Manejar errores HTTP
                            try {
                                const response = JSON.parse(xhr.responseText);
                                const errorMessage = response.error || 'Error al actualizar la lectura. Por favor, intenta de nuevo.';
                                
                                inputElement.style.backgroundColor = '#f8d7da';
                                inputElement.style.color = '#721c24';
                                inputElement.disabled = false;
                                alert(errorMessage);
                            } catch (e) {
                                inputElement.style.backgroundColor = '#f8d7da';
                                inputElement.style.color = '#721c24';
                                inputElement.disabled = false;
                                alert('Error al actualizar la lectura. Por favor, intenta de nuevo.');
                            }
                            
                            setTimeout(() => {
                                inputElement.style.backgroundColor = '';
                                inputElement.style.color = '';
                            }, 2000);
                        }
                    };



                    xhr.onerror = function() {

                        // Restaurar estado original en caso de error de conexión

                        inputElement.style.backgroundColor = '#f8d7da';

                        inputElement.style.color = '#721c24';

                        inputElement.disabled = false;

                        alert('Error de conexión. Por favor, intenta de nuevo.');

                        

                        setTimeout(() => {

                            inputElement.style.backgroundColor = '';

                            inputElement.style.color = '';

                        }, 2000);

                    };



                    // Enviar el formulario
                    const formData = new FormData();
                    
                    // Agregar manualmente cada campo
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    formData.append('current_reading', this.value);
                    
                    // Agregar reading_id o service_id según corresponda
                    const readingIdInput = form.querySelector('input[name="reading_id"]');
                    const serviceIdInput = form.querySelector('input[name="service_id"]');
                    
                    if (readingIdInput && readingIdInput.value) {
                        formData.append('reading_id', readingIdInput.value);
                    }
                    if (serviceIdInput && serviceIdInput.value) {
                        formData.append('service_id', serviceIdInput.value);
                    }
                    
                    // Agregar debugging
                    console.log('Datos del formulario:');
                    for (let [key, value] of formData.entries()) {
                        console.log(key, value);
                    }
                    console.log('URL de envío:', form.action);
                    console.log('Token CSRF:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    
                    xhr.send(formData);

                } else {

                    // Si no hay cambios, ir al siguiente campo

                    let found = false;

                    const currentIndex = parseInt(this.dataset.rowIndex);

                    for (let i = currentIndex + 1; i < currentReadingInputs.length; i++) {

                        const nextInput = currentReadingInputs[i];

                        if (!nextInput.disabled && !nextInput.readOnly) {

                            nextInput.focus();

                            nextInput.select();

                            found = true;

                            break;

                        }

                    }

                }

            }

        });



        // Restaurar valor original al perder el foco sin cambios

        input.addEventListener('blur', function() {

            if (this.value === '') {

                this.value = this.dataset.originalValue;

            }
            
            // Actualizar contador cada vez que se cambie un valor
            actualizarContadorProgreso();

        });

        // También actualizar cuando se ingrese un valor
        input.addEventListener('input', function() {
            // Actualizar contador mientras se escribe
            actualizarContadorProgreso();
        });

    });

    // Inicializar el contador de progreso al cargar la página
    actualizarContadorProgreso();

    // ===== CÓDIGO PARA MODAL DE EDICIÓN =====

    const editModal = document.getElementById('editReadingModal');

    if (editModal) {

        const editModalInstance = new bootstrap.Modal(editModal);



        editModal.addEventListener('show.bs.modal', function(event) {

            const button = event.relatedTarget;

            const readingId = button.getAttribute('data-bs-id');

            const currentReading = button.getAttribute('data-bs-current');

            const previousReading = button.getAttribute('data-bs-previous');

            const corte_reposicion = button.getAttribute('data-bs-corte_reposicion') || 0;

            const other = button.getAttribute('data-bs-other');



            document.getElementById('cargo_corte_reposicion').checked = parseInt(corte_reposicion) > 0;

            document.getElementById('other').value = other;

            document.getElementById('idReadingModal').textContent = readingId;

            document.getElementById('reading_id').value = readingId;

            document.getElementById('current_reading').value = currentReading;

            document.getElementById('previous_reading').value = previousReading;

        });

    }



    // ===== CÓDIGO PARA MODAL DE CARGA MASIVA =====

    const uploadModal = document.getElementById('uploadModal');

    if (uploadModal) {

        const dropZone = document.getElementById('dropZone');

        const fileInput = document.getElementById('fileInput');

        const nextButton = document.getElementById('nextButton');

        const step1 = document.getElementById('step1');

        const step2 = document.getElementById('step2');

        const step3 = document.getElementById('step3');

        const steps = document.querySelectorAll('.step');



        let currentStep = 1;

        let excelData = [];



        // Función para resetear modal

        function resetModal() {

            step1.classList.remove('d-none');

            step2.classList.add('d-none');

            step3.classList.add('d-none');

            nextButton.textContent = 'Siguiente';

            updateSteps(1);

            currentStep = 1;



            // Restaurar área de carga

            dropZone.innerHTML = `

                <i class="fas fa-file-excel"></i>

                <h5>Arrastra tu archivo Excel aquí</h5>

                <p class="text-muted">o haz clic para seleccionar</p>

                <input type="file" id="fileInput" class="d-none" accept=".xlsx, .xls">

            `;



            // Re-asignar eventos después del reset

            initFileInputEvents();

        }



        // Inicializar eventos de file input

        function initFileInputEvents() {

            const newFileInput = document.getElementById('fileInput');

            if (newFileInput) {

                newFileInput.addEventListener('change', handleFileInput);

            }

        }



        // Manejar selección de archivo

        function handleFileInput(e) {

            if (e.target.files.length > 0) {

                const fileName = e.target.files[0].name;

                dropZone.innerHTML = `

                    <i class="fas fa-check-circle text-success"></i>

                    <h5>${fileName}</h5>

                    <p class="text-success">Archivo listo para procesar</p>

                `;

                // Leer el archivo Excel y guardar los datos

                const file = e.target.files[0];

                readExcelFile(file);

            }

        }



        // Leer archivo Excel usando SheetJS

        function readExcelFile(file) {

            const reader = new FileReader();

            reader.onload = function(e) {

                const data = new Uint8Array(e.target.result);

                const workbook = XLSX.read(data, { type: 'array' });



                // Tomar la primera hoja

                const firstSheetName = workbook.SheetNames[0];

                const worksheet = workbook.Sheets[firstSheetName];



                // Convertir a JSON

                excelData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });



                // Actualizar vista previa

                renderPreviewTable();

            };

            reader.readAsArrayBuffer(file);

        }



        // Renderizar la vista previa en el paso 2

        function renderPreviewTable() {

            // Espera que excelData sea un array de arrays

            // Primer fila: encabezados

            // Siguientes filas: datos

            if (!excelData || excelData.length < 2) return;



            // Limitar a los primeros 3 registros

            const previewRows = excelData.slice(1, 4);



            // Encabezados esperados

            const headers = excelData[0];



            // Construir tabla HTML

            let tableHtml = `<table class="table table-striped mb-0"><thead><tr class="table-primary">`;

            headers.forEach(h => {

                tableHtml += `<th>${h}</th>`;

            });

            tableHtml += `<th>Estado</th></tr></thead><tbody>`;

            previewRows.forEach(row => {

                tableHtml += '<tr>';

                headers.forEach((h, idx) => {

                    tableHtml += `<td>${row[idx] !== undefined ? row[idx] : ''}</td>`;

                });



                // Validación simple: todos los campos presentes

                const isValid = row.length === headers.length && row.every(cell => cell !== undefined && cell !== '');

                tableHtml += `<td><span class="badge bg-${isValid ? 'success' : 'danger'}">${isValid ? 'Válido' : 'Inválido'}</span></td>`;

                tableHtml += '</tr>';

            });

            tableHtml += '</tbody></table>';



            // Insertar en el paso 2

            const previewTableDiv = step2.querySelector('.preview-table');

            if (previewTableDiv) {

                previewTableDiv.innerHTML = tableHtml;

            }

        }



        // Delegación de eventos para el dropZone

        document.body.addEventListener('click', function(e) {

            if (e.target.closest('#dropZone')) {

                fileInput.click();

            }

        });



        // Manejar arrastrar y soltar

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {

            dropZone.addEventListener(eventName, preventDefaults, false);

        });



        function preventDefaults(e) {

            e.preventDefault();

            e.stopPropagation();

        }



        function highlight() {

            dropZone.classList.add('bg-light');

        }



        function unhighlight() {

            dropZone.classList.remove('bg-light');

        }



        ['dragenter', 'dragover'].forEach(eventName => {

            dropZone.addEventListener(eventName, highlight, false);

        });



        ['dragleave', 'drop'].forEach(eventName => {

            dropZone.addEventListener(eventName, unhighlight, false);

        });



        // Manejar la carga del archivo

        dropZone.addEventListener('drop', handleDrop, false);



        function handleDrop(e) {

            const dt = e.dataTransfer;

            const files = dt.files;

            if (files.length > 0) {

                const fileName = files[0].name;

                dropZone.innerHTML = `

                    <i class="fas fa-check-circle text-success"></i>

                    <h5>${fileName}</h5>

                    <p class="text-success">Archivo listo para procesar</p>

                `;

            }

        }



        // Resetear al cerrar modal

        uploadModal.addEventListener('hidden.bs.modal', resetModal);



        // Manejar botón siguiente

        nextButton.addEventListener('click', function() {

            if (currentStep === 1) {

                if (!fileInput.files.length) {

                    alert('Por favor selecciona un archivo primero');

                    return;

                }



                step1.classList.add('d-none');

                step2.classList.remove('d-none');

                nextButton.textContent = 'Confirmar Importación';

                updateSteps(2);

                currentStep = 2;



                // Renderizar vista previa si ya se leyó el archivo

                renderPreviewTable();

            }

            else if (currentStep === 2) {
                // Enviar datos al backend vía AJAX
                if (!excelData || excelData.length < 2) {
                    alert('No se encontraron datos válidos en el archivo.');
                    return;
                }
                // Convertir a objetos (excluyendo encabezados)
                const headers = excelData[0];
                const rows = excelData.slice(1).map(row => {
                    const obj = {};
                    headers.forEach((h, idx) => {
                        obj[h] = row[idx];
                    });
                    return obj;
                });
                // Enviar por AJAX (POST)
                fetch("{{ route('guardar-lecturas') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ lecturas: rows })
                })
                .then(async response => {
                    let result;
                    try {
                        result = await response.json();
                    } catch (e) {
                        // Si la respuesta no es JSON, mostrar error genérico
                        step2.classList.add('d-none');
                        step3.classList.remove('d-none');
                        nextButton.textContent = 'Finalizar';
                        updateSteps(3);
                        currentStep = 3;
                        const totalRegistros = rows.length;
                        document.getElementById('totalToImport').textContent = totalRegistros;
                        document.getElementById('importedCount').textContent = 0;
                        document.getElementById('importedCount2').textContent = 0;
                        document.getElementById('errorCount').textContent = totalRegistros;
                        alert('Error al importar: respuesta no válida del servidor.');
                        return;
                    }
                    // Mostrar resultado en el paso 3
                    step2.classList.add('d-none');
                    step3.classList.remove('d-none');
                    nextButton.textContent = 'Finalizar';
                    updateSteps(3);
                    currentStep = 3;
                    // Actualizar los contadores
                    const totalRegistros = rows.length;
                    document.getElementById('totalToImport').textContent = totalRegistros;
                    document.getElementById('importedCount').textContent = result.guardadas || 0;
                    document.getElementById('importedCount2').textContent = result.guardadas || 0;
                    document.getElementById('errorCount').textContent = (result.errores ? result.errores.length : 0);
                })
                .catch(error => {
                    step2.classList.add('d-none');
                    step3.classList.remove('d-none');
                    nextButton.textContent = 'Finalizar';
                    updateSteps(3);
                    currentStep = 3;
                    const totalRegistros = rows.length;
                    document.getElementById('totalToImport').textContent = totalRegistros;
                    document.getElementById('importedCount').textContent = 0;
                    document.getElementById('importedCount2').textContent = 0;
                    document.getElementById('errorCount').textContent = totalRegistros;
                    alert('Error al importar: ' + error);
                });
            }

            else if (currentStep === 3) {

                const modal = bootstrap.Modal.getInstance(uploadModal);
                modal.hide();
                // Recargar la página para mostrar los datos actualizados
                window.location.reload();

            }

        });



        function updateSteps(activeStep) {

            steps.forEach((step, index) => {

                if (index < activeStep) {

                    step.classList.add('active');

                } else {

                    step.classList.remove('active');

                }

            });

        }



        // Inicializar eventos

        initFileInputEvents();

    }

    // Función para actualizar el contador de progreso en tiempo real
    function actualizarContadorProgreso() {
        const totalFilas = document.querySelectorAll('tbody tr').length;
        const filasConLectura = document.querySelectorAll('tbody tr').length - document.querySelectorAll('input[name="current_reading"][value=""]').length;
        
        const porcentaje = totalFilas > 0 ? Math.round((filasConLectura / totalFilas) * 100 * 10) / 10 : 0;
        const pendientes = totalFilas - filasConLectura;
        
        // Actualizar la barra de progreso mini
        const progressBar = document.querySelector('.progress-mini .progress-bar');
        const progressText = document.querySelector('.progress-mini .progress-text');
        const countText = document.querySelector('.progress-mini .text-muted');
        
        if (progressBar) {
            progressBar.style.width = porcentaje + '%';
            progressBar.setAttribute('aria-valuenow', porcentaje);
            
            // Cambiar el color de la barra según el progreso
            if (porcentaje === 100) {
                progressBar.style.background = 'linear-gradient(90deg, #28a745, #20c997)';
            } else if (porcentaje >= 75) {
                progressBar.style.background = 'linear-gradient(90deg, #20c997, #17a2b8)';
            } else if (porcentaje >= 50) {
                progressBar.style.background = 'linear-gradient(90deg, #ffc107, #fd7e14)';
            } else {
                progressBar.style.background = 'linear-gradient(90deg, #dc3545, #e83e8c)';
            }
        }
        
        if (progressText) {
            progressText.textContent = porcentaje + '%';
        }
        
        if (countText) {
            countText.textContent = filasConLectura + '/' + totalFilas;
        }
        
        // Habilitar/deshabilitar el botón Exportar
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            if (filasConLectura > 0) {
                exportBtn.classList.remove('disabled');
                exportBtn.removeAttribute('aria-disabled');
                exportBtn.setAttribute('tabindex', '0');
            } else {
                exportBtn.classList.add('disabled');
                exportBtn.setAttribute('aria-disabled', 'true');
                exportBtn.setAttribute('tabindex', '-1');
            }
        }
    }

    // Función para actualizar los contadores del modal de facturación
    function actualizarContadoresFacturacion() {
        const totalFilas = document.querySelectorAll('tbody tr').length;
        const filasConLectura = document.querySelectorAll('tbody tr');
        
        let boletasCount = 0;
        let facturasCount = 0;
        
        filasConLectura.forEach(fila => {
            const currentReadingInput = fila.querySelector('input[name="current_reading"]');
            const invoiceTypeSelect = fila.querySelector('select[name="invoice_type"]');
            
            // Solo contar si hay lectura ingresada (valor no vacío y mayor a 0)
            if (currentReadingInput && currentReadingInput.value && currentReadingInput.value.trim() !== '' && parseFloat(currentReadingInput.value) > 0) {
                const invoiceType = invoiceTypeSelect ? invoiceTypeSelect.value : 'boleta';
                
                if (invoiceType === 'boleta') {
                    boletasCount++;
                } else if (invoiceType === 'factura') {
                    facturasCount++;
                }
            }
        });
        
        // Actualizar los contadores en el modal
        const boletasCountElement = document.getElementById('boletasCount');
        const facturasCountElement = document.getElementById('facturasCount');
        
        if (boletasCountElement) {
            boletasCountElement.textContent = boletasCount;
        }
        
        if (facturasCountElement) {
            facturasCountElement.textContent = facturasCount;
        }
    }

    // Función para procesar facturación
    function procesarFacturacion(tipo) {
        if (tipo === 'boleta') {
            const boletasCount = document.getElementById('boletasCount').textContent;
            if (parseInt(boletasCount) === 0) {
                alert('No hay boletas para procesar. Asegúrese de que haya lecturas ingresadas con tipo "boleta".');
                return;
            }
            
            // Aquí puedes implementar la lógica para procesar boletas
            console.log('Procesando', boletasCount, 'boletas...');
            alert('Procesando ' + boletasCount + ' boletas...');
            
            // Cerrar el modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('invoiceModal'));
            modal.hide();
            
        } else if (tipo === 'factura') {
            const facturasCount = document.getElementById('facturasCount').textContent;
            if (parseInt(facturasCount) === 0) {
                alert('No hay facturas para procesar. Asegúrese de que haya lecturas ingresadas con tipo "factura".');
                return;
            }
            
            // Aquí puedes implementar la lógica para procesar facturas
            console.log('Procesando', facturasCount, 'facturas...');
            alert('Procesando ' + facturasCount + ' facturas...');
            
            // Cerrar el modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('invoiceModal'));
            modal.hide();
        }
    }

    // Hacer la función global para que sea accesible desde onclick
    window.procesarFacturacion = procesarFacturacion;

    // Actualizar contadores cuando se abra el modal de facturación
    const invoiceModal = document.getElementById('invoiceModal');
    if (invoiceModal) {
        invoiceModal.addEventListener('show.bs.modal', function () {
            actualizarContadoresFacturacion();
        });
    }

    // También actualizar contadores cuando cambie el tipo de factura en la tabla
    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'invoice_type') {
            actualizarContadoresFacturacion();
        }
    });

    // Actualizar contadores cuando se modifique una lectura
    document.addEventListener('input', function(e) {
        if (e.target && e.target.name === 'current_reading') {
            actualizarContadoresFacturacion();
        }
    });

    // Exportar tabla visible a Excel
    document.getElementById('exportBtn').addEventListener('click', function(e) {
        e.preventDefault();
        if (this.classList.contains('disabled')) return;
        // Selecciona la tabla principal de lecturas
        var table = document.querySelector('table.table-bordered');
        if (!table) {
            alert('No se encontró la tabla de lecturas.');
            return;
        }
        // Convierte la tabla HTML a una hoja de Excel usando SheetJS
        var wb = XLSX.utils.table_to_book(table, {sheet: 'Lecturas'});
        XLSX.writeFile(wb, 'lecturas_exportadas.xlsx');
    });
});
</script>

@endsection
