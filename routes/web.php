<?php
// Ruta para balance completo profesional
Route::get('orgs/contable/{id}/balance-completo', [App\Http\Controllers\Org\ContableController::class, 'balanceCompleto'])->name('orgs.contable.balanceCompleto');
// Exportar balance PDF, Excel, CSV y extracto bancario
Route::get('orgs/contable/{id}/exportar-pdf', [App\Http\Controllers\Org\ContableController::class, 'exportarPDF'])->name('orgs.contable.exportarPDF');
Route::get('orgs/contable/{id}/exportar-csv', [App\Http\Controllers\Org\ContableController::class, 'exportarCSV'])->name('orgs.contable.exportarCSV');
Route::get('orgs/contable/{id}/exportar-excel-balance', [App\Http\Controllers\Org\ContableController::class, 'exportarExcelBalance'])->name('orgs.contable.exportarExcelBalance');
Route::get('orgs/contable/{id}/descargar-extracto', [App\Http\Controllers\Org\ContableController::class, 'descargarExtracto'])->name('orgs.contable.descargarExtracto');
Route::get('orgs/contable/{id}/historial-cambios', [App\Http\Controllers\Org\ContableController::class, 'historialCambios'])->name('orgs.contable.historialCambios');
// Exportar a Excel para contabilidad de la organización
Route::get('orgs/contable/{id}/exportar-excel', [App\Http\Controllers\Org\ContableController::class, 'exportarExcel'])->name('orgs.contable.exportarExcel');

// Rutas resource para configuración inicial (incluye index, create, store, edit, update)
Route::resource('configuracion-inicial', App\Http\Controllers\ConfiguracionInicialController::class)->only([
    'index', 'store', 'edit', 'update'
]);

// Endpoint para guardar configuración de cuentas iniciales desde el modal (AJAX)
Route::post('/configuracion-cuentas-iniciales', [App\Http\Controllers\ConfiguracionInicialController::class, 'guardarDesdeModal']);
use App\Http\Controllers\PruebaController;
// Rutas mínimas para pruebas CRUD
Route::get('/prueba', [PruebaController::class, 'index'])->name('prueba.index');
Route::post('/prueba', [PruebaController::class, 'store'])->name('prueba.store');


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Org\LocationController;
use App\Http\Controllers\Org\ServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Org\ContableController;
use App\Http\Controllers\Org\MacroMedidorController;
use App\Http\Controllers\Org\ReadingController;
use App\Http\Controllers\Org\NotificationController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ConfiguracionInicialController;
Route::post('/sincronizar-saldos-iniciales', [ConfiguracionInicialController::class, 'sincronizarSaldosIniciales'])->name('sincronizar.saldos.iniciales');
use App\Http\Controllers\FKConstraintController;
use App\Http\Controllers\ConciliacionController;
use App\Http\Controllers\AuditoriaCuentaController;

// Rutas para FK Constraint Management (Issue #1451)
Route::prefix('fk-constraint')->name('fk-constraint.')->group(function() {
    Route::get('/', [FKConstraintController::class, 'index'])->name('index');
    Route::post('/check-dependencies', [FKConstraintController::class, 'checkDependencies'])->name('check-dependencies');
    Route::post('/safe-delete', [FKConstraintController::class, 'safeDelete'])->name('safe-delete');
    Route::post('/generate-script', [FKConstraintController::class, 'generateScript'])->name('generate-script');
    Route::post('/handle-delete', [FKConstraintController::class, 'handleDelete'])->name('handle-delete');
});

// Rutas para movimientos
Route::resource('movimientos', MovimientoController::class);

// Rutas para cuentas
Route::resource('cuentas', CuentaController::class);

// Rutas para categorías
Route::resource('categorias', CategoriaController::class);


// Rutas para conciliaciones
Route::resource('conciliaciones', ConciliacionController::class);

// Rutas para auditoría de cuentas (solo index y show)
Route::get('auditoria-cuentas', [AuditoriaCuentaController::class, 'index'])->name('auditoria-cuentas.index');
Route::get('auditoria-cuentas/{id}', [AuditoriaCuentaController::class, 'show'])->name('auditoria-cuentas.show');

