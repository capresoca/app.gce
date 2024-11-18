<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeMinacta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = ['ce_acta_id', 'ce_proconminuta_id', 'gn_archivo_id'];

    public function acta () {
        return $this->belongsTo(CeActa::class,'ce_acta_id');
    }

}
