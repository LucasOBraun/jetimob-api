<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Cliente;

class ApiKeyAuth
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('Authorization');
        $cliente = Cliente::where('api_key', $apiKey)->first();

        if (!$apiKey || !$cliente) {
            return response()->json([
                'success' => false,
                'error' => 'API Key inválida.',
                'code' => 'API_KEY_INVALID'
            ], 401);
        }

        $request->merge(['cliente_autenticado' => $cliente]);

        return $next($request);
    }
}
