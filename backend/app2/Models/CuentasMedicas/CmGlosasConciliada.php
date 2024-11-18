<?php

namespace App\Models\CuentasMedicas;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CmGlosasConciliada extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'cm_respuestas_glosa_id',
        'cm_manglosa_con_id',
        'gs_usuario_id',
        'estado',
        'observacion',
        'tipo',
        'valor_conciliado'
    ];

    public function glosa()
    {
        return $this->belongsTo(CmManglosa::class, 'cm_manglosa_con_id');
    }

    public function usuario () {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }
}
