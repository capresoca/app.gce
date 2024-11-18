<?php

namespace App\Models\RedServicios;

use App\Models\Aseguramiento\AsAfiliado;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RsMaestroipshistorico extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'rs_maestroipshistoricos';

    protected $fillable = [
        'id_grupoips',
        'as_afiliado_id',
        'rs_portabilidade_id',
        'gs_usuario_id',
        'rs_carguegrupoips_id'
    ];

    public function grupoIps () {
        return $this->belongsTo(RsMaestroipsgrup::class,'id_grupoips','id');
    }

    public function afiliado()
    {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function portabilidad()
    {
        return $this->belongsTo(RsPortabilidade::class, 'rs_portabilidade_id');
    }

    public function usuario () {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }

}
