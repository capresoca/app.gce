<?php

namespace App\Models\Presupuesto;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class PrConcepto extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    protected $searchable = ['search','codigo','nombre','nivel:nivel','tipo_rubro'];

    protected $fillable = [
        'codigo',
        'nombre',
        'pr_nivel_id',
        'pr_concepto_id',
        'pr_estructura_id',
        'tipo_rubro',
        'auxiliar'
    ];

    protected $appends = ['has_children','label','isDisabled','codigo_nombre','codigo_simple'];

    public function children(){
        return $this->hasMany(PrConcepto::class, 'pr_concepto_id');
    }

    public function presupuestoIngresos () {
        return $this->hasOne(PrDetstringreso::class, 'pr_rubro_id');
    }

    public function presupuesto()
    {
        return $this->hasOne(PrDetstrgasto::class,'pr_rubro_id');
    }

    public function getPresupuestoRubro($presupuesto_id)
    {
        return $this->presupuesto()->where('pr_strgasto_id',$presupuesto_id)->get()->valor_inicial;

    }

    public function getCodigoNombreAttribute ()
    {
        return $this->attributes['codigo'] . ' - ' .$this->attributes['nombre'];
    }

    public function getPresupuesto($presupuesto_id)
    {
        foreach ($this->children() as $child) {
            $this->sumaHijos($child,$presupuesto_id);
        }
    }

    private function sumaHijos($rubro,$presupuesto_id)
    {
        if($rubro->children){
            $child_sum = [];
            foreach ($rubro->children as $child){
                array_push($child_sum, ($this->sumaHijos($child,$presupuesto_id) + $child->getPresupuestoRubro($presupuesto_id)));
            }

            return max($child_sum);
        }

        return $rubro->getPresupuestoRubro($presupuesto_id);
    }

    public function nivel()
    {
        return $this->belongsTo(PrNiveles::class, 'pr_nivel_id');
    }

    public function detstrgasto () {
        return $this->hasOne(PrDetstrgasto::class, 'pr_rubro_id');
    }

    public function scopeDescendencia($query){
        $niveles = PrNiveles::all();
        $descendencia = 'children';
        for($i = 0; $i < $niveles->count() - 2; $i++ ){
            $descendencia = $descendencia.'.children';
        }
        return $query->with($descendencia);
    }

    public function scopeRubros ($query) {
        return $query->whereAuxiliar(1);
    }

    public function ingresos_presupuestados()
    {
        return $this->hasMany(PrDetstringreso::class, 'pr_rubro_id');
    }

    public function gastos_presupuestados()
    {
        return $this->hasMany(PrDetstrgasto::class, 'pr_rubro_id');
    }

    public function getIsDisabledAttribute()
    {
        return !$this->auxiliar;
    }

    public function getCodigoSimpleAttribute($key)
    {
        return str_replace('.','',$this->attributes['codigo']);
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $rubros = PrConcepto::select('id','codigo')->get();
            $items = array();
            foreach ($rubros as $key => $item) {
                $sinpunto = str_replace('.','',$item['codigo']);
                array_push($items, ['convertido' => $sinpunto, 'real' => $item['codigo']]);
            }
            $builder->where(function($query) use ($constraint, $items) {
                $search = $constraint->getValue();
                if (is_numeric($search)){
                    foreach ($items as $key => $i) {
                        if ($search == $i['convertido']) {
                            $query->where('codigo',$constraint->getOperator(),$i['real']);
                        }
                    }
                }
                $query->where('nombre',$constraint->getOperator(), $constraint->getValue())
                    ->orWhere('codigo',$constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public function getLabelAttribute () {
        return $this->nombre;
    }

    public function getHasChildrenAttribute()
    {
        if(!$this->children()->count()){
            return $this->ingresos_presupuestados()->count() || $this->gastos_presupuestados()->count();
        };

        return true;

    }

}
