<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmPacientesHospitalariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_pacientes_hospitalarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cm_censo_id')->unsigned();
            $table->integer('as_afiliado_id')->nullable();
            $table->integer('cm_concurrencia_id')->nullable();
            $table->string('identificacion',20);
            $table->string('nombre_paciente')->nullable();
            $table->integer('consecutivo_entidad')->nullable();
            $table->string('cama_servicio')->nullable();
            $table->dateTime('fecha_ingreso');
            $table->string('cod_dx')->nullable();
            $table->string('nombre_diagnostico')->nullable();
            $table->enum('estado',['Sin Asignar','Asignado','Cerrado']);

            $table->foreign('cm_censo_id')->references('id')->on('cm_censos')->onDelete('cascade');
            $table->foreign('cm_concurrencia_id')->references('id')->on('cm_concurrencias');
            $table->foreign('as_afiliado_id')->references('id')->on('as_afiliados');
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
        Schema::dropIfExists('cm_pacientes_hospitalarios');
    }
}
