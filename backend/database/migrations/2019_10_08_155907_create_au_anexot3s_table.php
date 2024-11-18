<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuAnexot3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('au_anexot3s', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nSolicitud',20)->index()->nullable()->comment('Número de solicitud prestador');
            $table->date('fecha')->comment('Fecha de radicación');
            $table->time('hora')->comment('Hora de radicación');
            $table->date('fOrdMed')->comment('Fecha de orden médica');
            $table->string('codigoIps',20)->comment('Código del prestador que solicita');
            $table->char('orgAten',2)->comment('Origen de la atención');
            $table->char('serSol',1)->comment('Servicio solicitado');
            $table->char('prior',1)->comment('Si/No Prioritario');
            $table->char('ubic',1)->comment('Ubicación del paciente');
            $table->string('serv',50)->comment('Servicio donde esta el paciente');
            $table->string('cama',15)->comment('# Cama');
            $table->string('jusCli',255)->comment('Justificación Clinica');
            $table->integer('gn_archivo_id')->comment('FK- Id de archivo del resumen de la HC');
            $table->string('dPrin',4)->comment('Diagnostico principal');
            $table->string('dRel1',4)->comment('Diagnostico Relacionado 1');
            $table->string('dRel2',4)->comment('Diagnostico Relacionado 2');
            $table->integer('as_afiliado_id')->comment('FK -ID del afiliado');
            $table->dateTime('fS')->comment('Fecha de procesamiento');
            $table->integer('gs_usuario_id')->nullable()->comment('FK - Id de usuario que proceso');
            $table->dateTime('fS1')->comment('Fecha de envió al prestador');
            $table->char('oriAut',1)->comment('Origen de la autorización');
            $table->char('viaSol',1)->comment('Vía de solicitud');
//            $table->string('regMed',10)->comment('Registro Médico');
//            $table->string('lregMed',50)->comment('Nombre del Médico');
            $table->integer('au_medico_id')->unsigned();
            $table->char('tInd',1)->comment('Indicativo del telefono de la persona que reporta');
            $table->string('tNum',10)->comment('Número de telefono de la persona que reporta');
            $table->string('tExt',6)->comment('Número de Extensión');
            $table->string('tCel',16)->comment('Telefono Celular');
            $table->string('lesp',20)->comment('Especialidad del Médico que autorizo el servicio');
            $table->string('docs',50)->nullable()->comment('Número');
            $table->integer('usuReg');
            $table->char('ind',1);
            $table->timestamps();

            $table->foreign('gn_archivo_id')->references('id')->on('gn_archivos')->onDelete('restrict');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
            $table->foreign('as_afiliado_id')->references('id')->on('as_afiliados')->onDelete('restrict');
            $table->foreign('au_medico_id')->references('id')->on('au_medicos_solicitantes')->onDelete('restrict');
            $table->foreign('usuReg')->references('id')->on('gn_archivos')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('au_anexot3s');
    }
}
