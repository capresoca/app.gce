<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsEps extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'as_epss';
    protected $fillable = [
        'cod_habilitacion',
        'estado',
        'nit',
        'nombre',
        'regimen'
    ];
    protected $hidden = ['created_at','updated_at'];
}
