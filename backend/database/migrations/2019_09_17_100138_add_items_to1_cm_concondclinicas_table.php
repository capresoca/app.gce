<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsTo1CmConcondclinicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('cm_concondclinicas')->insert([
            ['nombre' => 'Estado ginecológico'],
            ['nombre' => 'Vigilancia postparto'],
        ]);
        DB::table('cm_tipocausalhospitalizacions')->insert([
            ['nombre' => 'Condición clinica'],
            ['nombre' => 'Condición ginecológico'],
        ]);
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
