<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrModDetStrgastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_mod_det_strgastos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pr_mod_strgasto_id')->nullable();
            $table->foreign('pr_mod_strgasto_id')->references('id')->on('pr_mod_strgastos')->onDelete('restrict');
            $table->unsignedInteger('pr_detstrgasto_id')->nullable();
            $table->foreign('pr_detstrgasto_id')->references('id')->on('pr_detstrgastos')->onDelete('restrict');
            $table->unsignedInteger('pr_rubro_id')->nullable();
            $table->foreign('pr_rubro_id')->references('id')->on('pr_conceptos')->onDelete('restrict');
            $table->double('valor_inicial')->nullable();
            $table->enum('naturaleza', ['Crédito','Débito']);
            $table->double('valor_movimiento')->nullable();
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
        Schema::dropIfExists('pr_mod_det_strgastos');
    }
}
