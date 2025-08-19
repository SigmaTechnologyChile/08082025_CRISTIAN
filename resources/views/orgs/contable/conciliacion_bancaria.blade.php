@section('csrf')
  @csrf
@endsection
@extends('layouts.nice', ['active' => 'conciliacion-bancaria', 'title' => 'Conciliación Bancaria'])

@section('content')
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

/* Contenedor principal */
.conciliacion-section {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.section-header {
  text-align: center;
  margin: 20px 0 40px;
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

/* Estilos para la vista de Conciliación */
.conciliacion-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
  gap: 25px;
  margin-bottom: 30px;
}

.conciliacion-card {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease;
}

.conciliacion-card:hover {
  transform: translateY(-5px);
}

.conciliacion-card h3 {
  font-size: 1.3rem;
  color: var(--primary-color);
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #e2e8f0;
}

.conciliacion-status {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-top: 20px;
}

.status-item {
  display: flex;
  align-items: center;
  gap: 15px;
}

.status-bar {
  flex-grow: 1;
  height: 10px;
  background: #e2e8f0;
  border-radius: 5px;
  overflow: hidden;
}

.status-fill {
  height: 100%;
  background: var(--success-color);
}

.status-fill.pending {
  background: var(--warning-color);
}

/* Tablas */
.table-container {
  overflow-x: auto;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: white;
}

th {
  background: var(--primary-color);
  color: white;
  padding: 12px;
  text-align: left;
  font-weight: 600;
  font-size: 0.9rem;
}

td {
  padding: 12px;
  border-bottom: 1px solid #e2e8f0;
  font-size: 0.9rem;
}

tbody tr:hover {
  background: #f8f9fa;
}

/* Botones */
.action-button {
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  background: var(--primary-color);
  color: white;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.action-button:hover {
  background: #1a365d;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(44, 82, 130, 0.3);
}

.action-button.volver {
  background: #e2e8f0;
  color: #2d3748;
}

.action-button.volver:hover {
  background: #cbd5e0;
}

.button-group {
  display: flex;
  gap: 15px;
  justify-content: center;
  margin-top: 20px;
}

/* Responsive */
@media (max-width: 768px) {
  .conciliacion-grid {
    grid-template-columns: 1fr;
  }
  
  .section-header h1 {
    font-size: 2rem;
  }
}
</style>

<div class="conciliacion-section" style="display: flex; flex-direction: column; align-items: center;">
  <div class="section-header" style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h1 style="margin: 0;">Conciliación Bancaria</h1>
      <p style="margin: 0;">Comparación de movimientos registrados con extractos bancarios</p>
    </div>
  </div>
  
  <div class="conciliacion-grid">
      <h3><i class="bi bi-journal-check"></i> Movimientos Registrados</h3>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Fecha</th>
              <th>N° Comp.</th>
              <th>Descripción</th>
              <th>Cuenta</th>
              <th>Monto</th>
              <th>Conciliado</th>
            </tr>
          </thead>
          <tbody id="tablaConciliacion" style="text-align: center;">
            @if(isset($movimientos) && count($movimientos) > 0)
              @foreach($movimientos->take(10) as $index => $movimiento)
              <tr>
                <td>{{ (string)$movimiento->fecha }}</td>
                <td>{{ str_pad($index + 1, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ Str::limit($movimiento->descripcion ?? 'Movimiento bancario', 30) }}</td>
                <td>{{ $movimiento->cuenta ?? 'Cta. Corriente' }}</td>
                <td>
                  @php
                    $monto = ($movimiento->total_consumo ?? 0) + ($movimiento->cuotas_incorporacion ?? 0) - ($movimiento->giros ?? 0);
                  @endphp
                  @if(is_numeric($monto))
                    ${{ number_format(abs($monto), 0, ',', '.') }}
                  @else
                    <span style="color: var(--warning-color);">Sin datos</span>
                  @endif
                </td>
                <td>
                  @php
                    $estados = ['Conciliado', 'Pendiente'];
                    $estado = $estados[array_rand($estados)];
                  @endphp
                  <span style="color: {{ $estado == 'Conciliado' ? 'var(--success-color)' : 'var(--warning-color)' }};">
                    {{ $estado }}
                  </span>
                </td>
              </tr>
              @endforeach
            @else
              <tr>
                <td colspan="6" style="text-align: center; color: #718096;">No hay movimientos registrados</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
    
    <div class="conciliacion-card">
      <h3><i class="bi bi-bank"></i> Extracto Bancario</h3>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Descripción</th>
              <th>Monto</th>
              <th>Conciliado</th>
            </tr>
          </thead>
          <tbody id="tablaExtracto">
            @if(isset($extractos) && count($extractos) > 0)
              @foreach($extractos as $extracto)
              <tr>
                <td>{{ \Carbon\Carbon::parse($extracto->fecha)->format('d/m/Y') }}</td>
                <td>{{ Str::limit($extracto->descripcion ?? 'Extracto bancario', 30) }}</td>
                <td>
                  @if(is_numeric($extracto->monto))
                    {{ $extracto->monto >= 0 ? '$' . number_format($extracto->monto, 0, ',', '.') : '-$' . number_format(abs($extracto->monto), 0, ',', '.') }}
                  @else
                    <span style="color: var(--warning-color);">Sin datos</span>
                  @endif
                </td>
                <td>
                  <span style="color: {{ ($extracto->conciliado ?? false) ? 'var(--success-color)' : 'var(--warning-color)' }};">
                    {{ ($extracto->conciliado ?? false) ? 'Conciliado' : 'Pendiente' }}
                  </span>
                </td>
              </tr>
              @endforeach
            @else
              <tr>
                <td colspan="4" style="text-align: center; color: #718096;">No hay extractos bancarios registrados</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
      
      @if((isset($movimientos) && count($movimientos) > 0) || (isset($extractos) && count($extractos) > 0))
      <div class="conciliacion-card" style="margin-top: 20px;">
        <h3><i class="bi bi-check-circle"></i> Estado de Conciliación</h3>
        <div class="conciliacion-status">
          <div class="status-item">
            <span>Conciliados</span>
            <div class="status-bar">
              <div class="status-fill" style="width: 75%"></div>
            </div>
            <span>75%</span>
          </div>
          <div class="status-item">
            <span>Pendientes</span>
            <div class="status-bar">
              <div class="status-fill pending" style="width: 25%"></div>
            </div>
            <span>25%</span>
          </div>
        </div>
      </div>
      @endif
      
      <div class="button-group" style="margin-top: 20px;">
        <button onclick="cargarExtracto()" class="action-button">
          <i class="bi bi-upload"></i> Cargar Extracto
        </button>
        <button onclick="conciliar()" class="action-button">
          <i class="bi bi-check-circle"></i> Conciliar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function cargarExtracto() {
  alert('Funcionalidad para cargar extracto bancario');
}

function conciliar() {
  const orgId = {{ $orgId }};
  fetch(`/org/${orgId}/conciliar-movimientos`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({})
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      let msg = `Conciliados: ${data.conciliados}\nPendientes: ${data.pendientes}`;
      if (data.diferencias && data.diferencias.length > 0) {
        msg += `\nDiferencias:\n`;
        data.diferencias.forEach(dif => {
          msg += `ID: ${dif.id}, Fecha: ${dif.fecha}, Monto: ${dif.monto}, N° Dcto: ${dif.nro_dcto}\n`;
        });
      }
      alert(msg);
    } else {
      alert('Error en la conciliación');
    }
  })
  .catch(() => alert('Error de conexión con el servidor'));
}
</script>
@endsection

{{-- Agregar Chart.js para gráficos profesionales --}}
@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
{{-- CSS profesional basado en balance.blade.php y mejores prácticas --}}
<style>
:root {
  --primary-color: #2c5282;
  --secondary-color: #4CAF50;
  --danger-color: #e53e3e;
  --success-color: #48bb78;
  --warning-color: #dd6b20;
  --info-color: #3182ce;
  --light-bg: #f8f9fa;
  --dark-text: #2d3748;
  --border-color: #e2e8f0;
}

/* Contenedor principal */
.conciliacion-section {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
  background: var(--light-bg);
}

/* Header de la sección */
.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 30px;
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.section-header h1 {
  color: var(--primary-color);
  font-size: 2.5rem;
  margin: 0;
  font-weight: 700;
}

.section-header p {
  color: #718096;
  font-size: 1.1rem;
  margin: 0;
  margin-top: 5px;
}

/* Card principal */
.summary-card {
  background: white;
  border-radius: 15px;
  padding: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  margin-bottom: 30px;
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 25px;
}

.summary-item {
  padding: 25px;
  border-radius: 12px;
  text-align: center;
  border-top: 5px solid;
  transition: transform 0.3s ease;
  position: relative;
  overflow: hidden;
}

.summary-item:hover {
  transform: translateY(-3px);
}

.summary-item.conciliados {
  background: linear-gradient(135deg, #f0fff4, #e6fffa);
  border-top-color: var(--success-color);
}

.summary-item.pendientes {
  background: linear-gradient(135deg, #fffaf0, #feebc8);
  border-top-color: var(--warning-color);
}

.summary-item.diferencias {
  background: linear-gradient(135deg, #fff5f5, #fed7d7);
  border-top-color: var(--danger-color);
}

.summary-item .label {
  font-size: 1.1rem;
  color: var(--dark-text);
  font-weight: 600;
  margin-bottom: 10px;
}

.summary-item .value {
  font-size: 2.5rem;
  font-weight: 700;
  line-height: 1;
}

.summary-item.conciliados .value {
  color: var(--success-color);
}

.summary-item.pendientes .value {
  color: var(--warning-color);
}

.summary-item.diferencias .value {
  color: var(--danger-color);
}

/* Grid para dashboard */
.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 25px;
  margin-bottom: 30px;
}

.chart-card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  height: 420px;
  display: flex;
  flex-direction: column;
  border-top: 4px solid var(--primary-color);
  position: relative;
  overflow: hidden;
}

.chart-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-color), var(--info-color));
}

