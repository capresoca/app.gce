<?php

namespace App\Models\CuentasMedicas;

use App\Models\General\GnArchivo;
use App\Models\RedServicios\RsCie10;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmConmaterno extends Model implements Auditable
{
    Use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'cm_concurrencia_id',
        'fecha_parto',
        'tipo_parto',
        'via_parto',
        'controles',
        'edad_gestacion',
        'dx_nacimiento',
        'dx_relacionado',
        'cm_complicacionparto_id',
        'peso_recien_nacido',
        'perimetro_cefalico',
        'perimetro_abdominal',
        'apgar',
        'rh',
        'cm_complicacionneonato_id',
        'condicion_egreso',
        'historia_clinica_id',
    ];

    // Relaciones
    public function concurrencia() {
        return $this->belongsTo(CmConcurrencia::class,'cm_concurrencia_id');
    }
    public function diagnostico_nacimiento() {
        return $this->belongsTo(RsCie10::class,'dx_nacimiento');
    }
    public function diagnostico_relacionado() {
        return $this->belongsTo(RsCie10::class,'dx_relacionado');
    }
    public function complicacion_parto() {
        return $this->belongsTo(CmComplicacionparto::class,'cm_complicacionparto_id');
    }
    public function complicacion_neonato() {
        return $this->belongsTo(CmComplicacionneonato::class, 'cm_complicacionneonato_id');
    }
    public function historia_clinica() {
        return $this->belongsTo(GnArchivo::class,'historia_clinica_id');
    }

    public function getFechaPartoAttribute($value){
        return (new Carbon($value))->toDateString();
    }

    public static function allRelations()
    {
        return [
            'diagnostico_nacimiento',
            'diagnostico_relacionado',
            'complicacion_parto',
            'complicacion_neonato',
            'historia_clinica'
        ];
    }
    public function getCmConcurrenciaIdAttribute($value){
        return (int)$value;
    }
    public function getDxNacimientoAttribute($value){
        return (int)$value;
    }
    public function getDxRelacionadoAttribute($value){
        return (int)$value;
    }
    public function getCmComplicacionpartoIdAttribute($value){
        return (int)$value;
    }
    public function getCmComplicacionneonatoIdAttribute($value){
        return (int)$value;
    }
}
