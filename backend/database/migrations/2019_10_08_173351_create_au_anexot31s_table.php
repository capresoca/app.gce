<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuAnexot31sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('au_anexot31s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('au_anexot3s_id')->comment('id at3')->unsigned();
            $table->string('codigo',20)->index()->comment('codigo servicio autorizado (cups-cum-codigo propio)');
            $table->string('descrip',255)->comment('descrip servicio autorizado (cups-cum-codigo propio)');
            $table->tinyInteger('cant')->comment('cantidad solicitada');
            $table->tinyInteger('cAut')->comment('cantidad autorizada');
            $table->integer('gs_usuario_id')->comment('usuario q autoriza');
            $table->dateTime('fS')->comment('fecha de autorizacion');
            $table->integer('pAut')->comment('prestador autorizado');
            $table->char('nivel')->comment('nivel de complejidad');
            $table->char('modSer')->comment('modalidad de servicio tabla ref refModalidadServicio');
            $table->char('tipModSer')->comment('tipo modalidad servicio tabla ref refQx');
            $table->char('cober',2)->comment('coberturas tabla referencia refCobertura');
            $table->text('obs')->comment('observacion');
            $table->integer('espec')->comment('especialidad codigo especialidad')->unsigned();
            $table->date('fCad')->comment('fecha vencimiento de la uatorizacion');
            $table->double('valor',12,2)->comment('valor autorizacion');
            $table->double('copago',8,2)->comment('copago');
            $table->integer('cont')->comment('id contrato ');
            $table->char('aCond')->comment('si la autorizacion es condicionada o no tabla ref. refSiNo');
            $table->char('reco')->comment('Opcion de recobro tabal ref. refRecobro');
            $table->char('ind')->comment('indicador si esta anulada o no. 1= activa 2=anulada');
            $table->integer('nFac')->comment('id de factura relacionada a esta autorizacion')->unsigned()->default(0);
            $table->char('imp');
            $table->timestamps();

            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios');
            $table->foreign('espec')->references('id')->on('au_especialidads');
            $table->foreign('pAut')->references('id')->on('rs_entidades');
            $table->foreign('cont')->references('id')->on('rs_planescoberturas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('au_anexot31s');
    }
};

