<?php

namespace App\Models\Aseguramiento\BDUA;

use App\Models\Aseguramiento\AsAfiliado;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Input;

class AfAfiliadoMs extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'af_afiliado_ms';
    protected $primaryKey = 'consecutivo_ns';
    public $timestamps = false;

    public $fillable = [
        'codigo_entidad',
        'tipo_identificacion_padre',
        'numero_identificacion_padre',
        'tipo_identificacion',
        'numero_identificacion',
        'primer_apellido',
        'segundo_apellido',
        'primer_nombre',
        'segundo_nombre',
        'fecha_nacimiento',
        'genero',
        'departamento',
        'municipio',
        'zona_afiliacion',
        'fecha_afiliacion',
        'tipo_poblacion',
        'nivel_sisben',
        'modalidad_subsidio',
        'consecutivo_afiliado',
        'usuario_grabado',
        'fecha_grabado',
        'municipio_actual',
        'sw_nacimiento',
        'fecha_ms',
        'consecutivo_vigencia',
        'consecutivo_log_ms',
        'consecutivo_ms_procesada',
        'estado_procesado',
        'condicion_discapacidad',
        'parentesco',
        'etnia',
        'consecutivo_ips',
        'tipo_documento_cotizante',
        'numero_documento_cotizante',
        'consecutivo_afiliado_cotizante',
        'tipo_cotizante',
        'tipo_afiliado',
        'tipo_identificacion_aportante',
        'numero_identificacion_aportante',
        'consecutivo_aportante',
        'ibc'
    ];

    protected $searchable = ['search'];

    public function afiliado () {
        return $this->belongsTo(AsAfiliado::class,'consecutivo_afiliado');
    }

    public function cotizante () {
        return $this->belongsTo(AsAfiliado::class,'consecutivo_afiliado_cotizante');
    }

    public function af_afiliado () {
        return $this->belongsTo(AfAfiliadoMs::class,'consecutivo_ms_procesada');
    }

    public function logMs()
    {
        return $this->belongsTo(LogMsEncabezado::class,'consecutivo_log_ms');
    }

    public function vigencia () {
        return $this->belongsTo(TbVigenciaTraslado::class,'consecutivo_vigencia');
    }


    public function getEstadoProcesadoAttribute($key)
    {
        $name = null;
        switch ($this->attributes['estado_procesado']) {
            case 0: $name = 'Pendiente Respuesta'; break;
            case 1: $name = 'Si'; break;
            case 2: $name = 'Pendiente NEG'; break;
            case 3: $name = 'Anulado'; break;
            case 4: $name = 'Resuelto'; break;
            case 5: $name = 'Pendiente Respuesto'; break;
        }

        return $name;
    }



//    public function getAfiliadoAttribute($key)
//    {
//        return AsAfiliado::with('tipo_id')->where('identificacion', $this->attributes['identificacion'])->first();
//    }



    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($builder, $constraint) {
                $query->where('numero_identificacion',$constraint->getOperator(),$constraint->getValue());
            });
           
            $nombre1            = Input::get('nombre1');
            $nombre2            = Input::get('nombre2');
            $apellido1          = Input::get('apellido1');
            $apellido2          = Input::get('apellido2');
            $identificacion     = Input::get('identificacion');
            $vigencia           = Input::get('vigencia');
            $logtraslado        = Input::get('logtraslado');
            
            $builder->whereHas('logMs', function ($query) use ( $logtraslado){
                $query->where('consecutivo_log_ms', $logtraslado);
            });
            
            if(!empty($identificacion)) {
                $builder->where('numero_identificacion', $identificacion);
            }
            
            if(!empty($nombre1)) {
                $builder->where('primer_nombre', 'like', '%'.$nombre1.'%');
            }
            
            if(!empty($nombre2)) {
                $builder->where('segundo_nombre', 'like', '%'.$nombre2.'%');
            }
            
            if(!empty($apellido1)) {
                $builder->where('primer_apellido', 'like', '%'.$apellido1.'%');
            }
            
            if(!empty($apellido2)) {
                $builder->where('segundo_apellido', 'like', '%'.$apellido2.'%');
            }
            if(!empty($vigencia)) {
                $builder->where('consecutivo_vigencia', $vigencia);
            }
            /*if(!empty($vigencia)) {
                $builder->where('consecutivo_vigencia', $vigencia);
            }*/
            
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

}
