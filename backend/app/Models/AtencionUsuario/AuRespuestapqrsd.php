<?php

namespace App\Models\AtencionUsuario;

use App\Models\General\GnArchivo;
use Illuminate\Database\Eloquent\Model;

class AuRespuestapqrsd extends Model
{
    protected $fillable = [
        'respuesta','au_pqrsd_id','tipo','fecha_respuesta','estado'
    ];

    public function pqrsd()
    {
        return $this->belongsTo(AuPqrsd::class, 'au_pqrsd_id');
    }

    public function anexos()
    {
        return $this->belongsToMany(GnArchivo::class, 'au_respuestapqrsd_archivo','au_respuestapqrd_id','gn_archivo_id');
    }
}
