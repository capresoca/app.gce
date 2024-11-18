<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNsncvalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TABLE IF NOT EXISTS `ns_nc_val` (
              `as_svid_id` INT(10) NOT NULL,
              `consecutivo` varchar(12) NOT NULL DEFAULT '',
              `codigoEntidad` varchar(6) NOT NULL,
              `tipoIdentificacion` char(2) NOT NULL DEFAULT '',
              `identificacion` varchar(20) NOT NULL DEFAULT '',
              `primerApellido` varchar(20) NOT NULL DEFAULT '',
              `segundoApellido` varchar(20) NOT NULL DEFAULT '',
              `primerNombre` varchar(20) NOT NULL DEFAULT '',
              `segundoNombre` varchar(20) NOT NULL DEFAULT '',
              `fechaNacimiento` varchar(10) NOT NULL DEFAULT '',
              `codigoDepartamento` char(2) NOT NULL DEFAULT '',
              `codigoMunicipio` char(3) NOT NULL DEFAULT '',
              `codigoNovedad` char(3) NOT NULL DEFAULT '',
              `fechaInicio` varchar(10) NOT NULL DEFAULT '',
              `nuevoDato` varchar(20) NOT NULL DEFAULT '',
              `nuevoDato1` varchar(20) NOT NULL DEFAULT '',
              `nuevoDato2` varchar(20) NOT NULL DEFAULT '',
              `nuevoDato3` varchar(20) NOT NULL DEFAULT '',
              `nuevoDato4` varchar(20) NOT NULL DEFAULT '',
              `nuevoDato5` varchar(20) NOT NULL DEFAULT '',
              `nuevoDato6` varchar(20) NOT NULL DEFAULT '',
              `envioFosyga` char(1) NOT NULL DEFAULT '1',
              KEY `as_svid_id` (`as_svid_id`),
              KEY `iden` (`tipoIdentificacion`,`identificacion`)
            ) ENGINE=MyISAM AUTO_INCREMENT=1205016 DEFAULT CHARSET=latin1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ns_nc_val');
    }
}
