<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpNotificacion extends Model
{
    protected $fillable = ['mp_direccionamiento_id', 'tipo','notificacion_exitosa','aceptada','observaciones'];

}
