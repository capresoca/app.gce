<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Aseguramiento\AsTipbduaarchivo;

class InsertTipbduaarchivosS4valR4val extends Migration
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
                'iniciales' => 'S4',
                'sufijo' => 'VAL'
            ],
            [
                'iniciales' => 'S4',                 
                'sufijo' => 'VAL',           
                'nombre' => 'Respuestas Valida a solicitud de traslado regimen Subsidiado',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\S4VAL',
                'tipo_tramite'=>'S4',
            ]
        );
        AsTipbduaarchivo::updateOrCreate(
            [
                'iniciales' => 'R4',
                'sufijo' => 'VAL'
            ],
            [
                'iniciales' => 'R4',                 
                'sufijo' => 'VAL',           
                'nombre' => 'Respuestas Valida a solicitud de traslado regimen Contributivo',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\R4VAL',
                'tipo_tramite'=>'R4',
            ]
        );
        AsTipbduaarchivo::updateOrCreate(
            [
                'iniciales' => 'S4',
                'sufijo' => 'NEG'
            ],
            [
                'iniciales' => 'S4',                 
                'sufijo' => 'NEG',           
                'nombre' => 'Respuestas Negada/Glosada a solicitud de traslado regimen Subsidiado',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\S4NEG',
                'tipo_tramite'=>'S4',
            ]
        );
        AsTipbduaarchivo::updateOrCreate(
            [
                'iniciales' => 'R4',
                'sufijo' => 'NEG'
            ],
            [
                'iniciales' => 'R4',                 
                'sufijo' => 'NEG',           
                'nombre' => 'Respuestas Negada/Glosada a solicitud de traslado regimen Contributivo',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\R4NEG',
                'tipo_tramite'=>'R4',
            ]
        );
        AsTipbduaarchivo::updateOrCreate(
            [
                'iniciales' => 'R6'
            ],
            [
                'iniciales' => 'R6',         
                'nombre' => 'Rechazada Solicitud Traslado Contributivo',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\R6',
                'tipo_tramite'=>'Traslado Contributivo',
            ]
        );
        AsTipbduaarchivo::updateOrCreate(
            [
                'iniciales' => 'S6'
            ],
            [
                'iniciales' => 'S6',         
                'nombre' => 'Rechazada Solicitud Traslado Subsidiado',
                'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\S6',
                'tipo_tramite'=>'Traslado Subsidiado',
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
