<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = ['nome', 'email', 'saldo', 'api_key'];
    protected $attributes = [
        'saldo' => 0, // saldo inicial
    ];

    protected static function booted()
    {
        static::creating(function ($cliente) {
            $cliente->api_key = Str::uuid(); // gera api_key automaticamente
        });
    }
}
