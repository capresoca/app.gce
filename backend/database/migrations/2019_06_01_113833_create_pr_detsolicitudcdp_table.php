<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrDetsolicitudcdpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_detsolicitudcdps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pr_solicitudcdp_id')->unsigned();
            $table->integer('pr_detstrgasto_id')->unsigned();
            $table->double('valor');
            $table->foreign('pr_solicitudcdp_id')->references('id')->on('pr_solicitud_cps');
            $table->foreign('pr_detstrgasto_id')->references('id')->on('pr_detstrgastos');
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
        Schema::dropIfExists('pr_detsolicitudcdps');
    }
}
