<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class MpReporteentrega extends Model
{
    use PimpableTrait;

    protected $fillable = [
        'id',
        'suministro_id',
        'NoPrescripcion',
        'TipoTec',
        'ConTec',
        'TipoIDPaciente',
        'NoIDPaciente',
        'NoEntrega',
        'EstadoEntrega',
        'CausaNoEntrega',
        'ValorEntregado',
        'CodTecEntregado',
        'CantTotEntregada',
        'NoLote',
        'FecEntrega',
        'FecRepEntrega',
        'EstRepEntrega',
        'deleted_at'
    ];
    protected $searchable = ['search'];

    public function direccionamiento()
    {
        return $this->belongsTo(MpDireccionamiento::class,'suministro_id','suministro_id');
    }


    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use($builder,$constraint){
                $search = $constraint->getValue();
                if($constraint->getOperator() === Constraint::OPERATOR_LIKE){
                    $search = substr($constraint->getValue(),1,-1);
                }
                $query->orWhere('NoPrescripcion', 'like','%'.$search.'%')->orWhere('NoIDPaciente', 'like','%'.$search.'%');
            });

            return true;

        }

        // default logic should be executed otherwise
        return false;
    }

}
