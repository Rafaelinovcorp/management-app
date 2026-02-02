<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FaturaFornecedor extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'data',
        'fornecedor_id',
        'valor_total',
        'estado',
        'pdf_path',
    ];

    protected $casts = [
        'data' => 'date',
        'valor_total' => 'decimal:2',
    ];

    /* =======================
     * RELAÇÕES
     * ======================= */
    public function fornecedor()
    {
        return $this->belongsTo(Entidade::class, 'fornecedor_id');
    }

    public function linhas()
    {
        return $this->hasMany(FaturaFornecedorLinha::class);
    }

    /* =======================
     * HELPERS
     * ======================= */
    public function isRascunho(): bool
    {
        return $this->estado === 'Rascunho';
    }

    public function isValidada(): bool
    {
        return $this->estado === 'Validada';
    }

    public function isPaga(): bool
    {
        return $this->estado === 'Paga';
    }

    public function recalcularTotal(): void
    {
        $total = $this->linhas()->sum('preco_total');

        $this->update([
            'valor_total' => $total,
        ]);
    }
}
