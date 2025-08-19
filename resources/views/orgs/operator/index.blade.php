@extends('layouts.nice', ['active' => 'orgs.operator.index', 'title' => 'Libro de Caja Tabular'])




@section('content')
 <style>

        * {

            box-sizing: border-box;

            margin: 0;

            padding: 0;

        }

        body {

            font-family: 'Nunito', 'Segoe UI', Arial, sans-serif;

            background: linear-gradient(120deg, #eaf6ff 0%, #f8f9fa 100%);

            padding: 0;

            min-height: 100vh;

        }

        .reading-container {

            max-width: 900px;

            margin: 40px auto;

            background: #fff;

            border-radius: 18px;

            box-shadow: 0 8px 32px rgba(44,62,80,0.12);

            overflow: hidden;

            border: 1px solid #e3eaf3;

            position: relative;

        }

        .headerCard {

            background: linear-gradient(90deg, #3498db 0%, #6dd5fa 100%);

            color: #fff;

            padding: 38px 40px 28px 40px;

            text-align: center;

            border-bottom: 1px solid #e3eaf3;

        }

        .headerCard h1 {

            font-size: 2.2rem;

            font-weight: 700;

            margin-bottom: 8px;

            letter-spacing: 1px;

        }

        .headerCard p {

            opacity: 0.92;

            font-size: 1.1rem;

            font-weight: 400;

        }

        .content {

            padding: 38px 40px;

        }

        .form-group {

            margin-bottom: 22px;

        }

        .form-group label {

            display: block;

            margin-bottom: 7px;

            font-weight: 600;

            color: #34495e;

            font-size: 1rem;

            letter-spacing: 0.5px;

        }

        .form-group select, .form-group input {

            width: 100%;

            padding: 13px 16px;

            border: 1.5px solid #dbe7f6;

            border-radius: 10px;

            font-family: inherit;

            font-size: 1.05rem;

            background-color: #f4f8fb;

            transition: border-color 0.2s, box-shadow 0.2s;

        }

        .form-group select:focus, .form-group input:focus {

            border-color: #3498db;

            box-shadow: 0 0 0 2px #eaf6ff;

            outline: none;

            background-color: #fff;

        }

        .form-group select:disabled {

            background-color: #e9ecef;

            opacity: 0.7;

        }

        .btn {

            background: linear-gradient(90deg, #3498db 0%, #6dd5fa 100%);

            color: #fff;

            border: none;

            padding: 13px 28px;

            border-radius: 10px;

            cursor: pointer;

            font-size: 1.08rem;

            font-weight: 700;

            transition: box-shadow 0.2s, transform 0.2s, background 0.2s;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            box-shadow: 0 2px 8px rgba(44,62,80,0.08);

        }

        .btn:hover {

            background: linear-gradient(90deg, #2980b9 0%, #3498db 100%);

            box-shadow: 0 6px 18px rgba(44,62,80,0.13);

            transform: translateY(-2px) scale(1.03);

        }

        .btn:active {

            transform: scale(0.98);

        }

        .btn-outline {

            background: #fff;

            border: 2px solid #3498db;

            color: #3498db;

            font-weight: 700;

        }

        .btn-outline:hover {

            background: #eaf6ff;

            color: #2980b9;

        }

        .btn-group {

            display: flex;

            flex-wrap: wrap;

            margin-top: 25px;

            gap: 14px;

            justify-content: center;

        }

        #emailConfig {

            margin-top: 38px;

            padding: 28px 24px;

            background: linear-gradient(90deg, #f4f8fb 0%, #eaf6ff 100%);

            border-radius: 14px;

            border: 1.5px solid #dbe7f6;

            box-shadow: 0 2px 8px rgba(44,62,80,0.06);

        }

        .section-title {

            color: #2980b9;

            font-size: 1.35rem;

            margin-bottom: 18px;

            padding-bottom: 8px;

            border-bottom: 2px solid #eaf6ff;

            font-weight: 700;

            letter-spacing: 0.5px;

        }

        .reading-item {

            background: linear-gradient(90deg, #f4f8fb 0%, #eaf6ff 100%);

            padding: 18px 22px;

            margin: 13px 0;

            border-radius: 10px;

            border-left: 5px solid #3498db;

            box-shadow: 0 2px 8px rgba(44,62,80,0.07);

            position: relative;

            transition: box-shadow 0.2s;

        }

        .reading-item:hover {

            box-shadow: 0 6px 18px rgba(44,62,80,0.13);

        }

        .reading-item strong {

            color: #34495e;

            font-size: 1.12rem;

            display: block;

            margin-bottom: 7px;

        }

        .reading-item .delete-btn {

            position: absolute;

            top: 15px;

            right: 15px;

            background: linear-gradient(90deg, #e74c3c 0%, #ff7675 100%);

            color: #fff;

            border: none;

            width: 32px;

            height: 32px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            cursor: pointer;

            font-size: 1.2rem;

            box-shadow: 0 2px 8px rgba(231,76,60,0.12);

            transition: background 0.2s, box-shadow 0.2s;

        }

        .reading-item .delete-btn:hover {

            background: linear-gradient(90deg, #c0392b 0%, #e74c3c 100%);

            box-shadow: 0 6px 18px rgba(231,76,60,0.18);

        }

        #readingsList {

            margin-top: 38px;

        }

        .empty-state {

            text-align: center;

            padding: 38px 18px;

            color: #95a5a6;

            font-size: 1.08rem;

        }

        .empty-state i {

            font-size: 2.8rem;

            margin-bottom: 12px;

            opacity: 0.5;

        }

        .stats {

            display: flex;

            justify-content: space-between;

            background: linear-gradient(90deg, #eaf6ff 0%, #f4f8fb 100%);

            padding: 13px 18px;

            border-radius: 10px;

            margin-top: 18px;

            font-weight: 700;

            color: #34495e;

            font-size: 1.08rem;

            box-shadow: 0 2px 8px rgba(44,62,80,0.05);

        }

        @media (max-width: 768px) {

            .content {

                padding: 18px;

            }

            .headerCard {

                padding: 18px;

            }

            .headerCard h1 {

                font-size: 1.4rem;

            }

            .form-row {

                flex-direction: column;

                gap: 0;

            }

            .btn {

                width: 100%;

                margin-bottom: 10px;

            }

            #emailConfig {

                padding: 16px;

            }

        }

        @media (min-width: 768px) {

            .form-row {

                display: flex;

                gap: 22px;

            }

            .form-row .form-group {

                flex: 1;

            }

        }

        select {

            -webkit-appearance: none;

            -moz-appearance: none;

            appearance: none;

            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%233498db' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");

            background-repeat: no-repeat;

            background-position: right 18px center;

            background-size: 18px;

            padding-right: 44px;

        }

        .ios-fix {

            position: relative;

        }

        .ios-fix::after {

            content: "";

            position: absolute;

            top: 0;

            right: 0;

            bottom: 0;

            width: 44px;

            background: transparent;

            pointer-events: none;

        }

        .close-btn {

            position: absolute;

            top: 18px;

            right: 18px;

            background: linear-gradient(90deg, #e74c3c 0%, #ff7675 100%);

            color: #fff;

            border: none;

            width: 38px;

            height: 38px;

            border-radius: 50%;

            font-size: 1.6rem;

            font-weight: bold;

            cursor: pointer;

            box-shadow: 0 2px 8px rgba(231,76,60,0.12);

            z-index: 10;

            transition: background 0.2s, box-shadow 0.2s;

        }

        .close-btn:hover {

            background: linear-gradient(90deg, #c0392b 0%, #e74c3c 100%);

            box-shadow: 0 6px 18px rgba(231,76,60,0.18);

        }

    </style>

    <div class="reading-container">


        <div class="headerCard">

            <h1>Registro de Lecturas</h1>

            <p>Sistema para registrar lecturas de medidores</p>

        </div>



        <div class="content">

            <h2 class="section-title">Nueva Lectura <span id="mesAnioActual" style="font-size:16px; color:#3498db; background:#eaf6ff; border-radius:6px; padding:4px 10px; margin-left:10px;"></span></h2>

            <!-- Sectores -->

            <div class="form-row">

                <div class="form-group ios-fix">

                    <label for="sector">Sectores:</label>

                    <select id="sector" name="sector" class="form-control">

                        @if(isset($locations) && $locations->isNotEmpty())

                            <option value="">Seleccione un sector</option>

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



                <div class="form-group ios-fix">

                    <label for="service">Servicio:</label>

                    <select id="service" class="form-control" disabled>

                        <option value="">Primero seleccione un sector</option>

                    </select>

                </div>

            </div>



            <div class="form-group">

                <label for="reading">Lectura del medidor:</label>

                <input type="number" id="reading" class="form-control" placeholder="Ingrese la lectura">

            </div>



            <div class="btn-group">

                <button id="addReading" class="btn">✓ Agregar Lectura</button>

                <button id="clearAll" class="btn btn-outline">✕ Limpiar Todo</button>

            </div>



            <div id="readingsList">

                <h2 class="section-title">Lecturas Registradas</h2>

                <div id="readings"></div>



                <div class="stats">

                    <div>Total: <span id="totalReadings">0</span></div>

                    <div>Último: <span id="lastReading">-</span></div>

                </div>

                <div class="btn-group" style="margin-top:18px;">

                    <button id="saveToDB" class="btn">💾 Registra Lecturas</button>

                </div>

            </div>



            <div id="emailConfig">

                <h2 class="section-title">Enviar Lecturas por Correo</h2>

                <div class="form-group">

                    <label for="email">Correo destinatario:</label>

                    <input type="email" id="email" class="form-control" placeholder="correo@destino.com" required>

                </div>

                <div class="btn-group">

                    <button id="sendData" class="btn">✉️ Enviar Lecturas</button>

                </div>

            </div>

        </div>

    </div>




<!--
</body>

</html> -->
@endsection
@section('js')
   <script>

        // Botón cerrar para ocultar el registro de lecturas

        document.getElementById('closeReading').addEventListener('click', function() {

            document.querySelector('.reading-container').style.display = 'none';

        });

        // Función mejorada para guardar lecturas en la base de datos con actualización inmediata

        const saveToDBBtn = document.getElementById('saveToDB');

        saveToDBBtn.addEventListener('click', async () => {

            if (readings.length === 0) {

                alert('No hay lecturas para registrar en la base de datos');

                return;

            }

            // Confirmar acción antes de proceder

            if (!confirm(`¿Desea registrar ${readings.length} lectura(s) en la base de datos?`)) {

                return;

            }

            // Estado de carga inmediato

            saveToDBBtn.disabled = true;

            saveToDBBtn.innerHTML = '⏳ Registrando...';

            

            // Mostrar progreso visual

            const originalText = saveToDBBtn.innerHTML;

            let dots = 0;

            const progressInterval = setInterval(() => {

                dots = (dots + 1) % 4;

                saveToDBBtn.innerHTML = `⏳ Registrando${'.'.repeat(dots)}`;

            }, 500);

            try {

                console.log('Enviando lecturas a la base de datos:', readings);

                

                const response = await fetch('/guardar-lecturas', {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,

                        'Accept': 'application/json'

                    },

                    body: JSON.stringify({ lecturas: readings })

                });

                const data = await response.json();

                clearInterval(progressInterval);

                if (!response.ok) {

                    throw new Error(data.message || 'Error al guardar en la base de datos');

                }

                // Éxito: Actualización inmediata del sistema

                saveToDBBtn.innerHTML = '✅ ¡Registrado!';

                saveToDBBtn.style.background = 'linear-gradient(90deg, #27ae60 0%, #2ecc71 100%)';

                

                // Mostrar mensaje de éxito detallado

                const mensaje = `✅ ¡Éxito!\n\n${data.guardadas || readings.length} lectura(s) registrada(s) correctamente en la base de datos.\n\nFecha: ${new Date().toLocaleString()}\nPeriodo: ${readings[0]?.period || 'N/A'}\n\n🔄 Te redirigiremos a la página de lecturas para ver las boletas y totales actualizados...`;

                alert(mensaje);

                

                // Log detallado para debugging

                console.log('Respuesta del servidor:', data);

                console.log('Lecturas guardadas exitosamente:', data.guardadas || readings.length);

                

                // Limpiar datos locales inmediatamente

                readings = [];

                localStorage.removeItem('medidorReadings');

                updateReadingsDisplay();

                

                // Resetear formulario

                document.getElementById('sector').value = '';

                document.getElementById('service').innerHTML = '<option value="">Primero seleccione un sector</option>';

                document.getElementById('service').disabled = true;

                document.getElementById('reading').value = '';

                

                // FUNCIONALIDAD CLAVE: Redirigir a la página de lecturas donde se muestran las boletas y totales actualizados

                // El operador es solo para registrar lecturas, las boletas y totales se ven en readings/index

                setTimeout(() => {

                    console.log('Redirigiendo a la página de lecturas para ver boletas y totales actualizados...');

                    window.location.href = "{{ route('orgs.readings.index', $orgId) }}";

                }, 2000); // 2 segundos para permitir que el usuario vea el mensaje de éxito

            } catch (error) {

                clearInterval(progressInterval);

                console.error('Error detallado al guardar:', error);

                

                // Error: Mostrar información detallada

                saveToDBBtn.innerHTML = '❌ Error';

                saveToDBBtn.style.background = 'linear-gradient(90deg, #e74c3c 0%, #ff7675 100%)';

                

                const errorMsg = `❌ Error al registrar lecturas\n\nDetalle: ${error.message}\n\nLas lecturas permanecen en el sistema local.\nPuede intentar nuevamente.`;

                alert(errorMsg);

                

                // Restaurar botón después de 5 segundos

                setTimeout(() => {

                    saveToDBBtn.innerHTML = '💾 Registra Lecturas';

                    saveToDBBtn.style.background = 'linear-gradient(90deg, #3498db 0%, #6dd5fa 100%)';

                    saveToDBBtn.disabled = false;

                }, 5000);

            }

        });

        // Mostrar mes y año actual en la etiqueta
        document.addEventListener('DOMContentLoaded', () => {
            const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            const fecha = new Date();
            const mes = meses[fecha.getMonth()];
            const anio = fecha.getFullYear();
            document.getElementById('mesAnioActual').textContent = `${mes} ${anio}`;
        });

        // Almacenamiento local
        let readings = JSON.parse(localStorage.getItem('medidorReadings')) || [];

        // Elementos DOM
        const sectorSelect = document.getElementById('sector');
        const serviceSelect = document.getElementById('service');
        const readingInput = document.getElementById('reading');
        const addReadingBtn = document.getElementById('addReading');
        const clearAllBtn = document.getElementById('clearAll');
        const readingsContainer = document.getElementById('readings');
        const emailInput = document.getElementById('email');
        const sendDataBtn = document.getElementById('sendData');
        const totalReadingsEl = document.getElementById('totalReadings');
        const lastReadingEl = document.getElementById('lastReading');

        // Cargar sectores
        sectorSelect.addEventListener('change', cargarServicios);
        updateReadingsDisplay();
        document.addEventListener('DOMContentLoaded', () => {
            if (sectorSelect.value) cargarServicios();
        });

        function resetSelectOnIOS() {
            if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
                serviceSelect.disabled = true;
                serviceSelect.disabled = false;
            }
        }

        // --- NUEVA FUNCIÓN: Cargar servicios dinámicamente desde el backend con orden de rutas ---
        async function cargarServicios() {
            const sectorId = sectorSelect.value;
            serviceSelect.innerHTML = '<option value="">Seleccione servicio</option>';
            if (sectorId) {
                serviceSelect.disabled = true;
                // Mostrar opción de carga
                const loadingOption = document.createElement('option');
                loadingOption.textContent = 'Cargando...';
                loadingOption.disabled = true;
                loadingOption.selected = true;
                serviceSelect.appendChild(loadingOption);
                try {
                    // Usar la nueva ruta que incluye orden de rutas
                    const response = await fetch(`/org/{{ $orgId }}/servicios-por-sector/${sectorId}`);
                    if (!response.ok) throw new Error('Error al obtener servicios');
                    const servicios = await response.json();
                    serviceSelect.innerHTML = '<option value="">Seleccione servicio</option>';
                    if (servicios.length === 0) {
                        const opt = document.createElement('option');
                        opt.textContent = 'No hay servicios en este sector';
                        opt.disabled = true;
                        serviceSelect.appendChild(opt);
                        serviceSelect.disabled = true;
                        return;
                    }
                    console.log('📋 Servicios cargados con orden de rutas:', servicios);
                    servicios.forEach(servicio => {
                        const option = document.createElement('option');
                        option.value = `${servicio.numero}|${servicio.rut}|${servicio.full_name}`;
                        // Mostrar indicador de orden si existe
                        const ordenIndicator = servicio.orden ? ` [📍${servicio.orden}]` : '';
                        option.textContent = `${String(servicio.numero).padStart(4, '0')} - ${servicio.full_name}${ordenIndicator}`;
                        serviceSelect.appendChild(option);
                    });
                    
                    // Mostrar mensaje informativo sobre el orden
                    const serviciosConOrden = servicios.filter(s => s.orden).length;
                    const serviciosSinOrden = servicios.length - serviciosConOrden;
                    if (serviciosConOrden > 0) {
                        console.log(`🎯 ${serviciosConOrden} servicios ordenados según rutas, ${serviciosSinOrden} sin orden específico`);
                    }
                    
                    resetSelectOnIOS();
                    serviceSelect.disabled = false;
                } catch (e) {
                    serviceSelect.innerHTML = '<option value="">Error al cargar servicios</option>';
                    serviceSelect.disabled = true;
                }
            } else {
                serviceSelect.disabled = true;
            }
        }



        addReadingBtn.addEventListener('click', () => {
            const serviceData = serviceSelect.value.split('|');
            const reading = readingInput.value;
            if (!serviceSelect.value || !reading) {
                alert('Por favor complete todos los campos');
                return;
            }
            // Generar el periodo actual en formato YYYY-MM
            const fecha = new Date();
            const period = `${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}`;
            const newReading = {
                numero: serviceData[0],
                cliente: serviceData[2],
                rut: serviceData[1],
                sector: sectorSelect.options[sectorSelect.selectedIndex].text,
                lectura: reading,
                period: period, // <-- Campo requerido por backend
                timestamp: new Date().toLocaleString()
            };
            readings.push(newReading);
            localStorage.setItem('medidorReadings', JSON.stringify(readings));
            updateReadingsDisplay();
            readingInput.value = '';
            readingInput.focus();
        });



        clearAllBtn.addEventListener('click', () => {

            if (confirm('¿Está seguro de que desea eliminar todas las lecturas?')) {

                readings = [];

                localStorage.removeItem('medidorReadings');

                updateReadingsDisplay();

            }

        });



        function updateReadingsDisplay() {

            if (readings.length === 0) {

                readingsContainer.innerHTML = `

                    <div class="empty-state">

                        <div>📋</div>

                        <h3>No hay lecturas registradas</h3>

                        <p>Agregue lecturas usando el formulario superior</p>

                    </div>

                `;

            } else {

                readingsContainer.innerHTML = readings.map((reading, i) => `

                    <div class="reading-item">

                        <button class="delete-btn" data-index="${i}">×</button>

                        <strong>Servicio ${reading.numero.padStart(4, '0')}</strong>

                        Sector: ${reading.sector}<br>

                        Cliente: ${reading.cliente}<br>

                        RUT: ${reading.rut}<br>

                        Lectura: <strong>${reading.lectura}</strong><br>

                        Fecha: ${reading.timestamp}

                    </div>

                `).join('');



                document.querySelectorAll('.delete-btn').forEach(btn => {

                    btn.addEventListener('click', function() {

                        const index = parseInt(this.getAttribute('data-index'));

                        readings.splice(index, 1);

                        localStorage.setItem('medidorReadings', JSON.stringify(readings));

                        updateReadingsDisplay();

                    });

                });

            }



            totalReadingsEl.textContent = readings.length;

            lastReadingEl.textContent = readings.length ?

                readings[readings.length - 1].timestamp.split(',')[0] : '-';

        }



        sendDataBtn.addEventListener('click', async () => {

            const email = emailInput.value.trim();



            // Validación básica de email

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {

                alert('Por favor ingrese un correo electrónico válido');

                return;

            }

            if (readings.length === 0) {

                alert('No hay lecturas registradas para enviar');

                return;

            }

            // Deshabilitar botón durante el envío

            sendDataBtn.disabled = true;

            sendDataBtn.innerHTML = '<span class="spinner"></span> Enviando...';

            try {

                // --- INICIO: Preparación para envío de Excel por correo ---

                // Cuando el backend esté listo, generar un archivo Excel (.xlsx) con todos los datos de 'readings'.

                // El backend debe recibir el array 'lecturas' y crear el archivo Excel adjunto al correo.

                // Ejemplo de estructura enviada:

                // [

                //   {

                //     numero: '00002',

                //     cliente: 'SANZON XIMENA DREYSE RAMIREZ',

                //     rut: '5541271-5',

                //     sector: 'Callejón La copa',

                //     lectura: '1234',

                //     timestamp: '10/07/2025, 12:34:56'

                //   },

                //   ...

                // ]

                // El backend debe generar el Excel y adjuntarlo al correo enviado a 'email'.

                // --- FIN: Preparación para envío de Excel por correo ---

                const response = await fetch('/enviar-lecturas', {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,

                        'Accept': 'application/json'

                    },

                    body: JSON.stringify({

                        email: email,

                        asunto: 'Registro de Lecturas de Medidores',

                        lecturas: readings,

                        formato: 'excel' // Indica al backend que debe enviar el archivo en formato Excel

                    })

                });

                const data = await response.json();

                if (!response.ok) {

                    throw new Error(data.message || 'Error al enviar el correo');

                }

                alert(`Las lecturas se han enviado correctamente a ${email}`);

                // Limpiar después del envío exitoso

                readings = [];

                localStorage.removeItem('medidorReadings');

                updateReadingsDisplay();

                emailInput.value = '';

            } catch (error) {

                console.error('Error al enviar:', error);

                alert(`Error al enviar el correo: ${error.message}`);

            } finally {

                sendDataBtn.disabled = false;

                sendDataBtn.innerHTML = '✉️ Enviar Lecturas';

            }

        });

    </script>
@endsection
