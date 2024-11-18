<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GnDepartamento extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $hidden = ['created_at','updated_at'];

    public function municipios(){
        return $this->hasMany(GnMunicipio::class, 'gn_departamento_id');
    }
}
