<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCeRubroEstudioprevioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ce_rubro_estudioprevio', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ce_estprevio_id');
            $table->integer('pr_detstrgasto_id')->unsigned();
            $table->foreign('ce_estprevio_id')->references('id')->on('ce_proconestprevios');
            $table->foreign('pr_detstrgasto_id')->references('id')->on('pr_detstrgastos');
            $table->double('valor')->nullable();
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
        Schema::dropIfExists('ce_rubro_estudioprevio');
    }
}
