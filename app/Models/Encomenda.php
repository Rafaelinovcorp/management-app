<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Encomenda extends Model
{
    use HasFactory;

    protected $table = 'encomendas';

    protected $fillable = [
        'numero',
        'data',
        'entidade_id',
        'valor_total',
        'estado',
        'proposta_id',
    ];

    protected $casts = [
        'data' => 'date',
        'valor_total' => 'decimal:2',
    ];


    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    
    public function linhas()
    {
        return $this->hasMany(EncomendaLinha::class);
    }

    public function proposta()
    {
        return $this->belongsTo(Proposta::class);
    }



    public function isRascunho(): bool
    {
        return $this->estado === 'Rascunho';
    }

    public function isFechado(): bool
    {
        return $this->estado === 'Fechado';
    }

    public function recalcularTotal(): void
    {
        $total = $this->linhas()->sum('preco_total');

        $this->update([
            'valor_total' => $total,
        ]);
    }
}
