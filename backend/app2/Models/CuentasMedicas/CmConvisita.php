<?php

namespace App\Models\CuentasMedicas;

use App\Models\RedServicios\RsServicio;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class CmConvisita extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'cm_concurrencia_id',
        'fecha_visita',
        'evolucion',
        'observaciones',
        'fio2',
        'peep',
        'frec_ve',
        'modalidad',
        'frec_cardiaca',
        'temperatura',
        'tension_arterial',
        'frec_respiratoria',
        'glasgow',
        'uci',
        'habitacion_hospitalaria',
        'estancia_pertinente',
        'requiere_phd',
        'rs_especialidadtratante_id',
        'rs_especialidadinterconsultante_id',
        'ventilacion_asistido',
        'signos_vitales'
    ];


    // Relaciones
    public function concurrencia() {
        return $this->belongsTo(CmConcurrencia::class, 'cm_concurrencia_id');
    }
    public function insumos() {
        return $this->hasMany(CmConinsumo::class )->orderBy('codigo','asc');
    }
    public function concups() {
        return $this->hasMany(CmConcup::class )->orderBy('cantidad','asc');
    }
    public function condclinicas() {
        return $this->belongsToMany(CmConcondclinica::class,'cm_concondclinica_cm_convisita','cm_convisita_id','cm_concondclinica_id')->withTimestamps();
    }
    public function concums() {
        return $this->hasMany(CmConcum::class );
    }
    public function concie10s() {
        return $this->hasMany(CmConcie10::class );
    }
    public function usuario() {
        return $this->belongsTo(User::class, 'gs_usuario_id');
    }
    public function hallazgos() {
        return $this->hasMany(CmConhallazgo::class);
    }
    public function especialidad_tratante() {
        return $this->belongsTo(RsServicio::class,'cm_especialidadtratante_id');
    }
    public function especialidad_interconsultante() {
        return $this->belongsTo(RsServicio::class,'rs_especialidadinterconsultante_id');
    }
    public function causal_hospitalizacion() {
        return $this->hasOne(CmConcausalhospitalizacion::class);
    }

    public function getFechaVisitaAttribute($key)
    {
        return Carbon::parse($this->attributes['fecha_visita'])->format('Y-m-d');
    }

    public static function allRelations() {
        return [
            'insumos.glosa',
            'condclinicas',
            'concie10s.diagnostico_relacionado',
            'concie10s.glosa',
            'concups.cup',
            'concups.glosa',
            'concums.cum',
            'concums.glosa',
            'usuario',
            'hallazgos',
            'especialidad_tratante',
            'especialidad_interconsultante',
            'causal_hospitalizacion.tipo_causal_hospitalizacion',
            'causal_hospitalizacion.cup',
            'causal_hospitalizacion.cum',
            'causal_hospitalizacion.valoracion_especialista'
        ];
    }

    public static function boot()
    {
        parent::boot();

        CmConvisita::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }
        });

    }

}
