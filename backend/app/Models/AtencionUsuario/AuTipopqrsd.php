<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Model;

class AuTipopqrsd extends Model
{
    protected $fillable = [
        'tipo',
        'descripcion',
        'plazo',
        'horas',
        'requiere_respuesta',
        'requiere_motivo',
        'riesgo_vida'
    ];

    protected $appends = ['removable'];

    public function pqrs()
    {
        return $this->hasMany(AuPqrsd::class, 'au_tipopqrsd_id');
    }

    public function getRemovableAttribute()
    {
        return $this->pqrs()->count() ? false : true;
    }
}


