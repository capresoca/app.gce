<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValorEpsFieldInAuAudetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_autdetalles', function (Blueprint $table) {
            $table->double('valor_eps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('au_autdetalles', function (Blueprint $table) {
            $table->dropColumn('valor_eps');
        });
    }
}
