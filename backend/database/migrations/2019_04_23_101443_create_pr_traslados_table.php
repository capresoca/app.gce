<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrTrasladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_traslados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('consecutivo_presupuestal');
            $table->string('periodo', 4)->nullable();
            $table->date('fecha');
            $table->text('documento');
            $table->longText('observaciones')->nullable();
            $table->longText('concepto_anulacion')->nullable();
            $table->date('fecha_anulacion')->nullable();
            $table->enum('tipo',['Ingreso','Gasto']);
            $table->enum('estado',['Registrado','Anulado','Confirmado']);
            $table->unsignedInteger('pr_strgasto_id')->nullable();
            $table->foreign('pr_strgasto_id')->references('id')->on('pr_strgastos')->onDelete('restrict');
            $table->unsignedInteger('pr_stringreso_id')->nullable();
            $table->foreign('pr_stringreso_id')->references('id')->on('pr_stringresos')->onDelete('restrict');
            $table->integer('gs_usuario_id');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
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
        Schema::dropIfExists('pr_traslados');
    }
}
