<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConaltocostosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'cm_conaltocostos';

    /**
     * Run the migrations.
     * @table cm_conaltocostos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('cm_concurrencia_id');
            $table->enum('tipo', ['Artritis', 'Cancer', 'Enfermedades Huerfanas', 'Enfermedad Renal Cronica', 'Hemofiliar', 'VIH'])->nullable();
            $table->integer('rs_cie10_id');
            $table->nullableTimestamps();


            $table->foreign('cm_concurrencia_id')
                ->references('id')->on('cm_concurrencias')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('rs_cie10_id')
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
