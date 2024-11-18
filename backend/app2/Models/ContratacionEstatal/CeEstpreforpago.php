<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeEstpreforpago extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use PimpableTrait;

    protected $fillable = ['ce_proconestprevio_id', 'tipo', 'valor','descripcion'];
    protected $hidden = ['created_at', 'updated_at'];
//    protected $searchable = ['search'];

    public function estudioPrevio () {
        return $this->belongsTo(CeProconestprevio::class, 'ce_proconestprevio_id');
    }

    public function getFormaAttribute()
    {
        if($this->tipo === 'Porcentaje'){
            return $this->valor.'%'.' '.$this->descripcion;
        }

        return '$'.number_format($this->valor,0,',','.').' '.$this->descripcion;
    }

}
