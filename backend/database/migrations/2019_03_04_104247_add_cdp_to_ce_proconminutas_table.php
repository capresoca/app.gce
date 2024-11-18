<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCdpToCeProconminutasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ce_proconminutas`
                       CHANGE COLUMN `tipo` `tipo` ENUM('Capitado','Evento','Paquete Integral') 
	                   NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `objeto`;");
        Schema::table('ce_proconminutas', function (Blueprint $table) {
            $table->integer('pr_cdp_id')->nullable();
            $table->foreign('pr_cdp_id')->references('id')->on('pr_cdps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_proconminutas', function (Blueprint $table) {
            $table->dropForeign('ce_proconminutas_pr_cdp_id_foreign');
            $table->dropColumn('pr_cdp_id');
        });
    }
}
