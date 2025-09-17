<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    // Adiciona clientes
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'email' => 'nullable|email|unique:clientes,email',
        ]);

        $cliente = Cliente::create([
            'nome' => $request->nome,
            'email' => $request->email ?? null,
        ]);

        return response()->json($cliente, 201);
    }

    // Lista todos os clientes
    public function listaTodosClientes(Request $request)
    {
        $clientes = Cliente::all();

        return response()->json($clientes);
    }

    // Lista dados do próprio cliente
    public function listaCliente(Request $request, $id)
    {
        try {
            $cliente = $request->get('cliente_autenticado');
    
            if ($cliente->id != $id) {
                return response()->json([
                    'success' => false,
                    'error' => 'API Key inválida para este cliente.',
                    'code' => 'API_KEY_INVALID'
                ], 401);
            }
    
            return response()->json([
                'id' => $cliente->id,
                'nome' => $cliente->nome,
                'email' => $cliente->email,
                'saldo' => $cliente->saldo,
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
