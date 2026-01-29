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
    Route::post('/vies/check', [ViesController::class, 'check'])
    ->middleware('auth:sanctum');
});
