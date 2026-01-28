<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ViesController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Página pública
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| Rotas protegidas
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    Route::get('/home', function () {
    return redirect()->route('clientes.index');
});

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

   Route::get('/clientes', [ClientesController::class, 'index'])
        ->name('clientes.index');

    Route::get('/clientes/create', [ClientesController::class, 'create'])
        ->name('clientes.create');

    Route::post('/clientes', [ClientesController::class, 'store'])
        ->name('clientes.store');

    Route::get('/clientes/{cliente}/edit', [ClientesController::class, 'edit'])
        ->name('clientes.edit');

    Route::put('/clientes/{cliente}', [ClientesController::class, 'update'])
        ->name('clientes.update');

    Route::delete('/clientes/{cliente}', [ClientesController::class, 'destroy'])
        ->name('clientes.destroy');

    // VIES
    Route::post('/vies/check', [ViesController::class, 'check'])
        ->name('vies.check');
});
