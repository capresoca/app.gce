<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsvalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TABLE IF NOT EXISTS `ms_val` (
            `as_svid_id` INT(10) NOT NULL,
            `codigoEntidad` char(6)  DEFAULT NULL,
            `tipoIden` char(2)  DEFAULT NULL,
            `identificacion` char(20)  DEFAULT NULL,
            `primerApellido` char(45)  DEFAULT NULL,
            `segundoApellido` char(45)  DEFAULT NULL,
            `primerNombre` char(45)  DEFAULT NULL,
            `segundoNombre` char(45)  DEFAULT NULL,
            `fechaNacimiento` char(10)  DEFAULT NULL,
            `genero` char(1)  DEFAULT NULL,
            `codigoDepartamento` char(2)  DEFAULT NULL,
            `codigoMunicipio` char(3)  DEFAULT NULL,
            `zona` char(1)  DEFAULT NULL,
            `fechaAfiliacionSistema` char(10)  DEFAULT NULL,
            `tipoPoblacion` char(2)  DEFAULT NULL,
            `nivelSisben` char(1)  DEFAULT NULL,
            `ipsPrimaria` char(1)  DEFAULT NULL,
            `condicionDiscapacidad` char(2)  DEFAULT NULL,
            `tipDocCabeza` char(2)  DEFAULT NULL,
            `identCabeza` char(16)  DEFAULT NULL,
            `parentezco` char(10)  DEFAULT NULL,
            `etnia` char(2)  DEFAULT NULL,
            KEY `as_svid_id` (`as_svid_id`),
            KEY `tipoIden_identificacion` (`tipoIden`,`identificacion`))
            ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ms_val');
    }
}
