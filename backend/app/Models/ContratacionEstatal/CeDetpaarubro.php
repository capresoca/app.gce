<?php

namespace App\Models\ContratacionEstatal;

use App\Models\Presupuesto\PrRubro;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeDetpaarubro extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

//    protected $fillable = ['pr_rubro_id', 'ce_detplanadquisicione_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function rubro () {
        return $this->belongsTo(PrRubro::class,'pr_rubro_id','id');
    }

    public function detPlanadquisiciones () {
        return $this->belongsTo(CeDetplanadquisicione::class,'ce_detplanadquisicione_id','id');
    }

}
