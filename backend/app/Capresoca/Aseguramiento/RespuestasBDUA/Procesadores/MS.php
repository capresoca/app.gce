<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 8/11/2018
 * Time: 3:36 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;

class MS extends Procesador implements ProcesadorCSVInterface
{

    public function procesar()
    {
        foreach ($this->data as $datum) {
            //buscar el afiliado con precision alta

            //buscar por tipo e identificacion
            //buscar por solo cedula
        }
    }
}