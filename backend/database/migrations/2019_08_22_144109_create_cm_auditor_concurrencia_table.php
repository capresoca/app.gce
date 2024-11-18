<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmAuditorConcurrenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_auditor_concurrencia', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('auditor_id')->unsigned();
            $table->integer('concurrencia_id');
            $table->integer('user_id');
            $table->foreign('auditor_id')->references('id')->on('ac_auditores');
            $table->foreign('concurrencia_id')->references('id')->on('cm_concurrencias');
            $table->foreign('user_id')->references('id')->on('gs_usuarios');
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
        Schema::dropIfExists('cm_auditor_concurrencia');
    }
}