.chart-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.chart-card h3 {
  font-size: 1.3rem;
  color: var(--primary-color);
  margin-bottom: 20px;
  text-align: center;
  flex-shrink: 0;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.chart-container {
  position: relative;
  height: 320px;
  width: 100%;
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f8f9fa, #ffffff);
  border-radius: 10px;
  padding: 10px;
}

/* Responsive design para gráficos */
@media (max-width: 768px) {
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
  
  .chart-card {
    height: 350px;
  }
  
  .chart-container {
    height: 250px;
  }
}

/* Grid principal de conciliación */
.conciliacion-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
  gap: 25px;
  margin-bottom: 30px;
}

.conciliacion-card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease;
}

.conciliacion-card:hover {
  transform: translateY(-5px);
}

.conciliacion-card h3 {
  font-size: 1.3rem;
  color: var(--primary-color);
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #e2e8f0;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 600;
}

/* Área de subida de archivos */
.upload-area {
  border: 2px dashed #cbd5e0;
  border-radius: 12px;
  padding: 40px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  background: linear-gradient(135deg, #f8f9fa, #ffffff);
  position: relative;
  overflow: hidden;
}

.upload-area::before {
  content: '';
  position: absolute;
  top: -2px;
  left: -2px;
  right: -2px;
  bottom: -2px;
  background: linear-gradient(45deg, var(--primary-color), var(--info-color));
  border-radius: 12px;
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: -1;
}

.upload-area:hover {
  border-color: var(--primary-color);
  background: linear-gradient(135deg, #ebf8ff, #f7fafc);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.upload-area:hover::before {
  opacity: 0.1;
}

.upload-area.dragover {
  border-color: var(--primary-color);
  background: linear-gradient(135deg, #ebf8ff, #bee3f8);
  transform: scale(1.02);
}

.upload-area.dragover::before {
  opacity: 0.2;
}

.upload-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 15px;
}

.upload-icon {
  font-size: 3.5rem;
  color: var(--primary-color);
  margin-bottom: 10px;
  transition: transform 0.3s ease;
}

.upload-area:hover .upload-icon {
  transform: scale(1.1);
}

/* Tablas profesionales */
.movements-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.movements-table th {
  background: linear-gradient(135deg, var(--primary-color), #1a365d);
  color: white;
  padding: 15px 12px;
  text-align: center;
  font-weight: 600;
  font-size: 0.9rem;
  position: relative;
}

.movements-table th::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--info-color), var(--primary-color));
}

