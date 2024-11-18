<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpAbxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_abx', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proceso_id')->unsigned()->nullable();
            $table->char('codeps',6);
            $table->date('fecprocompe');
            $table->char('periodocompe',7);
            $table->char('tipodoccot',2);
            $table->string('numdoccot',20);
            $table->char('tipodocben',2);
            $table->string('numdocben',20);
            $table->char('tipoafil',1);
            $table->char('parentesco',1);
            $table->tinyInteger('totaldiascompen');
            $table->integer('valorupcadicio');
            $table->date('fecconsigupcadicio');
            $table->char('codoperador',2);
            $table->string('numplanilla',15);
            $table->char('regcompen',1);
            $table->string('codglosa',60);
            $table->string('grupedad',2);
            $table->string('zonageografica',2);
            $table->double('upcreconocida',10,2);
            $table->double('valorupcfondpyp',10,2);
            $table->double('valorupcadiciofosyga',10,2);
            $table->double('valorsoliupcadicio',10,2);
            $table->string('seriabdua')->index();
            $table->string('seriah')->index();
            $table->foreign('proceso_id')->references('id')->on('cp_resultados_compensacions');
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
        Schema::dropIfExists('cp_abx');
    }
}

