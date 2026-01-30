<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iva extends Model
{
    protected $table = 'ivas';

    protected $fillable = [
        'nome',
        'percentagem',
    ];

    public function artigos()
    {
        return $this->hasMany(Artigo::class);
    }
}
