<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablesConcurrencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_conaltocostos', function (Blueprint $table) {
            $table->unique('cm_concurrencia_id');
        });
        Schema::table('cm_conmaternos', function (Blueprint $table) {
            $table->unique('cm_concurrencia_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_conaltocostos', function (Blueprint $table) {
            //
        });
    }
}
