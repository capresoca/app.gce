<?php

namespace App\Models\CentroRegulador;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsRegimene;
use App\Models\Autorizaciones\AuModservicio;
use App\Models\General\GnArchivo;
use App\Models\OficinaJuridica\OjTutela;
use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsCups;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsServicio;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AuReferencia extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'consecutivo',
        'fecha_orden',
        'fecha_solicitud',
        'as_afiliado_id',
        'entidad_origen_id',
        'as_regimen_id',
        'tipo_origen',
        'cie10_ingreso_id',
        'au_modservicio_id',
        'rs_servicio_id',
        'oj_tutela_id',
        'alto_costo',
        'orden_medica_id',
        'observaciones',
        'historia_clinica_id',
        'gs_usuario_id',
        'estado',
        'cie10_egreso_id',
        'entidad_egreso_id',
        'estado_egreso',
        'fecha_egreso',
        'rs_cup_id',
        'orden'
    ];
    protected $hidden = ['created_at','updated_at'];
    protected $searchable = ['search'];

    public function usuario(){
        return $this->belongsTo(User::class, 'gs_usuario_id');
    }

    public function bitacoras () {
        return $this->hasMany(AuRefbitacora::class,'au_referencia_id')->orderBy('created_at','desc');
    }

    public function cups () {
        return $this->belongsTo(RsCups::class, 'rs_cup_id');
    }
//    public function bitacoras () {
//        return $this->hasMany(AuRefbitacora::class,'au_referencia_id');
//    }

    public function afiliado () {
        return $this->belongsTo(AsAfiliado::class,'as_afiliado_id');
    }

    public function regimen () {
        return $this->belongsTo(AsRegimene::class,'as_regimen_id');
    }

    public function mod_servicio () {
        return $this->belongsTo(AuModservicio::class, 'au_modservicio_id');
    }

    public function archivo_uno () {
        return $this->belongsTo(GnArchivo::class, 'orden_medica_id');
    }

    public function archivo_two () {
        return $this->belongsTo(GnArchivo::class, 'historia_clinica_id');
    }

    public function tutela () {
        return $this->belongsTo(OjTutela::class, 'oj_tutela_id');
    }

    public function cie10Uno () {
        return $this->belongsTo(RsCie10::class,'cie10_ingreso_id');
    }

    public function cie10Two () {
        return $this->belongsTo(RsCie10::class,'cie10_egreso_id');
    }

    public function entidadUno () {
        return $this->belongsTo(RsEntidade::class,'entidad_origen_id');
    }

    public function entidadTwo () {
        return $this->belongsTo(RsEntidade::class,'entidad_egreso_id');
    }

    public function servicio () {
        return $this->belongsTo(RsServicio::class,'rs_servicio_id');
    }

//    public function setOrdenAttribute ($value)
//    {
//        if(!$this->attributes['estado']) return null;
//        $this->attributes['orden'] = [
//            'Iniciada' => 1,
//            'Presentada' => 2,
//            'Aceptada' => 3,
//            'Seleccionada' => 4,
//            'Traslado Solicitado' => 5,
//            'Traslado Aceptado' => 6,
//            'Salida' => 7,
//            'Cancelada' => 8,
//            'Fallecida' => 9,
//            'Suspendida' => 10,
//            'Finalizada' => 11
//        ][$this->estado];
//    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('afiliado', function ($query) use ($constraint) {
                $search = $constraint->getValue();
                if($constraint->getOperator() === Constraint::OPERATOR_LIKE) {
                    $search = substr($constraint->getValue(),1,-1);
                }
                $query->where('identificacion', $search);
                if(!is_numeric($search)){
                    $query->orWhere('nombre_completo', 'like','%'.$search.'%');
                }
            })->orwhereHas('usuario', function ($query) use ($constraint) {
                    $query->where('name', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('identification', $constraint->getOperator(), $constraint->getValue());
                })->orWhere('consecutivo', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('estado', $constraint->getOperator(), $constraint->getValue());
//                ->orWhere('fecha_solicitud', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        AuReferencia::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }
            $table->consecutivo = AuReferencia::max('consecutivo') + 1;
        });

        AuReferencia::saving(function ($table) {
            $table->orden =  [
                'Iniciada' => 1,
                'Presentada' => 2,
                'Aceptada' => 3,
                'Seleccionada' => 4,
                'Traslado Solicitado' => 5,
                'Traslado Aceptado' => 6,
                'Salida' => 7,
                'Cancelada' => 8,
                'Fallecida' => 9,
                'Suspendida' => 10,
                'Finalizada' => 11
            ][$table->estado];
        });

    }

}
