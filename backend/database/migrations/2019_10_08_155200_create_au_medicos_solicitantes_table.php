<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuMedicosSolicitantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('au_medicos_solicitantes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->string('descripcion',255);
            $table->integer('au_especialidad_id')->unsigned();
            $table->integer('gs_usuario_id');
            $table->timestamps();
            $table->unique('codigo','au_especialidad_id');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios');
            $table->foreign('au_especialidad_id')->references('id')->on('au_especialidads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('au_medicos_solicitantes');
        Schema::enableForeignKeyConstraints();
    }
}
