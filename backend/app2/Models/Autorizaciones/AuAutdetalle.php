<?php

namespace App\Models\Autorizaciones;

use App\Models\RedServicios\RsCumcontratados;
use App\Models\RedServicios\RsCupscontratados;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AuAutdetalle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'au_autsolicitud_id',
        'au_autaprobada_id',
        'au_autorizacion_id',
        'codigo',
        'descripcion',
        'cantidad_solicitada',
        'cantidad_aprobada',
        'valor',
        'valor_usuario',
        'tipo_valor',
        'observaciones',
        'rs_cups_id',
        'rs_cum_id',
        'rs_otros_id'
    ];

    protected $appends = ['requiere_aprobacion'];

    public function cupcontratado()
    {
        return $this->belongsTo(RsCupscontratados::class, 'rs_cups_id');
    }

    public function cumcontratado()
    {
        return $this->belongsTo(RsCumcontratados::class, 'rs_cum_id');
    }

    public function scopeByNivelAutorizacion($query,$value)
    {
        if($value == 'Auditor Medico'){
            return $query->whereHas('cupcontratado',function ($query) use ($value) {
                $query->where('nivel_autorizacion', $value);
            });
        }
        return $query->whereHas('cupcontratado',function ($query) use ($value) {
            $query->where('nivel_autorizacion', $value);
        })->orWhere('rs_cum_id','!=', null)
            ->orWhere('rs_otros_id','!=', null);
    }

    public function getRequiereAprobacionAttribute()
    {
        return !$this->cupcontratado ? null : $this->cupcontratado->nivel_autorizacion === 'Auditor Medico';
    }

}


