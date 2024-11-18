<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTempMcontributivos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_mcontributivos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('IDENTIFICADOR',20)->nullable();
            $table->string('SERIAL',20);
            $table->string('TIPO_IDENTIFICACION',3);
            $table->string('NUMERO_IDENTIFICACION',20);
            $table->string('PRIMER_NOMBRE',80);
            $table->string('SEGUNDO_NOMBRE',80)->nullable();
            $table->string('PRIMER_APELLIDO',80);
            $table->string('SEGUNDO_APELLIDO',80)->nullable();
            $table->string('NACIMIENTO_FECHA',20);
            $table->string('DEPTO',100);
            $table->string('MUNI',100);
            $table->string('ESTADO',30);
            $table->string('ENT_ID',10);
            $table->string('ENTIDAD',200);
            $table->string('REGIMEN',20);
            $table->string('FECHA_CONTRATO',20);
            $table->string('FECHA_FIN_CONTRATO',20);
            $table->string('AFILIACION_ENTIDAD_FECHA',20);
            $table->string('ESTADO_FECHA',20);
            $table->string('TIPO_AFILIADO',30);
            $table->string('FECHA_AFILIACION_EFECTIVA',30);
            $table->string('cantidad_grp_fml',5);
            $table->unsignedInteger('as_msubsidiado_id');
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
        Schema::dropIfExists('temp_mcontributivos');
    }
}
