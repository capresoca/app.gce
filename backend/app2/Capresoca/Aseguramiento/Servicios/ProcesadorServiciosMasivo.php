<?php

namespace App\Capresoca\Aseguramiento\Servicios;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class ProcesadorServiciosMasivo {

    protected $respuesta = [];
    protected $file;

    public function __construct($file)
    {
        $this->files = $file;
    }
}