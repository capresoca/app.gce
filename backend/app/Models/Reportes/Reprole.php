<?php

namespace App\Models\Reportes;

use Illuminate\Database\Eloquent\Model;

class Reprole extends Model
{
    protected $table = 're_reproles';

    protected $fillable = [
        're_reporte_id',
        'gs_role_id'
    ];
}
