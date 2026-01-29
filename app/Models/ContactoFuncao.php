<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactoFuncao extends Model
{
    protected $table = 'contacto_funcoes';

    protected $fillable = [
        'nome',
        'ativo',
    ];
}
