<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;

class AsBduaaccione extends Model
{
    protected $fillable = [
        'as_bduaregrespuesta_id',
        'as_afiliado_id',
        'accion',
        'new_values',
        'old_values'
    ];

    public function afiliado(){
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function registro(){
        return $this->belongsTo(AsBduaregrespuesta::class, 'as_bduaregrespuesta_id');
    }

}
