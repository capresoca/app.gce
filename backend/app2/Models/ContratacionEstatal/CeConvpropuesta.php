<?php

namespace App\Models\ContratacionEstatal;

use App\Models\General\GnArchivo;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeConvpropuesta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use PimpableTrait;

    protected $fillable = ['ce_contratista_id', 'ce_proconconvocatoria_id', 'gn_archivo_id', 'seleccionada'];
    protected $hidden = ['created_at', 'updated_at'];

    public function contratista () {
        return $this->belongsTo(CeContratista::class, 'ce_contratista_id');
    }

    public function proconConvocatoria () {
        return $this->belongsTo(CeProconconvocatoria::class, 'ce_proconconvocatoria_id');
    }

    public function archivo () {
        return $this->belongsTo(GnArchivo::class, 'gn_archivo_id');
    }

}
