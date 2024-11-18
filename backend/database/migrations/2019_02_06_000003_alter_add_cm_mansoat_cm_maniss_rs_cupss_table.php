<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddCmMansoatCmManissRsCupssTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'rs_cupss';

    /**
     * Run the migrations.
     * @table rs_cupss
     *
     * @return void
     */
    public function up()
    {
        // if (Schema::hasTable($this->set_schema_table)) return;
        Schema::table($this->set_schema_table, function (Blueprint $table) {
            $table->integer('cm_mansoat_id')->unsigned()->nullable();
            $table->integer('cm_maniss_id')->unsigned()->nullable();
            $table->index(["cm_mansoat_id"], 'fk_rs_cupss_cm_mansoats_idx');
            $table->index(["cm_maniss_id"], 'fk_rs_cupss_cm_manisss1_idx');
            $table->foreign('cm_mansoat_id', 'fk_rs_cupss_cm_mansoats_idx')
                ->references('id')->on('cm_mansoats')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('cm_maniss_id', 'fk_rs_cupss_cm_manisss1_idx')
                ->references('id')->on('cm_manisss')
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
       // Schema::dropIfExists($this->set_schema_table);
     }
}
