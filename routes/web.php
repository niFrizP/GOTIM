<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Rutas de clientes
Route::resource('clientes', ClienteController::class);

// Ruta personalizada para reactivar clientes
Route::post('/clientes/{id}/reactivar', [ClienteController::class, 'reactivar'])->name('clientes.reactivar');


Route::get('/cxr/{regionId}', [CiudadController::class, 'getCiudadesPorRegion']);
Route::middleware(['auth', 'admin'])->group(function () {
    //Rutas Usuarios
    Route::resource('users', UserController::class);
    // Ruta personalizada para reactivar usuarios
    Route::post('/users/{user}/reactivar', [UserController::class, 'reactivar'])->name('users.reactivar');
});

require __DIR__ . '/auth.php';
