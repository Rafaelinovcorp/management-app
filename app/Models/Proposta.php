<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proposta extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'cliente_id',
        'data_proposta',
        'validade',
        'estado',
        'total',
    ];

    protected $casts = [
        'data_proposta' => 'date',
        'validade' => 'date',
        'total' => 'float',
    ];

    
    public function cliente()
    {
        return $this->belongsTo(Entidade::class, 'cliente_id');
    }

    public function linhas()
    {
        return $this->hasMany(PropostaLinha::class);
    }

  
    public function recalcularTotais(): void
    {
        $total = 0;

        foreach ($this->linhas as $linha) {
            $total += $linha->total;
        }

        $this->update([
            'total' => $total,
        ]);
    }
}
