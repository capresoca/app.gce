<?php

namespace App\Models\TalentoHumano;

use App\Models\Presupuesto\PrEntidadResolucione;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class ThEncargo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'concepto_encargo',
        'fecha_fin',
        'fecha_inicio',
        'gs_usuario_id',
        'pr_entidad_resolucion_id',
        'tipo_encargo',
        'estado'
    ];

    protected $appends = ['diferencia_dias'];
    protected $searchable = ['search'];

    public function entidadResolucion()
    {
        return $this->belongsTo(PrEntidadResolucione::class, 'pr_entidad_resolucion_id');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'gs_usuario_id');
    }

    public function getDiferenciaDiasAttribute ()
    {
        $fecha = Carbon::now()->format('Y-m-d');
        $fechaIncio = Carbon::parse($this->attributes['fecha_inicio']);
        $fechaFin = Carbon::parse($this->attributes['fecha_fin']);
        $diff = $fechaFin->diffInDays($fechaIncio);
        $diffTwo = $fechaIncio->diffInDays($fecha);

        if ($this->check_in_range($this->attributes['fecha_inicio'], $this->attributes['fecha_fin'], $fecha))
        {
            $val = ($diff + 1) . ($diff > 1 ? ' días' : ' día');
        } else if ($fechaIncio > $fecha){
            $val = 'Próximo encargo en '. $diffTwo . ($diffTwo > 1 ? ' días' : ' día');
        } else {
            $val = 'Finalizado';
        }
        return $val;
    }

    private function check_in_range ($fecha_inicio, $fecha_fin, $fecha) {
        if (($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin)) {
            return true;
        } else {
            return false;
        }
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint){
                $query->whereHas('usuario', function ($query) use ($constraint) {
                    $query->where('name', $constraint->getOperator(),$constraint->getValue())
                    ->orWhere('email', $constraint->getOperator(),$constraint->getValue())
                    ->orWhere('identification', $constraint->getOperator(),$constraint->getValue());
                })->orWhere('tipo_encargo', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('concepto_encargo', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }

}
