<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DepositoController;
use App\Http\Controllers\TransferenciaController;

Route::post('/clientes', [ClienteController::class, 'store']);
Route::get('/clientes', [ClienteController::class, 'listaTodosClientes']);

// Rotas protegidas com APIKey
Route::middleware('apikeyauth')->group(function () {
    Route::get('/clientes/{id}', [ClienteController::class, 'listaCliente']);
    Route::post('/clientes/{id}/deposito', [DepositoController::class, 'store']);
    Route::post('/clientes/{id}/transferencia', [TransferenciaController::class, 'store']);
});