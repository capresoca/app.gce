<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFieldsToCeProconminutas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutas', function (Blueprint $table) {
            $table->dropColumn('fecha');
            $table->dropColumn('nombre_corto');
        });
    }

}
