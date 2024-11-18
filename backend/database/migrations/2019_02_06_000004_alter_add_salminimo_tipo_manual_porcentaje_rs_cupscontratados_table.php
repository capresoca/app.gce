<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddSalminimoTipoManualPorcentajeRsCupscontratadosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'rs_cupscontratados';

    /**
     * Run the migrations.
     * @table rs_cupscontratados
     *
     * @return void
     */
    public function up()
    {
        // if (Schema::hasTable($this->set_schema_table)) return;
        Schema::table($this->set_schema_table, function (Blueprint $table) {
            $table->integer('rs_salminimo_id')->nullable();
            $table->enum('tipo_manual', ['ISS', 'SOAT', 'Propio'])->nullable();
            $table->double('porcentaje')->nullable();
            $table->index(["rs_salminimo_id"], 'fk_rs_cupscontratados_rs_salminimos1_idx');
            $table->foreign('rs_salminimo_id', 'fk_rs_cupscontratados_rs_salminimos1_idx')
                ->references('id')->on('rs_salminimos')
                ->onDelete('restrict')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       // Schema::dropIfExists($this->set_schema_table);
     }
}
