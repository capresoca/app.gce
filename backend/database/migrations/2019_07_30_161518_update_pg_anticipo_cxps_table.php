<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePgAnticipoCxpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pg_anticipo_cxps', function (Blueprint $table) {
            $table->dropIfExists();
        });
        Schema::table('pg_anticipocxps_dets', function (Blueprint $table) {
            $table->dropIfExists();
        });
        Schema::create('pg_anticipo_cxps', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('consecutivo');
            $table->unsignedBigInteger('pg_anticipo_id');
            $table->timestamp('fecha_documento');
            $table->integer('gs_usuario_id');
            $table->integer('gn_tercero_id');
            $table->integer('nf_cencosto_id')->nullable();
            $table->enum('estado', ['Confirmado','Anulado']);
            $table->longText('observacion')->nullable();
            $table->integer('gs_usuario_anula_id');
            $table->timestamp('fecha_anulacion');
            $table->longText('motivo_anula')->nullable();
            $table->timestamps();

            $table->foreign('pg_anticipo_id')->references('id')->on('pg_anticipos')->onDelete('restrict')->onUpdate('no action');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict')->onUpdate('no action');
            $table->foreign('gs_usuario_anula_id')->references('id')->on('gs_usuarios')->onDelete('restrict')->onUpdate('no action');
            $table->foreign('gn_tercero_id')->references('id')->on('gn_terceros')->onDelete('restrict')->onUpdate('no action');
            $table->foreign('nf_cencosto_id')->references('id')->on('nf_cencostos')->onDelete('restrict')->onUpdate('no action');
        });
        Schema::disableForeignKeyConstraints();
        Schema::create('pg_anticipocxps_dets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('pg_anticipo_cxp_id');
            $table->integer('pg_cxp_id');
            $table->integer('nf_cencosto_id')->nullable();
            $table->double('valor_abono')->default(0);
            $table->timestamps();
            $table->foreign('pg_anticipo_cxp_id')->references('id')->on('pg_anticipo_cxps')->onDelete('restrict')->onUpdate('no action');
            $table->foreign('pg_cxp_id')->references('id')->on('pg_cxps')->onDelete('restrict')->onUpdate('no action');
            $table->foreign('nf_cencosto_id')->references('id')->on('nf_cencostos')->onDelete('restrict')->onUpdate('no action');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pg_anticipo_cxps', function (Blueprint $table) {
            $table->dropIfExists();
        });
        Schema::create('pg_anticipocxps_dets', function (Blueprint $table) {
            $table->dropIfExists();
        });
    }
}
