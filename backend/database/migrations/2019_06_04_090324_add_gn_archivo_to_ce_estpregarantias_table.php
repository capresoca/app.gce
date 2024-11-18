<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGnArchivoToCeEstpregarantiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_estpregarantias', function (Blueprint $table) {
            $table->integer('gn_archivo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_estpregarantias', function (Blueprint $table) {
            $table->dropColumn('gn_archivo');
        });
    }
}
