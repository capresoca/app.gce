<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReRepdetallesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 're_repdetalles';

    /**
     * Run the migrations.
     * @table re_repdetalles
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('re_reportes_id')->unsigned();
            $table->string('ref', 45)->comment('Variable que la sentencia SQL');
            $table->string('type', 50)->comment('Tipo de dato de la variable de la sentencia SQL');
            $table->string('label')->comment('Nombre para mostrar en la interfaz gráfica.');

            $table->index(["re_reportes_id"], 'fk_re_repdetalles_re_reportes1_idx');
            $table->nullableTimestamps();


            $table->foreign('re_reportes_id', 'fk_re_repdetalles_re_reportes1_idx')
                ->references('id')->on('re_reportes')
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
