<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterChangeCeProconminutageosNullColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutageos', function (Blueprint $table) {
            $table->integer('porcentaje_contributivo')->nullable()->change();
            $table->integer('porcentaje_subsidiado')->nullable()->change();
        });

        Schema::table('ce_proconminutaupcservicios', function (Blueprint $table) {
            $table->integer('porcentaje')->nullable()->change();
            $table->integer('valor')->nullable()->change();
        });

        Schema::table('ce_proconminutaupcedades', function (Blueprint $table) {
            $table->integer('proporcion')->nullable()->change();
            $table->integer('valor')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_proconminutageos', function (Blueprint $table) {
            $table->integer('porcentaje_contributivo')->change();
            $table->integer('porcentaje_subsidiado')->change();
        });

        Schema::table('ce_proconminutaupcservicios', function (Blueprint $table) {
            $table->integer('porcentaje')->change();
            $table->integer('valor')->change();
        });

        Schema::table('ce_proconminutaupcedades', function (Blueprint $table) {
            $table->integer('proporcion')->change();
            $table->integer('valor')->change();
        });
    }
}
