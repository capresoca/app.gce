<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpAcxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_acx', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proceso_id')->unsigned()->nullable();
            $table->string('codeps',6);
            $table->date('fecprocompe');
            $table->string('periodocompe',7);
            $table->char('tipodoccot',2);
            $table->string('numdoccot',16);
            $table->char('tipocot',2);
            $table->string('tipodocapo',2);
            $table->string('numdocapo',20);
            $table->integer('ibc');
            $table->tinyInteger('totaldiascotiz');
            $table->integer('valorcoti');
            $table->date('fecconsigapo');
            $table->char('codoperador');
            $table->string('numplanilla',15)->index();
            $table->tinyInteger('regcompen');
            $table->string('codglosa',60)->index();
            $table->tinyInteger('totaldiascompe');
            $table->char('grupoentidad',2);
            $table->char('zonageografica',1);
            $table->double('upcreconocida',8,2);
            $table->double('provincapacidades',8,2);
            $table->double('valorfondpyp',8,2);
            $table->double('valorupcpyp',8,2);
            $table->double('valorcotizsubsoli',8,2);
            $table->string('seriabdua')->index();
            $table->string('seriaha')->index();
            $table->timestamps();

            $table->index(['tipodoccot','numdoccot'],'idenCoti');
            $table->index(['tipodocapo','numdocapo'],'idenApor');

            $table->foreign('proceso_id')->references('id')->on('cp_resultados_compensacions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_acx');
    }
}

