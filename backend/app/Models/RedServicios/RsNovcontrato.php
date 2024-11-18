<?php

namespace App\Models\RedServicios;

use App\Models\ContratacionEstatal\CeProconminuta;
use Illuminate\Database\Eloquent\Model;

class RsNovcontrato extends Model
{
    protected $fillable = [
        'tipo',
        'fecha',
        'valor',
        'plazo_meses',
        'plazo_dias',
        'descripcion',
        'rs_contrato_id',
        'estado'
    ];

    public function contrato()
    {
        return $this->belongsTo(CeProconminuta::class, 'ce_proconminuta_id');
    }
}

