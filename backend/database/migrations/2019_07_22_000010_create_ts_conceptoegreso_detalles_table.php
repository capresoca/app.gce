<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsConceptoegresoDetallesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'ts_conceptoegreso_detalles';

    /**
     * Run the migrations.
     * @table ts_conceptoegreso_detalles
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ts_compegreso_concepto_id')->unsigned();
            $table->integer('pg_cxp_id')->nullable();
            $table->double('valor')->nullable();

            $table->timestamps();

            $table->foreign('ts_compegreso_concepto_id', 'fk_ts_conceptoegreso_detalles_ts_compegreso_conceptos1_idx')
                ->references('id')->on('ts_compegreso_conceptos')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->foreign('pg_cxp_id', 'fk_ts_conceptoegreso_detalles_pg_cxps1_idx')
                ->references('id')->on('pg_cxps')
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
       Schema::dropIfExists($this->tableName);
     }
}
