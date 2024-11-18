<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpSuministrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $primary = DB::select("SELECT count(*) as primaries from INFORMATION_SCHEMA.KEY_COLUMN_USAGE where TABLE_NAME = 'mp_causasnoentregas' AND COLUMN_NAME = 'id' AND CONSTRAINT_SCHEMA = '".env('DB_DATABASE')."'");
        if(!$primary[0]->primaries){
            DB::statement('ALTER TABLE `mp_causasnoentregas`
	            CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT FIRST,
	            ADD PRIMARY KEY (`id`)');
        }
        Schema::create('mp_suministros', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('suministro_id');
            $table->tinyInteger('UltEntrega');
            $table->tinyInteger('EntregaCompleta');
            $table->integer('CausaNoEntrega')->nullable();
            $table->string('NoPrescripcionAsociada');
            $table->integer('ConTecAsociada');
            $table->double('CantTotEntregada',10,3);
            $table->string('NoLote',20);
            $table->double('ValorEntregado',15,3);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('CausaNoEntrega','causane_suministro')->references('id')->on('mp_causasnoentregas');
            $table->foreign('suministro_id')->references('suministro_id')->on('mp_direccionamientos');
            $table->foreign('NoPrescripcionAsociada')->references('NoPrescripcion')->on('mp_prescripciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_suministros');
    }
}
