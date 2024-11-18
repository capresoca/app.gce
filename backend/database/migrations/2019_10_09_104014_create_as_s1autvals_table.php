<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsS1autvalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_s1autvals', function (Blueprint $table) {
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
            $table->char('tipoSubsidio',2);
            $table->unsignedInteger('as_svid_id')->index()->comment('FK -ID Tabla de control');
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
        Schema::dropIfExists('as_s1autvals');
    }
}
