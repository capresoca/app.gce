<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConvisitasTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'cm_convisitas';

    /**
     * Run the migrations.
     * @table cm_convisitas
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('cm_concurrencia_id');
            $table->dateTime('fecha_visita');
            $table->longText('evolucion');
            $table->longText('observaciones')->nullable();
            $table->integer('gs_usuario_id');
            $table->nullableTimestamps();


            $table->foreign('cm_concurrencia_id')
                ->references('id')->on('cm_concurrencias')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('gs_usuario_id')
                ->references('id')->on('gs_usuarios')
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
