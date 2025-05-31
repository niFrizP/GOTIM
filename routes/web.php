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

// Rutas de servicios
Route::get('/servicios/validar-nombre', [ServicioController::class, 'validarNombre']);
Route::resource('servicios', ServicioController::class);

// Ruta para la página de inicio de sesión
Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta para la página de inicio
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de autenticación
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Rutas de clientes
Route::resource('clientes', ClienteController::class);

// Ruta personalizada para reactivar clientes
Route::post('/clientes/{id}/reactivar', [ClienteController::class, 'reactivar'])->name('clientes.reactivar');

// Rutas de ciudades y regiones
Route::get('/cxr/{regionId}', [CiudadController::class, 'getCiudadesPorRegion']);

// Rutas de administración
Route::middleware(['auth', 'admin'])->group(function () {
    //Rutas Usuarios
    Route::resource('users', UserController::class);
    // Ruta personalizada para reactivar usuarios
    Route::post('/users/{user}/reactivar', [UserController::class, 'reactivar'])->name('users.reactivar');
});

// Rutas de estado de categorías
Route::post('/categorias/{id}/reactivar', [CategoriaController::class, 'reactivar'])->name('categorias.reactivar');
Route::delete('/categorias/{id}/desactivar', [CategoriaController::class, 'desactivar'])->name('categorias.desactivar');

// Ruta para validar el nombre de la categoría
Route::get('/categorias/validar-nombre', [CategoriaController::class, 'validarNombre']);

// Ruta para mostrar categorías inactivas
Route::get('/categorias/inactivas', [CategoriaController::class, 'inactivas'])->name('categorias.inactivas');

Route::resource('categorias', CategoriaController::class)->except(['destroy']);

//Ruta tipo_producto
Route::patch('tipo_productos/{id}/desactivar', [TipoProductoController::class, 'desactivar'])->name('tipo_productos.desactivar');
Route::patch('tipo_productos/{id}/activar', [TipoProductoController::class, 'activar'])->name('tipo_productos.activar');
Route::resource('tipo_productos', TipoProductoController::class)->except(['destroy']);

// Rutas de inventario
Route::get('/inventario/historial', [InventarioController::class, 'historial'])->name('inventario.historial');
Route::resource('inventario', InventarioController::class);
Route::get('/inventario/{id}/reactivar', [InventarioController::class, 'reactivar'])->name('inventario.reactivar');
Route::get('/inventario/{id}/desactivar', [InventarioController::class, 'desactivar'])->name('inventario.desactivar');
Route::get('/inventario/{id}/ver', [InventarioController::class, 'show'])->name('inventario.ver');
Route::get('/inventario/{id}/edit', [InventarioController::class, 'edit'])->name('inventario.edit');
Route::get('/inventario/{id}/eliminar', [InventarioController::class, 'eliminar'])->name('inventario.eliminar');

// Rutas de productos
Route::resource('productos', ProductoController::class);
// Ruta personalizada para reactivar Producto
Route::post('/productos/{id}/reactivar', [ProductoController::class, 'reactivar'])->name('productos.reactivar');

Route::get('/ot/exportar-ots', [OTController::class, 'exportarListadoOT'])->name('ots.exportar.pdf');
// Ruta para validar el código de producto
Route::get('/productos/validar-codigo', [ProductoController::class, 'validarCodigo'])->name('productos.validar.codigo');

// Rutas de OT
Route::middleware(['auth'])->group(function () {
    Route::get('ot/historial', [OTController::class, 'historialGeneral'])
        ->name('ot.historial.global');
    // CRUD menos destroy
    Route::resource('ot', OTController::class)->except(['destroy']);

    // Ruta para exportar OT a PDF
    Route::get('/ot/export/{id}', [OTController::class, 'exportOrdenes'])->name('ot.export');


    // Desactivar (inhabilitar) OT
    Route::post('ot/{id_ot}/desactivar', [OTController::class, 'desactivar'])
        ->name('ot.desactivar');

    // Reactivar OT
    Route::post('ot/{id_ot}/reactivar', [OTController::class, 'reactivar'])
        ->name('ot.reactivar');
    Route::get('ot/{ot}/historial', [OTController::class, 'historial'])
        ->name('ot.historial');

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});



require __DIR__ . '/auth.php';
