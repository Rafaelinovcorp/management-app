<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entidade extends Model
{
    protected $table = 'entidades';

    protected $fillable = [
        'is_cliente',
        'is_fornecedor',
        'numero',
        'nif',
        'nome',
        'morada',
        'codigo_postal',
        'localidade',
        'pais_id',
        'telefone',
        'telemovel',
        'website',
        'email',
        'rgpd',
        'observacoes',
        'estado',
    ];

    protected $casts = [
        'is_cliente' => 'boolean',
        'is_fornecedor' => 'boolean',
        'rgpd' => 'boolean',

     
        'morada' => 'encrypted',
        'codigo_postal' => 'encrypted',
        'localidade' => 'encrypted',
        'telefone' => 'encrypted',
        'telemovel' => 'encrypted',
        'email' => 'encrypted',
        'observacoes' => 'encrypted',
    ];

    public function scopeClientes($query)
    {
        return $query->where('is_cliente', true);
    }
    
    public function scopeFornecedores($query)
    {
        return $query->where('is_fornecedor', true);
    }

}
