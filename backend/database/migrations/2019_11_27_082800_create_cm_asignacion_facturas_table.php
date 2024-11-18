<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmAsignacionFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_asignacion_facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ac_auditor_id')->comment('FK - ID del auditor');
            $table->integer('cm_factura_id')->comment('FK -ID de la factura asignada');
            $table->char('estado',1)->comment('Control de estado de auditor para cuando se
            reasigna. (1 = Activo, 2 = Inactivo)');
            $table->integer('gs_usuario_id')->comment('FK - ID del usuario que asigna');
            $table->date('fecha_reasignacion')->comment('Si la factura fue reasignada,
            este campo se actualizara por cada auditor que se reasigne')->default(null)->nullable();
            $table->timestamps();

            $table->foreign('ac_auditor_id')->references('id')->on('ac_auditores')->onDelete('restrict');
            $table->foreign('cm_factura_id')->references('id')->on('cm_facturas')->onDelete('restrict');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_asignacion_facturas');
    }
}
