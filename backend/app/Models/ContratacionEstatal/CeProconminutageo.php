<?php

namespace App\Models\ContratacionEstatal;

use App\Models\ContratacionEstatal\CeProconestprevio;
use App\Models\General\GnMunicipio;
use Illuminate\Database\Eloquent\Model;

class CeProconminutageo extends Model
{
    protected $fillable = [
        'ce_proconestprevio_id',
        'porcentaje_contributivo',
        'porcentaje_contributivo_portabilidad',
        'porcentaje_subsidiado',
        'porcentaje_subsidiado_portabilidad',
        'valor_upc_contributivo',
        'valor_upc_subsidiado',
        'gn_municipio_id'
    ];
    protected $hidden = ['updated_at', 'created_at'];

    public function estudioprevio()
    {
        return $this->belongsTo(CeProconestprevio::class, 'ce_proconestprevio_id');
    }

    public function municipio()
    {
        return $this->belongsTo(GnMunicipio::class, 'gn_municipio_id');
    }

    public function upcservicios()
    {
        return $this->hasMany(CeProconminutaupcservicio::class, 'ce_proconminutageo_id');
    }

    public function upcedades()
    {
        return $this->hasMany(CeProconminutaupcedade::class, 'ce_proconminutageo_id');
    }
}
