<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePgAnticiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_anticipos', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->integer('consecutivo')->comment('Consecutivo de anticipos');
            $table->integer('gn_tercero_id')->comment('FK - ID del tercero que pago anticipadamente.');
            $table->foreign('gn_tercero_id')->references('id')->on('gn_terceros')->onDelete('RESTRICT')->onUpdate('NO ACTION');
            $table->longText('observacion')->nullable()->comment('Descripción del pago anticipado');
            $table->enum('estado_documento', ['Registrado', 'Anulado', 'Confirmado'])->comment('Estado del anticipo');
            $table->date('fecha_documento')->comment('Fecha en la cual se realizó el anticipo');
            $table->integer('nf_niifanticipo_id')->nullable()->comment('FK - ID de cuenta que se paga en el anticipo.');
            $table->foreign('nf_niifanticipo_id')->references('id')->on('nf_niifs')->onDelete('restrict');
            $table->integer('nf_cencosto_id')->nullable()->comment('FK - ID de pago del centro de costo anticipo.');
            $table->foreign('nf_cencosto_id')->references('id')->on('nf_cencostos')->onDelete('restrict');
            $table->unsignedInteger('ts_compegreso_id')->nullable()->comment('FK - ID del comporbante de egreso');
            $table->foreign('ts_compegreso_id')->references('id')->on('ts_compegresos')->onDelete('restrict');
            $table->double('valor')->default(0)->comment('Valor del pago del anticipo.');
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
        Schema::dropIfExists('pg_anticipos');
    }
}
