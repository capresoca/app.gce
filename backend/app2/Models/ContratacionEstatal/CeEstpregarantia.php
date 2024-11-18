<?php

namespace App\Models\ContratacionEstatal;

use App\Models\General\GnArchivo;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeEstpregarantia extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use PimpableTrait;

    protected $fillable = ['ce_proconestprevio_id', 'ce_garantia_id','gn_archivo_id','descripcion'];

    protected $with = ['archivo'];

    public function proconEstprevio () {
        return $this->belongsTo(CeProconestprevio::class, 'ce_proconestprevio_id');
    }

    public function garantia () {
        return $this->belongsTo(CeGarantia::class, 'ce_garantia_id');
    }

    public function archivo()
    {
        return $this->belongsTo(GnArchivo::class,'gn_archivo');

    }



}
