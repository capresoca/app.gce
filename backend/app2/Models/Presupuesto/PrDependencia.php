<?php

namespace App\Models\Presupuesto;

use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCencosto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Searchable\Constraint;

class PrDependencia extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'codigo',
        'nombre',
        'nf_cencosto_id',
        'gn_tercero_id'
    ];

    protected $appends = ['codigo_nombre'];
    public $searchable = ['search'];
    protected $with = ['tercero'];

    public function getSearchableAttributes()
    {
        return ['search'];
    }

    public function getCodigoNombreAttribute () {
        return $this->codigo . ' - ' . $this->nombre;
    }

    public function tercero () {
        return $this->belongsTo(GnTercero::class,'gn_tercero_id');
    }
    public function cencosto () {
        return $this->belongsTo(NfCencosto::class,'nf_cencosto_id');
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
}
