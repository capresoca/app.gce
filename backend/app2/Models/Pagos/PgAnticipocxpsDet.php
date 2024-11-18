<?php

namespace App\Models\Pagos;

use App\Models\Niif\NfCencosto;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PgAnticipocxpsDet extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'pg_anticipocxps_dets';

    protected $with = ['cxp','cencosto'];

    public function cxp () {
        return $this->belongsTo(PgCxp::class,'pg_cxp_id');
    }

    public function cencosto () {
        return $this->belongsTo(NfCencosto::class,'nf_cencosto_id');
    }
}
