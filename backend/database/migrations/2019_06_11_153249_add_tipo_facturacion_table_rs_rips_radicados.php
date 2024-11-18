<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoFacturacionTableRsRipsRadicados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_rips_radicados', function (Blueprint $table) {
            $table->enum('tipo_facturacion',['Evento', 'Capita'])->default('Evento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_rips_radicados', function (Blueprint $table) {
            $table->dropColumn('tipo_facturacion');
        });
    }
}
