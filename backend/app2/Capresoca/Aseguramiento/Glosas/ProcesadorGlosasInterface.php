<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/09/2018
 * Time: 11:09 AM
 */

namespace App\Capresoca\Aseguramiento\Glosas;


use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsBduaglosa;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsTramite;

interface ProcesadorGlosasInterface
{
    public function __construct(AsBduaglosa $glosa, AsAfiliado $afiliado, AsBduaregrespuesta $respuesta);
    public function procesar();

}