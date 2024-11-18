<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsCmConcumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_concums', function (Blueprint $table) {
            $table->enum('causa',['ACCESIBILIDAD', 'CONTINUIDAD', 'OPORTUNIDAD', 'PERTINENCIA', 'SEGURIDAD']);
        });
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
