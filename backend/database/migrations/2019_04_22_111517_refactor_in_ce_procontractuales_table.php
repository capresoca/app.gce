<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorInCeProcontractualesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('ce_procontractuales', function (Blueprint $table) {
            $table->dropForeign('fk_ce_procontractuales_pg_uniapoyos1');
            $table->dropColumn('pg_uniapoyo_id');
            $table->dropForeign('fk_ce_procontractuales_pr_cdps1');
            $table->dropColumn('pr_cdp_id');

            $table->dropForeign('fk_ce_procontractuales_ce_detplanadquisiciones1');
            $table->dropColumn('ce_detplanadquisicione_id');

            $table->integer('pr_dependencia_id');
            $table->foreign('pr_dependencia_id')->references('id')->on('pr_dependencias');

        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_procontractuales', function (Blueprint $table) {
            $table->integer('pg_uniapoyo_id');
            $table->foreign('pg_uniapoyo_id','fk_ce_procontractuales_pg_uniapoyos1')
                        ->references('id')->on('pr_dependencias');

            $table->integer('pr_cdp_id');
            $table->foreign('pr_cdp_id','fk_ce_procontractuales_pr_cdps1')
                        ->references('id')->on('pr_cdps');

            $table->integer('ce_detplanadquisicione_id');
            $table->foreign('ce_detplanadquisicione_id','fk_ce_procontractuales_ce_detplanadquisiciones1')
                ->references('id')->on('ce_detplanadquisiciones');

            $table->dropForeign('ce_procontractuales_pr_dependencia_id_foreign','');
            $table->dropColumn('pr_dependencia_id');
        });
    }
}
