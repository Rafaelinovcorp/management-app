<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Artigo extends Model
{
    protected $fillable = [
        'referencia',
        'nome',
        'descricao',
        'preco',
        'iva_id',
        'foto',
        'observacoes',
        'estado',
    ];

    public function iva()
    {
        return $this->belongsTo(Iva::class);
    }
}
