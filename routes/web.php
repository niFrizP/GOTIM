<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    ClienteController,
    CiudadController,
    UserController,
    ServicioController,
    CategoriaController,
    TipoProductoController,
    InventarioController,
    ProductoController,
    OTController,
    DashboardController,
    EmpresaController
};
use App\Http\Middleware\{AdminMiddleware, SupervisorMiddleware, TecnicoMiddleware, AdminOrSupervisorMiddleware};
// Ruta inicial
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas autenticadas generales (todos los roles)
Route::middleware(['auth'])->group(function () {
    // Dashboard y Perfil
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Rutas para administrador, supervisor o técnico
Route::middleware(['auth', \App\Http\Middleware\AllUserMiddleware::class])->group(function () {
    // Clientes
    Route::resource('clientes', ClienteController::class);
    Route::post('/clientes/{id}/reactivar', [ClienteController::class, 'reactivar'])->name('clientes.reactivar');
    Route::get('/clientes/validar-rut', [ClienteController::class, 'validarRut'])->name('clientes.validar.rut');
    Route::get('/clientes/validar-email', [ClienteController::class, 'validarEmail'])->name('clientes.validar.email');
    Route::get('/clientes/{id}/validar-rut', [ClienteController::class, 'validarRutEditar'])->name('clientes.validar.rut.editar');
    Route::get('/clientes/{id}/validar-email', [ClienteController::class, 'validarEmailEditar'])->name('clientes.validar.email.editar');
    Route::get('/cxr/{id_region}', [CiudadController::class, 'getCiudadesPorRegion']);

    // Empresas
    Route::resource('empresas', EmpresaController::class);
    Route::post('/empresas/{id}/reactivar', [EmpresaController::class, 'reactivar'])->name('empresas.reactivar');
    Route::get('/empresas/comprobar/{rut}', [EmpresaController::class, 'ComprobarPorRut']);
    Route::get('/empresas/comprobar-nombre', [EmpresaController::class, 'comprobarNombre'])->name('empresas.comprobar.nombre');

    // OT
    Route::get('ot/historial', [OTController::class, 'historialGeneral'])->name('ot.historial.global');
    Route::resource('ot', OTController::class)->except(['destroy']);
    Route::get('/ot/export/{id}', [OTController::class, 'exportOrdenes'])->name('ot.export');
    Route::post('ot/{id_ot}/desactivar', [OTController::class, 'desactivar'])->name('ot.desactivar');
    Route::post('ot/{id_ot}/reactivar', [OTController::class, 'reactivar'])->name('ot.reactivar');
    Route::get('ot/{ot}/historial', [OTController::class, 'historial'])->name('ot.historial');
    Route::delete('/archivos-ot/{id}', [OTController::class, 'eliminarArchivo'])->name('archivos_ot.eliminar');
    Route::get('/ot/exportar-ots', [OTController::class, 'exportarListadoOT'])->name('ots.exportar.pdf');

});
// Rutas para administrador o supervisor
Route::middleware(['auth', \App\Http\Middleware\AdminOrSupervisorMiddleware::class])->group(function () {

    // Servicios
    Route::get('/servicios/validar-nombre', [ServicioController::class, 'validarNombre']);
    Route::resource('servicios', ServicioController::class);

    // Categorías
    Route::get('/categorias/validar-nombre', [CategoriaController::class, 'validarNombre']);
    Route::resource('categorias', CategoriaController::class)->except(['destroy']);
    Route::post('/categorias/{id}/reactivar', [CategoriaController::class, 'reactivar'])->name('categorias.reactivar');
    Route::delete('/categorias/{id}/desactivar', [CategoriaController::class, 'desactivar'])->name('categorias.desactivar');
    Route::get('/categorias/inactivas', [CategoriaController::class, 'inactivas'])->name('categorias.inactivas');

    // Tipo Producto
    Route::resource('tipo_productos', TipoProductoController::class)->except(['destroy']);
    Route::patch('tipo_productos/{id}/desactivar', [TipoProductoController::class, 'desactivar'])->name('tipo_productos.desactivar');
    Route::patch('tipo_productos/{id}/activar', [TipoProductoController::class, 'activar'])->name('tipo_productos.activar');

    // Inventario
    Route::get('/inventario/historial', [InventarioController::class, 'historial'])->name('inventario.historial');
    Route::resource('inventario', InventarioController::class);
    Route::get('/inventario/{id}/reactivar', [InventarioController::class, 'reactivar'])->name('inventario.reactivar');
    Route::get('/inventario/{id}/desactivar', [InventarioController::class, 'desactivar'])->name('inventario.desactivar');
    Route::get('/inventario/{id}/ver', [InventarioController::class, 'show'])->name('inventario.ver');
    Route::get('/inventario/{id}/edit', [InventarioController::class, 'edit'])->name('inventario.edit');
    Route::get('/inventario/{id}/eliminar', [InventarioController::class, 'eliminar'])->name('inventario.eliminar');

    // Productos
    Route::resource('productos', ProductoController::class);
    Route::post('/productos/{id}/reactivar', [ProductoController::class, 'reactivar'])->name('productos.reactivar');
    Route::get('/productos/create/validar-codigo', [ProductoController::class, 'validarCodigo'])->name('productos.validar.codigo');

});

// Solo administradores
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/reactivar', [UserController::class, 'reactivar'])->name('users.reactivar');
});

// Autenticación Laravel Breeze/Fortify
require __DIR__ . '/auth.php';
