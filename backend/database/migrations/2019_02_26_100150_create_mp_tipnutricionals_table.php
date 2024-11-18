<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpTipnutricionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('mp_tipnutricionals');

        Schema::create('mp_tipnutricionals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->index();
            $table->string('descripcion');
        });

        $sql = "INSERT INTO mp_tipnutricionals(codigo,descripcion)  VALUES
                ('1101','Sustitutos de comidas intermedias - 75 a 150 kcal no deben sobrepasar las 1200 kcal al día.'),
                ('1102','Sustitutos de una comida principal - 150 a 300 kcal no deben sobrepasar las 1200 kcal al día.'),
                ('1201','Aminoácidos libres'),
                ('1301','Proteína hidrolizada Basadas en Péptidos'),
                ('1302','Proteína Parcialmente hidrolizada'),
                ('1401','Diabetes - Baja carga de carbohidratos'),
                ('1402','Diabetes - Cantidad permitida de Fructosa ≤ 60g/día'),
                ('1403','DNT Aguda - F75 Formula lactea de realimentación 75Kc/100ml.'),
                ('1404','DNT Aguda - FTLC Formula terapeutica lista para el consumo 500kc/92g.'),
                ('1405','Estrés Metabólico - Altas en péptidos y  antioxidantes y modificadas en hidratos de carbono y lípidos.'),
                ('1406','Hepática - Alto en aminoácidos de cadena ramificada, bajo en aminoácidos aromáticos'),
                ('1407','Inmuno moduladoras - Arginina, glutamina, ω3 ácidos grasos, nucleótidos y antioxidantes.'),
                ('1408','Pulmonar - Alto aporte de proteína y moderado aporte en grasa.'),
                ('1409','Renal Diálisis - Alta en proteína y modificada en micronutrientes para neutralizar pérdidas por diálisis.'),
                ('1410','Renal Prediálisis - Estadios 2,3,4 Baja en proteína, fósforo y electrolitos.'),
                ('1411','Enfermedades del sistema nervioso'),
                ('1501','Alta en Proteína - Proteína mayor al 20% de la energía total'),
                ('1502','Con Fibra - 5 a 15 g/L'),
                ('1503','Densidad Calórica - 1 a 2 kcal/mL'),
                ('1504','Estándar - Distribución normal de la dieta'),
                ('1601','Modulos de proteina, carbohidratos, lipidos'),
                ('1701','Fórmulas especiales para niños (lactantes, niños de corta edad y niños)')
                ";

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_tipnutricionals');
    }
}
