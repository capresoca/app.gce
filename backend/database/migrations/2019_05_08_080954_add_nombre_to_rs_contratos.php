<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNombreToRsContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('rs_planescoberturas', function (Blueprint $table) {
            $table->string('nombre', 255)->nullable();
        });
        Schema::enableForeignKeyConstraints();
        \App\Capresoca\Contratos\MoveDataFromRsContratosToCeProconminuta::mover();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('rs_contratos', function (Blueprint $table) {
//            //
//        });
    }
}
