<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmEventoadversoepssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_eventoadversoepss', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',6);
            $table->string('nombre');
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::table('cm_eventoadversoepss')->insert([
            ['codigo' => 'E-1', 'nombre' => 'USUARIOS DETECTADOS POR SUPLANTACIÓN'],
            ['codigo' => 'E-2', 'nombre' => 'QUEJAS POR NO PRESTACIÓN DE SERVICIOS INCLUIDOS EN EL PLAN DE BENEFICIOS DEL SSFM'],
            ['codigo' => 'E-3', 'nombre' => 'PACIENTES A LOS CUALES SE LES RECONOCE SERVICIO INICIALMENTE RECHAZADO'],
            ['codigo' => 'E-4', 'nombre' => 'SERVICIO NEGADO POR INADECUADO PROCESO DE AFILIACIÓN'],
            ['codigo' => 'E-5', 'nombre' => 'PACIENTES INSATISFECHOS POR LO QUE CONSIDERAN INJUSTIFICADAS BARRERAS DE ACCESO A LA ATENCION'],
            ['codigo' => 'E-6', 'nombre' => 'SÍFILIS CONGÉNITA EN NACIMIENTOS EN EL ESM'],
            ['codigo' => 'E-7', 'nombre' => 'TUTELAS POR NO PRESTACION DE SERVICIOS POS'],
            ['codigo' => 'E-8', 'nombre' => 'PACIENTES CON INSUFICIENCIA RENAL CRÓNICA TERMINAL A CAUSA DE ENFERMEDAD OBSTRUCTIVA'],
            ['codigo' => 'E-9', 'nombre' => 'PACIENTES QUE MUEREN ENCONTRÁNDOSE EN LISTA DE ESPERA PARA LA AUTORIZACIÓN O REALIZACIÓN DE ALGUNA AYUDA DIAGNÓSTICA O PROCEDIMIENTO RELACIONADO CON LA ENFERMEDAD'],
            ['codigo' => 'E-10', 'nombre' => 'PACIENTES CON TRANSMISIÓN VERTICAL DE VIH'],
            ['codigo' => 'E-11', 'nombre' => 'DETECCIÓN DE CÁNCER DE CÉRVIX EN ESTADIOS AVANZADOS'],
            ['codigo' => 'E-12', 'nombre' => 'DETECCIÓN DE CÁNCER DE COLON EN ESTADIOS AVANZADO'],
            ['codigo' => 'E-13', 'nombre' => 'COMPLICACIONES ATRIBUIBLES A NO DISPONIBILIDAD DE INSUMOS O MEDICAMENTOS'],
            ['codigo' => 'E-14', 'nombre' => 'COMPLICACIONES DE LOS PACIENTES O FALLAS EN LA CONTINUIDAD DE LOS TRATAMIENTOS ATRIBUIBLES A TIEMPOS DE ESPERA PROLONGADOS'],
            ['codigo' => 'E-15', 'nombre' => 'DEMORA EN SUMINISTRO DE INSUMOS O MEDICAMENTOS POR TRÁMITES ADMINISTRATIVOS'],
            ['codigo' => 'E-16', 'nombre' => 'PACIENTES QUE SON REMITIDOS REPETIDAS VECES A INSTANCIAS EQUÍVOCAS O ERRÓNEAS ANTES DE SER REFERIDOS AL PUNTO DEFINITIVO DE ATENCIÓN'],
            ['codigo' => 'E-17', 'nombre' => 'COMPLICACIONES RELACIONADAS CON OXIGENOTERAPIA  ATRIBUIBLES A MONITORIZACION,SEGUIMIENTO O SUMINISTRO '],
            ['codigo' => 'E-18', 'nombre' => 'PACIENTE EN ESTADO CRITICO SIN ASISTENCIA'],
            ['codigo' => 'E-19', 'nombre' => 'PACIENTES EN LISTA DE ESPERA POR MAS DE TRES MESES'],
            ['codigo' => 'E-20', 'nombre' => 'DEMORAS EN LA REFERENCIA A OTROS NIVELES DE COMPLEJIDAD'],
            ['codigo' => 'E-21', 'nombre' => 'COMPLICACIONES ASOCIADAS AL PARTO EN GESTANTES SIN CONTROLES PRENATALES'],
            ['codigo' => 'E-22', 'nombre' => 'PACIENTES QUE MUEREN POR COMPLICACION DE DIABETES MELLITUS, POSTERIOR A LAS 72 HORAS DE ATENCION INICIAL'],
            ['codigo' => 'E-23', 'nombre' => 'OTROS']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_eventoadversoepss');
    }
}
