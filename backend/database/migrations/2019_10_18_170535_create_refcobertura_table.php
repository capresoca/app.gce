<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefcoberturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('refcobertura');

        Schema::create('refcobertura', function (Blueprint $table) {
            $table->char('codigo',2)->primary();
            $table->string('descrip',100);
            $table->char('nivel',1)->default(null);
            $table->char('cober',2)->default(null);
            $table->char('copa',1)->default(null);
            $table->char('ind',1)->default(null);
            $table->char('conv',2)->default(null);
            $table->char('noPoss',1)->default(null);
            $table->char('mps',1)->default(null);
            $table->char('ori',1)->default('1');
            $table->boolean('precedencia');
            $table->char('nivelCtro',1)->default(null);
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `refcobertura` 
                    CHANGE COLUMN `precedencia` `precedencia` TINYINT(2) NOT NULL COMMENT 'Orden de asignacion en datos adicionales de facturas'");

        $sql = "INSERT INTO refcobertura(codigo,descrip,nivel,cober,copa,ind,conv,noPoss,mps,ori,precedencia,nivelCtro) VALUES
                (12,'Insuficiencia Renal',4,NULL,NULL,1,7,NULL,NULL,1,22,4),
                (70,'Cancer',4,NULL,NULL,1,8,NULL,NULL,1,21,4),
                (50,'Gran Quemado',4,NULL,NULL,1,9,NULL,NULL,1,30,4),
                (20,'Sistema Nervioso Central',4,NULL,NULL,1,19,NULL,NULL,1,23,4),
                (10,'Enfermedad Cardiovascular',4,NULL,NULL,1,20,NULL,NULL,1,24,4),
                (40,'Trasplantes',4,NULL,NULL,1,27,NULL,NULL,1,28,4),
                (51,'Salud Mental',NULL,NULL,1,1,51,NULL,NULL,1,11,NULL),
                (42,'Urgencia - Baja complejidad',1,NULL,NULL,5,42,NULL,NULL,1,9,NULL),
                (43,'Urgencia - II y III Nivel',2,NULL,NULL,5,43,NULL,NULL,1,10,2),
                (66,'Presupuesto',NULL,NULL,1,NULL,66,NULL,NULL,1,-3,NULL),
                (86,'Capitado',NULL,NULL,NULL,NULL,86,NULL,NULL,1,-4,NULL),
                (99,'Tutela - alto costo',NULL,NULL,NULL,3,NULL,1,1,3,32,4),
                (98,'CTC - alto costo',NULL,NULL,NULL,2,NULL,1,1,2,31,4),
                (96,'Trans. De Paciente (Zona Especial)',1,NULL,NULL,4,96,NULL,NULL,1,8,NULL),
                (73,'Albergue',2,NULL,1,1,73,NULL,NULL,1,15,2),
                (74,'Tutela',NULL,NULL,NULL,3,74,1,1,3,13,NULL),
                (75,'CTC',NULL,NULL,1,2,75,1,1,2,12,NULL),
                (76,'No Pos',NULL,NULL,1,NULL,76,1,1,4,-2,NULL),
                (90,'Reemplazos Articulares',4,NULL,NULL,1,83,NULL,NULL,1,29,4),
                (80,'Trauma Mayor',4,NULL,1,1,85,NULL,NULL,1,27,4),
                (60,'Infeccion por VIH',4,NULL,NULL,1,86,NULL,NULL,1,26,4),
                (14,'Unidad Cuidados Intensivos',4,NULL,NULL,1,88,NULL,NULL,1,20,4),
                (30,'Patologias Congenitas',4,NULL,NULL,1,89,NULL,NULL,1,25,4),
                (53,'Apoyo Diagnostico',NULL,NULL,1,1,90,NULL,NULL,1,2,NULL),
                (91,'Apoyo Terapeutico',NULL,NULL,1,1,91,NULL,NULL,1,3,NULL),
                (92,'Consulta',NULL,NULL,NULL,1,92,NULL,NULL,1,4,NULL),
                (93,'Pyp',1,NULL,NULL,1,93,NULL,NULL,1,6,NULL),
                (94,'Suministros',1,NULL,1,4,94,NULL,NULL,1,-1,NULL),
                (95,'Medicamentos',NULL,NULL,NULL,4,95,NULL,NULL,1,1,NULL),
                (72,'Traslado de Pacientes en Ambulancia',NULL,NULL,NULL,1,72,NULL,NULL,1,7,NULL),
                (97,'Hospitalizacion',NULL,NULL,NULL,1,97,NULL,NULL,1,16,NULL),
                (77,'Parto Cesarea',NULL,NULL,NULL,1,98,NULL,NULL,1,17,2),
                (15,'Atencion Domiciliaria',NULL,NULL,1,1,NULL,NULL,NULL,1,19,NULL),
                (1,'Capitacion COMPLETA Baja complejidad',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,-5,1),
                (2,'Capitacion AMBULATORIA Baja complejidad',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,-6,1),
                (3,'Capitacion URGENCIA Y HOSP Baja complejidad',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,-7,1),
                (4,'Capitacion MEDICAMENTOS Baja complejidad',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,-8,1),
                (5,'Capitacion LABORATORIOS Baja complejidad',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,-9,1),
                (6,'Capitacion RADIOLOGIA Baja complejidad',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,-10,1),
                (7,'Capitacion RADIOLOGIA Y ECOGRAFIAS Baja complejidad',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,-11,1),
                (8,'Capitacion P y P Baja complejidad',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,-12,1),
                (9,'Capitacion ODONTOLOGIA Baja complejidad',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,-13,1),
                (19,'Hemofilia',4,NULL,NULL,1,19,NULL,NULL,1,33,4),
                (18,'Artritis Reumatoide',4,NULL,NULL,1,18,NULL,NULL,1,34,4)";

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refcobertura');
    }
}
