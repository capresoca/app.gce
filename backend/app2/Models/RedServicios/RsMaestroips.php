<?php

namespace App\Models\RedServicios;

use App\Models\Aseguramiento\AsAfiliado;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsMaestroips extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'rs_maestroips';

    protected $fillable = [
        'id_grupoips',
        'as_afiliado_id',
        'rs_portabilidade_id',
        'gs_usuario_id',
        'gs_usuariactualizacion_id',
        'rs_carguegrupoips_id'
    ];

    protected $searchable = ['search'];

    protected $hidden = ['created_at', 'updated_at'];

    public function afiliado()
    {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function grupoIps()
    {
        return $this->belongsTo(RsMaestroipsgrup::class, 'id_grupoips');
    }

    public function portabilidad()
    {
        return $this->belongsTo(RsPortabilidade::class, 'rs_portabilidade_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'gs_usuario_id');
    }

    public function usuario_cambio()
    {
        return $this->belongsTo(User::class, 'gs_usuariactualizacion_id');
    }

    //public function cargue ()
    //{
    //    return $this->belongsTo(RsCarguegrupo::class,'rs_carguegrupoips_id');
    //}

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
                $query->whereHas('afiliado', function ($query) use ($constraint) {
                    $query->where('nombre_completo', $constraint->getOperator(), $constraint->getValue());
                });
            });

            return true;
        }

        return false;
    }
}
