<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePgAnticipoCxpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_anticipo_cxps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('pg_anticipo_id')->comment('FK - ID del anticipo.');
            $table->foreign('pg_anticipo_id')->references('id')->on('pg_anticipos')->onDelete('restrict');
            $table->integer('pg_cxp_id')->comment('FK - ID de la cuenta por pagar.');
            $table->foreign('pg_cxp_id')->references('id')->on('pg_cxps')->onDelete('restrict');
            $table->double('valor_anterior')->default(0)->comment('Valor antes de anticipar.');
            $table->double('valor_pago')->default(0)->comment('Valor que resta de la CXP');
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
        Schema::dropIfExists('pg_anticipo_cxps');
    }
}
