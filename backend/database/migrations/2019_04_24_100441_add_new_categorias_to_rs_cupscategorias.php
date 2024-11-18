<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewCategoriasToRsCupscategorias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO rs_cupscategorias (codigo, nombre) VALUES
                            ('0144','PROCEDIMIENTOS EN OTRAS ESTRUCTURAS SUBCORTICALES'),
                            ('0143','PROCEDIMIENTOS EN SUBTÁLAMO'),
                            ('0213','RECONSTRUCCIÓN DE LA BASE O BÓVEDA DEL CRÁNEO'),
                            ('0224','COLOCACIÓN DE CATÉTER VENTRÍCULO PERITONEAL'),
                            ('0290','EMBOLIZACION DE TUMORES INTRACRANEANOS O ESPINALES'),
                            ('0291','CATETERIZACION SUPRASELECTIVA DE VASOS INTRACRANEANOS'),
                            ('0320','CORDOTOMÍAS'),
                            ('0392','OTRAS NEUROLISIS'),
                            ('0406','IMPLANTACION O SUSTITUCION O RETIRO DE OTRO NEUROESTIMULADOR'),
                            ('0484','OTRAS INYECCIONES DE ANESTESIA EN COLUMNA VERTEBRAL CON FINES ANALGÉSICOS'),
                            ('0802','EXTRACCIÓN DE CUERPO EXTRAÑO EN PARPADO'),
                            ('0830','CORRECCIÓN DE PTOSIS PALPEBRAL'),
                            ('0835','CORRECCION DE LAGOFTALMOS O RETRACCION PALPEBRAL'),
                            ('0836','CORRECClÓN DE ENTROPION'),
                            ('0837','CORRECCIÓN DE ECTROPIÓN'),
                            ('0838','OTRAS CORRECCIONES PALPEBRALES'),
                            ('0860','RECONSTRUCCIÓN DE PÁRPADOS'),
                            ('0880','REPARACIÓN DE HERIDA DE CEJA'),
                            ('0900','PEXIA EN LA GLÁNDULA LAGRIMAL'),
                            ('0920','DACRIOADENECTOMÍAS'),
                            ('0950','DRENAJE DEL SACO O CANALÍCULO LAGRIMAL'),
                            ('0990','OBLITERACIÓN O CIERRE DE PUNTOS LAGRIMALES'),
                            ('1040','REPARACIÓN DE SIMBLÉFARON'),
                            ('1072','RECONSTRUCCION DE SUPERFICIE OCULAR O FONDOS DE SACO'),
                            ('1073','CORRECCIONES DE CONJUNTIVOCHALASIS'),
                            ('1143','LIMPIEZA DE ENTREGARA'),
                            ('1154','RECUBRIMIENTO DE CÓRNEA'),
                            ('1160','QUERATOPLASTIA ENDOTELIAL'),
                            ('1232','LISIS DE SINEQUIAS'),
                            ('1263','CIRUGÍA FILTRANTE NO PENETRANTE'),
                            ('1300','EXTRACCION INTRACAPSULAR O EXTRACAPSULAR DE CRISTALINO'),
                            ('1370','INSERCIÓN DE LENTE INTRAOCULAR'),
                            ('1502','EXPLORACIONES DE MÚSCULOS EXTRAOCULARES'),
                            ('1520','ALARGAMIENTO O ACORTAMIENTO EN MUSCULO EXTRA OCULAR'),
                            ('1540','REPOSICIONAMIENTO DE MÚSCULOS EXTRAOCULARES'),
                            ('1630','EVISCERACIÓN DEL GLOBO OCULAR'),
                            ('1640','ENUCLEACIÓN DEL GLOBO OCULAR'),
                            ('1650','EXENTERACIONES DE ÓRBITA'),
                            ('1690','INYECCIONES EN ÓRBITA O GLOBO O ANEXOS OCULARES'),
                            ('3190','TRAQUEOPEXIAS'),
                            ('3888','LIGADURA ESCISIÓN DE VENAS VARICOSAS PÉLVICAS'),
                            ('4105','TRASPLANTEAUTÓLOGO'),
                            ('4106','TRASPLANTE ALOGÉNICO'),
                            ('4258','REPARACIÓN DE PERFORACIÓN O FÍSTULA ESOFÁGICA'),
                            ('4295','INSERCIÓN DE OTROS DISPOSITIVOS ESOFAGOGÁSTRICOS'),
                            ('4296','REPOSICIONAMIENTO O EXTRACCION DE DISPOSITIVO EN ESÓFAGO'),
                            ('4490','ABLACIÓN DE LESIÓN GÁSTRICA'),
                            ('4720','APENDICOSTOMÍA'),
                            ('4896','CORRECCIÓN DE LA CLOACA'),
                            ('4924','ESTIMULACIÓN ELÉCTRICA EN PERINÉ'),
                            ('4925','EVALUACIÓN ANATÓMICA O FUNCIONAL DE LA CLOACA'),
                            ('4993','INYECCION DE SUSTANCIA TERAPEUTICA EN ESFINTER ANAL'),
                            ('5429','BIOPSIAS POR PUNCION Y ASPIRACION GUIADA POR ECOENDOSCOPIA'),
                            ('5470','CORRECCION PARCIAL DE EVISCERACION PRENATAL (GASTROSQUISIS)'),
                            ('5526','BIOPSIA DE RIÑÓN O TEJIDOS PERIRRENALES'),
                            ('5540','NEFRECTOMÍA PARCIAL'),
                            ('5653','APENDICOVESICOSTOMÍA CUTÁNEA'),
                            ('5691','LIGADURA DE URÉTER'),
                            ('5773','CISTECTOMÍA TOTAL O RADICAL'),
                            ('5920','LITOTOMÍA O EXTRACCIÓN DE CUERPO EXTRAÑO EN RIÑÓN'),
                            ('5921','LITOTOMIAS O EXTRACCION DE CUERPO EXTRAÑO EN URÉTER'),
                            ('5922','LITOTOMIAS O EXTRACCION DE CUERPO EXTRANO EN VEJIGA'),
                            ('5923','LITOTOMIAS O EXTRACCION DE CUERPO EXTRANO EN URETRA'),
                            ('5924','LITOTRICIAS URINARIAS'),
                            ('6020','ADENOMECTOMIAS O PROSTATECTOMIAS TRANSURETRALES'),
                            ('6022','RESECCION TRANSURETRAL DE CONDUCTOS EYACULADORES'),
                            ('6390','ASPIRACIÓN DE EPIDÍDIMO'),
                            ('6446','TRASPLANTE DEL PENE'),
                            ('6450','PROCEDIMIENTOS DE TRANSFORMACIÓN SEXUAL'),
                            ('6460','CIRUGÍA DE LOS GENITALES AMBIGUOS'),
                            ('6461','BIOPSIAS EN GENITALES AMBIGUOS'),
                            ('6697','ESCISIÓN Y DRENAJE POR ABORTO TUBÁRICO'),
                            ('6811','BIOPSIAS DE ÚTERO'),
                            ('6980','EXTRACCIÓN DE CUERPOS EXTRAÑOS INTRAUTERINOS'),
                            ('7040','OBLITERACIÓN O ESCISIÓN DE VAGINA'),
                            ('7042','VAGINECTOMÍA RADICAL'),
                            ('7120','DRENAJE DE LAS GLÁNDULAS DE BARTHOLIN'),
                            ('7140','RESECCIÓN DE CLÍTORIS'),
                            ('7150','VULVECTOMÍAS'),
                            ('7400','CESÁREAS'),
                            ('7503','EVACUACIÓN UTERINA PARA TERMINACIÓN DEL EMBARAZO'),
                            ('7538','PROCEDIMIENTOS EN PLACENTA'),
                            ('7539','PROCEDIMIENTOS EN MEMBRANA'),
                            ('7550','REPARACION DE DESGARRO OBSTETRICO ACTUAL DE CUELLO UTERINO [CÉRVIX]'),
                            ('7580','COMPRESIÓN HEMOSTÁTICA'),
                            ('7730','OSTEOCONDROPLASTIAS DE OTROS HUESOS'),
                            ('7754','REPARACIÓN DE DEDO DE PIE EN MARTILLO O EN GARRA'),
                            ('7841','REPARACIÓN DE PSEUDOARTROSIS'),
                            ('8100','CORRECCION O RECONSTRUCCION DE DEFORMIDAD EN COLUMNA VERTEBRAL'),
                            ('8187','REVISIONES DE REEMPLAZOS PROTESICOS DE HOMBRO O CODO'),
                            ('8496','LITOTRICIA ORTOPÉDICA'),
                            ('8497','PROCEDIMIENTOS MÚLTIPLES EN MIEMBROS SUPERIORES'),
                            ('8620','DESBRIDAMIENTO ESCISIONAL'),
                            ('9443','TERAPIAS DE REHABILITACIÓN COGNITIVA'),
                            ('9519','TOMOGRAFIAS ÓPTICAS DE ESTRUCTURAS OCULARES'),
                            ('9982','OTROS MONITOREOS'),
                            ('9987','OTROS PROCEDIMIENTOS MISCELÁNEOS DE APOYO')");
        DB::statement("update rs_cupscategorias set rs_cupssubgrupo_id = (select id from rs_cupssubgrupos where codigo = left(rs_cupscategorias.codigo,3)) where id > 0");
        DB::statement("update rs_cupss set rs_cupscategoria_id = (select id from rs_cupscategorias where codigo = left(rs_cupss.codigo,4)) where id > 0");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ..
    }
}
