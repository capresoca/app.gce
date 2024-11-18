<?php

namespace App\Models\Aseguramiento;

use App\Compensacion\CpAporte;
use App\Compensacion\CpLiquidacione;
use App\Models\Aseguramiento\AsArl;
use App\Models\Aseguramiento\AsPagadore;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class AsAfiliadoPagador extends Model
{
    use PimpableTrait;

    protected $table = 'as_afiliado_pagador';

    protected $fillable = [
        'tipo_cotizante',
        'as_afiliado_id',
        'as_arl_id',
        'as_pagador_id',
        'fecha_vinculacion',
        'ibc',
        'estado'
    ];

    protected $searchable = ['afiliado:identificacion', 'search', 'fecha_vinculacion', 'afiliado:nombre_completo', 'as_afiliado_id', 'estado'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['periodos_mora'];

    public function arl()
    {
        return $this->belongsTo(AsArl::class, 'as_arl_id');
    }

    public function getSalarioAtribute()
    {
        return $this->ibc;
    }

    public function pagador()
    {
        return $this->belongsTo(AsPagadore::class, 'as_pagador_id');
    }

    public function afiliado()
    {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function aportes()
    {
        return $this->hasManyThrough(
            CpAporte::class,
            CpLiquidacione::class,
            'relacion_laboral_id',
            'liquidacion_id',
            'id',
            'id');
    }

    public function scopeJoinAportes($query)
    {
        $query->join('cp_liquidaciones', 'as_afiliado_pagador.id', 'cp_liquidaciones.relacion_laboral_id')
            ->join('cp_aportes', 'cp_liquidaciones.id', 'cp_aportes.liquidacion_id');
    }

    public function liquidaciones()
    {
        return $this->hasMany(CpLiquidacione::class, 'relacion_laboral_id')->orderBy('periodo', 'desc');
    }

    public function liquidaciones_unorder()
    {
        return $this->hasMany(CpLiquidacione::class, 'relacion_laboral_id');
    }

    public function getPeriodosMoraAttribute()
    {

        $liquidaciones_morosas = $this->liquidaciones()->where(function ($query) {

//            $query->whereDate('periodo','<',today()->subMonths(2)->toDateString())
            $query->doesntHave('aportes')
                ->orWhere(function ($query) {
                    $query->where('dias_pagados', '<', 30)
                        ->where(function ($query) {
                            $query->where(['retiro' => 0, 'ingreso' => 0]);
                        });
                });
        });

        return $liquidaciones_morosas->count();
    }

    public function esIndependiente()
    {
        return $this->pagador->identificacion === $this->afiliado->identificacion
            || substr($this->pagador->identificacion, 0, -1) === $this->afiliado->identificacion;
    }

    public function scopeVencidasMesSegunUltimosDosDigitos($query, $digitos, $periodo)
    {
        return $query->join('as_pagadores', 'as_afiliado_pagador.as_pagador_id', 'as_pagadores.id')
            ->where(DB::raw('substr(as_pagadores.identificacion,-2)'), '<', $digitos)
            ->where('as_afiliado_pagador.estado', 'Activo')
            ->whereDoesntHave('liquidaciones', function ($query) use ($periodo) {
                $query->where('periodo', '>=', $periodo);
            });
    }


    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $search = $constraint->getValue();

            if ($constraint->getOperator() === Constraint::OPERATOR_LIKE) {
                $search = substr($constraint->getValue(), 1, -1);
            }

            $builder->join('as_afiliados', 'as_afiliados.id', '=', 'as_afiliado_pagador.as_afiliado_id')
                ->join('as_pagadores', 'as_pagadores.id', '=', 'as_afiliado_pagador.as_pagador_id')
                ->select(DB::raw('*,as_afiliado_pagador.id as id,as_afiliado_pagador.estado as estado, as_afiliado_pagador.ibc as ibc'));

            if (!is_numeric($search)) {
                $builder->where(function ($query) use ($search) {
                    $query->where('as_afiliados.nombre_completo', 'like', '%' . $search . '%')
                        ->orWhere('as_pagadores.razon_social', 'like', '%' . $search . '%');
                });
            } else {
                $builder->where(function ($query) use ($search) {
                    $query->where('as_afiliados.identificacion', $search)
                        ->orWhere('as_pagadores.identificacion', $search);
                });

            }

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    protected function processEstadoFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('as_afiliado_pagador.estado', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
