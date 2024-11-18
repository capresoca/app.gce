<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCeGarantias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $garantias = [
            [
                'codigo'        => 1,
                'nombre'        => 'Amparo salarios prestaciones sociales e indemnizaciones',
                'descripcion'   => 'Tiene por objeto cubrir a la entidad pública asegurada de 
                                    los perjuicios que se le ocasionen como consecuencia del 
                                    incumplimiento de las obligaciones laborales del contratista 
                                    garantizado, frente al personal requerido para la ejecución del contrato amparado.',


            ],
            [
                'codigo'        => 2,
                'nombre'        => 'Buen Manejo y correcta inversión del Anticipo o Pago por Anticipado',
                'descripcion'   => 'El amparo de buen manejo y correcta inversión del anticipo cubre a la 
                                    Entidad Contratante de los perjuicios sufridos como consecuencia de los 
                                    siguientes riesgos:
                                    a. No inversión del anticipo.
                                    b. Uso indebido del anticipo.
                                    c. Apropiación indebida del anticipo.'

            ],
            [
                'codigo'        => 3,
                'nombre'        => 'Poliza de Calidad del servicio',
                'descripcion'   => 'El amparo de calidad del servicio cubre a la Entidad Estatal por los perjuicios 
                                    derivados de la deficiente calidad del servicio prestado por el contratista. 
                                    Estos perjuicios generalmente se presentan con posterioridad a la terminación 
                                    del contrato, como por ejemplo los que se generan por la mala calidad o 
                                    insuficiencia de los entregables en un contrato de consultoría.'
            ],
            [
                'codigo'        => 4,
                'nombre'        => 'Poliza de Calidad y correcto funcionamiento de los bienes',
                'descripcion'   => 'El amparo de calidad y correcto funcionamiento de los bienes tiene por objeto cubrir 
                                    a la entidad por los perjuicios imputables al contratista garantizado por los siguientes hechos:

                                        La mala calidad o las deficiencias técnicas de los bienes o equipos suministrados 
                                        por el contratista, de acuerdo con las especificaciones técnicas establecidas en el contrato.
                                        El incumplimiento de los parámetros o normas técnicas establecidas para el respectivo bien o equipo.'
            ],
            [
                'codigo'        => 5,
                'nombre'        => 'Poliza de Cumplimiento',
                'descripcion'   => ''
            ],
            [
                'codigo'        => 6,
                'nombre'        => 'Poliza de Garantia',
                'descripcion'   => ''
            ],
            [
                'codigo'        => 7,
                'nombre'        => 'Responsabilidad Civil frente a terceros',
                'descripcion'   => ''
            ],

        ];

        DB::table('ce_garantias')->insert($garantias);
    }

}
