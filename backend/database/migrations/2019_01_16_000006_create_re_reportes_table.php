<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReReportesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 're_reportes';

    /**
     * Run the migrations.
     * @table re_reportes
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nombre')->comment('Nombre del reporte');
            $table->longText('sentencia')->comment('Sentencia SQL');
            $table->string('descripcion')->nullable()->comment('Descripcion del reporte');
            $table->integer('gs_modulo_id')->comment('FK - Modulo donde aparecerá el reporte');
            $table->integer('gs_usuario_id')->comment('FK - Usuario que crea el reporte');

            $table->index(["gs_modulo_id"], 'fk_re_reportes_gs_modulos_idx');

            $table->index(["gs_usuario_id"], 'fk_re_reportes_gs_usuarios1_idx');
            $table->nullableTimestamps();


            $table->foreign('gs_modulo_id', 'fk_re_reportes_gs_modulos_idx')
                ->references('id')->on('gs_modulos')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('gs_usuario_id', 'fk_re_reportes_gs_usuarios1_idx')
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
       Schema::dropIfExists($this->set_schema_table);
     }
}
