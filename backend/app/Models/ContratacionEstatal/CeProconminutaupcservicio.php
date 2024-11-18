<?php

namespace App\Models\ContratacionEstatal;

use App\Models\RedServicios\RsServicio;
use Illuminate\Database\Eloquent\Model;

class CeProconminutaupcservicio extends Model
{
    protected $fillable = ['ce_proconminutageo_id', 'rs_servicio_id', 'porcentaje', 'valor', 'porcent_pob_contributivo', 'porcent_pob_subsidiado'];
    protected $hidden = ['updated_at', 'created_at'];

    public function proconminutageo()
    {
        return $this->belongsTo(CeProconminutageo::class, 'ce_proconminutageo_id');
    }

    public function servicio()
    {
        return $this->belongsTo(RsServicio::class, 'rs_servicio_id');
    }
}
