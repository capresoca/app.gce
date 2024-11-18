<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyInCeEstpreforpagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_estpreforpagos', function (Blueprint $table) {
            $table->dropColumn('fecha');
            $table->text('descripcion')->after('valor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_estpreforpagos', function (Blueprint $table) {
            $table->dropColumn('descripcion');
            $table->date('fecha');
        });
    }
}
