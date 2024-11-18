<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Aseguramiento\AsTipbduaarchivo;
use Illuminate\Support\Facades\DB;

class InsertTipbduarchivosS4R4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    DB::statement("ALTER TABLE as_tipbduaarchivos
	CHANGE COLUMN `tipo_tramite` `tipo_tramite` 
    ENUM('Traslado Subsidiado','Afiliacion','Novedad Subsidiado','Novedad Contributivo','Afiliacion Aportante','Traslado Contributivo','R4','S4') 
    NOT NULL;");
        AsTipbduaarchivo::updateOrCreate(
            ['id' => 30],
            [
                'iniciales' => 'S4',                
                'nombre' => 'Respuestas a solicitud de traslado regimen Subsidiado',
                'tipo_tramite'=>'S4',
            ]
        );
        AsTipbduaarchivo::updateOrCreate(
            ['id' => 31],
            [
                'iniciales' => 'R4',                
                'nombre' => 'Respuestas a solicitud de traslado regimen Contributivo',
                'tipo_tramite'=>'R4',
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
