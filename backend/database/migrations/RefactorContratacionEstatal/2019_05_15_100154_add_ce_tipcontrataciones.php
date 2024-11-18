<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCeTipcontrataciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ce_tipcontrataciones`
	                    CHANGE COLUMN `ce_plantilla_id` `ce_plantilla_id` INT(11) NULL AFTER `nombre`;");
        $tipos = [
            [
                'codigo' => 1,
                'nombre' => 'Convocatoria Publica',
            ],
            [
                'codigo' => 2,
                'nombre' => 'Convocatoria Privada o Cerrada',
            ],
            [
                'codigo' => 3,
                'nombre' => 'Contratación directa'
            ],
            [
                'codigo' => 4,
                'nombre' => 'Enajenación de Bienes'
            ]
        ];

        DB::table('ce_tipcontrataciones')->insert($tipos);
    }

}
