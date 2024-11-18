<?php

namespace App\Models\Pagos;

use App\Models\Niif\NfNiif;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PgConpago extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['codigo_nombre'];
    protected $guarded = ['niif', 'codigo_nombre'];

    public function niif(){
        return $this->belongsTo(NfNiif::class, 'nf_niif_id');
    }

    public function getCodigoNombreAttribute () {
        return $this->codigo . ' - ' . $this->nombre;
    }
}
