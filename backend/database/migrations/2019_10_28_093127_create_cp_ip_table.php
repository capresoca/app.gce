<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpIpTable extends Migration
{

    public function up()
    {
        Schema::create('cp_ip', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero_registro',5);
            $table->string('tipo_registro',1);
            $table->string('codigo_formato',2);
            $table->string('nit',16);
            $table->string('digito_verificacion_nit',1);
            $table->string('razon_social_aportante',150);
            $table->string('tipo_documento_aportante',2);
            $table->string('identificacion_aportante',16);
            $table->string('digito_verificacion_aportante',1);
            $table->string('tipo_aportante',1);
            $table->string('direccion_correspondencia',40);
            $table->string('codigo_ciudad_municipio',3);
            $table->string('codigo_departamento',2);
            $table->string('telefono',10);
            $table->string('fax',10);
            $table->string('correo_electronico',60);
            $table->string('periodo_pago',7);
            $table->date('fecha_pago');
            $table->string('forma_presentacion',1);
            $table->string('codigo_sucursal',10);
            $table->string('nombre_sucursal',40);
            $table->integer('numero_empleados');
            $table->integer('numero_afiliados');
            $table->string('codigo_operador',2);
            $table->string('tipo_planilla_pensionado',1);
            $table->integer('dias_mora');
            $table->integer('numero_registros_salida');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('cp_ip');
    }
}

