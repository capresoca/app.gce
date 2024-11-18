<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GsTabla extends Model
{
    protected $fillable = ['tabla','descripcion','fecha_creacion', 'fecha_actualizacion'];
}
