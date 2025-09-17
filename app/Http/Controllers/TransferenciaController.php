<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class TransferenciaController extends Controller
{
    public function store(Request $request, $id)
    {
        try {
            if (!$request->has('valor') || !is_numeric($request->valor)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Dados inválidos.',
                    'code' => 'INVALID_DATA'
                ], 422);
            }
    
            if ($request->valor <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Valor de transferência inadequado.',
                    'code' => 'INVALID_TRANSFER_VALUE'
                ], 422);
            }
    
            $authCliente = $request->cliente_autenticado;
            $cliente = Cliente::find($id);
    
            if ($authCliente->id !== $cliente->id) {
                return response()->json([
                    'success' => false,
                    'error' => 'API Key inválida.',
                    'code' => 'API_KEY_INVALID'
                ], 401);
            }
    
            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'error' => 'Cliente não encontrado.',
                    'code' => 'CLIENT_NOT_FOUND'
                ], 404);
            }
    
            if (!$request->has('destinatario_id')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Destinatário não informado.',
                    'code' => 'MISSING_RECIPIENT'
                ], 422);
            }
    
            $destinatario = Cliente::find($request->destinatario_id);
            
            if (!$destinatario) {
                return response()->json([
                    'success' => false,
                    'error' => 'Destinatário não encontrado.',
                    'code' => 'CLIENT_NOT_FOUND'
                ], 404);
            }
    
            if ($authCliente->id === $destinatario->id) {
                return response()->json([
                    'success' => false,
                    'error' => 'Não é possível transferir para a própria conta.',
                    'code' => 'TRANSFER_TO_SELF'
                ], 400);
            }
    
            if ($authCliente->saldo < $request->valor) {
                return response()->json([
                    'success' => false,
                    'error' => 'Saldo insuficiente.',
                    'code' => 'INSUFFICIENT_FUNDS'
                ], 400);
            }

            // Salva novo saldo do cliente que trasnfere
            $authCliente->saldo -= $request->valor;
            $authCliente->save();

            // Salva novo saldo do destinatário
            $destinatario->saldo += $request->valor;
            $destinatario->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Transferência realizada com sucesso.',
                'origem' => [
                    'id' => $authCliente->id,
                    'nome' => $authCliente->nome,
                    'email' => $authCliente->email,
                    'saldo' => $authCliente->saldo
                ],
                'destino' => [
                    'nome' => $destinatario->nome,
                    'email' => $destinatario->email,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro interno no servidor.',
                'message' => $e->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }
}
