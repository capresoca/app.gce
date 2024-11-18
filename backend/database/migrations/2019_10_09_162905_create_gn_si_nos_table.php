<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGnSiNosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gn_si_no', function (Blueprint $table) {
            $table->increments('id');
            $table->string('desc');
        });

        DB::table('gn_si_no')->insert(
            [
                [
                    'id' => 1,
                    'desc' => 'Si'
                ],
                [
                    'id' => 2,
                    'desc' => 'No'
                ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gn_si_no');
    }
}
