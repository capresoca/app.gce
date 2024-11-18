<?php

namespace App\Models\Pagos;

use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCencosto;
use App\Models\Niif\NfConrtf;
use App\Models\Niif\NfNiif;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PgCxpdetalle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'pg_cxp_id',
        'pg_conpago_id',
        'nf_niif_id',
        'nf_cencosto_id',
        'gn_tercero_id',
        'pg_uniapoyo_id',
        'nf_conrtf_id',
        'valor',
        'naturaleza',
        'retencion'
    ];
//    protected $guarded = ['conpago','uniapoyo','cxp','tercero','cencosto','niif'];

    public function niif () {
        return $this->belongsTo(NfNiif::class,'nf_niif_id');
    }

    public function tercero () {
        return $this->belongsTo(GnTercero::class, 'gn_tercero_id');
    }

    public function cencosto () {
        return $this->belongsTo(NfCencosto::class, 'nf_cencosto_id');
    }

    public function conpago () {
        return $this->belongsTo(PgConpago::class, 'pg_conpago_id');
    }

    public function uniapoyo () {
        return $this->belongsTo(PgUniapoyo::class, 'pg_uniapoyo_id');
    }

    public function conrtf () {
        return $this->belongsTo(NfConrtf::class,'nf_conrtf_id');
    }

    public function cxp () {
        return $this->belongsTo(PgCxp::class,'pg_cxp_id');
    }
}
