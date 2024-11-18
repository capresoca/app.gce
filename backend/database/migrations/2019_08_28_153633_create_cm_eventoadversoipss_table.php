<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmEventoadversoipssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_eventoadversoipss', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',6);
            $table->string('nombre');
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::table('cm_eventoadversoipss')->insert([
            ['codigo' => 'I-1', 'nombre' => 'CIRUGÍAS O PROCEDIMIENTOS CANCELADOS POR FACTORES ATRIBUIBLES AL DESEMPEÑO DE  LA IPS O DE LOS PROFESIONALES'],
            ['codigo' => 'I-2', 'nombre' => 'PACIENTES CON TROMBOSIS VENOSA PROFUNDA A QUIENES NO SE LES REALIZA CONTROL DE PRUEBAS DE COAGULACIÓN'],
            ['codigo' => 'I-3', 'nombre' => 'INGRESO NO PROGRAMADO A UCI LUEGO DE PROCEDIMIENTO QUE IMPLICA LA ADMINISTRACIÓN DE ANESTESIA'],
            ['codigo' => 'I-4', 'nombre' => 'PACIENTES CON NEUMONÍAS BRONCOASPIRATIVAS EN PEDIATRÍA O UCI NEONATAL'],
            ['codigo' => 'I-5', 'nombre' => 'PACIENTES CON ÚLCERAS DE POSICIÓN'],
            ['codigo' => 'I-6', 'nombre' => 'DISTOCIA INADVERTIDA'],
            ['codigo' => 'I-7', 'nombre' => 'SHOCK HIPOVOLÉMICO POST - PARTO'],
            ['codigo' => 'I-8', 'nombre' => 'MATERNAS CON CONVULSIÓN INTRAHOSPITALARIA'],
            ['codigo' => 'I-9', 'nombre' => 'CIRUGÍA EN PARTE EQUIVOCADA O EN PACIENTE EQUIVOCADO'],
            ['codigo' => 'I-10', 'nombre' => 'PACIENTES CON HIPOTENSIÓN SEVERA POP'],
            ['codigo' => 'I-11', 'nombre' => 'INFARTO EN LAS SIGUIENTES 72 HORAS POP'],
            ['codigo' => 'I-12', 'nombre' => 'REINGRESO AL SERVICIO DE URGENCIAS POR MISMA CAUSA ANTES DE 72 HORAS'],
            ['codigo' => 'I-13', 'nombre' => 'REINGRESO A HOSPITALIZACIÓN POR LA MISMA CAUSA ANTES DE 15 DÍAS'],
            ['codigo' => 'I-14', 'nombre' => 'ENTREGA EQUIVOCADA DE UN NEONATO'],
            ['codigo' => 'I-15', 'nombre' => 'ROBO INTRA – INSTITUCIONAL DE NIÑOS'],
            ['codigo' => 'I-16', 'nombre' => 'FUGA DE PACIENTES SIQUIÁTRICOS HOSPITALIZADOS'],
            ['codigo' => 'I-17', 'nombre' => 'SUICIDIO DE PACIENTES INTERNADOS'],
            ['codigo' => 'I-18', 'nombre' => 'CONSUMO INTRA - INSTITUCIONAL DE SICOACTIVOS'],
            ['codigo' => 'I-19', 'nombre' => 'CAÍDAS DESDE SU PROPIA ALTURA INTRA-INSTITUCIONAL'],
            ['codigo' => 'I-20', 'nombre' => 'RETENCIÓN DE CUERPOS EXTRAÑOS EN PACIENTES INTERNADOS'],
            ['codigo' => 'I-21', 'nombre' => 'QUEMADURAS POR LÁMPARAS DE FOTOTERAPIA Y PARA ELECTROCAUTERIO'],
            ['codigo' => 'I-22', 'nombre' => 'ESTANCIA PROLONGADA POR NO DISPONIBILIDAD DE INSUMOS O MEDICAMENTOS'],
            ['codigo' => 'I-23', 'nombre' => 'UTILIZACIÓN INADECUADA DE ELEMENTOS CON OTRA INDICACIÓN'],
            ['codigo' => 'I-24', 'nombre' => 'FLEBITIS EN SITIOS DE VENOPUNCIÓN'],
            ['codigo' => 'I-25', 'nombre' => 'RUPTURA PREMATURA DE MEMBRANAS SIN CONDUCTA DEFINIDA'],
            ['codigo' => 'I-26', 'nombre' => 'ENTREGA EQUIVOCADA DE REPORTES DE LAB'],
            ['codigo' => 'I-27', 'nombre' => 'REVISIÓN DE REEMPLAZOS ARTICULARES POR INICIO TARDÍO DE LA REHABILITACIÓN'],
            ['codigo' => 'I-28', 'nombre' => 'LUXACIÓN POST - QUIRÚRGICA EN REEMPLAZO DE CADERA'],
            ['codigo' => 'I-29', 'nombre' => 'ACCIDENTES POSTRANSFUSIONALES'],
            ['codigo' => 'I-30', 'nombre' => 'ASALTO SEXUAL EN LA INSTITUCIÓN'],
            ['codigo' => 'I-31', 'nombre' => 'NEUMOTÓRAX POR VENTILACIÓN MECÁNICA'],
            ['codigo' => 'I-32', 'nombre' => 'ASFIXIA PERINATAL'],
            ['codigo' => 'I-33', 'nombre' => 'DETERIORO NEUROLOGICO (GLASGOW) SIN TRATAMIENTO DEFINIDO'],
            ['codigo' => 'I-34', 'nombre' => 'SECUELAS POST - REANIMACIÓN'],
            ['codigo' => 'I-35', 'nombre' => 'PÉRDIDA DE PERTENENCIAS DE USUARIOS'],
            ['codigo' => 'I-36', 'nombre' => 'PACIENTES CON DIAGNÓSTICO DE APENDICITIS QUE NO HAN SIDO ATENDIDOS A LAS 12 HORAS DE REALIZADO EL DIAGNÓSTICO'],
            ['codigo' => 'I-37', 'nombre' => 'INFECCION NOSOCOMIAL'],
            ['codigo' => 'I-38', 'nombre' => 'MORTALIDAD POR IRA/EDA EN MENOR 5 AÑOS'],
            ['codigo' => 'I-39', 'nombre' => 'REACCION ADVERSA A MEDICAMENTOS'],
            ['codigo' => 'I-40', 'nombre' => 'EXPULSIVO PROLONGADO SIN CONDUCTA DEFINIDA EN LA PRIMERA HORA'],
            ['codigo' => 'I-41', 'nombre' => 'MENOR DE EDAD QUEMADO'],
            ['codigo' => 'I-42', 'nombre' => 'OTROS']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_eventoadversoipss');
    }
}
