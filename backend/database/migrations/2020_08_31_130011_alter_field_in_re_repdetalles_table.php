<?php

use App\Models\Reportes\Repdetalle;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldInReRepdetallesTable extends Migration
{
    /**
     * @author Jorge Eduardo Hernández Oropeza 01/09/2020
     * @copyright Creando SOluciones Informaticas S.A
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_repdetalles', function (Blueprint $table) {
            $table->string('entidades')->nullable()->after('label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('re_repdetalles', function (Blueprint $table) {
            $table->dropColumn('entidades');
        });
    }
}
