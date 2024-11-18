<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeActa extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use PimpableTrait;

    protected $fillable = ['codigo', 'ce_plantillas_id', 'nombre'];

    public function plantilla () {
        return $this->belongsTo(CePlantilla::class, 'ce_plantillas_id');
    }
}
