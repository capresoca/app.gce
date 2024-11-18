<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrModStrgastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_mod_strgastos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('consecutivo');
            $table->string('periodo', 4)->nullable();
            $table->text('documento');
            $table->longText('detalle_modificacion');
            $table->longText('concepto_anulacion')->nullable();
            $table->date('fecha_anulacion')->nullable();
            $table->enum('estado',['Registrada','Anulada','Confirmada']);
            $table->integer('gs_usuario_id');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
            $table->unsignedInteger('gs_strgasto_id');
            $table->foreign('gs_strgasto_id')->references('id')->on('pr_strgastos')->onDelete('restrict');
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
        Schema::dropIfExists('pr_mod_strgastos');
    }
}
