<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaSetting extends Model
{
    protected $table = 'empresa_settings';

    protected $fillable = [
        'nome',
        'nif',
        'morada',
        'codigo_postal',
        'localidade',
        'logotipo_path',
    ];
}
