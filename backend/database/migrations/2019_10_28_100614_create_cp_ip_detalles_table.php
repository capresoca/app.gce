<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpIpDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_ip_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cd_ip_id')->unsigned();
            $table->string('secuencia',7);
            $table->char('tipo_registro',1);
            $table->string('tipo_documento_cotizante',2);
            $table->string('identificacion_cotizante',16);
            $table->string('primer_apellido',20);
            $table->string('segundo_apellido',30);
            $table->string('primer_nombre',20);
            $table->string('segundo_nombre',30);
            $table->string('primer_apellido_causante', 20);
            $table->string('segundo_apellido_causante', 30);
            $table->string('primer_nombre_causante', 20);
            $table->string('segundo_nombre_causante', 30);
            $table->string('tipo_documento_causante', 2);
            $table->string('documento_causante', 16);
            $table->string('tipo_pension', 2);
            $table->string('pension_compartida', 1);
            $table->string('tipo_pensionado', 1);
            $table->string('pensionado_exterior', 1);
            $table->string('departamento_residencia', 2);
            $table->string('municipio_residencia', 3);
            $table->char('ingreso', 1);
            $table->char('retiro', 1);
            $table->string('tde', 1);
            $table->string('tae', 1);
            $table->string('vsp', 1);
            $table->string('sus', 1);
            $table->tinyInteger('dias_Cotizados');
            $table->integer('ibc');
            $table->float('tarifa', 6,5);
            $table->integer('cotizacion_obligatoria');
            $table->integer('upc_adicional');
            $table->date('fecha_de_ingreso_formato');
            $table->date('fecha_de_retiro_formato');
            $table->date('fecha_inicio_vsp');
            $table->date('valor_mesada_pensional');
            $table->foreign('cd_ip_id')->references('id')->on('cp_ip');
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
        Schema::dropIfExists('cp_ip_detalles');
    }
}