// Endpoint AJAX para obtener clientes por sector (location_id)
// Route::get('/clientes-por-sector/{locationId}', [LocationController::class, 'clientesPorSector']); // Movida al grupo ajax
// Ruta para guardar lecturas masivas desde el frontend (AJAX)
Route::post('/guardar-lecturas', [ReadingController::class, 'store'])->name('guardar-lecturas');
// Endpoint para obtener servicios y miembros por sector (AJAX)
Route::get('/servicios-por-sector/{sectorId}', [ServiceController::class, 'porSector']);

// Endpoint para obtener la lista de sectores (usado por el panel demo)
Route::get('/sectores-list', [LocationController::class, 'sectoresList']);



Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('login.logout');



Auth::routes();



Route::get('/dashboard-mis-organizaciones', [DashboardController::class, 'misOrganizaciones'])->name('dashboardmisorganizaciones');

Route::get('/', function () {

    return view('welcome');

})->name('welcome');

Route::get('/quienes-somos', function () {

    return view('abouts');

})->name('abouts');

Route::get('/faq', function () {

    return view('faq');

})->name('faq');

Route::get('/precios', function () {

    return view('pricing');

})->name('pricing');

Route::get('/contacto', function () {

    return view('contact');

})->name('contact');



Route::get('/pago-cuenta', function () {

    return view('accounts.rutificador');

})->name('accounts.rutificador');



Route::get('/deudas/{rut}', [App\Http\Controllers\AccountController::class, 'debt'])->name('accounts.debt');



Route::get('/terminos-y-condiciones', function () {

    return view('termsofservice');

})->name('termsofservice');

Route::get('/politicas-de-privacidad', function () {

    return view('privacypolicy');

})->name('privacypolicy');

Route::get('/cuenta/acceso', function () {

    return view('account_login');

})->name('account.login');

Route::get('/cuenta/registro', function () {

    return view('account_register');

})->name('account.register');



Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout-save');

Route::PATCH('/checkout/{order_code}', [App\Http\Controllers\CheckoutController::class, 'update'])->name('checkout-update');

Route::PATCH('/checkout/{order_code}/editar-entrada', [App\Http\Controllers\CheckoutController::class, 'recovery_update'])->name('checkout-update-recovery');

