<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinDiasFieldInAuTipincapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_tipincapacidades', function (Blueprint $table) {
            $table->integer('min_dias')->nullable()->default(0);
        });
        DB::statement("UPDATE `au_tipincapacidades` SET `min_dias`='14', `max_dias`='28', `dias`='0' WHERE  `id`=8;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('au_tipincapacidades', function (Blueprint $table) {
            $table->dropColumn('min_dias');
        });
    }
}
