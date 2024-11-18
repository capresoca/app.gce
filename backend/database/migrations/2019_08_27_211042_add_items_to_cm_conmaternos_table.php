<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsToCmConmaternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('cm_conmaternos', function (Blueprint $table) {
            $table->integer('cm_complicacionparto_id')->unsigned()->nullable();
            $table->float('peso_recien_nacido')->nullable();
            $table->float('perimetro_cefalico')->nullable();
            $table->float('perimetro_abdominal')->nullable();
            $table->string('apgar')->nullable();
            $table->string('rh',30)->nullable();
            $table->integer('cm_complicacionneonato_id')->unsigned()->nullable();
            $table->enum('condicion_egreso',['VIVO', 'MUERTO', 'REMITIDO', 'PHD']);
            $table->integer('historia_clinica_id')->nullable();

            $table->foreign('cm_complicacionparto_id')->references('id')->on('cm_complicacionpartos')->onDelete('no action')->onUpdate('restrict');
            $table->foreign('cm_complicacionneonato_id')->references('id')->on('cm_complicacionneonatos')->onDelete('no action')->onUpdate('restrict');
            $table->foreign('historia_clinica_id')->references('id')->on('gn_archivos')->onDelete('no action')->onUpdate('restrict');
        });
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_conmaternos` ALTER `dx_nacimiento` DROP DEFAULT, ALTER `dx_relacionado` DROP DEFAULT');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_conmaternos` CHANGE COLUMN `dx_nacimiento` `dx_nacimiento` INT(11) NULL AFTER `edad_gestacion`,
	CHANGE COLUMN `dx_relacionado` `dx_relacionado` INT(11) NULL AFTER `dx_nacimiento`');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_conmaternos', function (Blueprint $table) {
            //
        });
    }
}
