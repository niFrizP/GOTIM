<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CiudadController;

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


require __DIR__ . '/auth.php';
