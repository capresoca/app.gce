<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsRefserviciosmedicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_refserviciosmedicos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descrip',50)->unique()->comment('Descripción del servicio médico');
            $table->char('categoria',1)->comment('Categoria del servicio médico');
            $table->integer('ipsprimaria_id')->index()->comment('Id de la ips primaria');
            $table->double('orden',3, 1)->comment('Orden');
            $table->integer('ipsmediana_id')->nullable()->index()->comment('Id de la ips primaria');
            $table->integer('arch')->nullable()->index()->default(null);
            $table->char('capitado',1)->index()->default('0');
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
        Schema::dropIfExists('rs_refserviciosmedicos');
    }
}
