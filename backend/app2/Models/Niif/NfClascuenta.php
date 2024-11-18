<?php

namespace App\Models\Niif;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class NfClascuenta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function nivelClaseCuenta () {
        return $this->hasMany(NfNiif::class,'nf_clascuenta_id')->where('nf_padre_id','=', '')->get();
    }

}
