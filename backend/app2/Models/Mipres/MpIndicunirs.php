<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpIndicunirs extends Model
{
    protected $table = 'mp_indicunirs';

    protected $fillable = ['ConOrden','CodIndicacion','mp_medicamento_id'];
}
