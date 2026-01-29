<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContactoFuncao;


class Contacto extends Model
{
    use HasFactory;

    protected $table = 'contactos';

    protected $fillable = [
        'numero',
        'entidade_id',
        'nome',
        'apelido',
        'funcao_id',
        'telefone',
        'telemovel',
        'email',
        'consentimento_rgpd',
        'observacoes',
        'estado',
    ];

    protected $casts = [
        'consentimento_rgpd' => 'boolean',
        'estado' => 'boolean',
    ];

    /* =======================
     |  Relações
     |=======================*/

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

   public function funcao()
{
    return $this->belongsTo(ContactoFuncao::class, 'contacto_funcao_id');
}

}
