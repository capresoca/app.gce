<?php

namespace App\Models\RedServicios;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsCarguegrupo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'tipo',
        'total_registros',
        'observacion',
        'gs_usuario_id'
    ];

    protected $searchable = ['search'];

    public function gruposCargados()
    {
        return $this->hasMany(RsMaestroips::class,'rs_carguegrupoips_id');
    }

    public function gruposAnteriores () {
        return $this->hasMany(RsMaestroipshistorico::class,'rs_carguegrupoips_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
                $query->whereHas('usuario', function ($query) use ($constraint) {
                    $query->where('name',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('email',$constraint->getOperator(),$constraint->getValue());
                })->orWhere('tipo',$constraint->getOperator(), $constraint->getValue())
                    ->orWhere('id',$constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }

}
