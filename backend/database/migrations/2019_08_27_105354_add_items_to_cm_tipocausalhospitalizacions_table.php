<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsToCmTipocausalhospitalizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_tipocausalhospitalizacions', function (Blueprint $table) {
            $table->string('tabla')->nullable();
        });
        DB::table('cm_tipocausalhospitalizacions')->where('nombre', 'PROGRAMACIÓN DE CIRUGÍA')
            ->update(['tabla' => 'cups']);
        DB::table('cm_tipocausalhospitalizacions')->where('nombre', 'REALIZACIÓN DE PROCEDIMIENTO')
            ->update(['tabla' => 'cups']);
        DB::table('cm_tipocausalhospitalizacions')->where('nombre', 'INICIO MANEJO ANTIBIÓTICO DE AMPLIO ESPECTRO')
            ->update(['tabla' => 'cums']);
        DB::table('cm_tipocausalhospitalizacions')->where('nombre', 'VALORACIÓN POR ESPECIALISTA')
            ->update(['tabla' => 'especialidad']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_tipocausalhospitalizacions', function (Blueprint $table) {
            //
        });
    }
}
