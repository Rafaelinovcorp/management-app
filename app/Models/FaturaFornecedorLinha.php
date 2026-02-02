<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FaturaFornecedorLinha extends Model
{
    use HasFactory;

    protected $fillable = [
        'fatura_fornecedor_id',
        'descricao',
        'quantidade',
        'preco_unitario',
        'iva_percentagem',
        'iva_valor',
        'preco_total',
    ];

    protected $casts = [
        'preco_unitario' => 'decimal:2',
        'iva_percentagem' => 'decimal:2',
        'iva_valor' => 'decimal:2',
        'preco_total' => 'decimal:2',
    ];

    public function fatura()
    {
        return $this->belongsTo(FaturaFornecedor::class, 'fatura_fornecedor_id');
    }
}
