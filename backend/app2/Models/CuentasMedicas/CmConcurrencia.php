<?php

namespace App\Models\CuentasMedicas;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\AuditorCuentas\AcAuditore;
use App\Models\CentroRegulador\AuReferencia;
use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsServicio;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class CmConcurrencia extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $searchable = ['auditores:gs_usuario_id','search'];
    protected $appends = ['tiempo_estancia','color_semaforo'];

    protected $fillable = [
        'as_afiliado_id',
        'consecutivo_ips',
        'fecha',
        'rs_entidad_id',
        'rs_servicio_id',
        'rs_especialidad_id',
        'au_referencia_id',
        'via_ingreso',
        'rs_cie10_ingreso',
        'rs_entorigen_id',
        'rs_cie10_ingreso',
        'rs_cie10_relacionado'
    ];

    // Relaciones
    public function referencia() {
        return $this->belongsTo(AuReferencia::class, 'au_referencia_id' );
    }
    public function afiliado(){
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }
    public function entidad(){
        return $this->belongsTo(RsEntidade::class, 'rs_entidad_id');
    }
    public function especialidad(){
        return $this->belongsTo(RsServicio::class, 'rs_especialidad_id');
    }
    public function servicio(){
        return $this->belongsTo(RsServicio::class, 'rs_servicio_id');
    }
    public function visitas(){
        return $this->hasMany(CmConvisita::class)->orderBy('fecha_visita','desc');
    }
    public function altocostos() {
        return $this->hasMany(CmConaltocosto::class );
    }
    public function entidad_origen() {
        return $this->belongsTo(RsEntidade::class, 'rs_entorigen_id');
    }
    public function diagnostico_ingreso() {
        return $this->belongsTo(RsCie10::class, 'rs_cie10_ingreso');
    }
    public function diagnostico_relacionado() {
        return $this->belongsTo(RsCie10::class, 'rs_cie10_relacionado');
    }
    public function eventos() {
        return $this->hasMany(CmConevento::class)->orderBy('fecha','desc');
    }
    public function gestion_riesgos() {
        return $this->hasMany(CmCongestionriesgo::class );
    }
    public function maternos() {
        return $this->hasMany(CmConmaterno::class);
    }
    public function egresos() {
        return $this->hasMany(CmConegreso::class);
    }
    public function auditores() {
        return $this->belongsToMany(AcAuditore::class,'cm_auditor_concurrencia','concurrencia_id','auditor_id');
    }

    public static function allRelations()
    {
        return [
            'afiliado',
            'referencia',
            'entidad',
            'especialidad',
            'servicio',
            'visitas',
            'altocostos',
            'entidad_origen',
            'diagnostico_ingreso',
            'diagnostico_relacionado',
            'eventos.historia_clinica',
            'eventos.evento_adverso_eps',
            'eventos.evento_adverso_ips',
            'eventos.glosa',
            'gestion_riesgos.evento_salud_publica',
            'gestion_riesgos.patologia_trazadora',
            'gestion_riesgos.complicacion_patologia',
            'maternos.historia_clinica',
            'egresos.diagnostico_egreso',
            'egresos.diagnostico_relacionado'
        ];
    }

    public function getTiempoEstanciaAttribute($key)
    {
        $paciente_hospitalario = CmPacientesHospitalario::where('cm_concurrencia_id',$this->attributes['id'])->first();
        if ($paciente_hospitalario) {
            $fecha1 = Carbon::parse($paciente_hospitalario->fecha_ingreso);
            $fechaActual = Carbon::now();
            $diff = $fechaActual->diffInDays($fecha1);

            return $diff;
        }
        return null;
    }

    public function getColorSemaforoAttribute ($key)
    {
        if ($this->tiempo_estancia) {
            $semaforos = ['orange', 'green', 'red'];

            if ($this->tiempo_estancia <= 1) return $semaforos[1];
            if ($this->tiempo_estancia > 1 && $this->tiempo_estancia <= 4) return $semaforos[0];
            if ($this->tiempo_estancia >= 5) return $semaforos[2];
        }
        return null;
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use($builder,$constraint){

                $query->whereHas('entidad',function ($query) use($builder,$constraint){
                    $query->where('nombre',$constraint->getOperator(),$constraint->getValue());
                })->orWhere('consecutivo',$constraint->getOperator(),$constraint->getValue())
                    ->orWhereHas('afiliado',function ($query) use($builder,$constraint){
                        $query->where('nombre_completo',$constraint->getOperator(),$constraint->getValue())
                            ->orWhere('identificacion',$constraint->getOperator(),$constraint->getValue());

                    });
            });
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public static function boot()
    {
        parent::boot();

        CmConcurrencia::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }
            $table->consecutivo = CmConcurrencia::max('consecutivo') + 1;
            $table->estado = 'Abierta';
        });

    }
}
