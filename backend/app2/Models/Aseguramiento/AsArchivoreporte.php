<?php

namespace App\Models\Aseguramiento;

use App\Models\General\GnArchivo;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AsArchivoreporte extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $searchable = ['tramites:tipo_tramite'];
    protected $guarded = [];

    public function tramites(){
        return $this->hasMany(AsTramite::class, 'as_archivoreporte_id');
    }

    public function novedades(){
        return $this->hasManyThrough(AsNovtramite::class, AsTramite::class, 'as_archivoreporte_id','as_tramite_id');
    }

    public function archivo(){
        return $this->belongsTo(GnArchivo::class, 'archivo_id');
    }

}
