<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpCausasnoentregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_causasnoentregas', function (Blueprint $table) {
            $table->integer('id');
            $table->string('causa',155);
            $table->boolean('medicamentos');
            $table->boolean('procedimientos');
            $table->boolean('dispositivos');
            $table->boolean('nutricionales');
            $table->boolean('complementarios');
            $table->enum('aplica_a',['No entrega total','Entrega parcial','Entrega diferida']);
            $table->tinyInteger('tipo');
        });

        $causasnoentregas = array(
            array(
                "id" => 1,
                "causa" => "Misma solicitud en otra prescripción",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "No entrega total",
                "tipo" => 1
            ),
            array(
                "id" => 2,
                "causa" => "Existe evidencia de interacción o reacción medicamentosa",
                "medicamentos" => 1,
                "procedimientos" => 0,
                "dispositivos" => 0,
                "nutricionales" => 1,
                "complementarios" => 0,
                "aplica_a" => "No entrega total",
                "tipo" => 2
            ),
            array(
                "id" => 3,
                "causa" => "La indicación de uso del medicamento no está aprobada por el INVIMA",
                "medicamentos" => 1,
                "procedimientos" => 0,
                "dispositivos" => 0,
                "nutricionales" => 1,
                "complementarios" => 0,
                "aplica_a" => "No entrega total",
                "tipo" => 2
            ),
            array(
                "id" => 4,
                "causa" => "Presentación no fraccionable",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 0,
                "nutricionales" => 1,
                "complementarios" => 0,
                "aplica_a" => "Entrega parcial",
                "tipo" => 2
            ),
            array(
                "id" => 5,
                "causa" => "Suministro por tutela",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "No entrega total",
                "tipo" => 2
            ),
            array(
                "id" => 6,
                "causa" => "Paciente corresponde a otra EPS",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "No entrega total",
                "tipo" => 2
            ),
            array(
                "id" => 7,
                "causa" => "No fue posible contactar al paciente",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "No entrega total",
                "tipo" => 2
            ),
            array(
                "id" => 8,
                "causa" => "Paciente fallecido",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "No entrega total",
                "tipo" => 2
            ),
            array(
                "id" => 9,
                "causa" => "Paciente se niega a recibir el suministro",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "No entrega total",
                "tipo" => 2
            ),
            array(
                "id" => 10,
                "causa" => "No se han agotado los topes o su prescripción corresponde a los condicionamientos de cobertura del PBS",
                "medicamentos" => 1,
                "procedimientos" => 0,
                "dispositivos" => 1,
                "nutricionales" => 0,
                "complementarios" => 0,
                "aplica_a" => "No entrega total",
                "tipo" => 3
            ),
            array(
                "id" => 11,
                "causa" => "La prescripción excede la dosis máxima recomendada",
                "medicamentos" => 1,
                "procedimientos" => 0,
                "dispositivos" => 0,
                "nutricionales" => 1,
                "complementarios" => 0,
                "aplica_a" => "Entrega parcial",
                "tipo" => 3
            ),
            array(
                "id" => 12,
                "causa" => "La prescripción excede los tres meses tratándose de una formulación de primera vez",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "Entrega parcial",
                "tipo" => 3
            ),
            array(
                "id" => 13,
                "causa" => "La prescripción excede el año y no está formulada como sucesiva",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "Entrega parcial",
                "tipo" => 2
            ),
            array(
                "id" => 14,
                "causa" => "El INVIMA no aprobó el MVND",
                "medicamentos" => 1,
                "procedimientos" => 0,
                "dispositivos" => 0,
                "nutricionales" => 1,
                "complementarios" => 0,
                "aplica_a" => "No entrega total",
                "tipo" => 2
            ),
            array(
                "id" => 15,
                "causa" => "El paciente tiene suministro de otra prescripción",
                "medicamentos" => 1,
                "procedimientos" => 0,
                "dispositivos" => 0,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "Entrega diferida",
                "tipo" => 2
            ),
            array(
                "id" => 16,
                "causa" => "El prescriptor y el paciente son el mismo",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "No entrega total",
                "tipo" => 1
            ),
            array(
                "id" => 17,
                "causa" => "Tecnología incluida en el Plan de Beneficios en Salud",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 0,
                "complementarios" => 0,
                "aplica_a" => "No entrega total",
                "tipo" => 2
            ),
            array(
                "id" => 18,
                "causa" => "Exclusión del Plan de Beneficios en Salud",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "No entrega total",
                "tipo" => 2
            ),
            array(
                "id" => 19,
                "causa" => "Tecnología cubierta por otro Plan Adicional en Salud",
                "medicamentos" => 1,
                "procedimientos" => 1,
                "dispositivos" => 1,
                "nutricionales" => 1,
                "complementarios" => 1,
                "aplica_a" => "No entrega total",
                "tipo" => 2
            ),
        );

        DB::table('mp_causasnoentregas')->insert($causasnoentregas);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_causasnoentregas');
    }
}
