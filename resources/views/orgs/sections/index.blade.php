@extends('layouts.nice', ['active' => 'orgs.sections.index', 'title' => 'Tramos'])

@section('content')
    <div class="pagetitle">
        <h1>{{$org->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard', $org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item active">Tramos</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
    <div class="card top-selling overflow-auto">
        <div class="card-body pt-2">
            <form method="POST" action="{{ route('orgs.sections.storeFixedCost', $org->id) }}">
                @csrf
                <!-- Fila para los TÍTULOS -->
                <div class="row mb-2">
                    <div class="col-md-2">
                        <label class="form-label">Valor cargo fijo:</label>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Interes cargo vencido:</label>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Interes cargo Mora:</label>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Valor corte y reposición</label>
                    </div>
                    <div class="col-md-2">
                        <label for="max_covered_m3" class="form-label">M³ cubre subsidio</label>
                    </div>
                </div>

                <!-- Fila para los INPUTS -->
                <div class="row align-items-end mb-3">
                    <div class="col-md-2">
                        <input type="number" name="fixed_charge_penalty" class="form-control"
                            value="{{ old('fixed_charge_penalty', $fixedCostConfig->fixed_charge_penalty) }}"
                            placeholder="Ingresa el valor">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="expired_penalty" class="form-control"
                            value="{{ old('expired_penalty', $fixedCostConfig->expired_penalty) }}"
                            placeholder="Ingresa el valor">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="late_fee_penalty" class="form-control"
                            value="{{ old('late_fee_penalty', $fixedCostConfig->late_fee_penalty) }}"
                            placeholder="Ingresa el valor">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="replacement_penalty" class="form-control"
                            value="{{ old('replacement_penalty', $fixedCostConfig->replacement_penalty) }}"
                            placeholder="Ingresa el valor">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0" name="max_covered_m3" id="max_covered_m3" class="form-control"
                            value="{{ old('max_covered_m3', $fixedCostConfig->max_covered_m3) }}"
                            placeholder="Ingresa m3">
                    </div>
                </div>

                <!-- Fila para los BOTONES -->
                <div class="col-md-12 d-flex justify-content-between">  <!-- Distribuye espacio entre elementos -->
    <!-- Botón Guardar (izquierda) -->
    <button type="submit" class="btn btn-primary pulse-btn p-1 px-2 rounded-2">
        <i class="bi bi-funnel-fill me-2"></i>Guardar
    </button>

    <!-- Contenedor para Exportar y Nuevo tramo (derecha) -->
    <div class="d-flex gap-2">  <!-- Ajusta "gap-2" para espacio entre botones -->
    <button type="submit" class="btn btn-primary pulse-btn p-1 px-2 rounded-2">
        <i class="bi bi-plus-circle me-2"></i>Nuevo tramo
    </button>

<button type="button" class="btn btn-primary pulse-btn p-1 px-2 rounded-2" onclick="window.print();">
    <i class="bi bi-printer me-2"></i>Imprimir
</button>
</div>
</section>

            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
        <th scope="col" class="text-center">Tramo</th>
        <th scope="col" class="text-center">Desde</th>
        <th scope="col" class="text-center">Hasta</th>
        <th scope="col" class="text-center">Valor $</th>
        <th scope="col" class="text-center">Acciones</th>
    </tr>
</thead>
    @foreach($tiers as $tier)
    <tr data-id="{{ $tier->id }}">
        <td class="text-center">{{ $tier->tier_name}}</td>
        <td class="text-center">{{$tier->range_from}}</td>
        <td class="text-center">{{$tier->range_to}}</td>
        <td class="text-center">{{$tier->value}}</td>
        <td class="text-center">
            <div class="d-flex justify-content-center">
                <a href="{{ route('orgs.sections.edit', ['id' => $org->id, 'tramoId' => $tier->id]) }}"
                    class="btn btn-sm btn-success me-2"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Editar">
                    <i class="bi bi-pencil-square me-1"></i> Editar
                </a>
                <a href="{{ route('orgs.sections.destroy', ['id' => $org->id, 'tramoId' => $tier->id]) }}"
                    class="btn btn-sm btn-danger delete-tier"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    data-tier-name="{{ $tier->tier_name }}"
                    title="Eliminar">
                    <i class="bi bi-trash me-1"></i> Eliminar
                </a>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
                    </table>
                </div>
            </div>
        </div>
    {{-- Sin paginación para exportar todos los tramos filtrados --}}
    </section>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar eliminación de tramos con SweetAlert
    const deleteButtons = document.querySelectorAll('.delete-tier');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const url = this.getAttribute('href');
            const tierName = this.getAttribute('data-tier-name');

            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar el tramo "${tierName}"? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crear formulario dinámicamente para enviar DELETE
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    // Token CSRF
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    // Method DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Agregar al DOM y enviar
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif
