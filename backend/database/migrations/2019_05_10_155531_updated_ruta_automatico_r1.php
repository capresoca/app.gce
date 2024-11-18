<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Aseguramiento\AsTipbduaarchivo;

class UpdatedRutaAutomaticoR1 extends Migration
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
                'iniciales' => 'AUTOMATICOS-R1'
            ],
            [
                'iniciales' => 'AUTOMATICOS-R1',                 
                'sufijo' => NULL,           
                'nombre' => 'Solicitud Entrante Traslado Contributivo Automático',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\R1Automatico',
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
