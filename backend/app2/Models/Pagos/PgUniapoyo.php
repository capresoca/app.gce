<?php

namespace App\Models\Pagos;

use App\Models\Niif\NfCencosto;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PgUniapoyo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['cod_nombre'];
    protected $guarded = ['cencosto'];

    public function cencosto(){
        return $this->belongsTo(NfCencosto::class, 'nf_cencosto_id');
    }

    public function getCodNombreAttribute()
    {
        return $this->codigo . ' - ' . $this->nombre;
    }

}
