<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConinsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_coninsumos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',45);
            $table->string('descripcion',500);
            $table->double('valor');
            $table->double('cantidad');
            $table->integer('cm_convisita_id');
            $table->longText('observaciones')->nullable();

            $table->foreign('cm_convisita_id')->references('id')->on('cm_convisitas')->onUpdate('no action')->onDelete('restrict');

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
        Schema::dropIfExists('cm_coninsumos');
    }
}
