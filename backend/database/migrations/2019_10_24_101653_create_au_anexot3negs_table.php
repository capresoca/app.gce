<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuAnexot3negsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('au_anexot3negs', function (Blueprint $table) {
            $table->increments('id');
            $table->char('codigo',1);
            $table->longText('observacion')->default(null);
            $table->integer('gs_usuario_id');
            $table->unsignedInteger('au_anexot31_id');
            $table->timestamps();

            $table->foreign('codigo')->references('codigo')->on('refmotivoneg')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('au_anexot31_id')->references('id')->on('au_anexot31s')->onUpdate('no action')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('au_anexot3negs');
    }
}
