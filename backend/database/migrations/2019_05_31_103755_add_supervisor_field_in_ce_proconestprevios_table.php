<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupervisorFieldInCeProconestpreviosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconestprevios', function (Blueprint $table) {
            $table->integer('supervisor_id')->nullable();
            $table->foreign('supervisor_id')->references('id')->on('gn_terceros');
            $table->integer('pr_solicitud_cp_id')->nullable()->unsigned();
            $table->foreign('pr_solicitud_cp_id')->references('id')->on('pr_solicitud_cps');
            $table->enum('estado',['Registrado','Revisado','Confirmado'])->default('Registrado');
            $table->integer('registro')->nullable();
            $table->integer('reviso')->nullable();
            $table->integer('confirmo')->nullable();
            $table->dateTime('revisado_at')->nullable();
            $table->dateTime('confirmado_at')->nullable();

            $table->foreign('registro')->references('id')->on('gn_terceros');
            $table->foreign('reviso')->references('id')->on('gn_terceros');
            $table->foreign('confirmo')->references('id')->on('gn_terceros');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_proconestprevios', function (Blueprint $table) {
            $table->dropForeign('ce_proconestprevios_supervisor_id_foreign');
            $table->dropColumn('supervisor_id');
            $table->dropForeign('ce_proconestprevios_pr_solicitud_cp_id_foreign');
            $table->dropColumn('pr_solicitud_cp_id');
            $table->dropForeign('ce_proconestprevios_confirmo_foreign');
            $table->dropColumn('confirmo');
            $table->dropForeign('ce_proconestprevios_reviso_foreign');
            $table->dropColumn('reviso');
            $table->dropForeign('ce_proconestprevios_registro_foreign');
            $table->dropColumn('registro');
            $table->dropColumn('revisado_at');
            $table->dropColumn('confirmado_at');
            $table->dropColumn('estado');
        });
    }
}
