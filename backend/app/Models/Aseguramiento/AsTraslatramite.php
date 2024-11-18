<?php

namespace App\Models\Aseguramiento;

use App\Models\General\GnSexo;
use Illuminate\Database\Eloquent\Model;

class AsTraslatramite extends Model
{

    protected $fillable = [
        'as_afiliado_id',
        'as_tramite_id',
        'as_formtrasmov_id',
        'codigo_entidad',
        'as_tipafiliado_id'
    ];

    protected $appends = ['tipo_traslado'];

    public function tramite()
    {
        return $this->belongsTo(AsTramite::class, 'as_tramite_id');
    }

    public function afiliado()
    {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function formulario()
    {
        return $this->belongsTo(AsFormtrasmov::class, 'as_formtrasmov_id');
    }

    public function sexo()
    {
        return $this->belongsTo(GnSexo::class, 'gn_sexo_id');
    }

    public function tipo_afiliado()
    {
        return $this->belongsTo(AsTipafiliado::class, 'as_tipafiliado_id');
    }

    public function getTipoTrasladoAttribute()
    {
        return 3;
    }




}
