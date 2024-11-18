<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempMsubsidiadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_msubsidiados', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('as_msubsidiado_id');
            $table->string('codigo_bdua',20);
            $table->index('codigo_bdua');
            $table->string('codigo_eps',10);
            $table->string('tipo_identificacion_cabeza',3);
            $table->string('identificacion_cabeza',20);
            $table->string('tipo_identificacion',3);
            $table->string('numero_identificacion',20);
            $table->string('primer_apellido',100);
            $table->string('segundo_apellido',100)->nullable();
            $table->string('primer_nombre',100);
            $table->string('segundo_nombre',100)->nullable();
            $table->string('fecha_nacimiento',10);
            $table->string('sexo',2);
            $table->string('tipo_afiliado',2)->nullable();
            $table->string('parentesco', 2)->nullable();
            $table->string('grupo_poblacional',2)->nullable();
            $table->string('nivel_sisben',2)->nullable();
            $table->string('ficha_sisben', 20)->nullable();
            $table->string('condicion_beneficiario',2)->nullable();
            $table->string('codigo_departamento',3);
            $table->string('codigo_municipio',6);
            $table->string('zona',2);
            $table->string('fecha_afiliacion_sistema',10);
            $table->string('fecha_afiliacion_eps',10);
            $table->string('numero_contrato',10)->nullable();
            $table->string('fecha_inicio_contrato',10)->nullable();
            $table->string('tipo_contrato',10)->nullable();
            $table->string('pertenecia_etnica',2)->nullable();
            $table->string('mod_subsidio',2)->nullable();
            $table->string('estado',10);
            $table->string('fecha_novedad',10)->nullable();
            $table->string('fecha_desconocida',10)->nullable();
            $table->foreign('as_msubsidiado_id')->references('id')->on('as_msubsidiados')->onDelete('RESTRICT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_msubsidiados');
    }
}
