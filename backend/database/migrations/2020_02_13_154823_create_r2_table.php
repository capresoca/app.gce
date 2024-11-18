<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateR2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_r2s', function (Blueprint $table) {
            $table->string('serial',20);
            $table->string('codeps',7)->comment('Código de la EPS que solicita el traslado');
            $table->char('tipoIdentificacion', 2);
            $table->string('identificacion', 20);
            $table->string('primerApellido', 30);
            $table->string('segundoApellido', 30);
            $table->string('primerNombre', 30);
            $table->string('segundoNombre', 30);
            $table->string('fechaAfiliacion',10);
            $table->string('fechaUpc',10);
            $table->char('tipoIdentCot',2);
            $table->string('idenCot',16);
            $table->char('parentezco',1);
            $table->char('codDepartamento',2);
            $table->char('codMunicipio',3);
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
        Schema::dropIfExists('r2');
    }
}
