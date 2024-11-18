<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefespecialidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('refespecialidad');

        Schema::create('refespecialidad', function (Blueprint $table) {
            $table->char('codigo',3)->primary();
            $table->string('descrip',50);
            $table->char('nivel',1)->default('3');
            $table->char('codHab',3)->default(null);
            $table->char('codHabHos',3)->default(null);
            $table->char('ind',1)->default(null);
            $table->timestamps();
        });

        $sql = "INSERT INTO refespecialidad(codigo,descrip,nivel,codHab,codHabHos,ind) VALUES
                    (1,'Anestesia',3,301,301,1),
                    (2,'Anestesia- cardiovascular',3,303,303,1),
                    (3,'Cardiologia',3,302,302,NULL),
                    (4,'Cardiologia - electrofisiologia',3,302,302,NULL),
                    (5,'Cardiologia - hemodinamia',3,302,302,NULL),
                    (6,'Cardiologia pediatrica',4,361,361,NULL),
                    (9,'Cirugia cardiovascular',3,303,202,1),
                    (10,'Cirugia cardiovascular pediatrica',3,361,361,NULL),
                    (11,'Cirugia de cabeza y cuello',3,362,201,1),
                    (12,'Cirugia de cabeza y cuello oncologica',3,362,201,NULL),
                    (13,'Otros',2,NULL,NULL,NULL),
                    (14,'Cirugia de seno',3,364,232,1),
                    (15,'Cirugia gastrointestinal y hepatobiliar',3,367,235,1),
                    (16,'Cirugia gastrointestinal y hepatobiliar pediatrica',3,367,235,NULL),
                    (17,'Cirugia General',2,304,203,NULL),
                    (18,'Cirugia ginecologica',3,368,204,NULL),
                    (19,'Cirugia Maxilofacial',3,411,205,NULL),
                    (20,'Cirugia neurologica',3,305,206,NULL),
                    (21,'Cirugia oftalmologica',3,335,208,NULL),
                    (22,'Cirugia oncologica',3,373,210,1),
                    (23,'Cirugia oral',3,410,211,1),
                    (24,'Cirugia ortopedica',3,339,207,NULL),
                    (25,'Cirugia otorrinolaringologia',3,340,209,NULL),
                    (26,'Cirugia Pediatrica',3,306,212,NULL),
                    (27,'Cirugia pediatrica oncologica',3,374,227,NULL),
                    (28,'Cirugia Plastica',3,307,213,NULL),
                    (29,'Cirugia terapia endovascular',3,218,218,NULL),
                    (30,'Cirugia urologica',3,355,215,NULL),
                    (31,'Cirugia vascular periferica',3,372,214,1),
                    (32,'Cirugia vascular y angiolofica',3,372,214,NULL),
                    (33,'Coloproctologia',3,377,377,1),
                    (34,'Dermatologia',3,365,233,NULL),
                    (35,'Dermatologia oncologia',3,375,233,NULL),
                    (36,'Dolor y cuidados paliativos',3,309,309,1),
                    (37,'Electrofisiologia',3,378,378,NULL),
                    (38,'Endocrinologia',3,310,310,NULL),
                    (39,'Endocrinologia oncologica',3,310,310,NULL),
                    (40,'Endocrinologia pediatrica',3,310,310,1),
                    (41,'Endodoncia',3,311,311,NULL),
                    (42,'Enfermeria',3,312,312,1),
                    (44,'Estomatologia',3,313,313,NULL),
                    (45,'Fisiatria',3,739,739,NULL),
                    (46,'Fisioterapia',3,739,739,NULL),
                    (47,'Foniatria',3,740,740,NULL),
                    (48,'Fonoaudiologia',3,740,740,NULL),
                    (49,'Gastroenterologia',3,316,235,NULL),
                    (50,'Gastroenterologia oncologica',3,316,235,NULL),
                    (51,'Gastroenterologia pediatrica',3,316,235,1),
                    (52,'Genetica',3,317,317,1),
                    (53,'Geriatria',3,318,318,1),
                    (54,'Gerontologia',3,319,319,NULL),
                    (55,'Ginecologia y Obstetricia',2,320,907,1),
                    (56,'Ginecologia oncologia',3,379,204,NULL),
                    (57,'Hematologia',3,321,321,1),
                    (58,'Hematologia pediatrica',3,391,391,1),
                    (59,'Hepatologia',3,316,235,1),
                    (60,'Hepatologia pediatrica',3,316,235,NULL),
                    (61,'Implantologia',3,322,226,NULL),
                    (62,'Infectologia',3,323,323,1),
                    (63,'Infectologia pediatrica',3,323,323,NULL),
                    (64,'Inmunologia',3,324,324,NULL),
                    (65,'Mastologia',3,364,232,1),
                    (66,'Medicina familiar',3,325,325,1),
                    (67,'Medicina fisica y del deporte',3,326,326,1),
                    (68,'Medicina fisica y rehabilitacion',3,327,327,1),
                    (69,'Medicina general',1,328,328,1),
                    (70,'Medicina interna',2,329,329,1),
                    (71,'Medicina nuclear',3,383,383,1),
                    (73,'Nefrologia',3,330,330,1),
                    (74,'Nefrologia pediatrica',3,384,384,1),
                    (75,'Neonatologia',3,385,385,1),
                    (76,'Neumologia',3,331,331,1),
                    (77,'Neumologia oncologica',3,331,331,NULL),
                    (78,'Neumologia pediatrica',3,386,386,1),
                    (79,'Neurocirugía',3,387,206,1),
                    (80,'Neurocirugia oncologica',3,387,206,NULL),
                    (81,'Neurologia',3,332,206,1),
                    (82,'Neurologia pediatrica',3,388,206,1),
                    (83,'Neurosicologia',2,344,344,NULL),
                    (84,'Neurosiquiatria',3,345,345,NULL),
                    (85,'Nutricion y dietetica',3,333,333,1),
                    (86,'Odontologia Especializada',3,334,334,1),
                    (87,'Odontologia General',1,334,334,1),
                    (88,'Odontologia oncologica',3,334,334,NULL),
                    (89,'Odontopediatria',3,396,396,1),
                    (90,'Oftalmologia',2,335,208,1),
                    (91,'Oftalmologia - cornea',3,335,208,NULL),
                    (92,'Oftalmologia glaucomatologo',3,335,208,NULL),
                    (93,'Oftalmologia iridologo',3,335,208,NULL),
                    (94,'Oftalmologia oncologica',3,390,208,NULL),
                    (95,'Oftalmologia pediatrica',3,335,208,NULL),
                    (96,'Oftalmologia plastica',3,335,208,NULL),
                    (97,'Oftalmologia retinologo',3,335,208,NULL),
                    (98,'Oncologia clinica',3,709,210,1),
                    (99,'Oncologia pediatrica',3,374,227,1),
                    (100,'Optometria',3,337,337,1),
                    (101,'Ortodoncia',3,338,338,1),
                    (102,'Ortopedia y traumatologia',2,339,207,1),
                    (103,'Ortopedia oncologica',3,393,207,NULL),
                    (104,'Ortopedia Pediatrica',3,392,207,1),
                    (105,'Otorrinolaringologia',3,340,209,1),
                    (106,'Otras consultas (subespecialidades)',3,356,356,NULL),
                    (107,'Patologia',3,341,341,NULL),
                    (108,'Pediatria',2,342,212,1),
                    (109,'Perinatologia',3,320,320,NULL),
                    (110,'Periodoncia',3,343,343,1),
                    (111,'Psicologia',2,344,344,1),
                    (112,'Psiquiatria',3,345,103,1),
                    (113,'Radiologia e Imagenes Diagnosticas',3,710,710,1),
                    (114,'Radiologia - diagnostica e intervencionista',3,710,710,NULL),
                    (115,'Radioterapia oncologica - Quimioterapia',3,711,711,1),
                    (116,'Rehabilitacion oncologica',3,346,346,NULL),
                    (117,'Rehabilitacion oral',3,347,347,1),
                    (118,'Reumatologia',3,348,348,1),
                    (119,'Salud ocupacional',1,349,349,NULL),
                    (120,'Psiquiatria Pediatrica',3,345,103,1),
                    (121,'Terapia alternativa',3,350,350,1),
                    (122,'Terapia del lenguaje',3,351,351,NULL),
                    (123,'Terapia ocupacional',3,352,352,1),
                    (124,'Terapia respiratoria',3,353,353,1),
                    (125,'Toxicologia',3,354,354,1),
                    (126,'Transplante',3,216,216,1),
                    (127,'Trasplante hepatico adulto',3,221,221,NULL),
                    (128,'Trasplante hepatico pediatrico',3,221,221,NULL),
                    (129,'Urologia',3,355,215,1),
                    (130,'Urologia oncologica',3,395,215,NULL),
                    (131,'Urologia pediatrica',3,355,215,NULL),
                    (147,'Ginecologia',2,368,204,NULL),
                    (148,'Ortoptica',2,335,208,NULL),
                    (135,'Alergología',3,324,324,NULL),
                    (149,'Cirugía articular',3,339,207,NULL),
                    (150,'Cirugía ocular',3,335,208,NULL),
                    (136,'Cirugia de torax',3,366,234,1),
                    (151,'Medicina del Dolor (Algologia)',3,309,309,NULL),
                    (72,'N/A',4,328,328,NULL),
                    (43,'Trabajo Social',1,NULL,NULL,NULL),
                    (132,'Equipo interdisciplinario',3,NULL,NULL,NULL),
                    (133,'Estomatologia y cirugia oral',3,211,410,1),
                    (134,'Radiologia oral y maxilofacial',3,724,724,1),
                    (137,'Medicina Aerospacial',3,NULL,NULL,NULL),
                    (138,'Medicina alternativa (ayurveda)',3,350,350,1),
                    (139,'Medicina alternativa (homeopatia)',3,398,398,1),
                    (140,'Medicina alternativa (medicina tradicional china)',3,400,400,1),
                    (141,'Medicina alternativa (naturopatia)',3,404,404,1),
                    (142,'Medicina del trabajo',3,407,407,1),
                    (143,'Medicina forense',3,NULL,NULL,NULL),
                    (144,'Oncohematologia pediatrica',3,391,391,1),
                    (145,'Reumatologia pediatrica',3,348,348,1),
                    (146,'Sexología clínica ',3,NULL,NULL,NULL),
                    (152,'Cirugia Vascular',4,303,202,NULL),
                    (153,'Otologia',2,NULL,NULL,NULL),
                    (155,'Retinologia Pediatrica',3,335,208,NULL),
                    (154,'Estrabologo Neurologo',3,NULL,NULL,NULL),
                    (156,'Oftamologo Esp en Orbita',3,335,208,NULL)";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refespecialidad');
    }
}
