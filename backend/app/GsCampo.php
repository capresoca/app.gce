<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GsCampo extends Model
{
    protected $fillable = ['gs_tabla_id','llave','campo', 'tipo','tipo_full', 'permite_vacio','valor_defecto','descripcion'];
}
