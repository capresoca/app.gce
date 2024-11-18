<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsS1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_s1s', function (Blueprint $table) {
//            $table->increments('id');
            $table->string('codeps',7);
            $table->char('tipoIdentificacion',2);
            $table->string('identificacion',20);
            $table->string('primerApellido',30);
            $table->string('segundoApellido',30);
            $table->string('primerNombre',30);
            $table->string('segundoNombre',30);
            $table->string('fechaNacimiento',10);
            $table->char('genero',1);
            $table->char('tipoIdentificacion1',2);
            $table->string('identificacion1',20);
            $table->string('primerApellido1',30);
            $table->string('segundoApellido1',30);
            $table->string('primerNombre1',30);
            $table->string('segundoNombre1',30);
            $table->string('fechaNacimiento1',10);
            $table->char('genero1',1);
            $table->char('codigoDepartamento',2);
            $table->char('codigoMunicipio',3);
            $table->char('zona',1);
            $table->string('fechaAfiliacionEps',10);
            $table->char('tipoPobla',2);
            $table->char('nsisben',2);
            $table->char('tipoTraslado',2);
            $table->char('tipoIdenCabezaFamilia',2);
            $table->char('idenCabezaFamilia',20);
            $table->char('relacionCabezaFamilia',1);
            $table->dateTime('fS')->default('0000-00-00 00:00:00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('as_s1s');
    }
}
