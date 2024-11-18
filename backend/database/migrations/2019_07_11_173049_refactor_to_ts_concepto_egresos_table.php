<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorToTsConceptoEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_concepto_egresos', function (Blueprint $table) {
            $table->dropColumn('facturacion');
            $table->dropColumn('anticipos');
            $table->dropColumn('descuentos');
            $table->dropColumn('presupuesto');
            $table->dropColumn('acreedores');
            $table->dropColumn('concepto_cajamenor');
            $table->dropColumn('orden_pago');
            $table->dropColumn('reembolso_cajamenor');
            $table->dropColumn('descuento_cajamenor');
            $table->dropColumn('cdt');
            $table->dropColumn('devolucion_paciente');
            $table->dropColumn('bancos');

            $table->dropForeign('nf_niif_concepto_egreso_bancos');
            $table->dropForeign('pr_rubro_ingreso_concepto_egreso');
            $table->dropForeign('pr_rubro_presupuestal_concepto_egreso');

            $table->dropColumn('pr_rubro_presupuestal_id');
            $table->dropColumn('pr_rubro_ingreso_id');
            $table->dropColumn('nf_niif_bancos_id');
            $table->dropColumn('cod_cajamenor');

        });

        Schema::table('ts_concepto_egresos', function (Blueprint $table) {
            $table->boolean('facturacion');
            $table->boolean('anticipos');
            $table->boolean('descuentos');
            $table->boolean('presupuesto');
            $table->boolean('acreedores');
            $table->boolean('concepto_cajamenor');
            $table->boolean('orden_pago');

            $table->boolean('reembolso_cajamenor');
            $table->boolean('descuento_cajamenor');
            $table->boolean('cdt');
            $table->boolean('devolucion_paciente');
            $table->boolean('bancos');
            $table->boolean('maneja_contrato');
            $table->boolean('maneja_incapacidad');
            $table->boolean('maneja_cuentamedica');

            $table->integer('pr_detstrgasto_id')->unsigned();
            $table->foreign('pr_detstrgasto_id')->references('id')->on('pr_detstrgastos');
//
            $table->integer('ts_cuenta_id');
            $table->foreign('ts_cuenta_id')->references('id')->on('ts_cuentas');

            $table->integer('ts_caja_id');
            $table->foreign('ts_caja_id')->references('id')->on('ts_cajas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_concepto_egresos', function (Blueprint $table) {
            $table->dropColumn('facturacion');
            $table->dropColumn('anticipos');
            $table->dropColumn('descuentos');
            $table->dropColumn('presupuesto');
            $table->dropColumn('acreedores');
            $table->dropColumn('concepto_cajamenor');
            $table->dropColumn('orden_pago');
            $table->dropColumn('reembolso_cajamenor');
            $table->dropColumn('descuento_cajamenor');
            $table->dropColumn('cdt');
            $table->dropColumn('devolucion_paciente');
            $table->dropColumn('bancos');
            $table->dropColumn('maneja_contrato');
            $table->dropColumn('maneja_incapacidad');
            $table->dropColumn('maneja_cuentamedica');
            $table->dropForeign('ts_concepto_egresos_pr_detstrgasto_id_foreign');
            $table->dropForeign('ts_concepto_egresos_ts_caja_id_foreign');
            $table->dropForeign('ts_concepto_egresos_ts_cuenta_id_foreign');
        });


        Schema::table('ts_concepto_egresos', function (Blueprint $table) {

            $table->dropColumn('pr_detstrgasto_id');
            $table->dropColumn('ts_cuenta_id');
            $table->dropColumn('ts_caja_id');

            $table->enum('facturacion',['Si','No']);
            $table->enum('anticipos',['Si','No']);
            $table->enum('descuentos',['Si','No']);
            $table->enum('presupuesto',['Si','No']);
            $table->enum('acreedores',['Si','No']);
            $table->enum('concepto_cajamenor',['Si','No']);
            $table->enum('orden_pago',['Si','No']);
            $table->enum('reembolso_cajamenor',['Si','No']);
            $table->enum('descuento_cajamenor',['Si','No']);
            $table->enum('cdt',['Si','No']);
            $table->enum('devolucion_paciente',['Si','No']);
            $table->enum('bancos',['Si','No']);

            $table->integer('pr_rubro_presupuestal_id');
            $table->integer('pr_rubro_ingreso_id');
            $table->integer('nf_niif_bancos_id');
            $table->integer('cod_cajamenor');
            $table->foreign('pr_rubro_presupuestal_id','pr_rubro_presupuestal_concepto_egreso')
                ->references('id')->on('pr_rubros');
            $table->foreign('pr_rubro_ingreso_id','pr_rubro_ingreso_concepto_egreso')
                ->references('id')->on('pr_rubros');
            $table->foreign('nf_niif_bancos_id','nf_niif_concepto_egreso_bancos')
                ->references('id')->on('nf_niifs');
        });
    }
}





