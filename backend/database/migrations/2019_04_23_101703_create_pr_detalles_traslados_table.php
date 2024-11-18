<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrDetallesTrasladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_detalles_traslados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pr_traslado_id');
            $table->foreign('pr_traslado_id')->references('id')->on('pr_traslados')->onDelete('restrict');
            $table->unsignedInteger('pr_detstrgasto_id')->nullable();
            $table->foreign('pr_detstrgasto_id')->references('id')->on('pr_detstrgastos')->onDelete('restrict');
            $table->unsignedInteger('pr_detstringreso_id')->nullable();
            $table->foreign('pr_detstringreso_id')->references('id')->on('pr_detstringresos')->onDelete('restrict');
            $table->integer('pr_tipo_gasto_id')->nullable();
            $table->foreign('pr_tipo_gasto_id')->references('id')->on('pr_tipos_gastos')->onDelete('restrict');
            $table->integer('pr_tipo_ingreso_id')->nullable();
            $table->foreign('pr_tipo_ingreso_id')->references('id')->on('pr_tipo_ingresos')->onDelete('restrict');
            $table->unsignedInteger('pr_rubro_id')->nullable();
            $table->foreign('pr_rubro_id')->references('id')->on('pr_conceptos')->onDelete('restrict');
            $table->unsignedInteger('pr_rubro_trasladado_id')->nullable();
            $table->foreign('pr_rubro_trasladado_id')->references('id')->on('pr_conceptos')->onDelete('restrict');
            $table->double('saldo_rubro')->default(0);
            $table->double('valor_movimiento')->default(0);
            $table->enum('naturaleza',['Crédito', 'Débito']);
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
        Schema::dropIfExists('pr_detalles_traslados');
    }
}
