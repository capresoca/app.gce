<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeModcontratacione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = ['codigo', 'nombre'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['mod_cod_nombre'];

    public function getModCodNombreAttribute()
    {
        return $this->codigo . ' - ' . $this->nombre;
    }
}
