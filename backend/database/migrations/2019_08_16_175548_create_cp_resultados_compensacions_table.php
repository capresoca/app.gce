<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpResultadosCompensacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cp_archivocompensaciones');
        Schema::create('cp_resultados_compensacions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('archivo_abx_id')->nullable();
            $table->integer('archivo_acx_id')->nullable();
            $table->json('errors_abx')->nullable();
            $table->json('errors_acx')->nullable();
            $table->date('fecha_resultado');
            $table->enum('estado',['Procesando','Procesado']);
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
        Schema::dropIfExists('cp_resultados_compensacions');
    }
}
