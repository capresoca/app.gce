<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Aseguramiento\AsTipbduaarchivo;

class UpdatedRutaAutomaticoR2S2 extends Migration
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
                'iniciales' => 'AUTOMATICOS-R2'
            ],
            [
                'iniciales' => 'AUTOMATICOS-R2',                 
                'sufijo' => NULL,           
                'nombre' => 'Solicitud Entrante Traslado Contributivo Automático',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\R2Automatico',
                'tipo_tramite'=> 'Traslado Contributivo',
            ]
        );
        AsTipbduaarchivo::updateOrCreate(
            [
                'iniciales' => 'AUTOMATICOS-S2'
            ],
            [
                'iniciales' => 'AUTOMATICOS-S2',                 
                'sufijo' => NULL,           
                'nombre' => 'Solicitud Entrante Traslado Subsidiado Automático',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\S2Automatico',
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
        //
    }
}
