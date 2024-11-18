<?php

namespace App\Models\RedServicios;

use App\Models\Aseguramiento\AsAfiliado;
use App\RedServicios\RsServhabilitados;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RsAfiliadoServicio extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'rs_servcontratado_id',
        'as_afiliado_id',
        'rs_portabilidad_id',
        'rs_servportabilidad_id',
        'rs_servhabilitado_id',
        'rs_asignamasivo_id'
    ];

    public function servicio_contratado() {
        return $this->belongsTo(RsServcontratado::class,'rs_servcontratado_id');
    }
    public function afiliado() {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }
    public function portabilidad() {
        return $this->belongsTo(RsPortabilidade::class,'rs_portabilidad_id');
    }
    public function servicio_portabilidad() {
        return $this->belongsTo(RsServhabilitados::class,'rs_servportabilidad_id');
    }
    public function servicio_habilitado() {
        return $this->belongsTo(RsServhabilitados::class,'rs_servhabilitado_id');
    }
    public function asigna_masivo() {
        return $this->belongsTo(RsAsignamasivo::class,'rs_asignamasivo_id');
    }

    public static function allRelations() {
        return ['afiliado','servicio_habilitado.servicio','asigna_masivo.usuario'];
    }

}
