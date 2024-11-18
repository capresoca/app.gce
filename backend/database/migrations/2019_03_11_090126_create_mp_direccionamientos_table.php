<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpDireccionamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_direccionamientos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('IdDireccionamiento')->nullable();
            $table->integer('mp_prescripcion_id')->unsigned();
            $table->string('NoPrescripcion',20);
            $table->string('TipoTec',1);
            $table->integer('ConTec');
            $table->string('TipoIDPaciente',2);
            $table->string('NoIDPaciente',17);
            $table->integer('as_afiliado_id')->nullable();
            $table->integer('NoEntrega');
            $table->integer('NoSubEntrega')->nullable();
            $table->string('TipoIdProv',2)->default('NI');
            $table->string('NoIDProv',17);
            $table->integer('rs_entidade_id')->nullable();

            $table->integer('CodMunEnt');
            $table->integer('gn_munentidad_id');

            $table->date('FecMaxEnt');
            $table->float('CantTotAEntregar');
            $table->string('DirPaciente', 80)->nullable();
            $table->string('CodSerTecAEntregar',20);

            $table->integer('mp_medicamento_id')->unsigned()->nullable();
            $table->integer('mp_coplementario_id')->unsigned()->nullable();
            $table->integer('mp_nutricional_id')->unsigned()->nullable();
            $table->integer('mp_dispositivo_id')->unsigned()->nullable();
            $table->integer('mp_procedimiento_id')->unsigned()->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('mp_direccionamientos');
    }
}
