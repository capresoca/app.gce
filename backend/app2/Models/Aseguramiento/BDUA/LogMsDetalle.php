<?php

namespace App\Models\Aseguramiento\BDUA;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class LogMsDetalle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    protected $table = 'log_ms_detalle';
    protected $primaryKey = 'consecutivo_log_ms_detalle';
    public $timestamps = false;

    protected $fillable = [
        'tipo_identificacion_afiliado',
        'numero_identificacion_afiliado',
        'fecha_inicio_novedad',
        'consecutivo_ms',
        'consecutivo_log_ms',
        'informacion',
        'consecutivo_ma'
    ];
    
    protected $searchable = ['search'];
    
    public function cabecera()
   
    {
        return $this->belongsTo(LogMsEncabezado::class,'consecutivo_log_ms');
    }
    
    
    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('consecutivo_ms', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }
    

//    public function afAfiliado () {
//        return $this->belongsTo(AfAfiliadoMs::class,'consecutivo_ms');
//    }

}
