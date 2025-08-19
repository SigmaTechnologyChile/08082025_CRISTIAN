@extends('layouts.nice', ['active' => 'orgs.historyDTE', 'title' => 'Historial de DTE'])

@section('content')
    <div class="pagetitle">
        <h1>{{$org->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard', $org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item active">Historial de DTE</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card top-selling overflow-auto">
            <div class="card-body pt-2">
                <form method="GET" id="filterForm" action="{{ route('historyDTE', ['id' => $org->id]) }}">
                    <div class="row g-2 align-items-end flex-nowrap" style="width: 100%; overflow-x: auto;">
    <!-- Fecha Desde -->
    <div class="col-auto flex-grow-1" style="min-width: 180px;">
    <label class="form-label fw-semibold d-flex justify-content-center">Fecha Desde</label>
        <input type="date" name="start_date" class="form-control rounded-3"
            value="{{ request('start_date', date('Y-m-01')) }}">
    </div>

    <!-- Fecha Hasta -->
    <div class="col-auto flex-grow-1" style="min-width: 180px;">
    <label class="form-label fw-semibold d-flex justify-content-center">Fecha Hasta</label>
        <input type="date" name="end_date" class="form-control rounded-3"
            value="{{ request('end_date', date('Y-m-d')) }}">
    </div>

    <!-- Sectores -->
    <div class="col-auto" style="width: 140px;">
        <label class="form-label fw-semibold d-flex justify-content-center">Sectores</label>
        <select name="sector" class="form-select rounded-3">
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

    <!-- Tipo de DTE -->
    <div class="col-auto flex-grow-1" style="min-width: 150px;">
        <label class="form-label fw-semibold d-flex justify-content-center">Tipo de DTE</label>
        <select name="type_dte" class="form-select rounded-3">
            <option value="">Todos</option>
            <option value="Boleta" {{ request('type_dte') == 'Boleta' ? 'selected' : '' }}>Boleta</option>
            <option value="Factura" {{ request('type_dte') == 'Factura' ? 'selected' : '' }}>Factura</option>
        </select>
    </div>

    <!-- Buscador -->
    <div class="col-auto flex-grow-1" style="min-width: 220px;">
        <label class="form-label fw-semibold d-flex justify-content-center">Buscar</label>
        <div class="position-relative">
            <input type="text" name="search" class="form-control ps-5 rounded-end-3"
                placeholder="Buscar por nombre, apellido, sector" value="{{ request('search') }}"
                oninput="this.parentElement.querySelector('.search-icon').style.opacity = this.value ? '0' : '1';">
            <i class="bi bi-search fs-5 text-primary search-icon"
                style="position: absolute; top: 50%; left: 15px; transform: translateY(-50%); pointer-events: none; transition: opacity 0.2s;"></i>
        </div>
    </div>

    <!-- Botones -->
    <div class="col-auto d-flex align-items-center gap-2">
        <button type="submit" class="btn btn-primary pulse-btn px-3 rounded-2">
            <i class="bi bi-funnel-fill me-2"></i>Filtrar
        </button>
        <a href="{{ route('orgs.exportDTE', $org->id) }}" class="btn btn-primary pulse-btn px-3 rounded-2">
            <i class="bi bi-box-arrow-right me-2"></i>Exportar
        </a>
        <a href="{{ route('orgs.printAllDTE', array_merge(['id' => $org->id], request()->only(['start_date', 'end_date', 'sector', 'search', 'type_dte']))) }}"
           class="btn btn-primary pulse-btn px-3 rounded-2" target="_blank">
            <i class="bi bi-printer me-2"></i>Imprimir
         </a>
                    </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                                <th scope="col" class="text-center">ID</th>
                                <th scope="col" class="text-center">Orden</th>
                                <th scope="col" class="text-center">Rut</th>
                                <th scope="col" class="text-center">Nombres</th>
                                <th scope="col" class="text-center">Apellidos</th>
                                <th scope="col" class="text-center">N° Servicio</th>
                                <th scope="col" class="text-center">Sector</th>
                                <th scope="col" class="text-center">Tipo Dcto.</th>
                                <th scope="col" class="text-center">Monto</th>
                                <th scope="col" class="text-center">Estado de Pago</th>
                                <th scope="col" class="text-center">Periodo</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order_items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td class="text-center">{{ $item->id }}</td>
                                    <td class="text-center">{{ $item->order_code }}</td>
                                    <td class="text-center">
                                        <a
                                            href="{{route('orgs.members.edit', [$org->id, $item->member->id])}}">{{ $item->member->rut }}</a>
                                    </td>
                                    <td class="text-center">{{ $item->member->first_name }}</td>
                                    <td class="text-center">{{ $item->member->last_name }}</td>
                                    <td class="text-center">{{ Illuminate\Support\Str::padLeft($item->service->nro, 5, 0) }}
                                    </td>
                                    <td class="text-center">{{ $item->location->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $item->type_dte }}</td>
                                    <td class="text-center">
                                        <span class="text-success fw-bold">@money($item->total)</span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->payment_status)
                                            <span class="badge bg-success">Pagado</span>
                                        @else
                                            <span class="badge bg-warning">Pendiente de pago</span>
                                        @endif
                                    </td>
                                    <td class="text-center" style="white-space: nowrap;">{{ $item->reading->period }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            @if($item->type_dte == "Boleta")
                                                <a href="{{ route('orgs.readings.boleta', [$item->org_id, $item->reading_id]) }}"
                                                    target="_blank" class="btn btn-success btn-sm py-1 px-2"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Boleta">
                                                    <i class="bi bi-file-earmark-post"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('orgs.readings.factura', [$item->org_id, $item->reading_id]) }}"
                                                    target="_blank" class="btn btn-success btn-sm py-1 px-2"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Factura">
                                                    <i class="bi bi-file-earmark"></i>
                                                </a>
                                            @endif
                                            <a href="#" class="btn btn-warning btn-sm py-1 px-2" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Enviar Mail">
                                                <i class="ri-mail-send-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">{!! $order_items->render('pagination::bootstrap-4') !!} </div>
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
                            <!-- Columna izquierda - Datos personales -->
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
                                    <textarea class="form-control" id="observations" name="observations"
                                        rows="3"></textarea>
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
@endsection
