@php
    use Carbon\Carbon;
@endphp
@extends('layouts.nice', ['active' => 'movimientos', 'title' => 'Movimientos'])

{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

@section('content')
<div class="contable-container">
    <!-- Breadcrumbs modernos -->
    
    <!-- Sección principal -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-arrow-left-right"></i>
                    Movimientos Financieros
                </h1>
                <p class="contable-subtitle">Consulta y gestión de todos los movimientos financieros del sistema</p>
            </div>
            <!-- Botón imprimir movido a la cabecera de filtros -->
        </div>

        <div class="contable-body">
            <!-- Filtros modernizados -->
            <div class="contable-form-section contable-mb-xl">
                <div class="contable-form-header">
                    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <h3 style="margin: 0;">
                            <i class="bi bi-funnel"></i>
                            Filtros de Búsqueda
                        </h3>
                        <button class="contable-btn contable-btn-danger" style="color: #fff;" onclick="imprimirTablaMovimientos()">
                            <i class="bi bi-printer"></i>
                            Imprimir
                        </button>
                    </div>
                </div>
                <div class="contable-form-body">
                    <div class="contable-form-grid contable-form-grid-4">
                        <div class="contable-form-group">
                            <label class="contable-form-label">
                                <i class="bi bi-calendar-date"></i>
                                Fecha Desde
                            </label>
                            <input type="date" id="fechaDesde" class="contable-form-input">
                        </div>
                        <div class="contable-form-group">
                            <label class="contable-form-label">
                                <i class="bi bi-calendar-check"></i>
                                Fecha Hasta
                            </label>
                            <input type="date" id="fechaHasta" class="contable-form-input">
                        </div>
                        <div class="contable-form-group">
                            <label class="contable-form-label">
                                <i class="bi bi-tags"></i>
                                Tipo de Movimiento
                            </label>
                            <select id="tipoMovimiento" class="contable-form-input contable-form-select">
                                <option value="">Todos los tipos</option>
                                <option value="ingreso">💰 Ingresos</option>
                                <option value="egreso">💸 Egresos</option>
                                <option value="transferencia">🔄 Transferencias</option>
                            </select>
                        </div>
                        <div class="contable-form-group">
                            <label class="contable-form-label">&nbsp;</label>
                            <button type="button" onclick="filtrarMovimientos()" class="contable-btn contable-btn-primary contable-btn-full">
                                <i class="bi bi-search"></i>
                                Aplicar Filtros
                            </button>
@push('scripts')
<script>
function imprimirTablaMovimientos() {
    var tabla = document.querySelector('.contable-table-wrapper');
    if (!tabla) return window.print();
    var ventana = window.open('', '', 'height=700,width=1000');
    ventana.document.write('<html><head><title>Imprimir Movimientos</title>');
    ventana.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    ventana.document.write('<style>body{font-family:sans-serif;padding:20px;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #ccc;padding:8px;} th{background:#f8f9fa;} }</style>');
    ventana.document.write('</head><body >');
    ventana.document.write('<h2>Movimientos Financieros</h2>');
    ventana.document.write(tabla.innerHTML);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.focus();
    ventana.print();
    ventana.close();
}

function filtrarMovimientos() {
    var fechaDesde = document.getElementById('fechaDesde').value;
    var fechaHasta = document.getElementById('fechaHasta').value;
    var tipo = document.getElementById('tipoMovimiento').value;
    var filas = document.querySelectorAll('.contable-table tbody tr');
    filas.forEach(function(fila) {
        var mostrar = true;
        var fecha = fila.querySelector('td:nth-child(1)')?.innerText.trim();
        var tipoMov = fila.querySelector('td:nth-child(2) .badge')?.innerText.trim().toLowerCase();
        // Formato fecha: dd-mm-yyyy
        if (fecha && (fechaDesde || fechaHasta)) {
            var partes = fecha.split('-');
            var fechaObj = new Date(partes[2], partes[1]-1, partes[0]);
            if (fechaDesde) {
                var partesDesde = fechaDesde.split('-');
                var desdeObj = new Date(partesDesde[0], partesDesde[1]-1, partesDesde[2]);
                if (fechaObj < desdeObj) mostrar = false;
            }
            if (fechaHasta) {
                var partesHasta = fechaHasta.split('-');
                var hastaObj = new Date(partesHasta[0], partesHasta[1]-1, partesHasta[2]);
                if (fechaObj > hastaObj) mostrar = false;
            }
        }
        if (tipo && tipoMov) {
            // El select tiene valores en minúsculas: ingreso, egreso, transferencia
            // El badge puede tener "Ingreso", "Egreso", "Transferencia" (capitalizado)
            // Normalizamos ambos a minúsculas para comparar
            if (tipoMov !== tipo.toLowerCase()) mostrar = false;
        }
        fila.style.display = mostrar ? '' : 'none';
    });
}
</script>
@endpush
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de movimientos modernizada -->
            <div class="contable-table-wrapper">
                <table class="contable-table">
                    <thead class="contable-table-header">
                        <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Categoría</th>
                                    <th>Monto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movimientos as $mov)
                                    <tr>
                                            <td>{{ $mov->fecha ? date('d-m-Y', strtotime((string)$mov->fecha)) : '' }}</td>
                                        <td>
                                            <span class="badge bg-{{ strtolower($mov->tipo) === 'ingreso' ? 'success' : (strtolower($mov->tipo) === 'egreso' ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($mov->tipo) }}
                                            </span>
                                        </td>
                                        <td>{{ $mov->descripcion ?? $mov->detalle ?? '' }}</td>
                                        <td>
                                            @if($mov->categoria instanceof \App\Models\Categoria)
                                                {{ $mov->categoria->nombre }}
                                            @else
                                                {{ $mov->categoria ?? $mov->rubro ?? '' }}
                                            @endif
                                        </td>
                                        <td class="{{ strtolower($mov->tipo) === 'ingreso' ? 'text-success' : (strtolower($mov->tipo) === 'egreso' ? 'text-danger' : '') }}">
                                            ${{ number_format((float)$mov->monto, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('movimientos.edit', ['id' => $orgId, 'movimiento' => $mov->id]) }}" class="btn btn-sm btn-outline-primary" title="Editar movimiento">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('movimientos.destroy', ['id' => $orgId, 'movimiento' => $mov->id]) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar movimiento">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No hay movimientos registrados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.filters-row {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    min-width: 120px;
}

.filter-group label {
    margin-bottom: 5px;
    font-weight: 600;
}
</style>

<!-- Eliminado: toda la lógica JS y localStorage. Ahora los movimientos se muestran desde el backend usando Blade. -->
@endsection
