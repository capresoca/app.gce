<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConeventosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'cm_coneventos';

    /**
     * Run the migrations.
     * @table cm_coneventos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('cm_convisita_id');
            $table->enum('tipo', ['Evento Adverso', 'Falla', 'Fuga']);
            $table->dateTime('fecha')->nullable();
            $table->longText('descripcion')->nullable();
            $table->tinyInteger('salud_publica')->nullable()->default('0');
            $table->nullableTimestamps();


            $table->foreign('cm_convisita_id')
                ->references('id')->on('cm_convisitas')
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
