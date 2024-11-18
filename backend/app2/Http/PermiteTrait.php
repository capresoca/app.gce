<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 16/05/2018
 * Time: 2:46 PM
 */

namespace App\Http;

//Este trait permite establecer los permisos para cada uno de los metodos del controlador.

trait PermiteTrait
{
    public static function checkPermisos($controller, $componente_id){
        $controller->middleware('permite:ver,'.$componente_id)->only('index');
        $controller->middleware('permite:agregar,'.$componente_id)->only('store');
        $controller->middleware('permite:ver,'.$componente_id)->only('search');
    }
}