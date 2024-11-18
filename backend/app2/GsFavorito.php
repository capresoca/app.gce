<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GsFavorito extends Model
{
    protected $fillable = ['gs_usuario_id','gs_componente_id'];
}
