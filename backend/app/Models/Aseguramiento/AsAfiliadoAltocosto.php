<?php

namespace App\Models\Aseguramiento;

use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AsAfiliadoAltocosto extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $searchable = ['search'];

    protected $fillable = ['as_afiliado_id','as_tipaltocosto_id','rs_cie10_id','fecha_diagnostico','rs_entidad_tratante_id'];

    // Relaciones
    public function afiliado() {
        return $this->belongsTo( AsAfiliado::class, 'as_afiliado_id' );
    }
    public function tipo() {
        return $this->belongsTo(AsTipaltocosto::class,'as_tipaltocosto_id');
    }
    public function diagnostico() {
        return $this->belongsTo(RsCie10::class,'rs_cie10_id');
    }
    public function entidadTratante() {
        return $this->belongsTo(RsEntidade::class,'rs_entidad_tratante_id');
    }

    public static function allRelations() {
        return ['afiliado', 'tipo', 'diagnostico', 'entidadTratante'];
    }

    // METODOS ADICIONALES
    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use($builder,$constraint){
                $search = $constraint->getValue();
                if($constraint->getOperator() === Constraint::OPERATOR_LIKE){
                    $search = substr($constraint->getValue(),1,-1);
                }
                $query->orWhereHas('afiliado',function ($query) use($builder,$constraint){
                    $query->where('nombre_completo',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('identificacion',$constraint->getOperator(),$constraint->getValue());
                });

            });
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

}
