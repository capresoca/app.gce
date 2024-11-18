<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefmotivonegTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('refmotivoneg');
        Schema::create('refmotivoneg', function (Blueprint $table) {
            $table->char('codigo', 1)->primary();
            $table->string('descrip',300);
            $table->char('ind',1);
            $table->timestamps();
        });

        $sql = "INSERT INTO refmotivoneg(codigo,descrip,ind) VALUES
                ('G', 'G - La cobertura de la póliza SOAT no ha sido agotada','1'),
                ('1', '1 - La cobertura no es POS','1'),
                ('2', '2 - La justificacion clinica es insuficiente','1'),
                ('3', '3 - Servicio autorizado previamente','1'),
                ('4', '4 - Servicio autorizado en atencion integral','1'),
                ('5', '5 - Servicio no pertinente al DX','1'),
                ('6', '6 - Solicitud extemporanea','1'),
                ('7', '7 - Solicitud no pertenece al ambito de atencion','1'),
                ('8', '8 - No se precisa la Especialidad','1'),
                ('9', '9 - Radicacion incorrecta','1'),
                ('T', 'T - Formula medica no cumple con articulo 2.5.3.10.16 decreto 780','1'),
                ('A', 'A - Servicio incluido en paquete, capita o Pgp','1'),
                ('C', 'C -  El servicio es responsabilidad de la ARL','1'),
                ('Z', '10- Solicitud corresponde a una exclusion del plan de beneficios','1')";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refmotivoneg');
    }
}
