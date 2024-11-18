<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/01/2019
 * Time: 11:25 AM
 */

namespace App\Capresoca\CuentasMedicas;


use App\Models\Autorizaciones\AuAnexot31;
use App\Models\Autorizaciones\AuAutaprobada;
use App\Models\CuentasMedicas\CmFactura;
use App\Models\RedServicios\RsCups;
use App\Traits\EnumsTrait;

trait ServiciosRipsTrait
{

    protected function getFactura($codigoFactura)
    {
        $consulta = CmFactura::where([
            'cm_radicado_id'    => $this->radicado->id,
            'consecutivo'       => $codigoFactura
        ])->select('id','fecha')->first();

        if(!$consulta) throw new \Exception('No se encontro la factura: '.$codigoFactura);

        return $consulta;
    }

    protected function getAutorizacion($consecutivo)
    {
        $autorizacion = AuAutaprobada::where('consecutivo',$consecutivo)->first();
        if(!$autorizacion) throw new \Exception('No se encontro la factura: '.$consecutivo);
        return $autorizacion;

        //$autorizacion = AuAnexot31::whereId((integer) $consecutivo)->first();
        //
        //if(!$autorizacion) throw new \Exception('No se encontró la factura: '.$consecutivo);
        //
        //return $autorizacion;
    }

    protected function getCup($codigoCup)
    {
        $cup = RsCups::where('codigo',$codigoCup)->select('id','descripcion')->first();

        if(!$cup) throw new \Exception('No se encontro el cup: '.$codigoCup);

        return $cup;
    }

    protected function getValueForEnum($table,$column, $index)
    {
        if(!$index) return null;

        $enum_values = EnumsTrait::getEnumValues($table,$column);

        return $enum_values[$index - 1];

    }

}