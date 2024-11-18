<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeEstpreactividade extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use PimpableTrait;

    protected $fillable = ['ce_proconestprevio_id', 'actividad'];
    protected $hidden = ['updated_at', 'created_at'];
//    protected $searchable = ['search'];

    public function estudioPrevio () {
        return $this->belongsTo(CeProconestprevio::class, 'ce_proconestprevio_id');
    }
}
