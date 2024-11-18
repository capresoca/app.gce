<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsRepsTableRsEntidadesBases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_entidades_bases', function (Blueprint $table) {
            $table->string('codigo_prestador',15)->nullable();
            $table->string('nombre_prestador',500)->nullable();
            $table->string('codigo_habilitacion',15)->nullable();
            $table->string('numero_sede',3)->nullable();
            $table->string('tipo_zona',50)->nullable();
            $table->string('barrio',500)->nullable();
            $table->string('cepo_coddigo',500)->nullable();
            $table->string('centro_poblado',500)->nullable();
            $table->string('fecha_apertura',20)->nullable();
            $table->string('fecha_cierre',20)->nullable();
            $table->string('nits_nit',20)->nullable();
            $table->string('dv',20)->nullable();
            $table->string('clase_persona',500)->nullable();
            $table->string('naju_codigo',2)->nullable();
            $table->string('naturaleza',500)->nullable();
            $table->string('clpr_codigo',2)->nullable();
            $table->string('ese',2)->nullable();
            $table->string('nivel',2)->nullable();
            $table->string('caracter',50)->nullable();
            $table->string('sede_principal',2)->nullable();
            $table->string('habilitado',2)->nullable();
            $table->string('numero_sede_principal',2)->nullable();
            $table->string('fecha_corte_REPS',200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
