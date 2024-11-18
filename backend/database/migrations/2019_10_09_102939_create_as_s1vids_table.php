<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsS1vidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_s1vids', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fechaProceso')->default('0000-00-00');
            $table->integer('gs_usuario_id');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onUpdate('no action')->onDelete('restrict');
            $table->dateTime('fS')->default('0000-00-00 00:00:00');
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
        Schema::dropIfExists('as_s1vids');
    }
}
