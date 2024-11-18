<?php

namespace App\Models\ContratacionEstatal;

use App\Models\Presupuesto\PrDetstrgasto;
use Illuminate\Database\Eloquent\Model;

class CeImputacionEstprevio extends Model
{

    protected $table = 'ce_rubro_estudioprevio';
    protected $fillable = [
        'ce_estprevio_id',
        'pr_detstrgasto_id',
        'valor'
    ];

    public function strgasto()
    {
        return $this->belongsTo(PrDetstrgasto::class,'pr_detstrgasto_id');
    }
}
