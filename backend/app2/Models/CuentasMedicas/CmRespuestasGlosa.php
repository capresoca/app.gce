<?php

namespace App\Models\CuentasMedicas;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CmRespuestasGlosa extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'cm_glosa_id',
        'cm_respuesta_manglosa_id',
        'gs_usuario_id',
        'estado',
        'observacion',
        'tipo',
        'valor_respuesta_glosa'
    ];

    public function conciliadas () {
        return $this->hasMany(CmGlosasConciliada::class,'cm_respuestas_glosa_id');
    }

    public function glosa()
    {
        return $this->belongsTo(CmManglosa::class, 'cm_respuesta_manglosa_id');
    }

    public function usuario () {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }
}
