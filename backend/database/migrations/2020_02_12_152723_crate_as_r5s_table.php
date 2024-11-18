<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateAsR5sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_r5s', function (Blueprint $table) {
            $table->integer('serial');
            $table->string('epssol', 7);
            $table->char('tipoIdentificacion', 2);
            $table->string('identificacion', 20);
            $table->string('epsact', 7);
            $table->integer('consInterno');
            $table->char('tipoIdentificacion1', 2);
            $table->string('identificacion1', 20);
            $table->char('resultado', 1);
            $table->char('causal', 1);
            $table->string('fechaAprobacion', 10);
            $table->unsignedInteger('as_svid_id')->nullable();
            $table->foreign('as_svid_id')->references('id')->on('as_s1vids')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
