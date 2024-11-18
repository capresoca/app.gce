<?php

namespace App\Models\CuentasMedicas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class CmCongestionriesgo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'cm_concurrencia_id',
        'evento_centinela',
        'cm_eventosaludpublica_id',
        'cm_patologiatrazadora_id',
        'cm_comppatologia_id',
        'ruta_atencion',
        'observaciones'
    ];

    protected $appends = ['descripcion'];

    // Relaciones
    public function concurrencia() {
        return $this->belongsTo(CmConcurrencia::class,'cm_concurrencia_id' );
    }
    public function evento_salud_publica() {
        return $this->belongsTo(CmEventosaludpublica::class, 'cm_eventosaludpublica_id' );
    }
    public function patologia_trazadora() {
        return $this->belongsTo(CmPatologiatrazadora::class,'cm_patologiatrazadora_id');
    }
    public function complicacion_patologia() {
        return $this->belongsTo(CmComppatologia::class, 'cm_comppatologia_id');
    }

    public static function allRelations() {
        return ['concurrencia','evento_salud_publica','patologia_trazadora','complicacion_patologia'];
    }

    public function getDescripcionAttribute()
    {
        $gestion = '';
        if ($this->evento_salud_publica) {
            $gestion .= 'Evento salud pública: '.$this->evento_salud_publica->nombre.'<br>';
        }
        if ($this->evento_centinela) {
            $gestion .= 'Evento centinela: '.$this->evento_centinela.'<br>';
        }
        if ($this->patologia_trazadora) {
            $gestion .= 'Patología trazadora: '.$this->patologia_trazadora->nombre.'<br>';
        }
        if ($this->complicacion_patologia) {
            $gestion .= 'Complicación patología: '.$this->complicacion_patologia->nombre.'<br>';
        }
        if ($this->ruta_atencion) {
            $gestion .= 'Ruta de atención: '.$this->ruta_atencion.'<br>';
        }
        return $gestion;
    }

    public static function boot()
    {
        parent::boot();
        CmCongestionriesgo::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }
        });
    }
}
