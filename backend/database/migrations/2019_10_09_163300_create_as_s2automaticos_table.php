<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsS2automaticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_s2automaticos', function (Blueprint $table) {
            $table->string('serial',20);
            $table->string('codeps',7)->comment('Código de la EPS que solicita el traslado');
            $table->char('tipoIdentificacion', 2);
            $table->string('identificacion', 20);
            $table->string('primerApellido', 30);
            $table->string('segundoApellido', 30);
            $table->string('primerNombre', 30);
            $table->string('segundoNombre', 30);
            $table->char('codigoDepartamento', 2);
            $table->char('codigoMunicipio', 3);
            $table->string('fechaAfiliacion',10);
            $table->string('fechaUpc',10);
            $table->unsignedInteger('as_svid_id')->nullable();
            $table->foreign('as_svid_id')->references('id')->on('as_s1vids')->onUpdate('no action')->onDelete('restrict');
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
        Schema::dropIfExists('as_s2automaticos');
    }
}
