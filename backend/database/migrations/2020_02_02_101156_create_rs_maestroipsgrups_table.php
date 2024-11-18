<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsMaestroipsgrupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_maestroipsgrups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('municipio_id')->index()->comment('Id del municipio')->default(null);
            $table->integer('departamento_id')->index()->comment('Id del departamento')->default(null);
            $table->char('descrip')->unique()->comment('Descripción de la tabla grupo IPS')->default(null);
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
        Schema::dropIfExists('rs_maestroipsgrups');
    }
}
