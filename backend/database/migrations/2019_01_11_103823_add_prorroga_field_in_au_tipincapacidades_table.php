<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProrrogaFieldInAuTipincapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_tipincapacidades', function (Blueprint $table) {
            $table->tinyInteger('prorroga');
        });

        DB::statement('UPDATE `au_tipincapacidades` SET `prorroga`=1 WHERE  `id`=4 or `id`=6 or `id`=7 or `id`=16;
                    '
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('au_tipincapacidades', function (Blueprint $table) {
            $table->dropColumn('prorroga');
        });
    }
}
