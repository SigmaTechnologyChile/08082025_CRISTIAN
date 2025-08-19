@extends('layouts.nice', ['active' => 'orgs.libro.caja', 'title' => 'Libro de Caja Tabular'])

{{-- CSS personalizado para vista tabular --}}
<style>
:root {
  --primary-color: #2c5282;
  --secondary-color: #4CAF50;
  --danger-color: #e53e3e;
  --success-color: #48bb78;
  --warning-color: #dd6b20;
  --purple-color: #805ad5;
  --orange-color: #dd6b20;
  --light-bg: #f8f9fa;
}

.libro-caja-section {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
}

.section-header {
  text-align: center;
  margin: 20px 0 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
}

.section-header h1 {
  color: var(--primary-color);
  font-size: 2.5rem;
  margin-bottom: 10px;
  position: relative;
  display: inline-block;
  padding-bottom: 15px;
}

.section-header h1::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 150px;
  height: 4px;
  background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
  border-radius: 2px;
}

.section-header p {
  color: #4a5568;
  font-size: 1.1rem;
  max-width: 800px;
  margin: 0 auto;
}

.saldo-section {
  background: linear-gradient(135deg, #2c5282, #1a365d);
  border-radius: 12px;
  padding: 25px;
  color: white;
  margin-bottom: 30px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.saldo-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.saldo-item {
  background: rgba(255, 255, 255, 0.15);
  padding: 15px;
  border-radius: 8px;
  backdrop-filter: blur(5px);
}

.saldo-item label {
  color: #ebf8ff;
  font-size: 1rem;
  margin-bottom: 8px;
  display: block;
}

.saldo-item input {
  background: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: white;
  font-size: 1.2rem;
  font-weight: 600;
  padding: 10px;
  width: 100%;
  border-radius: 6px;
}

/* TOTALES EN FILA HORIZONTAL */
.totals-container {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 30px;
  flex-wrap: wrap;
}

.total-card {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
  text-align: center;
  transition: transform 0.3s ease;
  flex: 1;
  min-width: 250px;
}

.total-card:hover {
  transform: translateY(-5px);
}

.total-card.ingresos {
  border-top: 5px solid var(--success-color);
}

.total-card.egresos {
  border-top: 5px solid var(--danger-color);
}

.total-card.saldo {
  border-top: 5px solid var(--primary-color);
}

.total-card.ahorro {
  border-top: 5px solid var(--purple-color);
}

.total-card h3 {
  font-size: 1.2rem;
  color: #4a5568;
  margin-bottom: 15px;
}

.total-card .value {
  font-size: 2.2rem;
  font-weight: 700;
}

.total-card.ingresos .value {
  color: var(--success-color);
}

.total-card.egresos .value {
  color: var(--danger-color);
}

.total-card.saldo .value {
  color: var(--primary-color);
}

.total-card.ahorro .value {
  color: var(--purple-color);
}

.table-container {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
  overflow-x: auto;
  margin-bottom: 30px;
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  flex-wrap: wrap;
  gap: 15px;
}

.table-header h2 {
  color: var(--primary-color);
  font-size: 1.6rem;
  font-weight: 600;
}

.filters {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  flex-direction: column;
}

.filter-group label {
  font-size: 0.9rem;
  margin-bottom: 5px;
  color: #4a5568;
}

.filter-group input, .filter-group select {
  padding: 8px 12px;
  border: 1px solid #cbd5e0;
  border-radius: 6px;
  font-size: 14px;
}

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 1000px;
}

thead {
  background: linear-gradient(120deg, var(--primary-color), #1a365d);
  color: white;
}

th {
  padding: 16px 12px;
  text-align: center;
  font-weight: 600;
  border: 1px solid #2c5282;
}

.ingresos-header {
  background: #2c5282;
}

.egresos-header {
  background: #1a365d;
}

tbody tr {
  border-bottom: 1px solid #e2e8f0;
  transition: background-color 0.2s;
}

tbody tr:nth-child(even) {
  background-color: #f8fafc;
}

tbody tr:hover {
  background-color: #ebf8ff;
}

td {
  padding: 14px 10px;
  text-align: center;
  border: 1px solid #e2e8f0;
  font-size: 14px;
}

.ingreso-cell {
  background-color: #f0fff4;
  color: #2f855a;
  font-weight: 500;
}

.egreso-cell {
  background-color: #fff5f5;
  color: #c53030;
  font-weight: 500;
}

.action-button {
  padding: 14px 30px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 16px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.action-button.volver {
  background: linear-gradient(120deg, #718096, #4a5568);
  color: white;
}

.button-group {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 30px;
  flex-wrap: wrap;
}

/* RESPONSIVE DESIGN */
@media (max-width: 900px) {
  .libro-caja-section {
    padding: 10px;
  }
  .section-header h1 {
    font-size: 2rem;
  }
  .totals-container {
    flex-direction: column;
    gap: 10px;
  }
  .total-card {
    min-width: 180px;
    padding: 15px;
  }
  .table-container {
    padding: 10px;
  }
  .table-header h2 {
    font-size: 1.2rem;
  }
  th, td {
    padding: 8px 4px;
    font-size: 12px;
  }
}

@media (max-width: 600px) {
  .libro-caja-section {
    padding: 5px;
  }
  .section-header {
    flex-direction: column;
    gap: 10px;
    margin: 10px 0 20px;
  }
  .section-header h1 {
    font-size: 1.3rem;
    padding-bottom: 8px;
  }
  .section-header h1::after {
    width: 80px;
    height: 2px;
  }
  .section-header p {
    font-size: 0.9rem;
    max-width: 100%;
  }
  .saldo-section {
    padding: 10px;
    border-radius: 8px;
  }
  .saldo-grid {
    grid-template-columns: 1fr;
    gap: 8px;
  }
  .saldo-item {
    padding: 8px;
    border-radius: 6px;
  }
  .totals-container {
    flex-direction: column;
    gap: 8px;
    margin-bottom: 15px;
  }
  .total-card {
    min-width: 120px;
    padding: 10px;
    font-size: 0.9rem;
  }
  .total-card h3 {
    font-size: 1rem;
    margin-bottom: 8px;
  }
  .total-card .value {
    font-size: 1.2rem;
  }
  .table-container {
    padding: 5px;
    border-radius: 6px;
  }
  .table-header {
    flex-direction: column;
    gap: 8px;
    margin-bottom: 10px;
  }
  .table-header h2 {
    font-size: 1rem;
  }
  .filters {
    flex-direction: column;
    gap: 6px;
  }
  th, td {
    padding: 4px 2px;
    font-size: 10px;
  }
  table {
    min-width: 600px;
  }
}
</style>

@section('content')
<div class="libro-caja-section">
    <!-- Header de la sección -->
  <div class="section-header">
    <div>
      <h1>Libro de Caja Tabular</h1>
      <p>Registro diario de todos los movimientos financieros</p>
    </div>
    </div>
    
    <!-- Sección de saldos -->
    <div class="saldo-section">
        <div class="saldo-grid">
            <div class="saldo-item">
                <label>Saldo Caja General</label>
                <input type="text" value="${{ number_format($saldoCajaGeneral ?? 0, 0, ',', '.') }}" readonly>
            </div>
            <div class="saldo-item">
                <label>Saldo Cuenta Corriente 1</label>
                <input type="text" value="${{ number_format($saldoCuentaCorriente1 ?? 0, 0, ',', '.') }}" readonly>
            </div>
            <div class="saldo-item">
                <label>Saldo Cuenta Corriente 2</label>
                <input type="text" value="${{ number_format($saldoCuentaCorriente2 ?? 0, 0, ',', '.') }}" readonly>
            </div>
            <div class="saldo-item">
                <label>Saldo Cuenta Ahorro</label>
                <input type="text" value="${{ number_format($saldoCuentaAhorro ?? 0, 0, ',', '.') }}" readonly>
            </div>
            <div class="saldo-item">
                <label>Saldo Total</label>
                <input type="text" value="${{ number_format($saldoTotal ?? 0, 0, ',', '.') }}" readonly>
            </div>
        </div>
    </div>
    
    <!-- TOTALES EN FILA HORIZONTAL -->
    <div class="totals-container">
        <div class="total-card ingresos">
            <h3>Total Ingresos</h3>
            <div class="value">${{ number_format($totalIngresos ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="total-card egresos">
            <h3>Total Egresos</h3>
            <div class="value">${{ number_format($totalEgresos ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="total-card saldo">
            <h3>Saldo Final</h3>
            <div class="value">${{ number_format($saldoFinal ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>
    
    <!-- Tabla de movimientos -->
    <div class="table-container">
        <div class="table-header">
            <h2>Movimientos Financieros</h2>
            <div class="filters">
                <div class="filter-group">
                    <label>Desde</label>
                    <input id="fechaDesde" type="date" value="{{ date('Y-m-01') }}">
                </div>
                <div class="filter-group">
                    <label>Hasta</label>
                    <input id="fechaHasta" type="date" value="{{ date('Y-m-d') }}">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button class="action-button" style="padding: 10px 15px;">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
            </div>
        </div>
        
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th colspan="8" class="ingresos-header">Entradas Ingresos</th>
                        <th colspan="9" class="egresos-header">Salidas Egresos</th>
                    </tr>
                    <tr>
                        
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Total Consumo</th>
                        <th>Cuotas Incorporación</th>
                        <th>Otros Ingresos</th>
                        <th>Giros</th>
                        <th>TOTAL INGRESOS</th>
                        <th>Energía Eléctrica</th>
                        <th>Sueldos y Leyes Sociales</th>
                        <th>Gastos de Operación</th>
                        <th>Gastos de Mantención</th>
                        <th>Gastos de Administración</th>
                        <th>Gastos de Mejoramiento</th>
                        <th>Otros Gastos</th>
                        <th>Depósitos</th>
                        <th>TOTAL EGRESOS</th>
                    </tr>
                </thead>
                <tbody>
          @foreach($movimientos as $movimiento)
          <tr>
                        <td>{{ date('d/m/Y', strtotime($movimiento->fecha)) }}</td>
            <td style="text-align: left;">{{ $movimiento->descripcion }}</td>
            <!-- INGRESOS -->
            <td class="{{ ($movimiento->tipo == 'ingreso' && $movimiento->grupo == 'Total Consumo') ? 'ingreso-cell' : '' }}">
              {{ ($movimiento->tipo == 'ingreso' && $movimiento->grupo == 'Total Consumo') ? '$' . number_format((float)$movimiento->monto, 0, ',', '.') : '' }}
            </td>
            <td class="{{ ($movimiento->tipo == 'ingreso' && $movimiento->grupo == 'Cuotas Incorporación') ? 'ingreso-cell' : '' }}">
              {{ ($movimiento->tipo == 'ingreso' && $movimiento->grupo == 'Cuotas Incorporación') ? '$' . number_format((float)$movimiento->monto, 0, ',', '.') : '' }}
            </td>
            <td class="{{ ($movimiento->tipo == 'ingreso' && $movimiento->grupo == 'Otros Ingresos') ? 'ingreso-cell' : '' }}">
              {{ ($movimiento->tipo == 'ingreso' && $movimiento->grupo == 'Otros Ingresos') ? '$' . number_format((float)$movimiento->monto, 0, ',', '.') : '' }}
            </td>
            <!-- GIROS: mostrar columna giros -->
            <td class="ingreso-cell">
              {{ $movimiento->giros > 0 ? '$' . number_format((float)$movimiento->giros, 0, ',', '.') : '' }}
            </td>
            <td class="ingreso-cell" style="font-weight: bold;">
              @if($movimiento->tipo == 'ingreso')
                ${{ number_format((float)$movimiento->monto, 0, ',', '.') }}
              @endif
            </td>
            <!-- EGRESOS -->
            <td class="{{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Energía Eléctrica') ? 'egreso-cell' : '' }}">
              {{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Energía Eléctrica') ? '$' . number_format((float)$movimiento->monto, 0, ',', '.') : '' }}
            </td>
            <td class="{{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Sueldos y Leyes Sociales') ? 'egreso-cell' : '' }}">
              {{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Sueldos y Leyes Sociales') ? '$' . number_format((float)$movimiento->monto, 0, ',', '.') : '' }}
            </td>
            <td class="{{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Gastos de Operación') ? 'egreso-cell' : '' }}">
              {{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Gastos de Operación') ? '$' . number_format((float)$movimiento->monto, 0, ',', '.') : '' }}
            </td>
            <td class="{{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Gastos de Mantención') ? 'egreso-cell' : '' }}">
              {{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Gastos de Mantención') ? '$' . number_format((float)$movimiento->monto, 0, ',', '.') : '' }}
            </td>
            <td class="{{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Gastos de Administración') ? 'egreso-cell' : '' }}">
              {{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Gastos de Administración') ? '$' . number_format((float)$movimiento->monto, 0, ',', '.') : '' }}
            </td>
            <td class="{{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Gastos de Mejoramiento') ? 'egreso-cell' : '' }}">
              {{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Gastos de Mejoramiento') ? '$' . number_format((float)$movimiento->monto, 0, ',', '.') : '' }}
            </td>
            <td class="{{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Otros Gastos') ? 'egreso-cell' : '' }}">
              {{ ($movimiento->tipo == 'egreso' && $movimiento->grupo == 'Otros Gastos') ? '$' . number_format((float)$movimiento->monto, 0, ',', '.') : '' }}
            </td>
            <!-- DEPÓSITOS: mostrar columna depositos -->
            <td class="egreso-cell">
              {{ $movimiento->depositos > 0 ? '$' . number_format((float)$movimiento->depositos, 0, ',', '.') : '' }}
            </td>
            <td class="egreso-cell" style="font-weight: bold;">
              @if($movimiento->tipo == 'egreso')
                ${{ number_format((float)$movimiento->monto, 0, ',', '.') }}
              @endif
            </td>
          </tr>
          @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
  <!-- Botón de retorno eliminado -->
</div>
@endsection
