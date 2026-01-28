<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\FornecedoresController;
use App\Http\Controllers\Api\ViesController;


/*
|--------------------------------------------------------------------------
| API Routes (JSON)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    // CLIENTES
   //   Route::get('/clientes', [ClientesApiController::class, 'index'])
   //    ->name('api.clientes.index');


    Route::post('/vies/check', [ViesController::class, 'check'])
    ->middleware('auth:sanctum');


    // FORNECEDORES
    Route::apiResource('Fornecedores', FornecedoresController::class);

    Route::get('vies/{country}/{nif}', [ViesController::class, 'check']);

});
