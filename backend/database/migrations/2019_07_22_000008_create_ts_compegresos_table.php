<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsCompegresosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'ts_compegresos';

    /**
     * Run the migrations.
     * @table ts_compegresos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('fecha');
            $table->bigInteger('consecutivo');
            $table->integer('gn_tercero_id');
            $table->enum('forma_pago', ['Cheque', 'Efectivo', 'Transferencia']);
            $table->integer('ts_cuenta_id')->nullable();
            $table->integer('ts_caja_id')->nullable();
            $table->integer('nf_cencosto_id')->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('rs_planescobertura_id')->nullable();
            $table->integer('gs_usuario_id');
            $table->integer('gs_usuanula_id')->nullable();
            $table->dateTime('fecha_anula')->nullable();
            $table->text('motivo_anula')->nullable();
            $table->tinyInteger('anticipo')->default('0');

            $table->timestamps();

            $table->foreign('gn_tercero_id', 'fk_laravel_gn_terceros_idx')
                ->references('id')->on('gn_terceros')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('ts_cuenta_id', 'fk_ts_compegresos_ts_cuentas1_idx')
                ->references('id')->on('ts_cuentas')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('ts_caja_id', 'fk_ts_compegresos_ts_cajas1_idx')
                ->references('id')->on('ts_cajas')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('nf_cencosto_id', 'fk_ts_compegresos_nf_cencostos1_idx')
                ->references('id')->on('nf_cencostos')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('rs_planescobertura_id', 'fk_ts_compegresos_rs_planescoberturas1_idx')
                ->references('id')->on('rs_planescoberturas')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('gs_usuario_id', 'fk_ts_compegresos_gs_usuarios1_idx')
                ->references('id')->on('gs_usuarios')
                ->onDelete('restrict')
                ->onUpdate('no action');

            $table->foreign('gs_usuanula_id', 'fk_ts_compegresos_gs_usuarios2_idx')
                ->references('id')->on('gs_usuarios')
                ->onDelete('restrict')
                ->onUpdate('no action');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->tableName);
     }
}
