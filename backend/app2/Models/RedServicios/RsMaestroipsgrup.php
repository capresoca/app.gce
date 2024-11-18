<?php

namespace App\Models\RedServicios;

use App\Models\General\GnMunicipio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsMaestroipsgrup extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'rs_maestroipsgrups';

    protected $fillable = [
        'municipio_id',
        'departamento_id',
        'descrip',
        'portable'
    ];

    protected $hidden = ['created_at','updated_at'];

    protected $searchable = ['search'];

    public function prestadores()
    {
        return $this->hasMany(RsMaestroipsgrudet::class,'idd');
    }

    public function municipio () {
        return $this->belongsTo(GnMunicipio::class, 'municipio_id');
    }

    public function departamento () {
        return $this->belongsTo(GnMunicipio::class, 'departamento_id');
    }

    public function getDescripAttribute($key)
    {
        return strtoupper($this->attributes['descrip']);
    }

    public static function allRelations()
    {
        return [
            'municipio',
            'departamento',
            'prestadores.entidad',
            'prestadores.usuario'
        ];
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
                $query->whereHas('municipio', function ($query) use ($constraint) {
                    $query->whereHas('departamento', function ($query) use ($constraint) {
                        $query->where('nombre',$constraint->getOperator(),$constraint->getValue());
                    })->orWhere('nombre',$constraint->getOperator(),$constraint->getValue());
                })->orWhere('descrip',$constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }

}
