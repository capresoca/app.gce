<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeingKeysTutelasToMpTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_medicamentos', function (Blueprint $table) {
            $table->integer('mp_prescripcion_id')->unsigned()->nullable()->change();
            $table->integer('mp_tutela_id')->unsigned()->nullable();
            $table->foreign('mp_tutela_id')->references('id')->on('mp_tutelas')->onDelete('restrict');

        });

        Schema::table('mp_procedimientos', function (Blueprint $table) {
            $table->integer('mp_prescripcion_id')->unsigned()->nullable()->change();
            $table->integer('mp_tutela_id')->unsigned()->nullable();
            $table->foreign('mp_tutela_id')->references('id')->on('mp_tutelas')->onDelete('restrict');

        });

        Schema::table('mp_dispositivos', function (Blueprint $table) {
            $table->integer('mp_prescripcion_id')->unsigned()->nullable()->change();
            $table->integer('mp_tutela_id')->unsigned()->nullable();
            $table->foreign('mp_tutela_id')->references('id')->on('mp_tutelas')->onDelete('restrict');
        });

        Schema::table('mp_nutricionals', function (Blueprint $table) {
            $table->integer('mp_prescripcion_id')->unsigned()->nullable()->change();
            $table->integer('mp_tutela_id')->unsigned()->nullable();
            $table->foreign('mp_tutela_id')->references('id')->on('mp_tutelas')->onDelete('restrict');

        });

        Schema::table('mp_complementarios', function (Blueprint $table) {
            $table->integer('mp_prescripcion_id')->unsigned()->nullable()->change();
            $table->integer('mp_tutela_id')->unsigned()->nullable();
            $table->foreign('mp_tutela_id')->references('id')->on('mp_tutelas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_medicamentos', function (Blueprint $table) {
            $table->dropForeign('mp_medicamentos_mp_tutela_id_foreign');
            $table->dropColumn('mp_tutela_id');
        });

        Schema::table('mp_procedimientos', function (Blueprint $table) {
            $table->dropForeign('mp_procedimientos_mp_tutela_id_foreign');
            $table->dropColumn('mp_tutela_id');
        });

        Schema::table('mp_dispositivos', function (Blueprint $table) {
            $table->dropForeign('mp_dispositivos_mp_tutela_id_foreign');
            $table->dropColumn('mp_tutela_id');
        });

        Schema::table('mp_nutricionals', function (Blueprint $table) {
            $table->dropForeign('mp_nutricionals_mp_tutela_id_foreign');
            $table->dropColumn('mp_tutela_id');
        });

        Schema::table('mp_complementarios', function (Blueprint $table) {
            $table->dropForeign('mp_complementarios_mp_tutela_id_foreign');
            $table->dropColumn('mp_tutela_id');
        });
    }
}
