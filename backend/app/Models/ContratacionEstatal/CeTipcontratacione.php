<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeTipcontratacione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use PimpableTrait;

    protected $fillable = ['codigo', 'nombre', 'ce_plantilla_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function plantilla () {
        return $this->belongsTo(CePlantilla::class, 'ce_plantilla_id');
    }

}
