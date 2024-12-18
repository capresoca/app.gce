<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMpUnimedidas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_unimedidas', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('codigo',5)->index();
            $table->string('sigla',50);
            $table->string('descripcion',500);
            $table->string('fecha_mipres',12);

            $table->timestamps();
        });

        $sql  = "INSERT INTO mp_unimedidas(codigo, sigla, descripcion, fecha_mipres) VALUES
                ('0001','EID50','dosis infecciosa de embrión 50','2016/11/18'),
                ('0002','EID50/dosis','dosis infecciosa de embrión 50/dosis','2016/11/18'),
                ('0003','AU/ml','unidades de alergia/mililitro','2016/11/18'),
                ('0004','A','amperio','2016/11/18'),
                ('0005','AgU','unidad(es) de antígeno','2016/11/18'),
                ('0006','AgU/ml','unidad(es) de antígeno/mililitro','2016/11/18'),
                ('0007','ATU','unidades de antitrombina','2016/11/18'),
                ('0008','anti-Xa IU','unidades internacionales de actividad anti-Xa','2016/11/18'),
                ('0009','anti-Xa IU/ml','unidades internacionales de actividad anti-Xa/mililitro','2016/11/18'),
                ('0010','Bq','bequerel(ios)','2016/11/18'),
                ('0011','Bq/g','bequerel(ios)/gramo','2016/11/18'),
                ('0012','Bq/kg','bequerel(ios)/kilogramo','2016/11/18'),
                ('0013','Bq/l','bequerel(ios)/litro','2016/11/18'),
                ('0014','Bq/µg','bequerel(ios)/microgramo','2016/11/18'),
                ('0015','Bq/µl','bequerel(ios)/microlitro','2016/11/18'),
                ('0016','Bq/mg','bequerel(ios)/miligramo','2016/11/18'),
                ('0017','Bq/ml','bequerel(ios)/mililitro','2016/11/18'),
                ('0018','billon CFU','billon de unidades formadoras de colonia','2016/11/18'),
                ('0019','billon CFU/g','billon de unidades formadoras de colonia/gramo','2016/11/18'),
                ('0020','billon CFU/ml','billon de unidades formadoras de colonia/mililitro','2016/11/18'),
                ('0021','billon de organismos','billon de organismos','2016/11/18'),
                ('0022','billon de organismos/g','billon de organismos/gramo','2016/11/18'),
                ('0023','billon de organismos/mg','billon de organismos/miligramo','2016/11/18'),
                ('0024','billon de organismos/ml','billon de organismos/mililitro','2016/11/18'),
                ('0025','cd','candela','2016/11/18'),
                ('0026','CCID50','dosis infecciosa cultivo celular 50','2016/11/18'),
                ('0027','CCID50/dosis','dosis infecciosa cultivo celular 50/dosis','2016/11/18'),
                ('0028','°C','temperatura en Celsius','2016/11/18'),
                ('0029','CFU/g','unidades formadoras de colonias/gramo','2016/11/18'),
                ('0030','CFU/ml','nidades formadoras de colonias/mililitro','2016/11/18'),
                ('0031','Co','culombio','2016/11/18'),
                ('0032','m3','metro cúbico','2016/11/18'),
                ('0033','Ci','curio(s)','2016/11/18'),
                ('0034','Ci/g','curie(s)/gramo','2016/11/18'),
                ('0035','Ci/kg','curie(s)/kilogramo','2016/11/18'),
                ('0036','Ci/litro','curie(s)/litro','2016/11/18'),
                ('0037','Ci/µg','curie(s)/microgramo','2016/11/18'),
                ('0038','Ci/µl','curie(s)/microlitro','2016/11/18'),
                ('0039','Ci/mg','curie(s)/miligramo','2016/11/18'),
                ('0040','Ci/ml','curie(s)/mililitro','2016/11/18'),
                ('0041','DAgU','unidad(es) de Antigeno D','2016/11/18'),
                ('0042','DAgU/ml','unidad(es) de Antigeno D/mililitro','2016/11/18'),
                ('0043','d','dia','2016/11/18'),
                ('0044','°','grado','2016/11/18'),
                ('0045','DF','forma de dosificación','2016/11/18'),
                ('0046','Gtt','gota(s)','2017/03/15'),
                ('0047','unidades ELISA','unidad de ensayo inmunoenzimático','2016/11/18'),
                ('0048','unidades ELISA/dosis','unidad de ensayo inmunoenzimático/dosis','2016/11/18'),
                ('0049','unidades ELISA/ml','unidad de ensayo inmunoenzimático/mililitro','2016/11/18'),
                ('0050','F','faradio','2016/11/18'),
                ('0051','FAI50','ensayo fluorescente dosis infecciosa 50','2016/11/18'),
                ('0052','FAI50/dosis','ensayo fluorescente dosis infecciosa 50/dosis','2016/11/18'),
                ('0053','GBq','gigabecquerel(ios)','2016/11/18'),
                ('0054','GBq/g','gigabecquerel/gramo','2016/11/18'),
                ('0055','GBq/kg','gigabecquerel/kilogramo','2016/11/18'),
                ('0056','GBq/l','gigabecquerel/litro','2016/11/18'),
                ('0057','GBq/µg','gigabecquerel/microgramo','2016/11/18'),
                ('0058','GBq/µl','gigabecquerel/microlitro','2016/11/18'),
                ('0059','GBq/mg','gigabecquerel/miligramo','2016/11/18'),
                ('0060','GBq/ml','gigabecquerel/mililitro','2016/11/18'),
                ('0061','g (titre)','gramo (titre)','2016/11/18'),
                ('0062','g','gramo(s)','2016/11/18'),
                ('0063','g/m3','gramo/metro cúbico','2016/11/18'),
                ('0064','g/l','gramo/litro','2016/11/18'),
                ('0065','g/ml','gramo/mililitro','2016/11/18'),
                ('0066','g/m2','gramo/metro cuadrado','2016/11/18'),
                ('0067','Gy','gray','2016/11/18'),
                ('0068','H','henrio','2016/11/18'),
                ('0069','Hz','hertz','2016/11/18'),
                ('0070','h','hora','2016/11/18'),
                ('0071','IOU','unidad(es) internacional(es) de opacidad','2016/11/18'),
                ('0072','UI','unidad(es) internacional(es)','2016/11/18'),
                ('0073','UI/g','unidad(es) internacional(es)/gramo','2016/11/18'),
                ('0074','UI/kg','unidad(es) internacional(es)/kilogramo','2016/11/18'),
                ('0075','UI/l','unidad(es) internacional(es)/litro','2016/11/18'),
                ('0076','UI/mg','unidad(es) internacional(es)/miligramo','2016/11/18'),
                ('0077','UI/ml','unidad(es) internacional(es)/mililitro','2016/11/18'),
                ('0078','J','julio','2016/11/18'),
                ('0079','KIU/ml','unidad calicreína inactivador/mililitro','2016/11/18'),
                ('0080','kat','katal','2016/11/18'),
                ('0081','K','kelvin','2016/11/18'),
                ('0082','kUI','unidad internacional de kilo','2016/11/18'),
                ('0083','unidades Kusp','unidad de la Farmacopea de los Estados Unidos de kilo','2016/11/18'),
                ('0084','unidades k','unidades kilo','2016/11/18'),
                ('0085','kBq','kilobecquerel(ios)','2016/11/18'),
                ('0086','kBq/g','kilobecquerel(ios)/gramo','2016/11/18'),
                ('0087','kBq/kg','kilobecquerel(ios)/kilogramo','2016/11/18'),
                ('0088','kBq/l','kilobecquerel(ios)/litro','2016/11/18'),
                ('0089','kBq/µg','kilobecquerel(ios)/microgramo','2016/11/18'),
                ('0090','kBq/µl','kilobecquerel(ios)/microlitro','2016/11/18'),
                ('0091','kBq/mg','kilobecquerel(ios)/miligramo','2016/11/18'),
                ('0092','kBq/ml','kilobecquerel(ios)/mililitro','2016/11/18'),
                ('0093','kg','kilogramo(s)','2016/11/18'),
                ('0094','kg/m3','kilogramo(s)/metro cúbico','2016/11/18'),
                ('0095','kg/l','kilogramo(s)/litro','2016/11/18'),
                ('0096','kg/m2','kilogramo(s)/metro cuadrado','2016/11/18'),
                ('0097','LacU','unidades de lactasa','2016/11/18'),
                ('0098','LfU','unidades de floculación (lime flocculation unit(s))','2016/11/18'),
                ('0099','LfU/ml','unidades de floculación (lime flocculation unit(s))/mililitro','2016/11/18'),
                ('0100','l','litro(s)','2016/11/18'),
                ('0101','log10 EID50','log 10 50% dosis infecciosa de embrión','2016/11/18'),
                ('0102','log10 EID51/dosis','log 10 50% dosis infecciosa de embrión/dosis','2016/11/18'),
                ('0103','log10 CCID50','log10 dosis infecciosa cultivo celular 50','2016/11/18'),
                ('0104','log10 CCID50/dosis','log10 dosis infecciosa de cultivo celular 50/dosis','2016/11/18'),
                ('0105','log10 unidades ELISA','log10 unidad de ensayo inmunoenzimático','2016/11/18'),
                ('0106','log10 unidades ELISA/dosis','log10 unidad de ensayo inmunoenzimático/dosis','2016/11/18'),
                ('0107','log10 FAI50','log10 ensayo fluorescente dosis infecciosa del 50%','2016/11/18'),
                ('0108','log10 FAI50/dosis','log10 ensayo fluorescente dosis infecciosa del 50%/dosis','2016/11/18'),
                ('0109','log10 PFU','log10 unidad(es) formadoras de placa','2016/11/18'),
                ('0110','log10 PFU/dosis','log10 unidad(es) formadoras de placa/dosis','2016/11/18'),
                ('0111','log10 TCID50','log10 dosis infecciosa de cultivo tisular 50% ','2016/11/18'),
                ('0112','log10 TCID50/dosis','log10 dosis infecciosa de cultivo tisular 50%/dosis','2016/11/18'),
                ('0113','log10/ml','log10/ml','2016/11/18'),
                ('0114','LU','unidades de loomis','2016/11/18'),
                ('0115','LU/g','unidades de loomis/gramo','2016/11/18'),
                ('0116','LU/ml','unidades de loomis/mililitro','2016/11/18'),
                ('0117','lm','lumen','2016/11/18'),
                ('0118','lx','lux','2016/11/18'),
                ('0119','unidades MUSP','mega; unidad de la Farmacopea de los Estados Unidos','2016/11/18'),
                ('0120','MBq','megabecquerel(ios)','2016/11/18'),
                ('0121','MBq/g','megabecquerel(ios)/gramo','2016/11/18'),
                ('0122','MBq/kg','megabecquerel(ios)/kilogramo','2016/11/18'),
                ('0123','MBq/l','megabecquerel(ios)/litro','2016/11/18'),
                ('0124','MBq/µg','megabecquerel(ios)/microgramo','2016/11/18'),
                ('0125','MBq/µl','megabecquerel(ios)/microlitro','2016/11/18'),
                ('0126','MBq/mg','megabecquerel(ios)/miligramo','2016/11/18'),
                ('0127','MBq/ml','megabecquerel(ios)/mililitro','2016/11/18'),
                ('0128','m','metro','2016/11/18'),
                ('0129','µCi','microcurio(s)','2016/11/18'),
                ('0130','µCi/g','microcurio(s)/gramo','2016/11/18'),
                ('0131','µCi/kg','microcurio(s)/kilogramo','2016/11/18'),
                ('0132','µCi/l','microcurio(s)/litro','2016/11/18'),
                ('0133','µCi/µg','microcurio(s)/microgramo','2016/11/18'),
                ('0134','µCi/µl','microcurio(s)/microlitro','2016/11/18'),
                ('0135','µCi/mg','microcurio(s)/miligramo','2016/11/18'),
                ('0136','µCi/ml','microcurio(s)/mililitro','2016/11/18'),
                ('0137','µg','microgramo(s)','2016/11/18'),
                ('0138','µg/m3','microgramo(s)/metro cúbico','2016/11/18'),
                ('0139','µg/kg','microgramo(s)/kilogramo','2016/11/18'),
                ('0140','µg/l','microgramo(s)/litro','2016/11/18'),
                ('0141','µg/µl','microgramo(s)/microlitro','2016/11/18'),
                ('0142','µg/ml','microgramo(s)/mililitro','2016/11/18'),
                ('0143','µg/m2','microgramo(s)/metro cuadrado','2016/11/18'),
                ('0144','µkat','microkatal','2016/11/18'),
                ('0145','µkat','microkatales','2016/11/18'),
                ('0146','µl','microlitro(s)','2016/11/18'),
                ('0147','µl/ml','microlitro(s)/mililitro','2016/11/18'),
                ('0148','µmol','micromol(es)','2016/11/18'),
                ('0149','µmol/l','micromol(es)/litro','2016/11/18'),
                ('0150','µmol/ml','micromol(es)/mililitro','2016/11/18'),
                ('0151','mCi','milicurio(s)','2016/11/18'),
                ('0152','mCi/g','milicurio(s)/gramo','2016/11/18'),
                ('0153','mCi/kg','milicurio(s)/kilogramo','2016/11/18'),
                ('0154','mCi/l','milicurio(s)/litro','2016/11/18'),
                ('0155','mCi/µg','milicurio(s)/microgramo','2016/11/18'),
                ('0156','mCi/µl','milicurio(s)/microlitro','2016/11/18'),
                ('0157','mCi/mg','milicurio(s)/miligramo','2016/11/18'),
                ('0158','mCi/ml','milicurio(s)/mililitro','2016/11/18'),
                ('0159','mEq','miliequivalente(s)','2016/11/18'),
                ('0160','mEq/g','miliequivalente(s)/gramo','2016/11/18'),
                ('0161','mEq/kg','miliequivalente(s)/kilogramo','2016/11/18'),
                ('0162','mEq/l','miliequivalente(s)/litro','2016/11/18'),
                ('0163','mEq/µg','miliequivalente(s)/microgramo','2016/11/18'),
                ('0164','mEq/µl','miliequivalente(s)/microlitro','2016/11/18'),
                ('0165','mEq/mg','miliequivalente(s)/miligramo','2016/11/18'),
                ('0166','mEq/ml','miliequivalente(s)/mililitro','2016/11/18'),
                ('0167','mg (titer)','miligramo (titer)','2016/11/18'),
                ('0168','mg','miligramo(s)','2016/11/18'),
                ('0169','mg/m3','miligramo(s)/metro cúbico','2016/11/18'),
                ('0170','mg/g','miligramo(s)/gramo','2016/11/18'),
                ('0171','mg/kg','miligramo(s)/kilogramo','2016/11/18'),
                ('0172','mg/l','miligramo(s)/litro','2016/11/18'),
                ('0173','mg/ml','miligramo(s)/mililitro','2016/11/18'),
                ('0174','mg/m2','miligramo(s)/metro cuadrado','2016/11/18'),
                ('0175','mkatal','milikatal','2016/11/18'),
                ('0176','ml','mililitro(s)','2016/11/18'),
                ('0177','ml/cm2','mililitro(s)/centímetro cuadrado','2016/11/18'),
                ('0178','mm','milimetro','2016/11/18'),
                ('0179','mmol','milimol(es)','2016/11/18'),
                ('0180','mmol/g','milimol(es)/gramo','2016/11/18'),
                ('0181','mmol/kg','milimol(es)/kilogramo','2016/11/18'),
                ('0182','mmol/l','milimol(es)/litro','2016/11/18'),
                ('0183','mmol/ml','milimol(es)/mililitro','2016/11/18'),
                ('0184','millon UFC','millones de unidades formadoras de colonias','2016/11/18'),
                ('0185','millon UFC/g','millones de unidades formadoras de colonias/gramo','2016/11/18'),
                ('0186','millon UFC/ml','millones de unidades formadoras de colonias/mililitro','2016/11/18'),
                ('0187','millon UI','millones de unidadades internacionales','2016/11/18'),
                ('0188','millon de organismos','millon de organismos','2016/11/18'),
                ('0189','millon de organismos/g','millon de organismos/gramo','2016/11/18'),
                ('0190','millon de organismos/mg','millon de organismos/miligramo','2016/11/18'),
                ('0191','millon de organismos/ml','millon de organismos/mililitro','2016/11/18'),
                ('0192','millon de unidades USP','millon de unidades de la Farmacopea de los Estados Unidos','2016/11/18'),
                ('0193','millon de unidades','millon de unidades','2016/11/18'),
                ('0194','mOsm/kg','miliosmol(es)/kilogramo','2016/11/18'),
                ('0195','min','minuto','2016/11/18'),
                ('0196','mol','mol(es)','2016/11/18'),
                ('0197','mol/g','mol(es)/gramo','2016/11/18'),
                ('0198','mol/kg','mol(es)/kilogramo','2016/11/18'),
                ('0199','mol/l','mol(es)/litro','2016/11/18'),
                ('0200','mol/mg','mol(es)/miligramo','2016/11/18'),
                ('0201','mol/ml','mol(es)/mililitro','2016/11/18'),
                ('0202','nCi','nanocurio(s)','2016/11/18'),
                ('0203','ng','nanogramo(s)','2016/11/18'),
                ('0204','nkat','nanokatal','2016/11/18'),
                ('0205','nl','nanolitro(s)','2016/11/18'),
                ('0206','nmol','nanomol(es)','2016/11/18'),
                ('0207','nmol/ml','nanomol(es)/mililitro','2016/11/18'),
                ('0208','N','newton','2016/11/18'),
                ('0209','unidades NIH/cm2','NIH unidades de trombina inactivada/centímetro cuadrado','2016/11/18'),
                ('0210','?','ohmio','2016/11/18'),
                ('0211','OZ','onza','2016/11/18'),
                ('0212','PPM','parte por millon','2016/11/18'),
                ('0213','PPM','pascal','2016/11/18'),
                ('0214','%','porcentaje','2016/11/18'),
                ('0215','% (v/v)','porcentaje volumen/volumen','2016/11/18'),
                ('0216','% (p/v)','porcentaje peso/volumen','2016/11/18'),
                ('0217','% (p/p)','porcentaje peso/peso','2016/11/18'),
                ('0218','pg','picogramo(s)','2016/11/18'),
                ('0219','pkat','picokatal','2016/11/18'),
                ('0220','PFU','unidades formadoras de placa','2016/11/18'),
                ('0221','PFU e. 1000 LD50 en ratón','unidad formadora de placa equivalente a  1000 DL50 en ratón','2016/11/18'),
                ('0222','PFU/dosis','unidades formadoras de placa/dosis','2016/11/18'),
                ('0223','PFU/ml','unidades formadoras de placa/mililitro','2016/11/18'),
                ('0224','unidad formadora de viruela','unidad(es) formadoras de viruela','2016/11/18'),
                ('0225','LB','libra','2016/11/18'),
                ('0226','unidades de presión/ml','unidades de presión/mililitro','2016/11/18'),
                ('0227','PNU/ml','unidades de nitrogeno proteico/mililitro','2016/11/18'),
                ('0228','QS','cantidad suficiente','2016/11/18'),
                ('0229','r/min','revoluciones/minuto','2016/11/18'),
                ('0230','s','segundos','2016/11/18'),
                ('0231','S','siemens','2016/11/18'),
                ('0232','Sv','sievert','2016/11/18'),
                ('0233','cm2','centímetro cuadrado','2016/11/18'),
                ('0234','m2','metro cuadrado','2016/11/18'),
                ('0235','T','tesla','2016/11/18'),
                ('0236','miles CFU','miles de unidades formadoras de colonia','2016/11/18'),
                ('0237','miles CFU/g','miles de unidades formadoras de colonia/gramo','2016/11/18'),
                ('0238','miles CFU/ml','miles de unidades formadoras de colonia/mililitro','2016/11/18'),
                ('0239','miles de organismos','miles de organismos','2016/11/18'),
                ('0240','miles de organismos/g','miles de organismos/gramo','2016/11/18'),
                ('0241','miles de organismos/ml','miles de organismos/mililitro','2016/11/18'),
                ('0242','TCID50/dosis','dosis infecciosa de cultivo tisular 50/ dosis','2016/11/18'),
                ('0243','titre','titre','2016/11/18'),
                ('0244','t','tonelada','2016/11/18'),
                ('0245','unidad de tuberculina','unidad(es) de tuberculina','2016/11/18'),
                ('0246','unidad de tuberculina/ml','unidad(es) de tuberculina/mililitro','2016/11/18'),
                ('0247','U','unidades','2016/11/18'),
                ('0248','U/g','unidades/gramo','2016/11/18'),
                ('0249','U/ml','unidades/mililitro','2016/11/18'),
                ('0250','unidades USP','unidades de la Farmacopea de los Estados Unidos','2016/11/18'),
                ('0251','V','voltio','2016/11/18'),
                ('0252','W','vatio','2016/11/18'),
                ('0253','Wb','weber','2016/11/18'),
                ('9000','Dosis','Dosis','2016/11/18'),
                ('9001','TPU','TPU','2017/01/01'),
                ('9002','TSU','TSU','2017/01/01'),
                ('9003','DBU','DBU','2017/01/01'),
                ('9004','SU','SU','2017/01/01'),
                ('9005','IR','IR','2017/01/01'),
                ('9006','DPP','DPP','2017/01/01'),
                ('9007','HEP','HEP','2017/01/01'),
                ('9008','UT','UT','2017/01/01'),
                ('9009','SQ','SQ','2017/01/01'),
                ('9010','UB','UB','2017/01/01'),
                ('9011','DL50','Dosis letal 50','2017/01/01'),
                ('9012','AU','Unidades de Alergia','2017/01/01'),
                ('9013','BAU','Bioequivalente Unidades de Alergia','2017/01/01'),
                ('9014','PNU','Unidades de nitrogeno proteico','2017/01/01');";

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_unimedidas');
    }
}