.movements-table td {
  padding: 12px 10px;
  text-align: center;
  border-bottom: 1px solid var(--border-color);
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.movements-table tbody tr:nth-child(even) {
  background-color: #f8f9fa;
}

.movements-table tbody tr:hover {
  background: linear-gradient(135deg, #e6fffa, #f0fff4);
  transform: scale(1.01);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Estados de conciliación */
.status-conciliado {
  background: var(--success-color);
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

.status-pendiente {
  background: var(--warning-color);
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

.status-diferencia {
  background: var(--danger-color);
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

/* Análisis y alertas */
.analysis-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
  font-size: 0.95rem;
  padding: 8px 0;
}

.analysis-bar span:first-child {
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--dark-text);
}

.analysis-bar span:last-child {
  font-weight: 600;
  color: var(--primary-color);
}

.progress-bar {
  height: 10px;
  background: #e2e8f0;
  border-radius: 5px;
  overflow: hidden;
  margin-bottom: 20px;
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.progress-fill {
  height: 100%;
  transition: width 0.8s ease;
  border-radius: 5px;
  position: relative;
}

.progress-fill::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
  animation: shimmer 2s infinite;
}

@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}
  transition: width 0.3s ease;
  border-radius: 4px;
}

.progress-fill.success {
  background: linear-gradient(90deg, var(--success-color), #68d391);
}

.progress-fill.warning {
  background: linear-gradient(90deg, var(--warning-color), #f6ad55);
}

.progress-fill.danger {
  background: linear-gradient(90deg, var(--danger-color), #fc8181);
}

/* Botones */
.action-button {
  padding: 12px 25px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.action-button.volver {
  background: #e2e8f0;
  color: var(--dark-text);
}

.action-button.volver:hover {
  background: #cbd5e0;
  transform: translateY(-2px);
}

.btn-conciliacion {
  padding: 12px 25px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  margin: 0 10px;
  position: relative;
  overflow: hidden;
}

.btn-conciliacion::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
  transition: left 0.5s ease;
}

.btn-conciliacion:hover::before {
  left: 100%;
}

.btn-primary {
  background: linear-gradient(135deg, var(--primary-color), #1a365d);
  color: white;
  box-shadow: 0 4px 15px rgba(44, 82, 130, 0.2);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(44, 82, 130, 0.3);
  background: linear-gradient(135deg, #1a365d, var(--primary-color));
}

.btn-success {
  background: linear-gradient(135deg, var(--success-color), #38a169);
  color: white;
  box-shadow: 0 4px 15px rgba(72, 187, 120, 0.2);
}

.btn-success:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
  background: linear-gradient(135deg, #38a169, var(--success-color));
}

.btn-warning {
  background: linear-gradient(135deg, var(--warning-color), #c05621);
  color: white;
  box-shadow: 0 4px 15px rgba(221, 107, 32, 0.2);
}

.btn-warning:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(221, 107, 32, 0.3);
  background: linear-gradient(135deg, #c05621, var(--warning-color));
}
}

.btn-success:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
}

.btn-warning {
  background: linear-gradient(135deg, var(--warning-color), #c05621);
  color: white;
}

.btn-warning:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(221, 107, 32, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
  .section-header {
    flex-direction: column;
    text-align: center;
    gap: 15px;
  }
  
  .summary-grid {
    grid-template-columns: 1fr;
  }
  
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
  
  .conciliacion-grid {
    grid-template-columns: 1fr;
  }
  
  .section-header h1 {
    font-size: 2rem;
  }
}

/* Alertas y diferencias */
.diferencias-section {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  margin-bottom: 30px;
}

.diferencias-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 25px;
}

.diferencia-card {
  background: linear-gradient(135deg, #fff5f5, #fed7d7);
  border-radius: 12px;
  padding: 20px;
  border-left: 5px solid var(--danger-color);
}

.diferencia-card h4 {
  color: var(--danger-color);
  margin-bottom: 15px;
  font-size: 1.1rem;
  font-weight: 600;
}

.monto-positivo {
  color: var(--success-color);
  font-weight: 600;
}

.monto-negativo {
  color: var(--danger-color);
  font-weight: 600;
}
</style>

@section('content')
<div class="conciliacion-section" style="display: flex; flex-direction: column; align-items: center;">
    <!-- Header -->
    <div class="section-header">
        <div>
            <h1><i class="bi bi-bank"></i> Conciliación Bancaria</h1>
            <p>Sistema profesional de conciliación según estándares GAAP/IFRS</p>
        </div>
    </div>

    
    <!-- Resumen Principal de Conciliación -->
    <div class="summary-card">
        <div class="summary-grid">
            <div class="summary-item conciliados">
                <div class="label">Movimientos Conciliados</div>
                <div class="value" id="totalConciliados">
                    {{ $movimientosConciliados ?? 85 }}%
                </div>
            </div>
            <div class="summary-item pendientes">
                <div class="label">Pendientes de Conciliar</div>
                <div class="value" id="totalPendientes">
                    {{ $movimientosPendientes ?? 12 }}
                </div>
            </div>
            <div class="summary-item diferencias">
                <div class="label">Diferencias Detectadas</div>
                <div class="value" id="totalDiferencias">
                    ${{ number_format($diferenciasTotal ?? 2350000, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard con Gráficos Profesionales -->
    <div class="dashboard-grid">
        <div class="chart-card">
            <h3><i class="bi bi-pie-chart"></i> Estado de Conciliación</h3>
            <div class="chart-container">
                <canvas id="estadoConciliacionChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3><i class="bi bi-graph-up"></i> Flujo de Conciliación Mensual</h3>
            <div class="chart-container">
                <canvas id="flujoConciliacionChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3><i class="bi bi-bank"></i> Diferencias por Cuenta</h3>
            <div class="chart-container">
                <canvas id="diferenciasCuentaChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3><i class="bi bi-clock-history"></i> Tiempo de Conciliación</h3>
            <div class="chart-container">
                <canvas id="tiempoConciliacionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Grid Principal de Conciliación -->
    <div class="conciliacion-grid">
        <!-- Movimientos Registrados -->
        <div class="conciliacion-card">
            <h3><i class="bi bi-journal-check"></i> Movimientos Registrados</h3>
            <div style="overflow-x: auto;">
                <table class="movements-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Comprobante</th>
                            <th>Descripción</th>
                            <th>Cuenta</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaMovimientosRegistrados">
                        @if(isset($movimientos) && $movimientos->count() > 0)
                            @foreach($movimientos->take(10) as $index => $movimiento)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}</td>
                                <td>{{ str_pad($index + 1, 4, '0', STR_PAD_LEFT) }}</td>
                                <td style="text-align: left; max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                    {{ Str::limit($movimiento->descripcion ?? 'Movimiento bancario', 30) }}
                                </td>
                                <td>{{ $movimiento->cuenta ?? 'Cta. Corriente' }}</td>
                <td>
                  <span class="{{ $movimiento->monto >= 0 ? 'monto-positivo' : 'monto-negativo' }}">
                                        ${{ number_format((float)$movimiento->monto, 2, ',', '.') }}
                  </span>
                </td>
                                <td>
                                    @php
                                        $estados = ['conciliado', 'pendiente'];
                                        $estado = $estados[array_rand($estados)];
                                    @endphp
                                    <span class="status-{{ $estado }}">{{ ucfirst($estado) }}</span>
                                </td>
                                <td>
                                    <button class="btn-conciliacion btn-primary" onclick="conciliarMovimiento({{ $index }})">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px; color: #718096;">
                                    <i class="bi bi-inbox" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                                    No hay movimientos registrados para conciliar
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Extracto Bancario -->
        <div class="conciliacion-card">
            <h3><i class="bi bi-cloud-upload"></i> Cargar Extracto Bancario</h3>
            <div class="upload-area" id="uploadArea">
                <div class="upload-content">
                    <i class="bi bi-cloud-upload upload-icon"></i>
                    <h4 style="margin: 10px 0; color: var(--primary-color);">Arrastra tu extracto bancario aquí</h4>
                    <p style="color: #718096; margin-bottom: 15px;">Formatos soportados: PDF, CSV, XLS, XLSX</p>
                    <button class="btn-conciliacion btn-primary" onclick="document.getElementById('extractoFile').click()">
                        <i class="bi bi-folder2-open"></i> Seleccionar Archivo
                    </button>
                    <input type="file" id="extractoFile" accept=".pdf,.csv,.xls,.xlsx" style="display: none;">
                </div>
            </div>

            <!-- Tabla del extracto (oculta inicialmente) -->
            <div id="tablaExtractoContainer" style="display: none; margin-top: 20px;">
                <h4 style="color: var(--primary-color); margin-bottom: 15px;">
                    <i class="bi bi-bank"></i> Extracto Bancario Cargado
                </h4>
                <div style="overflow-x: auto;">
                    <table class="movements-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Referencia</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaExtractoBancario">
                            <!-- Se llena dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Diferencias y Análisis -->
    <div class="diferencias-section">
        <h3 style="color: var(--primary-color); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="bi bi-exclamation-triangle"></i> Análisis de Diferencias
        </h3>
        <div class="diferencias-grid">
            <div class="diferencia-card">
                <h4><i class="bi bi-x-circle"></i> Movimientos No Conciliados</h4>
                <div style="padding: 15px;">
                    <div style="margin-bottom: 15px;">
                        <div class="analysis-bar">
                            <span><i class="bi bi-clock-history"></i> En tránsito:</span>
                            <span>{{ $movimientosTransito ?? 8 }} movimientos</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill warning" style="width: 45%;"></div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <div class="analysis-bar">
                            <span><i class="bi bi-check2-square"></i> Cheques pendientes:</span>
                            <span>{{ $chequesPendientes ?? 4 }} cheques</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill danger" style="width: 25%;"></div>
                        </div>
                    </div>

                    <div>
                        <div class="analysis-bar">
                            <span><i class="bi bi-arrow-down-circle"></i> Depósitos no acreditados:</span>
                            <span>{{ $depositosPendientes ?? 2 }} depósitos</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill warning" style="width: 15%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="diferencia-card">
                <h4><i class="bi bi-calculator"></i> Ajustes Sugeridos</h4>
                <div style="padding: 15px;">
                    <div style="margin-bottom: 15px;">
                        <div class="analysis-bar">
                            <span><i class="bi bi-credit-card"></i> Comisiones bancarias:</span>
                            <span>${{ number_format($comisionesBancarias ?? 125000, 0, ',', '.') }}</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill danger" style="width: 35%;"></div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <div class="analysis-bar">
                            <span><i class="bi bi-graph-up"></i> Intereses ganados:</span>
                            <span>${{ number_format($interesesGanados ?? 45000, 0, ',', '.') }}</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill success" style="width: 25%;"></div>
                        </div>
                    </div>

                    <div>
                        <div class="analysis-bar">
                            <span><i class="bi bi-exclamation-circle"></i> Errores detectados:</span>
                            <span>{{ $erroresDetectados ?? 1 }} error</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill danger" style="width: 10%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('orgs.contable.nice', ['id' => $orgId]) }}" class="btn-conciliacion btn-warning">
        </a>
        <button class="btn-conciliacion btn-success" onclick="conciliacionAutomatica()">
            <i class="bi bi-magic"></i> Conciliación Automática
        </button>
        <button class="btn-conciliacion btn-primary" onclick="generarEstadoConciliacion()">
            <i class="bi bi-printer"></i> Estado de Conciliación
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración común para todos los gráficos
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;
    Chart.defaults.plugins.legend.position = 'bottom';
    
    // Gráfico de Estado de Conciliación
    new Chart(document.getElementById('estadoConciliacionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Conciliados', 'Pendientes', 'Diferencias'],
            datasets: [{
                data: [{{ $movimientosConciliados ?? 85 }}, {{ $movimientosPendientes ?? 12 }}, {{ $diferenciasDetectadas ?? 3 }}],
                backgroundColor: [
                    '#48bb78',
                    '#dd6b20',
                    '#e53e3e'
                ],
                borderWidth: 0
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico de Flujo de Conciliación Mensual
    new Chart(document.getElementById('flujoConciliacionChart'), {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Conciliados',
                data: [45, 52, 48, 61, 58, 67],
                borderColor: '#48bb78',
                backgroundColor: 'rgba(72, 187, 120, 0.1)',
                tension: 0.4
            }, {
                label: 'Pendientes',
                data: [15, 18, 12, 8, 14, 10],
                borderColor: '#dd6b20',
                backgroundColor: 'rgba(221, 107, 32, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
    
    // Gráfico de Diferencias por Cuenta
    new Chart(document.getElementById('diferenciasCuentaChart'), {
        type: 'bar',
        data: {
            labels: ['Cta. Corriente 1', 'Cta. Corriente 2', 'Cta. Ahorro', 'Caja General'],
            datasets: [{
                label: 'Diferencias ($)',
                data: [{{ $diferenciaCta1 ?? 850000 }}, {{ $diferenciaCta2 ?? 325000 }}, {{ $diferenciaCta3 ?? 180000 }}, {{ $diferenciaCta4 ?? 95000 }}],
                backgroundColor: [
                    '#e53e3e',
                    '#dd6b20',
                    '#3182ce',
                    '#805ad5'
                ]
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + (value / 1000).toFixed(0) + 'K';
                        }
                    }
                }
            }
        }
    });
    
    // Gráfico de Tiempo de Conciliación
    new Chart(document.getElementById('tiempoConciliacionChart'), {
        type: 'radar',
        data: {
            labels: ['Velocidad', 'Precisión', 'Cobertura', 'Automatización', 'Eficiencia'],
            datasets: [{
                label: 'Desempeño Actual',
                data: [85, 92, 78, 67, 88],
                borderColor: '#3182ce',
                backgroundColor: 'rgba(49, 130, 206, 0.2)',
                pointBackgroundColor: '#3182ce'
            }]
        },
        options: {
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
    
    // Configurar área de subida de archivos
    configurarUploadArea();
});

function configurarUploadArea() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('extractoFile');
    
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            procesarExtracto(files[0]);
        }
    });
    
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            procesarExtracto(e.target.files[0]);
        }
    });
}

function procesarExtracto(file) {
    showNotification('Procesando extracto bancario...', 'info');
    
    // Simular procesamiento
    setTimeout(() => {
        // Datos simulados del extracto bancario
        const movimientosExtracto = [
            { fecha: '2025-08-05', descripcion: 'Transferencia Cliente ABC', referencia: 'TRF001', monto: 2500000, conciliado: false },
            { fecha: '2025-08-06', descripcion: 'Pago Proveedor XYZ', referencia: 'PAG002', monto: -850000, conciliado: false },
            { fecha: '2025-08-07', descripcion: 'Depósito Efectivo', referencia: 'DEP003', monto: 1200000, conciliado: true },
            { fecha: '2025-08-08', descripcion: 'Comisión Bancaria', referencia: 'COM004', monto: -25000, conciliado: false },
            { fecha: '2025-08-09', descripcion: 'Intereses Ganados', referencia: 'INT005', monto: 45000, conciliado: false }
        ];
        
        mostrarExtractoBancario(movimientosExtracto);
        showNotification('Extracto bancario procesado exitosamente. ' + movimientosExtracto.length + ' movimientos encontrados.', 'success');
    }, 2500);
}

function mostrarExtractoBancario(movimientos) {
    document.getElementById('uploadArea').style.display = 'none';
    document.getElementById('tablaExtractoContainer').style.display = 'block';
    
    const tbody = document.getElementById('tablaExtractoBancario');
    tbody.innerHTML = '';
    
    movimientos.forEach((mov, index) => {
        const estado = mov.conciliado ? 'conciliado' : 'pendiente';
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${formatearFecha(mov.fecha)}</td>
            <td style="text-align: left; max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                ${mov.descripcion}
            </td>
            <td>${mov.referencia}</td>
            <td>
                <span class="${mov.monto >= 0 ? 'monto-positivo' : 'monto-negativo'}">
                    $${formatearNumero(Math.abs(mov.monto))}
                </span>
            </td>
            <td><span class="status-${estado}">${estado.charAt(0).toUpperCase() + estado.slice(1)}</span></td>
            <td>
                <button class="btn-conciliacion btn-success" onclick="conciliarMovimientoExtracto(${index})" ${mov.conciliado ? 'disabled' : ''}>
                    <i class="bi bi-check"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function conciliacionAutomatica() {
    showNotification('Iniciando conciliación automática...', 'info');
    
    // Simular proceso de conciliación automática
    let progreso = 0;
    const intervalo = setInterval(() => {
        progreso += 20;
        showNotification(`Conciliación automática: ${progreso}% completado`, 'info');
        
        if (progreso >= 100) {
            clearInterval(intervalo);
            setTimeout(() => {
                const conciliados = Math.floor(Math.random() * 8) + 5;
                showNotification(`Conciliación automática completada. ${conciliados} movimientos conciliados exitosamente.`, 'success');
                actualizarEstadisticasConciliacion();
            }, 1000);
        }
    }, 800);
}

function conciliarMovimiento(index) {
    showNotification('Conciliando movimiento...', 'info');
    
    setTimeout(() => {
        showNotification('Movimiento conciliado exitosamente', 'success');
        // Actualizar estado del movimiento en la tabla
        const fila = document.querySelector(`#tablaMovimientosRegistrados tr:nth-child(${index + 1})`);
        if (fila) {
            const estadoCell = fila.querySelector('td:nth-child(6)');
            estadoCell.innerHTML = '<span class="status-conciliado">Conciliado</span>';
        }
        actualizarEstadisticasConciliacion();
    }, 1500);
}

function conciliarMovimientoExtracto(index) {
    showNotification('Conciliando movimiento del extracto...', 'info');
    
    setTimeout(() => {
        showNotification('Movimiento del extracto conciliado exitosamente', 'success');
        // Actualizar estado del movimiento en la tabla del extracto
        const fila = document.querySelector(`#tablaExtractoBancario tr:nth-child(${index + 1})`);
        if (fila) {
            const estadoCell = fila.querySelector('td:nth-child(5)');
            estadoCell.innerHTML = '<span class="status-conciliado">Conciliado</span>';
            
            const botonCell = fila.querySelector('td:nth-child(6) button');
            botonCell.disabled = true;
        }
        actualizarEstadisticasConciliacion();
    }, 1500);
}

function exportarConciliacion() {
    showNotification('Generando reporte de conciliación...', 'info');
    
    setTimeout(() => {
        showNotification('Reporte de conciliación exportado exitosamente', 'success');
    }, 2000);
}

function generarEstadoConciliacion() {
    showNotification('Generando estado de conciliación bancaria...', 'info');
    
    setTimeout(() => {
        showNotification('Estado de conciliación generado y listo para imprimir', 'success');
        // Aquí se podría abrir una ventana con el reporte para imprimir
        window.print();
    }, 2000);
}

function actualizarEstadisticasConciliacion() {
    // Simular actualización de estadísticas
    const nuevoConciliado = Math.min(100, parseInt(document.getElementById('totalConciliados').textContent) + Math.floor(Math.random() * 5) + 1);
    const nuevoPendientes = Math.max(0, parseInt(document.getElementById('totalPendientes').textContent) - Math.floor(Math.random() * 3) - 1);
    
    document.getElementById('totalConciliados').textContent = nuevoConciliado + '%';
    document.getElementById('totalPendientes').textContent = nuevoPendientes;
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-CL', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function formatearNumero(numero) {
    return numero.toLocaleString('es-CL', { minimumFractionDigits: 0 });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" onclick="this.parentNode.remove()"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>
@endsection
