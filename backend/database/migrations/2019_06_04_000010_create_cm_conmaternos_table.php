<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConmaternosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'cm_conmaternos';

    /**
     * Run the migrations.
     * @table cm_conmaternos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('cm_concurrencia_id');
            $table->dateTime('fecha_parto')->nullable();
            $table->enum('tipo_parto', ['Unico', 'Multiple']);
            $table->enum('via_parto', ['Vaginal', 'Cesarea'])->nullable();
            $table->integer('controles')->nullable();
            $table->integer('edad_gestacion')->nullable();
            $table->integer('dx_nacimiento');
            $table->integer('dx_relacionado');
            $table->nullableTimestamps();


            $table->foreign('cm_concurrencia_id')
                ->references('id')->on('cm_concurrencias')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('dx_nacimiento')
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
