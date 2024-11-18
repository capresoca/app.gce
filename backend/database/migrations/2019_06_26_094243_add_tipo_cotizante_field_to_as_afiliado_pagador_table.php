<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoCotizanteFieldToAsAfiliadoPagadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('as_afiliado_pagador', function (Blueprint $table) {
            $table->integer('tipo_cotizante');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('as_afiliado_pagador', function (Blueprint $table) {
            $table->dropColumn('tipo_cotizante');
        });
    }
}
