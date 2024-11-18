<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmEventosaludpublicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_eventosaludpublicas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('descripcion',300)->nullable();
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::table('cm_eventosaludpublicas')->insert([
            ['nombre' => 'ACCIDENTE OFÍDICO CC NO'],
            ['nombre' => 'CÓLERA PC SI'],
            ['nombre' => 'CHAGAS C NO'],
            ['nombre' => 'DENGUE CLÁSICO P NO'],
            ['nombre' => 'DENGUE HEMORRÁGICO P NO'],
            ['nombre' => 'DIFTERIA P NO'],
            ['nombre' => 'ENCEFALITIS DEL NILO OCCIDENTAL EN HUMANOS P SÍ'],
            ['nombre' => 'ENCEFALITIS EQUINA DEL OESTE EN HUMANOS P SÍ'],
            ['nombre' => 'ENCEFALITIS EQUINA VENEZOLANA EN HUMANOS P SÍ'],
            ['nombre' => 'ENFERMEDADES DE ORIGEN PRIÓNICO P SÍ'],
            ['nombre' => 'EXPOSICIÓN RÁBICA P NO'],
            ['nombre' => 'FIEBRE AMARILLA P SÍ'],
            ['nombre' => 'FIEBRE TIFOIDEA Y PARATIFOIDEA P NO'],
            ['nombre' => 'HEPATITIS A C NO'],
            ['nombre' => 'HEPATITIS B C NO'],
            ['nombre' => 'INTOXICACIÓN POR ALIMENTOS Y POR AGUA (BROTE) P SÍ'],
            ['nombre' => 'INTOXICACIÓN POR PLAGUICIDAS P SÍ'],
            ['nombre' => 'INTOXICACIÓN POR FÁRMACOS P SÍ'],
            ['nombre' => 'INTOXICACIÓN POR METANOL P SÍ'],
            ['nombre' => 'INTOXICACIÓN POR METALES PESADOS P SÍ'],
            ['nombre' => 'INTOXICACIÓN POR SOLVENTES P SÍ'],
            ['nombre' => 'INTOXICACIÓN POR OTRAS SUSTANCIAS QUÍMICAS P SÍ'],
            ['nombre' => 'LEISHMANIASIS CUTÁNEA C NO'],
            ['nombre' => 'LEISHMANIASIS MUCOSA C NO'],
            ['nombre' => 'LEISHMANIASIS VISCERAL C NO'],
            ['nombre' => 'LESIONES POR PÓLVORA C NO'],
            ['nombre' => 'LEPRA C NO'],
            ['nombre' => 'LEPTOSPIROSIS P NO'],
            ['nombre' => 'MALARIA ASOCIADA (FORMAS MIXTAS) C NO'],
            ['nombre' => 'MALARIA FALCIPARUM C NO'],
            ['nombre' => 'MALARIA MALARIAE C NO'],
            ['nombre' => 'MALARIA VIVAX C NO'],
            ['nombre' => 'MALARIA COMPLICADA C SÍ'],
            ['nombre' => 'MENINGITIS MENINGOCÓCCICA P NO'],
            ['nombre' => 'MENINGITIS POR HAEMOPHILUS INFLUENZAE P NO'],
            ['nombre' => 'MENINGITIS POR NEUMOCOCO P NO'],
            ['nombre' => 'MENINGITIS TUBERCULOSA P NO'],
            ['nombre' => 'MORTALIDAD MALARIA C NO'],
            ['nombre' => 'MORTALIDAD MATERNA C NO'],
            ['nombre' => 'MORTALIDAD PERINATAL C NO'],
            ['nombre' => 'MORTALIDAD POR CÓLERA C SÍ'],
            ['nombre' => 'MORTALIDAD POR DENGUE C NO'],
            ['nombre' => 'MORTALIDAD POR EDA DE 0 A 4 AÑOS C NO'],
            ['nombre' => 'MORTALIDAD POR IRA 0-4 AÑOS C NO'],
            ['nombre' => 'PARÁLISIS FLÁCIDA AGUDA (MENORES DE 15 AÑOS) P SÍ'],
            ['nombre' => 'PAROTIDITIS CC NO'],
            ['nombre' => 'PESTE (BUBÓNICA/NEUMÓNICA) P SÍ'],
            ['nombre' => 'RABIA EN PERROS O GATOS P NO'],
            ['nombre' => 'RABIA HUMANA P SÍ'],
            ['nombre' => 'RUBÉOLA S SÍ'],
            ['nombre' => 'RUBÉOLA CONGÉNITA S NO'],
            ['nombre' => 'SARAMPIÓN S SÍ'],
            ['nombre' => 'SÍFILIS CONGÉNITA P NO'],
            ['nombre' => 'SÍFILIS GESTACIONAL P NO'],
            ['nombre' => 'TÉTANOS ACCIDENTAL P NO'],
            ['nombre' => 'TÉTANOS NEONATAL P NO'],
            ['nombre' => 'TIFUS EPIDÉMICO TRASMITIDO POR PIOJOS C SÍ'],
            ['nombre' => 'TIFUS ENDÉMICO TRASMITIDO POR PULGAS C SÍ'],
            ['nombre' => 'TOS FERINA P NO'],
            ['nombre' => 'TUBERCULOSIS EXTRAPULMONAR C NO'],
            ['nombre' => 'TUBERCULOSIS PULMONAR C NO'],
            ['nombre' => 'VARICELA CC NO'],
            ['nombre' => 'VIH SIDA C NO']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_eventosaludpublicas');
    }
}
