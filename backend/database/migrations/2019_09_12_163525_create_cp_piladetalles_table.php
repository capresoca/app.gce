<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpPiladetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_piladetalles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('secuencia',5)->comment('Secuencia');
            $table->string('tipo_registro',1)->comment('Tipo de registro');
            $table->string('tipo_documento_cotizante',2)->comment('Tipo documento del cotizante');
            $table->string('identificacion_cotizante',16)->comment('No de identificación del cotizante');
            $table->string('tipo_cotizante',2)->comment('Tipo de cotizante');
            $table->string('subtipo_cotizante',2)->comment('Subtipo de cotizante');
            $table->string('extranjero_no_oblig',1)->nullable()->comment('Extranjero no obligado a cotizar pensiones');
            $table->string('col_exterior',1)->nullable()->comment('Colombiano en el exterior');
            $table->string('cod_dep_laboral',2)->comment('Código del departamento de la ubicación laboral');
            $table->string('cod_mun_laboral',3)->comment('Código del municipio de la ubicación laboral');
            $table->string('primer_apellido',20)->comment('Primer apellido');
            $table->string('segundo_apellido',30)->nullable()->comment('Segundo apellido');
            $table->string('primer_nombre',20)->comment('Primer nombre');
            $table->string('segundo_nombre',30)->nullable()->comment('Segundo nombre');
            $table->string('ingreso',1)->nullable()->comment('Ingreso');
            $table->string('retiro',1)->nullable()->comment('Retiro');
            $table->string('tde',1)->nullable()->comment('Traslado de otra administradora');
            $table->string('tae',1)->nullable()->comment('Traslado a otra administradora');
            $table->string('vsp',1)->nullable()->comment('Variación permanente de salario');
            $table->string('vst',1)->nullable()->comment('Variación transitoria de salario');
            $table->string('sln',1)->nullable()->comment('Suspención temporal del contrato');
            $table->string('ige',1)->nullable()->comment('Incapacidad general');
            $table->string('lma',1)->nullable()->comment('Licencia de maternidad o paternidad');
            $table->string('vac_lr',1)->nullable()->comment('Vacaciones, licencia remunerada');
            $table->unsignedTinyInteger('dias_cotizados')->comment('Días cotizados');
            $table->unsignedInteger('salario_basico')->comment('Salario básico');
            $table->unsignedInteger('base_cotizacion')->comment('Ingreso base cotización');
            $table->float('tarifa',6,5)->comment('Tarifa de aportes de salud');
            $table->unsignedInteger('cotizacion_obligatoria')->comment('Cotización obligatoria');
            $table->unsignedInteger('valor_upc_adicional')->comment('Valor de la UPC adicional');
            $table->string('correcciones',1)->nullable()->comment('Correcciones');
            $table->string('salario_integral',1)->nullable()->comment('Salario integral');
            $table->string('exonerado_pago_salud',1)->comment('Cotizante exonerado de pago de aporte de salud, SENA e ICBF - Ley 1607 de 2012');
            $table->date('fecha_ingreso')->nullable()->comment('Fecha de ingreso');
            $table->date('fecha_retiro')->nullable()->comment('Fecha de retiro');
            $table->date('fecha_inicio_vsp')->nullable()->comment('Fecha inicio VSP');
            $table->date('fecha_inicio_sln')->nullable()->comment('Fecha inicio SLN');
            $table->date('fecha_fin_sln')->nullable()->comment('Fecha fin SLN');
            $table->date('fecha_inicio_ige')->nullable()->comment('Fecha inicio IGE');
            $table->date('fecha_fin_ige')->nullable()->comment('Fecha fin IGE');
            $table->date('fecha_inicio_lma')->comment('Fecha inicio LMA');
            $table->date('fecha_fin_lma')->comment('Fecha fin LMA');
            $table->date('fecha_inicio_vac_lr')->nullable()->comment('Fecha inicio VAC - LR');
            $table->date('fecha_fin_vac_lr')->nullable()->comment('Fecha fin VAC - LR');
            $table->date('fecha_inicio_vct')->nullable()->comment('Fecha inicio VCT');
            $table->date('fecha_fin_vct')->nullable()->comment('Fecha fin VCT');
            $table->date('fecha_inicio_irl')->nullable()->comment('Fecha inicio IRL');
            $table->date('fecha_fin_irl')->nullable()->comment('Fecha fin IRL');
            $table->integer('cp_pila_id')->unsigned();
            $table->foreign('cp_pila_id')->references('id')->on('cp_pilas')->onUpdate('no action')->onDelete('restrict');
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
        Schema::dropIfExists('cp_piladetalles');
    }
}
