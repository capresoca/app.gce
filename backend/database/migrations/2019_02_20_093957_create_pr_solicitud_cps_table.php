<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrSolicitudCpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_solicitud_cps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('consecutivo');
            $table->date('fecha');
            $table->unsignedInteger('pr_detstrgasto_id');
            $table->foreign('pr_detstrgasto_id')->references('id')->on('pr_detstrgastos')->onDelete('restrict');
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
        Schema::dropIfExists('pr_solicitud_cps');
    }
}