Route::get('/checkout/{order_code}', [App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout-order-code');

Route::get('/resumen-orden/{order_code}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders-summary');



Route::get('/payment/{order_code}', [App\Http\Controllers\PaymentController::class, 'create_order_payment'])->name('Payment-order-code');

Route::get('/payment-response/{order_code}', [App\Http\Controllers\PaymentController::class, 'resume_order_payment'])->name('Payment-response');

Route::post('/payment-response/{order_code}', [App\Http\Controllers\PaymentController::class, 'resume_order_payment'])->name('Payment-response2');



Route::post('/cuenta/validar', [App\Http\Controllers\AccountController::class, 'access'])->name('account.access');

Route::get('/dashboard', [App\Http\Controllers\AccountController::class, 'dashboard'])->name('account.dashboard');

Route::get('/cuenta/perfil', [App\Http\Controllers\AccountController::class, 'profile'])->name('account.profile');



Route::get('/orgs/nuevo', [App\Http\Controllers\OrgController::class, 'create'])->name('orgs.create');

Route::get('/orgs', [App\Http\Controllers\OrgController::class, 'index'])->name('orgs.index');



Route::get('/suscripciones/nuevo', [App\Http\Controllers\SubscriptionController::class, 'create'])->name('subscriptions.create');

Route::get('/suscripciones', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscriptions.index');



Route::get('/paquetes/nuevo', [App\Http\Controllers\PackageController::class, 'create'])->name('packages.create');

Route::get('/paquetes', [App\Http\Controllers\PackageController::class, 'index'])->name('packages.index');

// Ruta para servicios por sector (fuera del grupo org) - con orden de rutas
Route::get('/org/{orgId}/servicios-por-sector/{sectorId}', [App\Http\Controllers\Org\OperatorController::class, 'getServicesBySectorWithOrder'])
    ->name('services.by.sector.ordered')
    ->where(['orgId' => '[0-9]+', 'sectorId' => '[0-9]+']);

// Ruta para servicios por sector (fuera del grupo org) - versión anterior sin orden
Route::get('/servicios-por-sector/{sectorId}', [App\Http\Controllers\Org\OperatorController::class, 'getServicesBySector'])
    ->name('services.by.sector')
    ->where(['sectorId' => '[0-9]+']);

// Rutas especiales para el módulo contable (completamente fuera de cualquier grupo)
Route::get('org/{id}/cuentas-iniciales', [App\Http\Controllers\Org\ContableController::class, 'cuentasIniciales'])->name('cuentas_iniciales.show');
Route::post('org/{id}/cuentas-iniciales', [App\Http\Controllers\Org\ContableController::class, 'store'])->name('cuentas_iniciales.store');
Route::get('org/{id}/registro-ingresos-egresos', [App\Http\Controllers\Org\ContableController::class, 'registroIngresosEgresos'])->name('registro_ingresos_egresos.show');
Route::get('{id}/libro-caja-crc', [App\Http\Controllers\Org\ContableController::class, 'libroCajaSoloVista'])
    ->name('libro.caja.crc')
    ->middleware('auth:crc');
Route::get('org/{id}/giros-depositos', [App\Http\Controllers\Org\ContableController::class, 'girosDepositos'])->name('giros_depositos.show');
Route::get('org/{id}/libro-caja-tabular', [App\Http\Controllers\Org\ContableController::class, 'libroCajaTabular'])->name('libro_caja_tabular.show');
Route::get('org/{id}/balance', [App\Http\Controllers\Org\ContableController::class, 'balance'])->name('balance.show');
Route::get('org/{id}/conciliacion-bancaria', [App\Http\Controllers\Org\ContableController::class, 'conciliacionBancaria'])->name('conciliacion_bancaria.show');
Route::get('org/{id}/informe-rubro', [App\Http\Controllers\Org\ContableController::class, 'informePorRubro'])->name('informe_rubro.show');
Route::get('org/{id}/movimientos', [App\Http\Controllers\Org\ContableController::class, 'movimientos'])->name('movimientos.show');
Route::get('org/{id}/sincronizar-cuentas', [App\Http\Controllers\Org\ContableController::class, 'sincronizarCuentas'])->name('sincronizar_cuentas.show');
Route::post('verificar-saldo-cuenta', [App\Http\Controllers\Org\ContableController::class, 'verificarSaldoCuenta'])->name('verificar_saldo_cuenta');
Route::post('procesar-ingreso', [App\Http\Controllers\Org\ContableController::class, 'procesarIngreso'])->name('procesar_ingreso');
Route::post('procesar-egreso', [App\Http\Controllers\Org\ContableController::class, 'procesarEgreso'])->name('procesar_egreso');
Route::post('procesar-giro', [App\Http\Controllers\Org\ContableController::class, 'procesarGiro'])->name('procesar_giro');
Route::post('procesar-deposito', [App\Http\Controllers\Org\ContableController::class, 'procesarDeposito'])->name('procesar_deposito');
Route::get('org/{id}/saldos-actuales', [App\Http\Controllers\Org\ContableController::class, 'obtenerSaldosActuales'])->name('obtener_saldos_actuales');

// API endpoints para datos dinámicos (eliminar hardcodeo)
Route::get('api/categorias', [App\Http\Controllers\Org\ContableController::class, 'getCategorias'])->name('api.categorias');
Route::get('api/bancos', [App\Http\Controllers\Org\ContableController::class, 'getBancos'])->name('api.bancos');

// Ruta AJAX para obtener servicios por sector
Route::get('org/{id}/ajax/clientes-por-sector/{locationId}', [App\Http\Controllers\Org\LocationController::class, 'clientesPorSector'])->name('ajax.clientes-por-sector');

// Ruta de prueba simple
Route::get('test-ajax', function() {
    return response()->json(['test' => 'OK', 'time' => now()]);
});

// Organizationes routes

Route::prefix('org')->name('orgs.')->group(function () {

    // Rutas para gestión contable
    Route::get('{id}/libro-caja', [ContableController::class, 'mostrarLibroCaja'])->name('libro.caja');
    Route::get('{id}/exportar-excel', [ContableController::class, 'exportarExcel'])->name('contable.exportarExcel');
    
    // Rutas para módulos contables específicos
    Route::get('{id}/contable/balance', [ContableController::class, 'balance'])->name('contable.balance');
    Route::get('{id}/contable/conciliacion-bancaria', [ContableController::class, 'conciliacionBancaria'])->name('contable.conciliacion.bancaria');
    Route::get('{id}/contable/movimientos', [ContableController::class, 'movimientos'])->name('contable.movimientos');
    Route::get('{id}/contable/informe-rubro', [ContableController::class, 'informePorRubro'])->name('contable.informe.rubro');
    Route::get('{id}/contable/registro-ingresos-egresos', [ContableController::class, 'registroIngresosEgresos'])->name('contable.registro.ingresos.egresos');
    Route::get('{id}/contable/cuentas-iniciales', [ContableController::class, 'cuentasIniciales'])->name('contable.cuentas.iniciales');
    Route::post('{id}/contable/cuentas-iniciales', [ContableController::class, 'guardarCuentasIniciales'])->name('contable.cuentas.iniciales.guardar');
    Route::get('{id}/contable/cuentas-iniciales-datos', [ContableController::class, 'obtenerDatosCuentasIniciales'])->name('contable.cuentas.iniciales.datos');
    Route::get('{id}/contable/cuentas-iniciales/{cuenta}', [ContableController::class, 'mostrarCuentaInicial'])->name('cuentas_iniciales.detalle');
    Route::get('{id}/contable/configuracion-cuentas', [ContableController::class, 'configuracionCuentas'])->name('contable.configuracion.cuentas');
    Route::get('{id}/contable/giros-depositos', [ContableController::class, 'girosDepositos'])->name('contable.giros.depositos');
    Route::get('{id}/contable/nice', [ContableController::class, 'nice'])->name('contable.nice');

    // Rutas para botones principales de acciones rápidas
    Route::get('{id}/ingresos', function($id) {
        return view('orgs.contable.ingresos', ['orgId' => $id]);
    })->name('ingresos.index');
    Route::get('{id}/egresos', function($id) {
        return view('orgs.contable.egresos', ['orgId' => $id]);
    })->name('egresos.index');



    Route::get('{id}/cuenta/crear-usuario', [App\Http\Controllers\AccountController::class, 'redirectCrearUsuario'])->name('accounts.crearUsuario');
    Route::post('{id}/cuenta/validacion-creacion-usuario', [App\Http\Controllers\AccountController::class, 'crearUsuario'])->name('validacionCreacionUsuario');


    Route::prefix('{id}/aguapotable')->name('aguapotable.')->group(function () {

        Route::get('macromedidor', [MacromedidorController::class, 'index'])->name('macromedidor.index');
        Route::post('/macromedidor/guardar', [MacromedidorController::class, 'guardar']);
        Route::get('/macromedidor/listar', [MacromedidorController::class, 'listar']);



    });



    Route::post('{id}/up_cargo_fijo', [App\Http\Controllers\OrgController::class, 'fixed_charge_store'])->name('fixed_charge_store');



    Route::get('{id}/dashboard', [App\Http\Controllers\OrgController::class, 'dashboard'])->name('dashboard');



    Route::get('{id}/socios', [App\Http\Controllers\Org\MemberController::class, 'index'])->name('members.index');

    Route::get('{id}/socios/nuevo', [App\Http\Controllers\Org\MemberController::class, 'create'])->name('members.create');



    Route::post('{id}/socios', [App\Http\Controllers\Org\MemberController::class, 'store'])->name('members.store');



    Route::get('{id}/socios/{member}/editar', [App\Http\Controllers\Org\MemberController::class, 'edit'])->name('members.edit');

    Route::put('{id}/socios/{memberId}', [App\Http\Controllers\Org\MemberController::class, 'update'])->name('members.update');

    Route::put('{id}/member/{memberId}/services/{serviceId}/transfer', [App\Http\Controllers\Org\MemberController::class, 'transferService'])->name('members.services.transfer');

    // Mostrar el formulario de edici贸n

    Route::get('{id}/member/{memberId}/services/{serviceId}/edit', [App\Http\Controllers\Org\MemberController::class, 'editService'])->name('members.services.edit');

    // Procesar el formulario (actualizar)

    Route::put('{id}/member/{memberId}/services/{serviceId}', [App\Http\Controllers\Org\MemberController::class, 'updateService'])->name('members.services.update');

    Route::put('{id}/member/{memberId}/services/{serviceId}/toggle', [App\Http\Controllers\Org\MemberController::class, 'toggleStatus'])->name('members.services.toggleStatus');

    //Export Excel

    Route::get('{id}/member/export', [App\Http\Controllers\Org\MemberController::class, 'export'])->name('members.export');



    Route::get('{id}/lecturas', [App\Http\Controllers\Org\ReadingController::class, 'index'])->name('readings.index');

    Route::get('{id}/lecturas/nuevo', [App\Http\Controllers\Org\ReadingController::class, 'create'])->name('readings.create');

    Route::get('{id}/lecturas/historial', [App\Http\Controllers\Org\ReadingController::class, 'history'])->name('readings.history');

    Route::post('{id}/lecturas/update', [App\Http\Controllers\Org\ReadingController::class, 'update'])->name('readings.update'); // Ruta para guardar la lectura
    Route::post('{id}/lecturas/mass-upload', [App\Http\Controllers\Org\ReadingController::class, 'massUpload'])->name('readings.mass_upload');

    // Ruta para DTE (Boleta o Factura)

    Route::get('{id}/lecturas/{readingId}/dte', [App\Http\Controllers\Org\ReadingController::class, 'dte'])->name('readings.dte');

    // Agregar estas dos rutas espec铆ficas para boleta y factura

    Route::get('{id}/lecturas/{readingId}/dte/boleta', [App\Http\Controllers\Org\ReadingController::class, 'dte'])->name('readings.boleta');

    Route::get('{id}/lecturas/{readingId}/dte/factura', [App\Http\Controllers\Org\ReadingController::class, 'dte'])->name('readings.factura');

    //Export Excel

    Route::get('{id}/lecturas/export', [App\Http\Controllers\Org\ReadingController::class, 'export'])->name('readings.export');

    Route::post('{id}/readings/update-current', [App\Http\Controllers\Org\ReadingController::class, 'current_reading_update'])->name('readings.current_reading_update');

    Route::get('{id}/lecturas/history/export', [App\Http\Controllers\Org\ReadingController::class, 'exportHistory'])->name('readings.history.export');



    Route::get('{id}/folios', [App\Http\Controllers\Org\FolioController::class, 'index'])->name('folios.index');

    Route::get('{id}/folios/nuevo', [App\Http\Controllers\Org\FolioController::class, 'create'])->name('folios.create');

    Route::post('{id}/folios/store', [App\Http\Controllers\Org\FolioController::class, 'store'])->name('folios.store');



    Route::get('{id}/tramos', [App\Http\Controllers\Org\SectionController::class, 'index'])->name('sections.index');

    Route::get('{id}/tramos/nuevo', [App\Http\Controllers\Org\SectionController::class, 'create'])->name('sections.create');

    Route::get('{id}/tramos/{tramoId}/editar', [App\Http\Controllers\Org\SectionController::class, 'edit'])->name('sections.edit');

    Route::put('{id}/tramos/{tramoId}', [App\Http\Controllers\Org\SectionController::class, 'update'])->name('sections.update');

    Route::delete('{id}/tramos/{tramoId}', [App\Http\Controllers\Org\SectionController::class, 'destroy'])->name('sections.destroy');

    Route::post('{id}/tramos/store-tier', [App\Http\Controllers\Org\SectionController::class, 'storeTier'])->name('sections.storeTier');                 // para tramos (tier_config)

    Route::post('{id}/tramos/store-fixed-cost', [App\Http\Controllers\Org\SectionController::class, 'storeFixedCost'])->name('sections.storeFixedCost'); // para fixed cost config




    //Export Excel
    Route::get('{id}/tramos/export', [App\Http\Controllers\Org\SectionController::class, 'export'])->name('sections.export');



    Route::get('{id}/servicios', [App\Http\Controllers\Org\ServiceController::class, 'index'])->name('services.index');

    Route::get('{id}/servicios/nuevo', [App\Http\Controllers\Org\ServiceController::class, 'create'])->name('services.create');

    Route::get('{id}/member/{memberId}/services/create', [App\Http\Controllers\Org\ServiceController::class, 'createForMember'])->name('services.createForMember');

    Route::post('{id}/member/{memberId}/services', [App\Http\Controllers\Org\ServiceController::class, 'storeForMember'])->name('services.store');

    //Export Excel

    Route::get('{id}/servicios/export', [App\Http\Controllers\Org\ServiceController::class, 'export'])->name('services.export');



    Route::get('{id}/orders', [App\Http\Controllers\Org\OrdersController::class, 'index'])->name('orders.index');

    Route::get('{id}/orders/{order_code}', [App\Http\Controllers\Org\OrdersController::class, 'show'])->name('orders.show');
    
    // Ruta temporal para debug
    Route::get('{id}/orders/{order_code}/debug', [App\Http\Controllers\Org\OrdersController::class, 'debug'])->name('orders.debug');

    Route::post('{id}/orders', [App\Http\Controllers\Org\OrdersController::class, 'store'])->name('orders.store');



    Route::get('{id}/voucher/{order_code}', [App\Http\Controllers\Org\DteController::class, 'create'])->name('voucher.create');

    Route::get('{id}/dte/{reading_id}', [App\Http\Controllers\Org\DteController::class, 'dte_create'])->name('dte.create');

    Route::get('{id}/dte-timbre/{reading_id}', [App\Http\Controllers\Org\DteController::class, 'dte_bell'])->name('dte.bell');



    Route::get('{id}/historialDTE', [App\Http\Controllers\Org\DteController::class, 'historyDTE'])->name('historyDTE');

    Route::get('{id}/historialDTE/export', [App\Http\Controllers\Org\DteController::class, 'exportDTE'])->name('exportDTE');

    Route::get('{id}/print-dtes', [App\Http\Controllers\Org\DteController::class, 'printAllDTE'])->name('printAllDTE');

    Route::get('{id}/historialDTE/{reading_id}/boleta', [ReadingController::class, 'multiBoletaPrint'])->name('multiBoletaPrint');

    Route::get('{id}/historialDTE/{reading_id}/factura', [ReadingController::class, 'multiBoletaPrint'])->name('multiFacturaPrint');



    Route::get('{id}/sectores', [App\Http\Controllers\Org\LocationController::class, 'index'])->name('locations.index');
    Route::get('{id}/panel-control-rutas', [App\Http\Controllers\Org\LocationController::class, 'panelControlRutas'])->name('locations.panelcontrolrutas');
    
    // Rutas para el control de orden de rutas
    Route::post('{id}/sectores/{locationId}/guardar-orden', [App\Http\Controllers\Org\LocationController::class, 'guardarOrdenRuta'])->name('locations.guardar-orden');
    Route::get('{id}/sectores/{locationId}/servicios-con-orden', [App\Http\Controllers\Org\LocationController::class, 'obtenerServiciosConOrden'])->name('locations.servicios-con-orden');
    
    Route::get('{id}/sectores/nuevo', [App\Http\Controllers\Org\LocationController::class, 'create'])->name('locations.create');
    Route::post('{id}/sectores', [App\Http\Controllers\Org\LocationController::class, 'store'])->name('locations.store');
    Route::get('{id}/sectores/{locationId}/editar', [App\Http\Controllers\Org\LocationController::class, 'edit'])->name('locations.edit');
    Route::put('{id}/sectores/{locationId}', [App\Http\Controllers\Org\LocationController::class, 'update'])->name('locations.update');
    Route::get('{id}/lecturas', [App\Http\Controllers\Org\ReadingController::class, 'index'])->name('readings.index');
    Route::get('{id}/lecturas/nuevo', [App\Http\Controllers\Org\ReadingController::class, 'create'])->name('readings.create');
    Route::post('{id}/lecturas/update', [App\Http\Controllers\Org\ReadingController::class, 'update'])->name('readings.update'); // Ruta para guardar la lectura
    Route::get('{id}/lecturas/{readingId}/dte', [App\Http\Controllers\Org\ReadingController::class, 'dte'])->name('readings.dte');
    Route::get('{id}/lecturas/export', [App\Http\Controllers\Org\ReadingController::class, 'export'])->name('readings.export');
    Route::get('{id}/servicios', [App\Http\Controllers\Org\ServiceController::class, 'index'])->name('services.index');
    Route::get('{id}/servicios/nuevo', [App\Http\Controllers\Org\ServiceController::class, 'create'])->name('services.create');
    Route::post('{id}/member/{memberId}/services', [App\Http\Controllers\Org\ServiceController::class, 'storeForMember'])->name('services.store');
    Route::get('{id}/servicios/export', [App\Http\Controllers\Org\ServiceController::class, 'export'])->name('services.export');
    Route::get('{id}/panel-control-rutas', [App\Http\Controllers\Org\LocationController::class, 'panelControlRutas'])->name('locations.panelcontrolrutas');

    Route::get('{id}/sectores/nuevo', [App\Http\Controllers\Org\LocationController::class, 'create'])->name('locations.create');



    Route::post('{id}/sectores', [App\Http\Controllers\Org\LocationController::class, 'store'])->name('locations.store');

    Route::get('{id}/sectores/{locationId}/editar', [App\Http\Controllers\Org\LocationController::class, 'edit'])->name('locations.edit');
    Route::put('{id}/sectores/{locationId}', [App\Http\Controllers\Org\LocationController::class, 'update'])->name('locations.update');

    //Route::delete('{id}/sectores/{locationId}', [App\Http\Controllers\Org\LocationController::class, 'destroy'])->name('locations.destroy');

    //Export Excel

    Route::get('{id}/sectores/export', [App\Http\Controllers\Org\LocationController::class, 'export'])->name('locations.export');



    Route::get('{id}/documentos', [App\Http\Controllers\Org\DocumentController::class, 'index'])->name('documents.index');

    Route::get('{id}/documentos/nuevo', [App\Http\Controllers\Org\DocumentController::class, 'create'])->name('documents.create');



    Route::get('{id}/inventarios', [App\Http\Controllers\Org\InventaryController::class, 'index'])->name('inventories.index');

    Route::get('{id}/inventarios/nuevo', [App\Http\Controllers\Org\InventaryController::class, 'create'])->name('inventories.create');

    Route::post('{id}/inventarios', [App\Http\Controllers\Org\InventaryController::class, 'store'])->name('inventories.store');

    Route::get('{id}/inventarios/{inventoryId}/editar', [App\Http\Controllers\Org\InventaryController::class, 'edit'])->name('inventories.edit');

    Route::put('{id}/inventarios/{inventoryId}', [App\Http\Controllers\Org\InventaryController::class, 'update'])->name('inventories.update');

    //Export Excel

    Route::get('{id}/inventarios/export', [App\Http\Controllers\Org\InventaryController::class, 'export'])->name('inventories.export');



    Route::get('{id}/inventarios/categories', [App\Http\Controllers\Org\InventoryCategoryController::class, 'index'])->name('categories.index');

    Route::get('{id}/inventarios/categories/create', [App\Http\Controllers\Org\InventoryCategoryController::class, 'create'])->name('categories.create');

    Route::post('{id}/inventarios/categories', [App\Http\Controllers\Org\InventoryCategoryController::class, 'store'])->name('categories.store');

    Route::get('{id}/inventarios/categories/{categoryId}/editar', [App\Http\Controllers\Org\InventoryCategoryController::class, 'edit'])->name('categories.edit');

    Route::put('{id}/inventarios/categories/{categoryId}', [App\Http\Controllers\Org\InventoryCategoryController::class, 'update'])->name('categories.update');

    Route::delete('{id}/inventarios/categories/{categoryId}', [App\Http\Controllers\Org\InventoryCategoryController::class, 'destroy'])->name('categories.destroy');

    //Export Excel

    Route::get('{id}/inventarios/categories/export', [App\Http\Controllers\Org\InventoryCategoryController::class, 'export'])->name('categories.export');



    Route::get('{id}/pagos', [App\Http\Controllers\Org\PaymentController::class, 'index'])->name('payments.index');

    Route::get('{id}/pagos/historial', [App\Http\Controllers\Org\PaymentController::class, 'history'])->name('payments.history');

    Route::get('{id}/pagos/servicios/{rut}', [App\Http\Controllers\Org\PaymentController::class, 'showServices'])->name('payments.services');

    Route::get('{id}/pagos/debug/{rut}', [App\Http\Controllers\Org\PaymentController::class, 'debugReadingStatus'])->name('payments.debug');

    Route::get('{id}/pagos/debug-values', [App\Http\Controllers\Org\PaymentController::class, 'debugValuesComparison'])->name('payments.debug.values');

    Route::get('{id}/pagos/debug-order/{orderCode}', [App\Http\Controllers\Org\PaymentController::class, 'debugOrderValues'])->name('payments.debug.order');

    Route::put('{id}/pagos/update/{reading_id}', [App\Http\Controllers\Org\PaymentController::class, 'update'])->name('payments.update');



    Route::get('{id}/pagos/nuevo', [App\Http\Controllers\Org\PaymentController::class, 'create'])->name('payments.create');

    Route::post('{id}/pagos/nuevo', [App\Http\Controllers\Org\PaymentController::class, 'store'])->name('orgs.payments.store');

    //Export Excel

    Route::get('{id}/pagos/export', [App\Http\Controllers\Org\PaymentController::class, 'export'])->name('payments.export');

    Route::get('{id}/pagos/history/export', [App\Http\Controllers\Org\PaymentController::class, 'exportHistory'])->name('payments.history.export');



    Route::get('{id}/notificaciones', [App\Http\Controllers\Org\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('{id}/notificaciones', [App\Http\Controllers\Org\NotificationController::class, 'store'])->name('notifications.store');



    Route::get('{id}/historyfolio/export', [App\Http\Controllers\Org\FolioController::class, 'export'])->name('historyfolio.export');



    Route::get('{id}/operator', [App\Http\Controllers\Org\OperatorController::class, 'index'])->name('operator.index');

    // Ruta para cargar miembros por sector
    Route::get('{id}/miembros-por-sector/{sectorId}', [App\Http\Controllers\Org\OperatorController::class, 'getMembersBySector'])
        ->name('operator.members.by.sector')
        ->where(['id' => '[0-9]+', 'sectorId' => '[0-9]+']);

    // Ruta de debug temporal
    Route::get('{id}/debug-miembros/{sectorId}', function($orgId, $sectorId) {
        \Log::info("Debug route accessed: orgId={$orgId}, sectorId={$sectorId}");
        
        // Verificar servicios directamente
        $servicios = \DB::table('services')
            ->join('members', 'services.member_id', '=', 'members.id')
            ->join('locations', 'services.locality_id', '=', 'locations.id_locations')
            ->where('services.locality_id', $sectorId)
            ->where('services.org_id', $orgId)
            ->select(
                'services.id as service_id',
                'services.nro as numero_servicio',
                'services.locality_id',
                'locations.name as sector',
                'members.rut',
                'members.first_name',
                'members.last_name'
            )
            ->get();
            
        return response()->json([
            'orgId' => $orgId,
            'sectorId' => $sectorId,
            'message' => 'Ruta funcionando',
            'timestamp' => now(),
            'url_called' => request()->fullUrl(),
            'servicios_encontrados' => $servicios->count(),
            'servicios' => $servicios,
            'query_info' => [
                'tabla_services' => \DB::table('services')->where('org_id', $orgId)->count() . ' servicios total en org',
                'tabla_services_sector' => \DB::table('services')->where('locality_id', $sectorId)->count() . ' servicios en sector',
                'tabla_locations' => \DB::table('locations')->where('org_id', $orgId)->count() . ' ubicaciones en org'
            ]
        ]);
    })->name('operator.debug.members')
      ->where(['id' => '[0-9]+', 'sectorId' => '[0-9]+']);



});



// Billings routes

Route::prefix('facturaciones')->name('billings.')->group(function () {



    Route::get('folios', [App\Http\Controllers\Billing\FolioController::class, 'index'])->name('folios.index');

    Route::get('folios/nuevo', [App\Http\Controllers\Billing\FolioController::class, 'create'])->name('folios.create');

    Route::post('folios/store', [App\Http\Controllers\Billing\FolioController::class, 'store'])->name('folios.store');

});



// Payments routes



// Kron routes

Route::prefix('kron')->name('kron.')->group(function () {



    Route::get('readings_charge_store', [App\Http\Controllers\KronController::class, 'readings_charge_store']);

});



// Ajax routes

Route::prefix('ajax')->name('ajax.')->group(function () {

    // Endpoint para obtener clientes por sector (location_id)
    Route::get('clientes-por-sector/{locationId}', [App\Http\Controllers\Org\LocationController::class, 'clientesPorSector']);

    Route::get('regiones', [App\Http\Controllers\AjaxController::class, 'state']);

    Route::get('{id}/comunas', [App\Http\Controllers\AjaxController::class, 'cities']);



    // Nueva ruta para comunas por regi贸n (sin provincia)

    Route::get('{stateId}/comunas-por-region', [App\Http\Controllers\AjaxController::class, 'citiesByState']);



    // Nueva ruta para verificar RUT

    Route::post('check-rut', [App\Http\Controllers\AjaxController::class, 'checkRut']);

});


// Rutas para lecturas de macromedidor (AJAX móvil)
Route::get('/org/{orgId}/aguapotable/macromedidor/listar', [App\Http\Controllers\MacromedidorReadingController::class, 'index']);
Route::post('/org/{orgId}/aguapotable/macromedidor/guardar', [App\Http\Controllers\MacromedidorReadingController::class, 'store']);


Route::get('pos-integrado', function () {

    return view('pos-integrate');

});

