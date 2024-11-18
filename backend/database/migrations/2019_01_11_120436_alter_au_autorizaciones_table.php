<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAuAutorizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_autorizaciones', function (Blueprint $table) {
            $table->date('fecha_vencimiento')->nullable();
        });
        DB::statement('ALTER TABLE `au_autsolicitudes`
                            CHANGE COLUMN `estado` `estado` ENUM("Registrada","Negada","Autorizada","Autorizada Parcialemente","Confirmada")');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('au_autorizaciones', function (Blueprint $table) {
            $table->dropColumn('fecha_vencimiento');
        });
        DB::statement('ALTER TABLE `au_autsolicitudes`
                            CHANGE COLUMN `estado` `estado` ENUM("Registrada","Negada","Autorizada","Autorizada Parcialemente")');

    }
}
