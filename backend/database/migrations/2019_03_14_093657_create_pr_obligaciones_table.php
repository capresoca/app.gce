<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrObligacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_obligaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('periodo', '4');
            $table->string('vigencia')->nullable();
            $table->integer('consecutivo');
            $table->string('documento');
            $table->longText('observaciones')->nullable();
            $table->date('fecha');
            $table->date('fecha_documento');
            $table->double('valor_obligacion')->default(0);
            $table->enum('estado',['Registrada','Confirmada','Anulada'])->default('Registrada');
            $table->integer('pr_entidad_resolucion_id')->nullable();
            $table->foreign('pr_entidad_resolucion_id')->references('id')->on('pr_entidad_resoluciones')->onDelete('restrict');
            $table->integer('gs_usuario_id')->nullable();
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
            $table->integer('gn_tercero_id')->nullable();
            $table->foreign('gn_tercero_id')->references('id')->on('gn_terceros')->onDelete('restrict');
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
        Schema::dropIfExists('pr_obligaciones');
    }
}
