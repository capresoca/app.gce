<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpPilasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_pilas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero_registro',5)->comment('Número del registro');
            $table->unsignedTinyInteger('tipo_registro')->comment('Tipo de registro');
            $table->string('codigo_formato',2)->comment('Código de formato');
            $table->string('nit',16)->comment('Número de identificación (NIT) de la EPS o EOC');
            $table->string('digito_verificacion_nit',1)->comment('Dígito de verificación del NIT');
            $table->string('razon_social_aportante',200)->comment('Nombre o razón social del aportante');
            $table->string('tipo_documento_aportante',2)->comment('Tipo documento del aportante');
            $table->string('identificacion_aportante',16)->comment('Número de identificación del aportante');
            $table->string('digito_verificacion_aportante',1)->comment('Dígito de verificación aportante');
            $table->string('tipo_aportante',2)->comment('Tipo de aportante');
            $table->string('direccion_correspondencia',40)->comment('Dirección de correspondencia');
            $table->string('codigo_ciudad_municipio',3)->comment('Código ciudad o municipio');
            $table->string('codigo_departamento',2)->comment('Código departamento');
            $table->string('telefono',10)->comment('Teléfono');
            $table->string('fax',10)->comment('Fax');
            $table->string('correo_electronico',60)->comment('Correo electrónico');
            $table->string('periodo_pago',7)->comment('Período de pago (aaaa-mm)');
            $table->string('codigo_arl',6)->comment('Código de la ARL');
            $table->string('tipo_planilla',1)->comment('Tipo de planilla');
            $table->date('fecha_pago_asociada')->nullable()->comment('Fecha de pago de la planilla asociada a esta planilla');
            $table->date('fecha_pago')->comment('Fecha de pago');
            $table->string('numero_asociada',10)->nullable()->comment('No de la planilla asociada a esta planilla');
            $table->string('numero_radicacion',10)->comment('Número de radicacion o de la planilla integrada de liquidación de aportes');
            $table->string('forma_presentacion',1)->comment('Forma de presentación');
            $table->string('codigo_sucursal',10)->nullable()->comment('Código de la sucursal o de la dependencia');
            $table->string('nombre_sucursal',40)->nullable()->comment('Nombre de la sucursal o de la dependencia');
            $table->string('numero_empleados',5)->comment('Número total de empleados');
            $table->string('numero_afiliados',5)->comment('Número total de afiliados a la administradora');
            $table->string('codigo_operador',2)->comment('Código del operador');
            $table->string('modalidad',1)->comment('Modalidad de la planilla');
            $table->string('dias_mora',4)->comment('Días de mora');
            $table->string('numero_registros_salida',8)->comment('Número de registros de salida tipo 2');
            $table->date('fecha_matricula_mercantil')->nullable()->comment('Fecha de matrícula mercantil');
            $table->string('codigo_departamento_34',2)->nullable()->comment('Código del departamento');
            $table->string('exonerado_pago_salud',1)->comment('Aportante exonerado de pago de aporte Salud, SENA e ICBF - Ley 1607 de 2012');
            $table->string('clase_aportante',1)->comment('Clase de aportante');
            $table->string('naturaleza_juridica',1)->comment('Naturaleza jurídica');
            $table->string('tipo_persona',1)->comment('Tipo persona');
            $table->date('fecha_archivo')->comment('Duda acerca de este campo, puede ser nombre de archivo');
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
        Schema::dropIfExists('cp_pilas');
    }
}
