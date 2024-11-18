<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpReporteEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_direccionamientos', function (Blueprint $table){
            $table->index('suministro_id');
        });

        Schema::create('mp_reporteentregas', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('suministro_id')->index()->nullable();
            $table->string('NoPrescripcion',20)->index();
            $table->string('TipoTec',4);
            $table->integer('ConTec');
            $table->string('TipoIDPaciente',4);
            $table->string('NoIDPaciente',20);
            $table->integer('NoEntrega');
            $table->tinyInteger('EstadoEntrega')->nullable();
            $table->integer('CausaNoEntrega')->nullable();
            $table->double('ValorEntregado')->nullable();
            $table->string('CodTecEntregado',30)->nullable();
            $table->double('CantTotEntregada')->nullable();
            $table->string('NoLote')->nullable();
            $table->dateTime('FecEntrega')->nullable();
            $table->dateTime('FecRepEntrega');
            $table->tinyInteger('EstRepEntrega');
            $table->softDeletes();
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
        Schema::dropIfExists('mp_reporteentregas');
        Schema::table('mp_direccionamientos', function (Blueprint $table){
            $table->dropIndex('mp_direccionamientos_suministro_id_index');
        });
    }
}
