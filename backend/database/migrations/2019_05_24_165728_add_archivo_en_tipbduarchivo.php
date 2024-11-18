<?php

use App\Models\Aseguramiento\AsTipbduaarchivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArchivoEnTipbduarchivo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `as_tipbduaarchivos`
	        CHANGE COLUMN `tipo_tramite` `tipo_tramite` ENUM('Traslado Subsidiado','Afiliacion','Novedad Subsidiado',
	        'Novedad Contributivo','Afiliacion Aportante','Traslado Contributivo','R4','S4','MC') NOT NULL COLLATE 
	            'utf8mb4_unicode_ci' AFTER `clase_procesador`;");

        DB::table('as_tipbduaarchivos')->insert([
                [
                    'iniciales'     => 'MC',
                    'nombre'        => 'Maestro Contributivo',
                    'tipo_tramite'  => 'MC',
                    'sufijo'        => '',
                    'clase_procesador'=> 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\MCFAC',
                    'id'            => 37
                ],
                [
                    'iniciales'     => 'MC',
                    'nombre'        => 'Fabrica afitramites contributivo',
                    'tipo_tramite'  => 'MC',
                    'sufijo'        => 'FAC',
                    'clase_procesador'=> 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\MCFAC',
                    'id'            => 38
                ],
                [
                    'iniciales'     => 'MC',
                    'nombre'        => 'Maestro Contributivo Validos',
                    'tipo_tramite'  => 'MC',
                    'sufijo'        => 'VAL',
                    'clase_procesador'=> 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\MCVAL',
                    'id'            => 39
                ],
                [
                    'iniciales'     => 'MC',
                    'nombre'        => 'Maestro Contributivo Glosados',
                    'tipo_tramite'  => 'MC',
                    'sufijo'        => 'NEG',
                    'clase_procesador'=> 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\MCNEG',
                    'id'            => 40
                ]

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

        AsTipbduaarchivo::find(37)->delete();
        AsTipbduaarchivo::find(38)->delete();
        AsTipbduaarchivo::find(39)->delete();
        AsTipbduaarchivo::find(40)->delete();

        DB::statement("ALTER TABLE `as_tipbduaarchivos`
	        CHANGE COLUMN `tipo_tramite` `tipo_tramite` ENUM('Traslado Subsidiado','Afiliacion','Novedad Subsidiado',
	        'Novedad Contributivo','Afiliacion Aportante','Traslado Contributivo','R4','S4') NOT NULL COLLATE 
	            'utf8mb4_unicode_ci' AFTER `clase_procesador`;");
    }
}
