<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReReprolesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 're_reproles';

    /**
     * Run the migrations.
     * @table re_reproles
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('re_reporte_id')->unsigned();
            $table->integer('gs_role_id');

            $table->nullableTimestamps();

            $table->foreign('re_reporte_id', 'fk_re_reproles_re_reportes1_idx')
                ->references('id')->on('re_reportes')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('gs_role_id', 'fk_re_reproles_gs_roles1_idx')
                ->references('id')->on('gs_roles')
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
