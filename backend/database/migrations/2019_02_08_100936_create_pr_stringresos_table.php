<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrStringresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_stringresos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('periodo', 4)->nullable();
            $table->integer('pr_entidad_resolucion_id')->nullable();
            $table->foreign('pr_entidad_resolucion_id')->references('id')->on('pr_entidad_resoluciones')->onDelete('restrict');
            $table->integer('gs_usuario_id')->nullable();
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
            $table->integer('gs_usuario_conf_id')->nullable();
            $table->foreign('gs_usuario_conf_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
            $table->enum('estado', ['Registrada', 'Confirmada','Anulada'])->default('Registrada');
            $table->dateTime('fecha')->nullable();
            $table->dateTime('fecha_confirmacion')->nullable();
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
        Schema::dropIfExists('pr_stringresos');
    }
}
