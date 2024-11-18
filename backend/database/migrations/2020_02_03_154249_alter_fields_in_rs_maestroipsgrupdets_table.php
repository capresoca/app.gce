<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInRsMaestroipsgrupdetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_maestroipsgrudets', function (Blueprint $table) {
            $table->unsignedInteger('idd')->after('id')->change();
            $table->integer('servicio_id')->nullable()->default(null)->after('idd')->change();
            $table->boolean('primaria')->default(0)->after('codigo')->comment('Opción para marcar si IPS es primaria');
            $table->string('codigo',50)->after('gs_usuario_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_maestroipsgrudets', function (Blueprint $table) {
            $table->integer('servicio_id')->after('idd')->change();
            $table->dropColumn('primaria');
            $table->string('codigo',20)->after('gs_usuario_id')->change();
        });
    }
}
