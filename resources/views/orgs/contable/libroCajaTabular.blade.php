<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libro Caja Tabular</title>
    <link rel="stylesheet" href="{{ asset('css/contable/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

@if($mostrarLibroCaja ?? false)
<div id="libroCajaSection" class="libro-caja-section" style="display: {{ $mostrarLibroCaja ? 'block' : 'none' }};">
@else
<div id="libroCajaSection" class="libro-caja-section" style="display: none;">
@endif

  <div class="section-header">
    <h1>Libro de Caja Tabular</h1>
    <p>Registro diario de todos los movimientos financieros</p>
  </div>

  <div class="saldo-section">
    <div class="saldo-grid">
      <div class="saldo-item">
        <label>Saldo Caja General</label>
        <input id="saldoCajaGeneral" type="text" value="$0" readonly>
      </div>
      <div class="saldo-item">
        <label>Saldo Cuenta Corriente 1</label>
        <input id="saldoCuentaCorriente1" type="text" value="$0" readonly>
      </div>
      <div class="saldo-item">
        <label>Saldo Cuenta Corriente 2</label>
        <input id="saldoCuentaCorriente2" type="text" value="$0" readonly>
      </div>
      <div class="saldo-item">
        <label>Cuenta Ahorro</label>
        <input id="saldoCuentaAhorro" type="text" value="$0" readonly>
      </div>
      <div class="saldo-item">
        <label>Saldo Total</label>
        <input id="saldoTotal" type="text" class="bg-gray-100" readonly>
      </div>
    </div>
  </div>

  <div class="totals-container">
    <div class="total-card ingresos">
      <h3>Total Ingresos</h3>
      <div class="value" id="totalIngresos">$0</div>
    </div>
    <div class="total-card egresos">
      <h3>Total Egresos</h3>
      <div class="value" id="totalEgresos">$0</div>
    </div>
    <div class="total-card saldo">
      <h3>Saldo Final</h3>
      <div class="value" id="saldoFinal">$0</div>
    </div>
  </div>

  <div class="table-container">
    <div class="table-header">
      <h2>Movimientos Financieros</h2>
      <div class="filters">
        <div class="filter-group">
          <label>Desde</label>
          <input id="fechaDesde" type="date">
        </div>
        <div class="filter-group">
          <label>Hasta</label>
          <input id="fechaHasta" type="date">
        </div>
        <div class="filter-group">
          <label>&nbsp;</label>
          <button onclick="filtrarPorFechas()" class="action-button" style="padding: 10px 15px;">
            <i class="bi bi-funnel"></i> Filtrar
          </button>
        </div>
      </div>
    </div>

    <div style="overflow-x: auto;">
      <table>
        <thead>
          <tr>
            <th colspan="7" class="ingresos-header">Entradas Ingresos</th>
            <th colspan="9" class="egresos-header">Salidas Egresos</th>
          </tr>
          <tr>
            <th>Acciones</th>
            <th>Fecha</th>
            <th>Descripción</th>
            <th>Total Consumo</th>
            <th>Cuotas Incorporación</th>
            <th>Otros Ingresos</th>
            <th>Giros</th>
            <th>TOTAL INGRESOS</th>
            <th>ENERGÍA ELÉCTRICA</th>
            <th>SUELDOS/LEYES SOCIALES</th>
            <th>OTROS GASTOS DE OPERACIÓN</th>
            <th>GASTOS MANTENCION</th>
            <th>GASTOS ADMINISTRACION</th>
            <th>GASTOS MEJORAMIENTO</th>
            <th>OTROS EGRESOS</th>
            <th>DEPÓSITOS</th>
            <th>TOTAL EGRESOS</th>
          </tr>
        </thead>
        <tbody id="tablaMovimientos">
          <!-- Los movimientos se generarán dinámicamente aquí -->
        </tbody>
      </table>
    </div>
  </div>
<div class="button-group">
  <button id="volverBtn" class="action-button volver">
    <i id="volverBtnIcon" class="bi bi-arrow-left"></i>
    <span id="volverBtnTexto">Volver al Registro</span>
  </button>
</div>


