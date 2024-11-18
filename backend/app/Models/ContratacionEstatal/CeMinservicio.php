<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeMinservicio extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'tipo', 'codigo', 'nombre', 'tarifa', 'ce_proconminuta_id'
    ];


    public function servisable()
    {
        return $this->morphTo();
    }
}
