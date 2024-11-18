<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeAddEstadosTramites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        DB::statement("ALTER TABLE `as_tramites` 
        CHANGE COLUMN `estado` `estado` ENUM('Registrado', 'Radicado', 'Enviado', 'Glosado', 'Validado', 'Anulado', 'Negado', 'Aprobado') 
        NOT NULL DEFAULT 'Registrado' ;");
        DB::statement("ALTER TABLE `as_tramites` 
        CHANGE COLUMN `tipo_tramite` `tipo_tramite` ENUM('Afiliacion', 'Novedad Subsidiado', 'Novedad Contributivo', 'Novedad Retroactivo', 'Afiliacion Aportante', 'Traslado Subsidiado', 'Traslado Contributivo', 'Solicitud Traslado Subsidiado', 'Solicitud Traslado Contributivo') 
        CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL COMMENT 'Tipo de Tramite a realizar. Numeral 1 del formulario';");
         
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
