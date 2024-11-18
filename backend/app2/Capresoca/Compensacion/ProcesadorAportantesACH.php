<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19/10/2018
 * Time: 5:15 PM
 */

namespace App\Capresoca\Compensacion;


use App\Capresoca\LeerCsv;

class ProcesadorAportantesACH
{
    use LeerCsv;
    protected $archivosAportantes;
    public function __construct($archivoAportantes)
    {
        $this->archivosAportantes = $archivoAportantes;
    }

    public function procesar(){
        $readerFinanciero = $this->leerFromUrl($this->archivosAportes->archivoFinanciero->ruta);
        $data = $readerFinanciero->getRecords();

    }
}