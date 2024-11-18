<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMpSercomplementariostu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_sercomplementariostu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',3);
            $table->text('descripcion');
        });

        $sql = "INSERT INTO mp_sercomplementariostu(codigo,descripcion) VALUES
                ('1','ACEITES (VEGETALES, ANIMALES, MINERALES)'),
                ('2','ALOJAMIENTO, HOSPEDAJE Y ALBERGUE'),
                ('3','ASEO PERSONAL (GEL ANTIBACTERIAL, DESODORANTES, PROTECTORES LABIALES, TOALLAS DE PAPEL, SOLUCIONES PARA LIMPIAR LENTES, TOALLAS HIGIENICAS)'),
                ('4','CREMAS ANTIPAÑALITIS'),
                ('5','CREMAS CICATRIZANTES Y REPARADORES DÉRMICOS'),
                ('6','CREMAS Y LOCIONES HUMECTANTES, HIDRATANTES Y EMOLIENTES'),
                ('7','EDULCORANTES'),
                ('8','EQUINOTERAPIA, HIPOTERAPIA Y/O REHABILITACIÓN ECUESTRE'),
                ('9','FAJAS'),
                ('10','HIGIENE ORAL (CEPILLO, CREMA, SEDA DENTAL, ENJUAGUE)'),
                ('11','JABONES COSMÉTICOS, ANTIALÉRGICOS Y ANTIBACTERIALES'),
                ('12','LOCIONES REPELENTES DE USO PERSONAL Y DOMÉSTICO'),
                ('13','MASAJES'),
                ('14','MUSICOTERAPIA'),
                ('15','OTRAS TERAPIAS (REGENERACIÓN DÉRMICA, FUNCIONES EJECUTIVAS, VOJTA, HALLIWICK, VOCACIONALES, PISCINAS, RECREATIVAS)'),
                ('16','PAÑITOS HÚMEDOS'),
                ('17','PROGRAMAS EDUCATIVOS INTEGRALES Y PSICOPEDAGOGIA'),
                ('18','SILLAS DE RUEDAS Y COCHES NEUROLÓGICOS'),
                ('19','SUEROTERAPIA'),
                ('20','TERAPIA ASISTIDA CON PERROS'),
                ('21','TERAPIA SOMBRA'),
                ('22','TRANSPORTE NO AMBULANCIA'),
                ('23','TRASLADOS Y TIQUETES AÉREOS'),
                ('24','VIÁTICOS'),
                ('25','BLOQUEADORES SOLARES'),
                ('26','CHAMPÚ Y LOCIONES CAPILARES'),
                ('27','IMPLANTOLOGÍA DENTAL'),
                ('28','LENTES DE CONTACTO'),
                ('29','MEDIAS DE COMPRESIÓN GRADUADA Y ANTIEMBÓLICAS'),
                ('30','MEDICAMENTOS FITOTERAPEÚTICOS'),
                ('31','MEDICAMENTOS HOMEOPÁTICOS'),
                ('32','PAÑALES'),
                ('33','TRATAMIENTOS DE ORTODONCIA'),
                ('34','TRATAMIENTOS DE PERIODONCIA'),
                ('35','ZAPATOS Y PLANTILLAS ORTOPÉDICAS'),
                ('36','CUIDADOR'),
                ('37','ASEO PERSONAL (GEL ANTIBACTERIAL, DESODORANTES, PROTECTORES LABIALES, TOALLAS DE PAPEL, TOALLAS HIGIENICAS, MAQUILLAJE)'),
                ('38','OTRAS TERAPIAS (TERAPIAS ALTERNATIVAS, DE REGENERACIÓN DÉRMICA, DE FUNCIONES EJECUTIVAS, VOJTA, HALLIWICK, VOCACIONALES, PISCINOTERAPIA, RECREATIVAS, BALNEOTERAPIA)'),
                ('39','SILLAS DE RUEDAS Y COCHES NEUROLÓGICOS CON SUS RESPECTIVOS ADITAMENTOS PARA GARANTIZAR LA SEGURIDAD DEL PACIENTE'),
                ('40','IMPLANTOLOGIA Y BLANQUEAMIENTO DENTAL'),
                ('41','LENTES DE CONTACTO, LENTES FOTOSENSIBLES, LENTES EXTERNOS CON FILTROS O PELICULAS ESPECIALES, MONTURAS Y ACCESORIOS DE LIMPIEZA O MANTENIMIENTO PARA ESTOS'),
                ('42','COLCHÓN Y/O COJÍN ANTIESCARAS'),
                ('43','INSUMOS PARA ATENCIÓN DOMICILIARIA DEL PACIENTE QUE NO SE ENCUENTRA EN EXTENSIÓN HOSPITALARIA EN EL DOMICILIO (PATO, ELEVADOR SANITARIO, SILLAS PARA BAÑO, GRÚA HIDRAÚLICA PARA MOVILIZACIÓN, TENSIOMÉTRO, CAMA ESPECIAL, ATRILES)'),
                ('44','ELEMENTOS RECREATIVOS (JUGUETES, INSUMOS PARA NATACIÓN)'),
                ('45','TRANSPORTE AMBULANCIA NO CUBIERTA POR EL PBS-UPC'),
                ('46','PRODUCTOS DE SOPORTE NUTRICIONAL'),
                ('47','ACTUALIZACIONES DE SOFTWARE, PROGRAMACIÓN Y ACCESORIOS DE IMPLANTE COCLEAR Y AUDIFONOS DE CONDUCCION OSEA NO CUBIERTO POR EL PBS_UPC'),
                ('48','CITRATO DE CALCIO+VITAMINA D-600/ 400 MG/UI GRÁNULOS SOBRE PPR 3.3 G'),
                ('49','MEZCLA DE ALMIDÓN DE MAÍZ MODIFICADO (ENTEREX) (ALMIDÓN DE MAÍZ MODIFICADO 227 G PARA RECONSTITUIR A SOLUCIÓN ORAL - 5.0 GRAMOS)'),
                ('50','SUPLEMENTOS DIETARIOS'),
                ('51','ALIMENTOS'),
                ('52','FÓRMULAS LÁCTEAS'),
                ('53','INSUMOS DE COMUNICACIÓN ASISTIDAS PARA PERSONAS CON DISCAPACIDAD'),
                ('54','HOSPEDAJE, ALIMENTACIÓN Y VIÁTICOS CONTEMPLADOS EN LEYES ESPECIALES DIFERENTES A LOS OTORGADOS A LAS COMUNIDADES INDÍGENAS'),
                ('55','ANTICUERPOS ANTIFOSFOLIPASA A2')";

        DB::statement($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_sercomplementariostu');
    }
}



