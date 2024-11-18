<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRevisionFinalizadaToCmFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_facturas', function (Blueprint $table) {
            $table->tinyInteger('revision_finalizada')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_facturas', function (Blueprint $table) {
            $table->dropColumn('revision_finalizada');
        });
    }
}
