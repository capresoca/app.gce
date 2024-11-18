<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCmRadcambiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cm_radcambios');

        Schema::create('cm_radcambios', function (Blueprint $table) {
            $table->increments('id')->comment('Id registro');
            $table->integer('cm_radicado_id')->comment('FK -Id de la Cuenta Radicada');
            $table->unsignedInteger('cm_estadosrad_id')->comment('FK - Id del estado del radicado');
            $table->string('descripcion')->nullable()->comment('Método o descripción de la traza de la cuenta');
            $table->char('aceptado','2')->nullable()->comment('1 = Si, 2 = No');
            $table->integer('gs_usuario_id')->comment('FK - Id del Usuario que realizo el cambio');
            $table->timestamps();

            $table->foreign('cm_radicado_id')->references('id')->on('cm_radicados')->onDelete('restrict');
            $table->foreign('cm_estadosrad_id')->references('id')->on('cm_estadosrads')->onDelete('restrict');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
        });

        DB::statement('TRUNCATE cm_radcambios');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_radcambios');
    }
}
