<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrDetsolicitudcdp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'pr_solicitudcdp_id',
        'pr_detstrgasto_id',
        'valor'
    ];

    public function detstrgasto ()
    {
        return $this->belongsTo(PrDetstrgasto::class,'pr_detstrgasto_id');
    }

}
