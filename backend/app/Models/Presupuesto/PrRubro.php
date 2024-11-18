<?php

namespace App\Models\Presupuesto;

use App\Models\ContratacionEstatal\CeDetpaarubro;
use App\Models\ContratacionEstatal\CeDetplanadquisicione;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Searchable\Constraint;

class PrRubro extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = ['pr_presupuesto_id', 'pr_fuenrecurso_id', 'descripcion'];

    public $searchable = ['search'];

    public function getSearchableAttributes()
    {
        return ['search'];
    }

    public function presupuesto () {
        return $this->belongsTo(PrPresupuesto::class,'pr_presupuesto_id');
    }

    public function fuenrecursos () {
        return $this->belongsTo(PrFuenrecurso::class, 'pr_fuenrecurso_id');
    }

    public function detPlanadquisicion () {
        return $this->belongsToMany(CeDetplanadquisicione::class,'ce_detpaarubros','pr_rubro_id', 'ce_detplanadquisicione_id');
    }
    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint){
                $query->where('descripcion',$constraint->getOperator(),$constraint->getValue());
            });
            return true;
        }
        return false;
    }
}
