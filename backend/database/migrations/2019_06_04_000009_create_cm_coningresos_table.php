<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConingresosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'cm_coningresos';

    /**
     * Run the migrations.
     * @table cm_coningresos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('cm_concurrencia_id');
            $table->dateTime('fecha_ingreso');
            $table->enum('via_ingreso', ['Consulta Externa', 'Urgencias', 'Remitido']);
            $table->integer('rs_entidad_id');
            $table->integer('dx_ingreso');
            $table->integer('dx_relacionado')->nullable();
            $table->integer('dx_relacionado2')->nullable();
            $table->nullableTimestamps();


            $table->foreign('cm_concurrencia_id')
                ->references('id')->on('cm_concurrencias')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('rs_entidad_id')
                ->references('id')->on('rs_entidades')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('dx_ingreso')
                ->references('id')->on('rs_cie10s')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('dx_relacionado')
                ->references('id')->on('rs_cie10s')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('dx_relacionado2')
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
