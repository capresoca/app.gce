<?php

namespace App\Models\Aseguramiento;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class RespuestaDBUA extends Model
{
    use PimpableTrait;

    protected $table = 'as_s1vids';

    protected $fillable = [
        'nombreArchivo',
        'fechaProceso',
        'ruta_archivo',
        'gs_usuario_id'
    ];

    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $search = $constraint->getValue();
            $builder->where('nombreArchivo','like','%'.$search.'%');
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }

}