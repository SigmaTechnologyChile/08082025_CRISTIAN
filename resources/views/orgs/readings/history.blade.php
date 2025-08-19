@extends('layouts.nice', ['active'=>'orgs.readings.histories','title'=>'Historial de lecturas y consumos'])

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
                <form method="GET" id="filterForm" action="{{ route('orgs.readings.history', $org->id) }}">
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
                        <!-- Sectores -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Sectores</label>
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

                        <!-- Buscador -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Buscar por nombre o RUT</label>
                            <div class="input-group input-group-sm search-input-group">
                                <span class="input-group-text border-0 bg-white ps-4">
                                    <i class="bi bi-search fs-5 text-primary"></i>
                                </span>
                                <input type="text" id="search" name="search" class="form-control rounded-end-3" placeholder="Buscar por nombre, apellido o RUT" value="{{ request('search') }}">
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
    <th scope="col" class="text-center">
        Lec.
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
    </th>
    <th scope="col" class="text-center">
        Periodo
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'period', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'period', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
    </th>
    <th scope="col" class="text-center">
        Sector
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'location_name', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'location_name', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
    </th>
    <th scope="col" class="text-center">
        nro Servicio
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nro', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nro', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
    </th>
    <th scope="col" class="text-center">
        RUT/RUN
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'rut', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'rut', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
    </th>
    <th scope="col" class="text-center">
        Nombre/Apellido
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'full_name', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'full_name', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
    </th>
    <th scope="col" class="text-center">
        Fecha Reg.
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'period', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'period', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
    </th>
    <th scope="col" class="text-center">
        Lectura
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'current_reading', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'current_reading', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
    </th>
    <th scope="col" class="text-center">
        Consumo Mes
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'cm3', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'cm3', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
    </th>
    <th scope="col" class="text-center">
        Monto Mes
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'total', 'order' => 'asc']) }}"><i class="bi bi-arrow-up"></i></a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'total', 'order' => 'desc']) }}"><i class="bi bi-arrow-down"></i></a>
    </th>
    <th scope="col" class="text-center">Documento</th>
</tr>
</thead>
<tbody>
    @foreach($readings as $reading)
    <tr data-id="{{ $reading->id }}">
        <td class="text-center">{{ $reading->id }}</td>
        <td class="text-center" style="white-space: nowrap;">{{ $reading->period }}</td>
        <td class="text-center">{{ $reading->location_name ?? 'N/A' }}</td>
        <td class="text-center">{{ Illuminate\Support\Str::padLeft($reading->nro,5,0) }}</td>
        <td class="text-center"><a href="{{route('orgs.members.edit',[$org->id,$reading->member_id])}}">{{ $reading->rut }}</a></td>
        <td class="text-center">{{ $reading->full_name ?? 'N/A'}}</td>
        <td class="text-center" style="white-space: nowrap;">{{ $reading->period }}</td>
        <td class="text-center">{{ $reading->current_reading }}</td>
        <td class="text-center"><span class="text-primary fw-bold">{{ $reading->cm3 }}</span></td>
        <td class="text-center"><span class="text-success fw-bold">@money($reading->total)</span></td>
        <td class="text-center">
            @if($reading->invoice_type == "boleta")
            <a href="{{ route('orgs.readings.boleta', [$reading->org_id, $reading->id]) }}" class="btn btn-info btn-sm py-1 px-2" target="_blank">
                <i class="bi bi-file-earmark-post"></i> Ver Boleta
            </a>
            @else
            <a href="{{ route('orgs.readings.factura', [$reading->org_id, $reading->id]) }}" class="btn btn-warning btn-sm py-1 px-2" target="_blank">
                <i class="bi bi-file-earmark"></i> Ver Factura
            </a>
            @endif
        </td>
    </tr>
    @endforeach


                        <!-- Modal de Edición de Lectura -->
                        <div class="modal fade" id="editReadingModal" tabindex="-1" aria-labelledby="editReadingModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editReadingModalLabel">Editar Lectura</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('orgs.readings.update', [$org->id]) }}" method="POST" id="editReadingForm">
                                            @csrf
                                            <input type="hidden" name="reading_id" id="reading_id">
                                            <div class="mb-3">
                                                <label for="current_reading" class="form-label">Lectura Actual:</label>
                                                <input type="number" class="form-control" id="current_reading"  name="current_reading"  required>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary" id="saveReadingBtn" onclick="document.getElementById('editReadingForm').submit();">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </tbody>
                </table>
            </div>
        </div>
    <!-- Paginación eliminada -->
    </div>
</section>

@endsection


@section('js')
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    $('#editReadingModal').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget);


        var readingId = button.data('bs-id');
        var currentReading = button.data('bs-current');


        var modal = $(this);


        modal.find('#reading_id').val(readingId);
        modal.find('#current_reading').val(currentReading);




    });
});

function openModal(readingId, currentReading) {
    document.getElementById('reading_id').value = readingId;
    document.getElementById('current_reading').value = currentReading;
    console.log(readingId);
    console.log(currentReading);
    $('#editReadingModal').modal('show');
}

window.addEventListener('load', function() {
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
                var wb = XLSX.utils.table_to_book(table, {sheet: 'Historial'});
                XLSX.writeFile(wb, 'historial_lecturas.xlsx');
            } catch (err) {
                alert('Error al exportar: ' + err.message);
            }
        }
    }
});
</script>
@endsection