<script>
  document.addEventListener("DOMContentLoaded", function () {
    const hoy = new Date();
    const primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    const ultimoDiaMes = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);

    document.getElementById('fechaDesde').valueAsDate = primerDiaMes;
    document.getElementById('fechaHasta').valueAsDate = ultimoDiaMes;

    // Reflejar saldos desde localStorage
    const saldoCajaGeneral = localStorage.getItem('saldoCajaGeneral') || 0;
    const saldoCuentaCorriente1 = localStorage.getItem('saldoCuentaCorriente1') || 0;
    const saldoCuentaCorriente2 = localStorage.getItem('saldoCuentaCorriente2') || 0;
    const saldoCuentaAhorro = localStorage.getItem('saldoCuentaAhorro') || 0;
    const saldoTotal =
      parseFloat(saldoCajaGeneral) +
      parseFloat(saldoCuentaCorriente1) +
      parseFloat(saldoCuentaCorriente2) +
      parseFloat(saldoCuentaAhorro);

    document.getElementById('saldoCajaGeneral').value = formatCurrency(saldoCajaGeneral);
    document.getElementById('saldoCuentaCorriente1').value = formatCurrency(saldoCuentaCorriente1);
    document.getElementById('saldoCuentaCorriente2').value = formatCurrency(saldoCuentaCorriente2);
    document.getElementById('saldoCuentaAhorro').value = formatCurrency(saldoCuentaAhorro);
    document.getElementById('saldoTotal').value = formatCurrency(saldoTotal);

    // Manejo del botón volver o cerrar sesión
    const volverBtn = document.getElementById('volverBtn');
    const volverBtnTexto = document.getElementById('volverBtnTexto');
    const volverBtnIcon = document.getElementById('volverBtnIcon');
    const sesionEspecialActiva = @json(Auth::user()?->isCrc() ?? false);

    if (sesionEspecialActiva) {
      volverBtnTexto.textContent = 'Cerrar Sesión';
      volverBtnIcon.className = 'bi bi-box-arrow-right';
    }

    volverBtn.addEventListener('click', () => {
      if (sesionEspecialActiva) {
        window.location.href = '/logout';
      } else {
        window.location.href = "{{ route('orgs.contable.index', ['id' => $id ?? $orgId ?? 1]) }}";
      }
    });

    actualizarTablaLibroCaja();
  });

  function formatCurrency(value) {
    const num = parseFloat(value) || 0;
    return num.toLocaleString('es-CL', { style: 'currency', currency: 'CLP', minimumFractionDigits: 0 });
  }

  function actualizarTablaLibroCaja() {
  const tbody = document.getElementById('tablaMovimientos');
  if (!tbody) return;
  tbody.innerHTML = '';

  // Obtener movimientos y fechas de filtro
  const movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
  const fechaDesde = document.getElementById('fechaDesde').value;
  const fechaHasta = document.getElementById('fechaHasta').value;

  // Filtrar por fecha
  const filtrados = movimientos.filter(m => {
    return (!fechaDesde || m.fecha >= fechaDesde) && (!fechaHasta || m.fecha <= fechaHasta);
  });

  filtrados.forEach(mov => {
    // Columnas de ingresos
    let totalConsumo = '';
    let cuotasIncorporacion = '';
    let otrosIngresos = '';
    let giros = '';
    let totalIngresos = '';
    // Columnas de egresos
    let energia = '';
    let sueldos = '';
    let otrosGastosOperacion = '';
    let gastosMantencion = '';
    let gastosAdministracion = '';
    let gastosMejoramiento = '';
    let otrosEgresos = '';
    let depositos = '';
    let totalEgresos = '';

    // Mapeo de ingresos
    if (mov.tipo === 'ingreso') {
      if (mov.categoria === 'venta_agua') totalConsumo = formatCurrency(mov.monto);
      else if (mov.categoria === 'cuotas_incorporacion') cuotasIncorporacion = formatCurrency(mov.monto);
      else otrosIngresos = formatCurrency(mov.monto);
      totalIngresos = formatCurrency(mov.monto);
    }
    // Mapeo de egresos
    else if (mov.tipo === 'egreso') {
      if (mov.categoria === 'energia_electrica') energia = formatCurrency(mov.monto);
      else if (mov.categoria === 'sueldos') sueldos = formatCurrency(mov.monto);
      else if (mov.categoria === 'otras_cuentas') otrosGastosOperacion = formatCurrency(mov.monto);
      else if (mov.categoria === 'mantencion' || mov.categoria === 'trabajos_domicilio') gastosMantencion = formatCurrency(mov.monto);
      else if (mov.categoria === 'insumos_oficina') gastosAdministracion = formatCurrency(mov.monto);
      else if (mov.categoria === 'materiales_red' || mov.categoria === 'mejoramiento') gastosMejoramiento = formatCurrency(mov.monto);
      else if (mov.categoria === 'viaticos') otrosEgresos = formatCurrency(mov.monto);
      totalEgresos = formatCurrency(mov.monto);
    }
    // Mapeo de transferencias
    else if (mov.tipo === 'transferencia') {
      if (mov.subtipo === 'giro') giros = formatCurrency(mov.monto);
      else if (mov.subtipo === 'deposito') depositos = formatCurrency(mov.monto);
    }

    // Fila
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td></td>
      <td>${mov.fecha || ''}</td>
      <td>${mov.descripcion || ''}</td>
      <td>${totalConsumo}</td>
      <td>${cuotasIncorporacion}</td>
      <td>${otrosIngresos}</td>
      <td>${giros}</td>
      <td>${totalIngresos}</td>
      <td>${energia}</td>
      <td>${sueldos}</td>
      <td>${otrosGastosOperacion}</td>
      <td>${gastosMantencion}</td>
      <td>${gastosAdministracion}</td>
      <td>${gastosMejoramiento}</td>
      <td>${otrosEgresos}</td>
      <td>${depositos}</td>
      <td>${totalEgresos}</td>
    `;
    tbody.appendChild(tr);
  });
}
</script>

</body>
</html>
