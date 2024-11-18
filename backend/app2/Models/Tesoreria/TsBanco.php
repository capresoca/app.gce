<?php

namespace App\Models\Tesoreria;

use App\Models\Niif\GnTercero;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TsBanco extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['codigo','nombre','gn_tercero_id','codigo_ach'];

    public function tercero(){
        return $this->belongsTo(GnTercero::class,'gn_tercero_id');
    }
}
