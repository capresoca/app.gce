<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmEstadosradsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_estadosrads', function (Blueprint $table) {
            $table->increments('id')->comment('Identificador del estado');
            $table->string('estado',15)->comment('Definición de nombre estado');
            $table->integer('dias')->comment('Días que debe durar el radicado con el estado');
            $table->timestamps();
        });

        $sql = "INSERT INTO cm_estadosrads(id,estado,dias) VALUES
                (1, 'Radicado',2),
                (2, 'Auditoria',18),
                (3, 'Contabilidad',4),
                (4, 'Tesoreria',4),
                (5, 'Pagado',1),
                (6, 'Archivo',999),
                (7,'Devuelta',0)";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_estadosrads');
    }
}
