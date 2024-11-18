<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31/08/2018
 * Time: 5:10 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA;


use App\Models\Aseguramiento\AsBduaproceso;

interface ProcesadorCSVInterface
{
    public function __construct($csvPath,AsBduaproceso $proceso);
    public function procesar();
}