<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoTramiteInEnumTramites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `as_tramites` 
        CHANGE COLUMN `tipo_tramite` `tipo_tramite` ENUM('Afiliacion', 'Novedad Subsidiado', 'Novedad Contributivo', 
            'Novedad Retroactivo', 'Afiliacion Aportante', 'Traslado Subsidiado', 'Traslado Contributivo', 'S4', 'R4','MC') 
        CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL COMMENT 'Tipo de Tramite a realizar. Numeral 1 del formulario';");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
