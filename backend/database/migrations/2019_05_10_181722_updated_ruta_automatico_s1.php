<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Aseguramiento\AsTipbduaarchivo;

class UpdatedRutaAutomaticoS1 extends Migration
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
                'iniciales' => 'AUTOMATICOS-S1'
            ],
            [
                'iniciales' => 'AUTOMATICOS-S1',                 
                'sufijo' => NULL,           
                'nombre' => 'Solicitud Entrante Traslado Contributivo Automático',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\S1Automatico',
                'tipo_tramite'=> 'Traslado Contributivo',
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
        //
    }
}
