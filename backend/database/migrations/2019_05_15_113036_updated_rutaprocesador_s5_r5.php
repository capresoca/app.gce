<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Aseguramiento\AsTipbduaarchivo;

class UpdatedRutaprocesadorS5R5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AsTipbduaarchivo::updateOrCreate(
            [
                'iniciales' => 'R5'
            ],
            [
                'iniciales' => 'R5',                 
                'sufijo' => NULL,           
                'nombre' => 'Aprobados o Negados Solicitud Traslado Contributivo',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\R5',
                'tipo_tramite'=> 'Traslado Contributivo',
            ]
        );
        AsTipbduaarchivo::updateOrCreate(
            [
                'iniciales' => 'S5'
            ],
            [
                'iniciales' => 'S5',                 
                'sufijo' => NULL,           
                'nombre' => 'Aprobados o Negados Solicitud Traslado Subsidiado',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\S5',
                'tipo_tramite'=> 'Traslado Subsidiado',
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        AsTipbduaarchivo::updateOrCreate(
            [
                'iniciales' => 'R5'
            ],
            [
                'iniciales' => 'R5',                 
                'sufijo' => NULL,           
                'nombre' => 'Aprobados o Negados Solicitud Traslado Contributivo',
                'clase_procesador' => NULL,
                'tipo_tramite'=> 'Traslado Contributivo',
            ]
        );
        AsTipbduaarchivo::updateOrCreate(
            [
                'iniciales' => 'S5'
            ],
            [
                'iniciales' => 'S5',                 
                'sufijo' => NULL,           
                'nombre' => 'Aprobados o Negados Solicitud Traslado Subsidiado',
                'clase_procesador' => NULL,
                'tipo_tramite'=> 'Traslado Subsidiado',
            ]
        );
    }
}
