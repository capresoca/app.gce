<?php

namespace App\Models\OficinaJuridica;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnArchivo;
use App\Models\General\GnDepartamento;
use App\Models\General\GnTipdocidentidade;
use App\Models\Autorizaciones\AuAutorizacione;
use App\Models\Autorizaciones\AuAutdetalle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class OjTutela extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'gn_tipdocentidad_id',
        'identificacion',
        'nombre',
        'oj_juzgado_id',
        'no_tutela',
        'fecha',
        'oj_pretencion_id',
        'desc_pretencion',
        'tipo_tutela',
        'ent_accionadas',
        'med_provisional',
        'gs_usuario_id',
        'gn_archivo_id',
        'estado',
        'fecha_notificacion',
        'gn_departamento_id',
        'incidente_desacato'
    ];
    protected $hidden = ['created_at','updated_at'];
    protected $searchable = ['nombre','identificacion','search'];
    protected $appends = ['last_fallo','last_contestacion','dias_opotunidad','last_desacato','last_impugnacion'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
                $query->whereHas('afiliados_tutelas', function ($query) use ($constraint) {
                    $search = $constraint->getValue();
                    if($constraint->getOperator() === Constraint::OPERATOR_LIKE) {
                        $search = substr($constraint->getValue(),1,-1);
                    }
                    $query->where('identificacion', $search);
                    if(!is_numeric($search)){
                        $query->orWhere('nombre_completo', 'like','%'.$search.'%');
                    }
                })->orWhere('nombre', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('no_tutela', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('tipo_tutela', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('estado', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public function departamento (){
        return $this->belongsTo(GnDepartamento::class, 'gn_departamento_id');
    }

    public function juzgado () {
        return $this->belongsTo(OjJuzgado::class,'oj_juzgado_id');
    }

    public function afiliados_tutelas () {
        return $this->hasMany(OjAfiliadosTutela::class, 'oj_tutela_id');
    }

    public function afiliado () {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function tipIdentidad () {
        return $this->belongsTo(GnTipdocidentidade::class, 'gn_tipdocentidad_id');
    }

    public function pretencion () {
        return $this->belongsTo(OjPretencione::class, 'oj_pretencion_id');
    }

    public function archivo () {
        return $this->belongsTo(GnArchivo::class, 'gn_archivo_id', 'id');
    }

    public function contestaciones () {
        return $this->hasMany(OjTutcontestacione::class, 'oj_tutela_id');
    }

    public function fallos () {
        return $this->hasMany(OjTutfallo::class,'oj_tutela_id');
    }

    public function impugnaciones () {
        return $this->hasMany(OjTutimpugnacione::class,'oj_tutela_id');
    }

    public function desacatos () {
        return $this->hasMany(OjTutdesacato::class,'oj_tutela_id');
    }

    public function bitacoras () {
        return $this->hasMany(OjTutbitacora::class, 'oj_tutela_id');
    }

    public function getLastFalloAttribute () {
        if ($this->fallos()) {
            return $this->fallos()->orderBy('updated_at', 'desc')->first();
        }
    }

    public function getLastContestacionAttribute ()
    {
        if ($this->contestaciones()) {
            return $this->contestaciones()->orderBy('updated_at', 'desc')->first();
        }
    }

    public function getLastDesacatoAttribute ()
    {
        if ($this->desacatos()) {
            return $this->desacatos()->orderBy('updated_at', 'desc')->first();
        }
    }

    public function getLastImpugnacionAttribute ()
    {
        if ($this->impugnaciones()) {
            return $this->impugnaciones()->orderBy('updated_at', 'desc')->first();
        }
    }


    public function getDiasOpotunidadAttribute () {
        $fecha_uno = Carbon::parse($this->fecha_notificacion);
        if (!$this->last_contestacion) {
            $today = Carbon::now()->format('Y-m-d');
            $fechaHoy = Carbon::parse($today);
            if (($fecha_uno === $fechaHoy)) return 0;
            $diasOpotunidad = ($fecha_uno->diffInDays($fechaHoy) > 0 ? '- ' : '') . $fecha_uno->diffInDays($fechaHoy);
        } else {
            $fecha_dos = Carbon::parse($this->last_contestacion->fecha);
            if ($fecha_uno === $fecha_dos) return 0;
            $diasOpotunidad = $fecha_dos->diffInDays($fecha_uno);
        }
        return $diasOpotunidad;
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        OjTutela::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }
        });

    }

    //agrego RJPT
    public function codDiagnostico () {
        return $this->hasMany(AuAutorizacione::class, 'oj_tutela_id');
    }

}
