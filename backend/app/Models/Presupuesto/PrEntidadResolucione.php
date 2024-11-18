<?php

namespace App\Models\Presupuesto;

use App\Models\Niif\GnTercero;
use App\Models\TalentoHumano\ThEncargo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Searchable\Constraint;

class PrEntidadResolucione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'codigo',
        'gn_tercero_id',
        'nombre',
        'resolucion',
        'valor',
        'identificacion_rep_legal',
        'representante_legal',
        'identificacion_jefe_pr',
        'jefe_presupuesto',
        'identificacion_jefe_financiero',
        'jefe_financiero',
        'periodo'
    ];

    public $searchable = ['search'];
    protected $appends = ['jefe_financiero_encargado', 'jefe_presupuestal_encargado','representante_encargado'];

    public function getSearchableAttributes()
    {
        return ['search'];
    }

    public function encargos ()
    {
        return $this->hasMany(ThEncargo::class, 'pr_entidad_resolucion_id','id');
    }

    public function presupuestoIngreso () {
        return $this->hasOne(PrStringreso::class, 'pr_entidad_resolucion_id');
    }

    public function presupuestoGasto () {
        return $this->hasOne(PrStrgasto::class, 'pr_entidad_resolucion_id');
    }

    public function tercero () {
        return $this->belongsTo(GnTercero::class,'gn_tercero_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint){
                $query->where('codigo',$constraint->getOperator(),$constraint->getValue())
                    ->orWhere('nombre', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }

    public function getJefeFinancieroEncargadoAttribute () {

        return $this->getEncargado('Jefe Financiero');

    }

    public function getJefePresupuestalEncargadoAttribute () {

        return $this->getEncargado('Jefe de Presupuesto');

    }

    public function getRepresentanteEncargadoAttribute () {

        return $this->getEncargado('Representante Legal');

    }

//    getEncargadosAttribute
    public function getEncargado ($val)
    {
        $encargos = $this->encargos;
        if (!$encargos) return null;
        $fecha = Carbon::now()->format('Y-m-d');
        $value = null;
        foreach ($encargos as $key => $encargo) {
            $fecha_inicio = $encargo->fecha_inicio;
            $fecha_fin = $encargo->fecha_fin;
            if ($encargo->tipo_encargo === $val &&
                $this->check_in_range($fecha_inicio, $fecha_fin, $fecha) && $encargo->estado === 1) {
                $value = $encargo;
            }
        }
        return $value;
    }

    private function check_in_range ($fecha_inicio, $fecha_fin, $fecha) {
        if (($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin)) {
            return true;
        } else {
            return false;
        }
    }

}
