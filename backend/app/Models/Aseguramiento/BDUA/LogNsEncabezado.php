<?php

namespace App\Models\Aseguramiento\BDUA;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class LogNsEncabezado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'log_ns_encabezado';
    protected $primaryKey = 'consecutivo_log';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'tipo',
        'fecha',
        'usuario_grabado',
        'fecha_grabado',
        'consecutivo_vigencia',
        'sw_finaliza',
    ];

    protected $searchable = ['search'];

    public function detalles () {
        return $this->hasMany(LogNsDetalle::class,'consecutivo_log_ns');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($builder, $constraint) {
                $query->where('usuario_grabado',$constraint->getOperator(),$constraint->getValue());
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
