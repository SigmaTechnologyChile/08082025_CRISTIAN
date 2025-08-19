@extends('layouts.nice', ['active'=>'orgs.locations.panelcontrolrutas', 'title'=>'Panel Control Rutas'])


@section('content')
 <style>

    :root {

      --primary-color: #2c5282;

      --secondary-color: #4CAF50;

      --danger-color: #e53e3e;

      --light-bg: #f8f9fa;

    }



    * {

      box-sizing: border-box;

      margin: 0;

      padding: 0;

    }



    body {

      font-family: 'Poppins', sans-serif;

      background: linear-gradient(135deg, #f8f9fa, #eef2f7);

      padding: 20px;

      color: #333;

      min-height: 100vh;

    }



    .container {

      max-width: 1000px;

      margin: 0 auto;

      background-color: #fff;

      padding: 30px;

      border-radius: 12px;

      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);

    }



    h1 {

      color: var(--primary-color);

      text-align: center;

      font-size: 2.2rem;

      margin-bottom: 30px;

      position: relative;

    }



    h1::after {

      content: '';

      position: absolute;

      bottom: -10px;

      left: 50%;

      transform: translateX(-50%);

      width: 120px;

      height: 4px;

      background: linear-gradient(to right, var(--primary-color), var(--secondary-color));

      border-radius: 2px;

    }



    label {

      font-weight: 600;

      margin-bottom: 6px;

      display: block;

    }



    select, input {

      width: 100%;

      padding: 12px;

      border-radius: 8px;

      border: 1px solid #ccc;

      background-color: #f8fafc;

      font-size: 16px;

      margin-bottom: 15px;

      transition: border 0.3s ease;

    }



    select:focus, input:focus {

      border-color: var(--primary-color);

      outline: none;

      background-color: #fff;

      box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.2);

    }



    .sector-selector {

      margin-bottom: 25px;

    }



    .client-list {

      border: 1px solid #ddd;

      border-radius: 8px;

      min-height: 200px;

      padding: 10px;

      background: #f9f9f9;

    }



    .client-item {

      background: white;

      padding: 12px;

      margin-bottom: 10px;

      border-radius: 8px;

      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);

      display: flex;

      justify-content: space-between;

      align-items: center;

    }



    .client-info {

      flex-grow: 1;

    }



    .service-number {

      font-weight: bold;

      color: #4a5568;

    }



    .client-actions {

      display: flex;

      gap: 8px;

    }



    .client-actions button {

      border: none;

      padding: 6px 10px;

      font-size: 14px;

      border-radius: 6px;

      cursor: pointer;

    }



    .move-btn {

      background-color: #edf2f7;

      color: #2d3748;

    }



    .move-btn:hover {

      background-color: #e2e8f0;

    }



    .btn-edit {

      background-color: var(--primary-color);

      color: white;

    }



    .btn-secondary {

      background-color: var(--danger-color);

      color: white;

    }



    .btn-primary {

      background: linear-gradient(120deg, var(--secondary-color), #388e3c);

      color: white;

      border: none;

      padding: 10px 20px;

      border-radius: 8px;

      cursor: pointer;

      font-weight: 600;

      font-size: 16px;

      transition: all 0.3s ease;

    }



    .btn-primary:hover {

      transform: translateY(-2px);

      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

    }



    .buttons {

      display: flex;

      justify-content: flex-end;

      gap: 15px;

      margin-top: 20px;

    }



    /* MODAL */

    .modal {

      display: none;

      position: fixed;

      z-index: 999;

      left: 0;

      top: 0;

      width: 100%;

      height: 100%;

      background-color: rgba(0, 0, 0, 0.75);

      justify-content: center;

      align-items: center;

    }



    .modal-content {

      background-color: white;

      padding: 30px;

      border-radius: 12px;

      max-width: 550px;

      width: 90%;

      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);

      position: relative;

    }



    .modal-content h2 {

      color: var(--primary-color);

      margin-bottom: 20px;

      font-size: 1.6rem;

    }



    .close {

      position: absolute;

      right: 20px;

      top: 20px;

      font-size: 24px;

      color: #aaa;

      cursor: pointer;

      transition: color 0.3s;

    }



    .close:hover {

      color: black;

    }



    .modal-buttons {

      display: flex;

      justify-content: flex-end;

      gap: 10px;

      margin-top: 10px;

    }



    /* Nuevo modal para rutas */

    .modal-content-large {

      max-width: 90%;

      width: 90%;

      max-height: 90vh;

      overflow: auto;

    }



    .routes-header {

      display: flex;

      justify-content: space-between;

      align-items: center;

      margin-bottom: 20px;

    }



    .export-btn {

      background: linear-gradient(120deg, #2c5282, #4a6fa9);

      color: white;

      border: none;

      padding: 10px 20px;

      border-radius: 8px;

      cursor: pointer;

      font-weight: 600;

      font-size: 16px;

      transition: all 0.3s ease;

    }



    .export-btn:hover {

      background: linear-gradient(120deg, #4a6fa9, #2c5282);

      transform: translateY(-2px);

      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

    }



    .routes-table {

      width: 100%;

      border-collapse: collapse;

      margin-top: 20px;

      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);

    }



    .routes-table th {

      background: linear-gradient(to right, var(--primary-color), #3a6ca8);

      color: white;

      text-align: left;

      padding: 15px;

      position: sticky;

      top: 0;

    }



    .routes-table td {

      padding: 12px 15px;

      border-bottom: 1px solid #eee;

    }



    .routes-table tr:nth-child(even) {

      background-color: #f8f9fa;

    }



    .routes-table tr:hover {

      background-color: #e9f7ff;

    }



    .sector-header {

      background-color: #2c5282;

      color: white;

      font-weight: bold;

      font-size: 1.1rem;

    }



    @media (max-width: 768px) {

      .client-item {

        flex-direction: column;

        align-items: flex-start;

      }



      .client-actions {

        margin-top: 10px;

      }



      .buttons {

        flex-direction: column;

        align-items: stretch;

      }



      .buttons button {

        width: 100%;

      }



      .routes-table {

        font-size: 14px;

      }



      .routes-table th,

      .routes-table td {

        padding: 8px 10px;

      }

    }

  </style>
  <div class="container">

    <h1>Configurar Rutas</h1>



    <div class="sector-selector">

      <label for="sector">Seleccionar Sector:</label>

      <select id="sector">

        <option value="">-- Seleccione un sector --</option>

        @if(isset($locations) && $locations instanceof \Illuminate\Support\Collection && $locations->isNotEmpty())

          @foreach($locations as $location)

            <option value="{{ $location->id }}">{{ $location->name }}</option>

          @endforeach

        @endif

      </select>

    </div>



    <div id="client-container">
      <h3>Clientes en este sector</h3>
      <table id="clientes-sector-table" class="table">
        <thead>
          <tr>
            <th>Location ID</th>
            <th>Service ID</th>
            <th>Member ID</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($clientes) && is_iterable($clientes))
            @foreach($clientes as $cliente)
              <tr>
                <td>{{ $cliente->location_id ?? '-' }}</td>
                <td>{{ $cliente->service_id ?? '-' }}</td>
                <td>{{ $cliente->member_id ?? '-' }}</td>
              </tr>
            @endforeach
          @else
            <tr><td colspan="3" style="text-align:center;color:#888;">No hay datos de clientes</td></tr>
          @endif
        </tbody>
      </table>
      <div class="buttons">
        <button id="add-client-btn" class="btn-primary">Agregar Cliente</button>
        <button id="save-btn" class="btn-primary">Guardar Cambios</button>
        <button id="view-routes-btn" class="btn-primary">Ver Rutas</button>
      </div>
    </div>

  </div>



  <!-- Modal para clientes -->

  <div id="client-modal" class="modal">

    <div class="modal-content">

      <span class="close">&times;</span>

      <h2 id="modal-title">Agregar Cliente</h2>

      <form id="client-form" class="modal-form">

        <input type="hidden" id="client-id">

        <label for="client-name">Nombre del Cliente:</label>

        <input type="text" id="client-name" required>

        <label for="client-service">N° Servicio:</label>

        <input type="text" id="client-service" required>

        <label for="client-phone">Teléfono:</label>

        <input type="text" id="client-phone">

        <div class="modal-buttons">

          <button type="button" id="cancel-btn" class="btn-secondary">Cancelar</button>

          <button type="submit" id="save-client-btn" class="btn-primary">Guardar</button>

        </div>

      </form>

    </div>

  </div>



  <!-- Nuevo modal para ver rutas -->

  <div id="routes-modal" class="modal">

    <div class="modal-content modal-content_large">

      <span class="close">&times;</span>

      <div class="routes-header">

        <h2>Rutas Completas</h2>

        <button id="export-btn" class="export-btn">Exportar a Excel</button>

      </div>

      <div id="routes-table-container">

        <table class="routes-table">

          <thead>

            <tr>

              <th>Sector</th>

              <th>Nombre del Cliente</th>

              <th>N° Servicio</th>

              <th>Teléfono</th>

              <th>Orden</th>

            </tr>

          </thead>

          <tbody id="routes-table-body">

            <!-- Datos se llenarán dinámicamente -->

          </tbody>

        </table>

      </div>

    </div>

  </div>



  <script>

    // Datos iniciales

    const clientsData = {

      callejon: [

        { id: 1, name: "Juan Pérez", service: "SERV-001", phone: "555-1001", order: 1 },

        { id: 2, name: "María González", service: "SERV-002", phone: "555-1002", order: 2 },

        { id: 3, name: "Carlos López", service: "SERV-003", phone: "555-1003", order: 3 }

      ],

      ruta: [

        { id: 4, name: "Ana Martínez", service: "SERV-004", phone: "555-2001", order: 1 },

        { id: 5, name: "Pedro Sánchez", service: "SERV-005", phone: "555-2002", order: 2 },

        { id: 6, name: "Laura Ramírez", service: "SERV-006", phone: "555-2003", order: 3 }

      ],

      villa: [

        { id: 7, name: "José García", service: "SERV-007", phone: "555-3001", order: 1 },

        { id: 8, name: "Sofía Hernández", service: "SERV-008", phone: "555-3002", order: 2 },

        { id: 9, name: "Miguel Díaz", service: "SERV-009", phone: "555-3003", order: 3 }

      ]

    };



    let currentSector = '';

    let currentClientId = 0;

    let isEditing = false;



    // Referencias a elementos del DOM

    const sectorSelect = document.getElementById('sector');

    const clientContainer = document.getElementById('client-container');

    const clientList = document.getElementById('client-list');

    const addClientBtn = document.getElementById('add-client-btn');

    const saveBtn = document.getElementById('save-btn');

    const viewRoutesBtn = document.getElementById('view-routes-btn');

    const clientModal = document.getElementById('client-modal');

    const routesModal = document.getElementById('routes-modal');

    const modalTitle = document.getElementById('modal-title');

    const clientForm = document.getElementById('client-form');

    const clientIdInput = document.getElementById('client-id');

    const clientNameInput = document.getElementById('client-name');

    const clientServiceInput = document.getElementById('client-service');

    const clientPhoneInput = document.getElementById('client-phone');

    const cancelBtn = document.getElementById('cancel-btn');

    const closeBtns = document.querySelectorAll('.close');

    const routesTableBody = document.getElementById('routes-table-body');

    const exportBtn = document.getElementById('export-btn');



    // Event listeners

    sectorSelect.addEventListener('change', loadSectorClients);

    addClientBtn.addEventListener('click', () => openModal(false));

    saveBtn.addEventListener('click', saveChanges);

    viewRoutesBtn.addEventListener('click', viewRoutes);

    clientForm.addEventListener('submit', handleClientSubmit);

    cancelBtn.addEventListener('click', closeModal);

    exportBtn.addEventListener('click', exportToExcel);



    // Cerrar modales

    closeBtns.forEach(btn => {

      btn.addEventListener('click', function() {

        clientModal.style.display = 'none';

        routesModal.style.display = 'none';

      });

    });



    // Cargar clientes del sector seleccionado

    function loadSectorClients() {

      currentSector = sectorSelect.value;

      if (!currentSector) {

        clientContainer.style.display = 'none';

        return;

      }



      clientContainer.style.display = 'block';

      clientList.innerHTML = '';



      const sortedClients = [...clientsData[currentSector]].sort((a, b) => a.order - b.order);



      sortedClients.forEach((client, index) => {

        const item = document.createElement('div');

        item.className = 'client-item';

        item.setAttribute('data-id', client.id);

        item.innerHTML = `

          <div class="client-info">

            <strong>${client.name}</strong><br>

            <span class="service-number">${client.service}</span>

          </div>

          <div class="client-actions">

            <button class="move-btn up-btn" ${index === 0 ? 'disabled' : ''}>↑</button>

            <button class="move-btn down-btn" ${index === sortedClients.length - 1 ? 'disabled' : ''}>↓</button>

            <button class="btn-edit edit-btn">Editar</button>

            <button class="btn-secondary delete-btn">Eliminar</button>

          </div>

        `;



        item.querySelector('.edit-btn').addEventListener('click', () => openModal(true, client.id));

        item.querySelector('.delete-btn').addEventListener('click', () => deleteClient(client.id));

        item.querySelector('.up-btn').addEventListener('click', () => moveClient(client.id, 'up'));

        item.querySelector('.down-btn').addEventListener('click', () => moveClient(client.id, 'down'));



        clientList.appendChild(item);

      });

    }



    // Mover cliente arriba/abajo

    function moveClient(id, dir) {

      const list = clientsData[currentSector];

      const idx = list.findIndex(c => c.id === id);

      if (dir === 'up' && idx > 0) {

        [list[idx].order, list[idx - 1].order] = [list[idx - 1].order, list[idx].order];

      } else if (dir === 'down' && idx < list.length - 1) {

        [list[idx].order, list[idx + 1].order] = [list[idx + 1].order, list[idx].order];

      }

      list.sort((a, b) => a.order - b.order);

      loadSectorClients();

    }



    // Abrir modal para agregar/editar cliente

    function openModal(editing, id = null) {

      isEditing = editing;

      if (editing) {

        modalTitle.textContent = 'Editar Cliente';

        const client = clientsData[currentSector].find(c => c.id === id);

        clientIdInput.value = client.id;

        clientNameInput.value = client.name;

        clientServiceInput.value = client.service;

        clientPhoneInput.value = client.phone;

        currentClientId = client.id;

      } else {

        modalTitle.textContent = 'Agregar Cliente';

        clientForm.reset();

        currentClientId = 0;

      }

      clientModal.style.display = 'flex';

    }



    // Cerrar modal

    function closeModal() {

      clientModal.style.display = 'none';

    }



    // Manejar envío del formulario de cliente

    function handleClientSubmit(e) {

      e.preventDefault();

      const data = {

        id: isEditing ? currentClientId : Date.now(),

        name: clientNameInput.value,

        service: clientServiceInput.value,

        phone: clientPhoneInput.value,

        order: isEditing ? clientsData[currentSector].find(c => c.id === currentClientId).order : clientsData[currentSector].length + 1

      };

      if (isEditing) {

        const idx = clientsData[currentSector].findIndex(c => c.id === currentClientId);

        clientsData[currentSector][idx] = data;

      } else {

        clientsData[currentSector].push(data);

      }

      closeModal();

      loadSectorClients();

    }



    // Eliminar cliente

    function deleteClient(id) {

      if (confirm('¿Estás seguro de eliminar este cliente?')) {

        clientsData[currentSector] = clientsData[currentSector].filter(c => c.id !== id);

        clientsData[currentSector].forEach((c, i) => c.order = i + 1);

        loadSectorClients();

      }

    }



    // Guardar cambios

    function saveChanges() {

      // En una implementación real, aquí se enviarían los cambios al servidor

      alert('Cambios guardados correctamente');

    }



    // Ver todas las rutas

    function viewRoutes() {

      // Limpiar la tabla

      routesTableBody.innerHTML = '';



      // Obtener nombres de sectores

      const sectorNames = {

        callejon: 'Callejón La Copa',

        ruta: 'Ruta J40',

        villa: 'Villa Navidad'

      };



      // Llenar la tabla con todos los datos

      Object.entries(clientsData).forEach(([sectorKey, clients]) => {

        // Ordenar clientes por orden

        const sortedClients = [...clients].sort((a, b) => a.order - b.order);



        sortedClients.forEach((client, index) => {

          const row = document.createElement('tr');



          // Si es el primer cliente del sector, agregar fila de encabezado

          if (index === 0) {

            const headerRow = document.createElement('tr');

            headerRow.innerHTML = `

              <td colspan="5" class="sector-header">${sectorNames[sectorKey]}</td>

            `;

            routesTableBody.appendChild(headerRow);

          }



          row.innerHTML = `

            <td>${sectorNames[sectorKey]}</td>

            <td>${client.name}</td>

            <td>${client.service}</td>

            <td>${client.phone}</td>

            <td>${client.order}</td>

          `;

          routesTableBody.appendChild(row);

        });

      });



      // Mostrar el modal

      routesModal.style.display = 'flex';

    }



    // Exportar a Excel

    function exportToExcel() {

      // Crear contenido CSV

      let csvContent = "data:text/csv;charset=utf-8,"

        + "Sector,Nombre,Servicio,Teléfono,Orden\n";



      // Obtener nombres de sectores

      const sectorNames = {

        callejon: 'Callejón La Copa',

        ruta: 'Ruta J40',

        villa: 'Villa Navidad'

      };



      // Agregar todos los datos

      Object.entries(clientsData).forEach(([sectorKey, clients]) => {

        const sortedClients = [...clients].sort((a, b) => a.order - b.order);



        sortedClients.forEach(client => {

          csvContent += `${sectorNames[sectorKey]},${client.name},${client.service},${client.phone},${client.order}\n`;

        });

      });



      // Crear enlace de descarga

      const encodedUri = encodeURI(csvContent);

      const link = document.createElement("a");

      link.setAttribute("href", encodedUri);

      link.setAttribute("download", "rutas_clientes.csv");

      document.body.appendChild(link);



      // Descargar archivo

      link.click();

      document.body.removeChild(link);

    }



    // Cerrar modales al hacer clic fuera

    window.addEventListener('click', (e) => {

      if (e.target === clientModal) closeModal();

      if (e.target === routesModal) routesModal.style.display = 'none';

    });



    const sectorSelect = document.getElementById('sector');
    const clientContainer = document.getElementById('client-container');
    const clientesTable = document.getElementById('clientes-sector-table');
    const clientesTbody = clientesTable.querySelector('tbody');
    const addClientBtn = document.getElementById('add-client-btn');
    const saveBtn = document.getElementById('save-btn');
    const viewRoutesBtn = document.getElementById('view-routes-btn');

    // Listener para el selector de sector
    if (sectorSelect) sectorSelect.onchange = async function() {
      const sectorId = sectorSelect.value;
      if (!sectorId) {
        clientesTbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#888;">No hay datos de clientes</td></tr>';
        return;
      }
      clientesTbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#888;">Cargando...</td></tr>';
      try {
        const res = await fetch(`/clientes-por-sector/${encodeURIComponent(sectorId)}`);
        if (!res.ok) throw new Error('No se pudieron cargar los miembros');
        const clientes = await res.json();
        clientesTbody.innerHTML = '';
        if (!Array.isArray(clientes) || clientes.length === 0) {
          clientesTbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#888;">No hay miembros en este sector</td></tr>';
          return;
        }
        clientes.forEach(c => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${c.location_id ?? '-'}</td>
            <td>${c.service_id ?? '-'}</td>
            <td>${c.member_id ?? '-'}</td>
          `;
          clientesTbody.appendChild(tr);
        });
      } catch (e) {
        clientesTbody.innerHTML = `<tr><td colspan="3" style="color:red;text-align:center;">${e.message}</td></tr>`;
      }
    };

    // Listener para los botones principales
    if (addClientBtn) addClientBtn.onclick = () => openModal(false);
    if (saveBtn) saveBtn.onclick = saveChanges;
    if (viewRoutesBtn) viewRoutesBtn.onclick = viewRoutes;

    // Mostrar mensaje por defecto al cargar la página
    window.addEventListener('DOMContentLoaded', function() {
      clientesTbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#888;">No hay datos de clientes</td></tr>';
    });
  </script>

</body>

</html>
@endsection
