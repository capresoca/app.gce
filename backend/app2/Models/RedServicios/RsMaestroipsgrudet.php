<?php

namespace App\Models\RedServicios;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsMaestroipsgrudet extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'idd',
        'servicio_id',
        'idIps',
        'gs_usuario_id',
        'codigo',
        'primaria'
    ];

    protected $searchable = ['search'];

    protected $hidden = ['created_at','updated_at'];

    public function grupoIps()
    {
        return $this->belongsTo(RsMaestroipsgrup::class,'idd');
    }

    public function servicio () {
        return $this->belongsTo(RsServicio::class,'servicio_id');
    }

    public function entidad()
    {
        return $this->belongsTo(RsEntidade::class, 'idIps');
    }

    public function usuario () {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
                $query->where('codigo',$constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }

}
