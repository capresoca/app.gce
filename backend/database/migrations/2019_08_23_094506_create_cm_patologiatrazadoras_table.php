<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmPatologiatrazadorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_patologiatrazadoras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('descripcion',300)->nullable();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('cm_patologiatrazadoras')->insert([
           ['nombre' => 'ASMA'],
           ['nombre' => 'CANCER'],
           ['nombre' => 'DIABETES'],
           ['nombre' => 'ECV'],
           ['nombre' => 'EDA/IRA EN MENOR DE 5 AÑOS'],
           ['nombre' => 'EPOC'],
           ['nombre' => 'ERC'],
           ['nombre' => 'GRAN QUEMADO'],
           ['nombre' => 'HEMOFILIA'],
           ['nombre' => 'HERIDOS EN COMBATE'],
           ['nombre' => 'HOSPITALIZACIÓN MATERNA'],
           ['nombre' => 'HTA EMBARAZO'],
           ['nombre' => 'MALTRATO INFANTIL'],
           ['nombre' => 'PATOLOGIA DE SNC'],
           ['nombre' => 'SEPSIS'],
           ['nombre' => 'SÍFILIS GESTACIONAL'],
           ['nombre' => 'SOAT/HPAF/HPACP'],
           ['nombre' => 'VIH'],
           ['nombre' => 'CRISIS HIPERTENSIVA'],
           ['nombre' => 'INTENTO SUICIDIO'],
           ['nombre' => 'MALTRATO'],
           ['nombre' => 'VIOLENCIA SEXUAL']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_patologiatrazadoras');
    }
}
