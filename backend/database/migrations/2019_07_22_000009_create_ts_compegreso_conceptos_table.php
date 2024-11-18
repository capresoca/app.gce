<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsCompegresoConceptosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'ts_compegreso_conceptos';

    /**
     * Run the migrations.
     * @table ts_compegreso_conceptos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ts_compegreso_id')->unsigned();
            $table->integer('ts_concepto_egreso_id');
            $table->timestamps();


            $table->foreign('ts_compegreso_id', 'fk_ts_compegreso_conceptos_ts_compegresos1_idx')
                ->references('id')->on('ts_compegresos')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->foreign('ts_concepto_egreso_id', 'fk_ts_compegreso_conceptos_ts_concepto_egresos1_idx')
                ->references('id')->on('ts_concepto_egresos')
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
