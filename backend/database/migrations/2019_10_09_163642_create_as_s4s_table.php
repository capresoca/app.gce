<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsS4sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_s4s', function (Blueprint $table) {
            $table->integer('serial');
            $table->string('epssol', 7);
            $table->char('tipoIdentificacion', 2);
            $table->string('identificacion', 20);
            $table->string('respuesta', 7);
            $table->char('causal', 2);
            $table->string('fechaFactible', 10);
            $table->unsignedInteger('as_svid_id')->nullable();
            $table->foreign('as_svid_id')->references('id')->on('as_s1vids')->onUpdate('no action')->onDelete('restrict');
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
        Schema::dropIfExists('as_s4s');
    }
}
