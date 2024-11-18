<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use App\Models\Niif\GnTercero;
use OwenIt\Auditing\Contracts\Auditable;

class CrVendedore extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'gn_tercero_id',
        'codigo',
        'nombre',
    ];
    public function tercero(){
        return $this->belongsTo(GnTercero::class,'gn_tercero_id');
    }
}
