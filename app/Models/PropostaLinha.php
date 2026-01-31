<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropostaLinha extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposta_id',
        'artigo_id',
        'fornecedor_id',
        'quantidade',
        'preco_unitario',
        'preco_custo',
        'iva_percentagem',
        'subtotal',
        'total',
    ];


    public function proposta()
    {
        return $this->belongsTo(Proposta::class);
    }

    public function artigo()
    {
        return $this->belongsTo(Artigo::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(Entidade::class, 'fornecedor_id');
    }

   
    public function calcularValores(): void
    {
        $this->subtotal = $this->quantidade * $this->preco_unitario;
        $this->total = $this->subtotal * (1 + ($this->iva_percentagem / 100));
    }
}
