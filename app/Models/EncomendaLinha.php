<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EncomendaLinha extends Model
{
    use HasFactory;

    protected $table = 'encomenda_linhas';

    protected $fillable = [
        'encomenda_id',
        'artigo_id',
        'fornecedor_id',
        'quantidade',
        'preco_unitario',
        'preco_total',
        'iva_percentagem',
        'iva_valor',
    ];

    protected $casts = [
        'preco_unitario' => 'decimal:2',
        'preco_total' => 'decimal:2',
        'iva_percentagem' => 'decimal:2',
        'iva_valor' => 'decimal:2',
    ];



    public function encomenda()
    {
        return $this->belongsTo(Encomenda::class);
    }

    public function artigo()
    {
        return $this->belongsTo(Artigo::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(Entidade::class, 'fornecedor_id');
    }


    public function calcularTotais(): void
    {
        $subtotal = $this->quantidade * $this->preco_unitario;
        $iva = ($subtotal * $this->iva_percentagem) / 100;

        $this->update([
            'preco_total' => $subtotal + $iva,
            'iva_valor'   => $iva,
        ]);
    }
}
