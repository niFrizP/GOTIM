<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TipoProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\OTController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;

// Ruta para la página de inicio de sesión
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas públicas (si necesitás alguna, ponela acá)

// Rutas protegidas con autenticación
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Clientes
    Route::resource('clientes', ClienteController::class);
    Route::get('/clientes/validar-rut', [ClienteController::class, 'validarRut'])->name('clientes.validar.rut');
    Route::get('/clientes/validar-email', [ClienteController::class, 'validarEmail'])->name('clientes.validar.email');
    Route::get('/clientes/{id}/validar-rut', [ClienteController::class, 'validarRutEditar'])->name('clientes.validar.rut.editar');
    Route::get('/clientes/{id}/validar-email', [ClienteController::class, 'validarEmailEditar'])->name('clientes.validar.email.editar');
    Route::post('/clientes/{id}/reactivar', [ClienteController::class, 'reactivar'])->name('clientes.reactivar');
    Route::get('/cxr/{id_region}', [CiudadController::class, 'getCiudadesPorRegion']);

    // Empresas
    Route::resource('empresas', EmpresaController::class);
    Route::get('/empresas/comprobar/{rut}', [EmpresaController::class, 'ComprobarPorRut']);
    Route::get('/empresas/comprobar-rut/{rut}', [EmpresaController::class, 'ComprobarPorRut']);
    Route::post('/empresas/{id}/reactivar', [EmpresaController::class, 'reactivar'])->name('empresas.reactivar');
    Route::get('/empresas/comprobar-nombre', [EmpresaController::class, 'comprobarNombre'])->name('empresas.comprobar.nombre');

    // Servicios
    Route::get('/servicios/validar-nombre', [ServicioController::class, 'validarNombre']);
    Route::resource('servicios', ServicioController::class);

    // Categorías
    Route::post('/categorias/{id}/reactivar', [CategoriaController::class, 'reactivar'])->name('categorias.reactivar');
    Route::delete('/categorias/{id}/desactivar', [CategoriaController::class, 'desactivar'])->name('categorias.desactivar');
    Route::get('/categorias/validar-nombre', [CategoriaController::class, 'validarNombre']);
    Route::get('/categorias/inactivas', [CategoriaController::class, 'inactivas'])->name('categorias.inactivas');
    Route::resource('categorias', CategoriaController::class)->except(['destroy']);

    // Tipo Producto
    Route::patch('tipo_productos/{id}/desactivar', [TipoProductoController::class, 'desactivar'])->name('tipo_productos.desactivar');
    Route::patch('tipo_productos/{id}/activar', [TipoProductoController::class, 'activar'])->name('tipo_productos.activar');
    Route::resource('tipo_productos', TipoProductoController::class)->except(['destroy']);

    // Inventario
    Route::get('/inventario/historial', [InventarioController::class, 'historial'])->name('inventario.historial');
    Route::resource('inventario', InventarioController::class);
    Route::get('/inventario/{id}/reactivar', [InventarioController::class, 'reactivar'])->name('inventario.reactivar');
    Route::get('/inventario/{id}/desactivar', [InventarioController::class, 'desactivar'])->name('inventario.desactivar');
    Route::get('/inventario/{id}/ver', [InventarioController::class, 'show'])->name('inventario.ver');
    Route::get('/inventario/{id}/edit', [InventarioController::class, 'edit'])->name('inventario.edit');
    Route::get('/inventario/{id}/eliminar', [InventarioController::class, 'eliminar'])->name('inventario.eliminar');

    // Productos
    Route::get('/productos/create/validar-codigo', [ProductoController::class, 'validarCodigo'])->name('productos.validar.codigo');
    Route::resource('productos', ProductoController::class);
    Route::post('/productos/{id}/reactivar', [ProductoController::class, 'reactivar'])->name('productos.reactivar');

    // Órdenes de Trabajo (OT)
    Route::get('ot/historial', [OTController::class, 'historialGeneral'])->name('ot.historial.global');
    Route::resource('ot', OTController::class)->except(['destroy']);
    Route::get('/ot/export/{id}', [OTController::class, 'exportOrdenes'])->name('ot.export');
    Route::post('ot/{id_ot}/desactivar', [OTController::class, 'desactivar'])->name('ot.desactivar');
    Route::post('ot/{id_ot}/reactivar', [OTController::class, 'reactivar'])->name('ot.reactivar');
    Route::get('ot/{ot}/historial', [OTController::class, 'historial'])->name('ot.historial');
    Route::delete('/archivos-ot/{id}', [OTController::class, 'eliminarArchivo'])->name('archivos_ot.eliminar');
    Route::get('/ot/exportar-ots', [OTController::class, 'exportarListadoOT'])->name('ots.exportar.pdf');
});

// Rutas que solo el admin puede acceder (dentro de auth)
Route::middleware(['auth', 'admin'])->group(function () {
    // Usuarios
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/reactivar', [UserController::class, 'reactivar'])->name('users.reactivar');
});

// Autenticación (Login, Register, Password Reset)
require __DIR__ . '/auth.php';
