<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefrecobrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('refrecobro');
        Schema::create('refrecobro', function (Blueprint $table) {
            $table->tinyIncrements('codigo');
            $table->string('descrip',50)->default(null);
            $table->char('servicio',2)->nullable();
            $table->char('ind',1)->default('1');
            $table->char('pos',1)->default(null);
            $table->timestamps();
        });

        $sql = "INSERT INTO refrecobro(codigo,descrip,servicio,ind,pos) VALUES
                (1, 'Fosyga - Tutela',NULL,'1',NULL),
                (2, 'Secretaria Salud - Tutela',NULL,'1',NULL),
                (3, 'Secretaria Salud - CTC',NULL,'1',NULL),
                (4, 'IPS baja complejidad (capitado)','7','1','1'),
                (5, 'Poliza AltoCosto',NULL,'1','1'),
                (6, 'IPS mediana complejidad','8',NULL,'1'),
                (7, 'No Pos',NULL,NULL,NULL)";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refrecobro');
    }
}
