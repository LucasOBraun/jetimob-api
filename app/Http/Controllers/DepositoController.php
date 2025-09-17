<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class DepositoController extends Controller
{
    public function store(Request $request, $id)
    {
        if (!$request->has('valor') || !is_numeric($request->valor)) {
            return response()->json([
                'success' => false,
                'error' => 'Dados inválidos.',
                'code' => 'INVALID_DATA'
            ], 422);
        }

        $authCliente = $request->cliente_autenticado;
        $cliente = Cliente::find($id);
        
        if (!$cliente) {
            return response()->json([
                'success' => false,
                'error' => 'Cliente não encontrado.',
                'code' => 'CLIENT_NOT_FOUND'
            ], 404);
        }

        if ($authCliente->id !== $cliente->id) {
            return response()->json([
                'success' => false,
                'error' => 'API Key inválida.',
                'code' => 'API_KEY_INVALID'
            ], 401);
        }

        if ($request->valor <= 0) {
            return response()->json([
                'success' => false,
                'error' => 'Valor de depósito inadequado.',
                'code' => 'INVALID_DEPOSIT_VALUE'
            ], 422);
        }

        $cliente->saldo += $request->valor;
        $cliente->save();

        return response()->json([
            'success' => true,
            'message' => 'Depósito realizado com sucesso.',
            'cliente' => [
                'id' => $cliente->id,
                'nome' => $cliente->nome,
                'email' => $cliente->email,
                'saldo' => $cliente->saldo
            ]
        ]);
    }
}
