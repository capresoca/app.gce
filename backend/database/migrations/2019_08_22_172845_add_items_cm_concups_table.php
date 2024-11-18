<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsCmConcupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_concups', function (Blueprint $table) {
            $table->enum('tipo_servicio',['ESTANCIA', 'PROCEDIMIENTO']);
            $table->enum('causa',['ACCESIBILIDAD', 'CONTINUIDAD', 'OPORTUNIDAD', 'PERTINENCIA', 'SEGURIDAD']);
            $table->enum('causa_especifica',[
                'ACEPTACIÓN SERVICIO DOMICILIARIO',
                'ESTANCIA PROLONGADA',
                'INOPORTUNIDAD EN PRESTACIÓN DEL SERVICIO',
                'SERVICIO NO PERTINENTE'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_concups', function (Blueprint $table) {
            //
        });
    }
}
