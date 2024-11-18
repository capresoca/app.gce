<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldPrematuroInAuTipincapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_tipincapacidades', function (Blueprint $table) {
            $table->boolean('prematuro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('au_tipincapacidades', function (Blueprint $table) {
            $table->dropColumn('prematuro');
        });
    }
}
