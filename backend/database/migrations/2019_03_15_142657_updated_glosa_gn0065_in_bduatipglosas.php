<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatedGlosaGn0065InBduatipglosas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            UPDATE `as_bduatipglosas` SET `clase_procesador`='App\\Capresoca\\Aseguramiento\\Glosas\\GN0065' WHERE  `codigo`='GN0065';
        ");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            UPDATE `as_bduatipglosas` SET `clase_procesador`='' WHERE  `codigo`='GN0065';
        ");
    }
}
