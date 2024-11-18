<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConegresosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'cm_conegresos';

    /**
     * Run the migrations.
     * @table cm_conegresos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('cm_concurrencias_id');
            $table->dateTime('fecha_egreso');
            $table->enum('estado_salida', ['Vivo', 'Muerto', 'Remitido']);
            $table->integer('rs_entidad_destino_id')->nullable();
            $table->integer('dx_egreso');
            $table->integer('dx_relacionado')->nullable();
            $table->nullableTimestamps();


            $table->foreign('cm_concurrencias_id')
                ->references('id')->on('cm_concurrencias')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('rs_entidad_destino_id')
                ->references('id')->on('rs_entidades')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('dx_egreso')
                ->references('id')->on('rs_cie10s')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('dx_relacionado')
                ->references('id')->on('rs_cie10s')
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
