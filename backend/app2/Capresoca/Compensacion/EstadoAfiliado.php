<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27/12/2018
 * Time: 10:11 AM
 */

namespace App\Capresoca\Compensacion;


use App\Models\Aseguramiento\AsAfiliado;

class EstadoAfiliado
{
    protected $afiliado;

    public function __construct(AsAfiliado $afiliado)
    {
        $this->afiliado = $afiliado;
    }

    public function getTotalIngresos()
    {
        return $this->afiliado->relaciones_laborales()
                    ->where('estado','Activo')
                    ->sum('ibc');
    }
}