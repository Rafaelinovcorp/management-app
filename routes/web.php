<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EntidadesController;
use App\Http\Controllers\ContactosController;
use App\Http\Controllers\IvaController;
use App\Http\Controllers\ViesController;
use App\Http\Controllers\ContactoFuncaoController;
use App\Http\Controllers\PropostasController;
use App\Http\Controllers\EncomendaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaSettingController;
use App\Http\Controllers\ArtigoController;
use App\Http\Controllers\FaturaFornecedorController;

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

    //artigos
    Route::get('/artigos', [ArtigoController::class, 'index'])
        ->name('artigos.index');
    Route::get('/artigos/create', [ArtigoController::class, 'create'])
        ->name('artigos.create');
    Route::post('/artigos', [ArtigoController::class, 'store'])
        ->name('artigos.store');
    Route::get('/artigos/{artigo}/edit', [ArtigoController::class, 'edit'])
        ->name('artigos.edit');
    Route::put('/artigos/{artigo}', [ArtigoController::class, 'update'])
        ->name('artigos.update');
    Route::delete('/artigos/{artigo}', [ArtigoController::class, 'destroy'])
        ->name('artigos.destroy');

    //IVa
    Route::get('/configuracoes/iva', [IvaController::class, 'index'])
      ->name('iva.index');

    Route::get('/configuracoes/iva/create', [IvaController::class, 'create'])
        ->name('iva.create');

    Route::post('/configuracoes/iva', [IvaController::class, 'store'])
        ->name('iva.store');

    Route::get('/configuracoes/iva/{iva}/edit', [IvaController::class, 'edit'])
        ->name('iva.edit');

    Route::put('/configuracoes/iva/{iva}', [IvaController::class, 'update'])
        ->name('iva.update');

    Route::delete('/configuracoes/iva/{iva}', [IvaController::class, 'destroy'])
        ->name('iva.destroy');

    //propostas
     Route::resource('propostas', PropostasController::class);

    Route::post('propostas/{id}/linhas', [PropostasController::class, 'addLinha'])
        ->name('propostas.linhas.add');

    Route::delete('propostas/linhas/{id}', [PropostasController::class, 'removeLinha'])
        ->name('propostas.linhas.remove');

    Route::post('propostas/{id}/fechar', [PropostasController::class, 'fechar'])
        ->name('propostas.fechar');

        
    //dowload
    Route::get('propostas/{id}/pdf', [PropostasController::class, 'pdf'])
        ->name('propostas.pdf');

    //converter para ecomenda
    Route::post(
    'propostas/{id}/converter-encomenda',
    [PropostasController::class, 'converterEmEncomenda']
        )->name('propostas.converter.encomenda');

    // Encomendas (Clientes)
    Route::get('/encomendas', [EncomendaController::class, 'index'])
        ->name('encomendas.index');
    
    Route::get('/encomendas/create', [EncomendaController::class, 'create'])
        ->name('encomendas.create');
    
    Route::post('/encomendas', [EncomendaController::class, 'store'])
        ->name('encomendas.store');
    
    Route::get('/encomendas/{encomenda}/edit', [EncomendaController::class, 'edit'])
        ->name('encomendas.edit');
    
    Route::put('/encomendas/{encomenda}', [EncomendaController::class, 'update'])
        ->name('encomendas.update');
    
    Route::delete('/encomendas/{encomenda}', [EncomendaController::class, 'destroy'])
        ->name('encomendas.destroy');
    // PDF Encomenda
Route::get('encomendas/{encomenda}/pdf', [EncomendaController::class, 'pdf'])
    ->name('encomendas.pdf');

    //faturas
    
Route::get('/financas/faturas-fornecedor', [FaturaFornecedorController::class, 'index'])
    ->name('faturas-fornecedor.index');

Route::get('/financas/faturas-fornecedor/create', [FaturaFornecedorController::class, 'create'])
    ->name('faturas-fornecedor.create');

Route::post('/financas/faturas-fornecedor', [FaturaFornecedorController::class, 'store'])
    ->name('faturas-fornecedor.store');

Route::get('/financas/faturas-fornecedor/{fatura}/edit', [FaturaFornecedorController::class, 'edit'])
    ->name('faturas-fornecedor.edit');

Route::put('/financas/faturas-fornecedor/{fatura}', [FaturaFornecedorController::class, 'update'])
    ->name('faturas-fornecedor.update');

Route::delete('/financas/faturas-fornecedor/{fatura}', [FaturaFornecedorController::class, 'destroy'])
    ->name('faturas-fornecedor.destroy');


Route::get('/financas/faturas-fornecedor/{fatura}/pdf', [FaturaFornecedorController::class, 'pdf'])
    ->name('faturas-fornecedor.pdf');


});
