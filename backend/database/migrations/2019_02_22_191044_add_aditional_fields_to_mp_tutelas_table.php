<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAditionalFieldsToMpTutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_tutelas', function (Blueprint $table) {
            $table->integer('as_afiliado_id')->nullable();
            $table->integer('rs_entidad_id')->nullable();
            $table->enum('regimen',['subsidiado','contributivo']);
            $table->enum('estado',['Revisión','Aprobado','Anulado','Duplicado'])->default('Revisión');
            $table->text('obervaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_tutelas', function (Blueprint $table) {
            $table->dropColumn('as_afiliado_id');
            $table->dropColumn('rs_entidad_id');
            $table->dropColumn('regimen');
            $table->dropColumn('estado');
            $table->dropColumn('obervaciones');
        });
    }
}
