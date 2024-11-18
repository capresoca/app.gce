<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConcurrenciasTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'cm_concurrencias';

    /**
     * Run the migrations.
     * @table cm_concurrencias
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('as_afiliado_id');
            $table->bigInteger('consecutivo');
            $table->string('consecutivo_ips', 45)->nullable();
            $table->dateTime('fecha');
            $table->integer('rs_entidad_id');
            $table->integer('rs_servicio_id');
            $table->integer('rs_especialidad_id');
            $table->integer('au_referencia_id')->nullable();
            $table->enum('estado', ['Abierta', 'Cerrada']);
            $table->integer('gs_usuario_id');
            $table->nullableTimestamps();


            $table->foreign('gs_usuario_id')
                ->references('id')->on('gs_usuarios')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('rs_entidad_id')
                ->references('id')->on('rs_entidades')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('rs_servicio_id')
                ->references('id')->on('rs_servicios')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('rs_especialidad_id')
                ->references('id')->on('rs_servicios')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('au_referencia_id')
                ->references('id')->on('au_referencias')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('as_afiliado_id')
                ->references('id')->on('as_afiliados')
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
