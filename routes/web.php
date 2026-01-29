<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EntidadesController;
use App\Http\Controllers\ContactosController;
use App\Http\Controllers\ViesController;
use App\Http\Controllers\ContactoFuncaoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaSettingController;
use App\Models\EmpresaSetting;
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
    return Inertia::render('Dashboard', [
        'empresa' => EmpresaSetting::first(),
    ]);
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

    // VIES
    Route::post('/vies/check', [ViesController::class, 'check'])
        ->name('vies.check');


    //entidades
        Route::get('/entidades', [EntidadesController::class, 'index'])
        ->name('entidades.index');

    Route::get('/entidades/create', [EntidadesController::class, 'create'])
        ->name('entidades.create');

    Route::post('/entidades', [EntidadesController::class, 'store'])
        ->name('entidades.store');

    Route::get('/entidades/{entidade}/edit', [EntidadesController::class, 'edit'])
        ->name('entidades.edit');

    Route::put('/entidades/{entidade}', [EntidadesController::class, 'update'])
        ->name('entidades.update');

    Route::delete('/entidades/{entidade}', [EntidadesController::class, 'destroy'])
        ->name('entidades.destroy');    

    // Contactos
    Route::get('/contactos', [ContactosController::class, 'index'])
        ->name('contactos.index');

    Route::get('/contactos/create', [ContactosController::class, 'create'])
        ->name('contactos.create');

    Route::post('/contactos', [ContactosController::class, 'store'])
        ->name('contactos.store');

    Route::get('/contactos/{contacto}/edit', [ContactosController::class, 'edit'])
        ->name('contactos.edit');

    Route::put('/contactos/{contacto}', [ContactosController::class, 'update'])
        ->name('contactos.update');

    Route::delete('/contactos/{contacto}', [ContactosController::class, 'destroy'])
        ->name('contactos.destroy');    

       //funcao contacto
       Route::prefix('configuracoes/contactos')->group(function () {

    Route::get('/funcoes', [ContactoFuncaoController::class, 'index'])
        ->name('contactos.funcoes.index');

    Route::get('/funcoes/create', [ContactoFuncaoController::class, 'create'])
        ->name('contactos.funcoes.create');

    Route::post('/funcoes', [ContactoFuncaoController::class, 'store'])
        ->name('contactos.funcoes.store');

    Route::get('/funcoes/{funcao}/edit', [ContactoFuncaoController::class, 'edit'])
        ->name('contactos.funcoes.edit');

    Route::put('/funcoes/{funcao}', [ContactoFuncaoController::class, 'update'])
        ->name('contactos.funcoes.update');

    Route::delete('/funcoes/{funcao}', [ContactoFuncaoController::class, 'destroy'])
        ->name('contactos.funcoes.destroy');
        }); 

        //empresa
    Route::get('/configuracoes/empresa', [EmpresaSettingController::class, 'edit'])
        ->name('empresa.edit');

    Route::post('/configuracoes/empresa', [EmpresaSettingController::class, 'update'])
        ->name('empresa.update');
});
