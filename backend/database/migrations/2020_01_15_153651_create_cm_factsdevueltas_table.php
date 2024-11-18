<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmFactsdevueltasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::dropIfExists('cm_factsdevueltas');
        DB::statement("CREATE TABLE cm_factsdevueltas SELECT * FROM `cm_facturas` LIMIT 0");
        DB::statement("TRUNCATE cm_factsdevueltas");
        //Schema::create('cm_factsdevueltas', function (Blueprint $table) {
        //    $table->increments('id');
        //    $table->timestamps();
        //});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_factsdevueltas');
    }
}
