<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnumTipoManualInRsCupscontratadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_cupscontratados', function (Blueprint $table) {
            $table->dropForeign('fk_rs_cupscontratados_rs_salminimos1_idx');
            $table->dropColumn('rs_salminimo_id');
            $table->dropColumn('tipo_manual');
            $table->dropColumn('porcentaje');
        });
        Schema::table('rs_cupscontratados', function (Blueprint $table) {
            $table->integer('rs_salminimo_id')->nullable();
            $table->enum('tipo_manual', ['ISS', 'SOAT', 'Institucional'])->nullable();
            $table->double('porcentaje')->nullable();
            $table->index(["rs_salminimo_id"], 'fk_rs_cupscontratados_rs_salminimos1_idx');
            $table->foreign('rs_salminimo_id', 'fk_rs_cupscontratados_rs_salminimos1_idx')
                ->references('id')->on('rs_salminimos')
                ->onDelete('restrict')
                ->onUpdate('no action');
        });

    }

    public function down()
    {
        Schema::table('rs_cupscontratados', function (Blueprint $table) {
            $table->dropForeign('fk_rs_cupscontratados_rs_salminimos1_idx');
            $table->dropColumn('rs_salminimo_id');
            $table->dropColumn('tipo_manual');
            $table->dropColumn('porcentaje');
        });
    }


}
