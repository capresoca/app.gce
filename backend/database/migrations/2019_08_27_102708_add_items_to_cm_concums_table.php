<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsToCmConcumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('cm_concums', function (Blueprint $table) {
            $table->dateTime('fecha');
            $table->integer('cm_manglosa_id');
            $table->foreign('cm_manglosa_id')->references('id')->on('cm_manglosas')->onUpdate('no action')->onDelete('restrict');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_concums', function (Blueprint $table) {
            //
        });
    }
}
