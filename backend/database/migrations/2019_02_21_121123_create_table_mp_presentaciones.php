<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMpPresentaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_presentaciones', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('codigo',5)->index();
            $table->string('descripcion',500);
            $table->string('fecha_mipres',12);

            $table->timestamps();
        });

        $sql = "INSERT INTO mp_presentaciones(codigo,descripcion,fecha_mipres) VALUES
                ('01','AMPOLLA','2016/11/18'),
                ('02','APLICADOR','2016/11/18'),
                ('03','BOLSA','2016/11/18'),
                ('04','BARRA','2016/11/18'),
                ('05','PERLA','2016/11/18'),
                ('06','BLISTER','2016/11/18'),
                ('07','ENVASE BLISTER','2016/11/18'),
                ('08','BLOQUE','2016/11/18'),
                ('09','BOLO','2016/11/18'),
                ('10','ENVASE','2016/11/18'),
                ('11','CAJA','2016/11/18'),
                ('12','LATA','2016/11/18'),
                ('13','FRASCO','2016/11/18'),
                ('14','CÁPSULA','2016/11/18'),
                ('15','CARTON','2016/11/18'),
                ('16','CARTUCHO','2016/11/18'),
                ('17','ESTUCHE','2016/11/18'),
                ('18','PELICULA DE PVC TRANSPARENTE DE COLORES ','2016/11/18'),
                ('19','UNIDADES CLÍNICAS','2016/11/18'),
                ('20','RECUBRIMIENTO','2016/11/18'),
                ('21','CONTENEDOR','2016/11/18'),
                ('22','RECUENTO','2016/11/18'),
                ('23','COPA','2016/11/18'),
                ('24','CILINDRO','2016/11/18'),
                ('25','VASOS DEWAR','2016/11/18'),
                ('26','DIAL PACK','2016/11/18'),
                ('27','DISCO','2016/11/18'),
                ('28','DOSIS EN ENVASE','2016/11/18'),
                ('29','GOTAS','2016/11/18'),
                ('30','TAMBOR - BOMBO','2016/11/18'),
                ('31','PELÍCULA','2016/11/18'),
                ('32','GENERADOR','2016/11/18'),
                ('33','GLÓBULO','2016/11/18'),
                ('34','DILUCIÓN HOMEOPÁTICA','2016/11/18'),
                ('35','IMPLANTE','2016/11/18'),
                ('36','INHALACIÓN','2016/11/18'),
                ('37','INHALADOR','2016/11/18'),
                ('38','INHALADOR, RECARGA ','2016/11/18'),
                ('39','INSERTO','2016/11/18'),
                ('40','FRASCO','2016/11/18'),
                ('41','JARRA','2016/11/18'),
                ('42','UNIDAD INHIBIDORA DE CALICREÍNA','2016/11/18'),
                ('43','KIT','2016/11/18'),
                ('44','PASTILLA','2016/11/18'),
                ('45','EMBALAJE','2016/11/18'),
                ('46','PAQUETE','2016/11/18'),
                ('47','BALDE ','2016/11/18'),
                ('48','PARTES','2016/11/18'),
                ('49','PARCHE','2016/11/18'),
                ('50','PÍLDORA (PELLET)','2016/11/18'),
                ('51','PIEZA','2016/11/18'),
                ('53','UNIDADES DE PRESIÓN ','2016/11/18'),
                ('54','ANILLO','2016/11/18'),
                ('55','SATURADO','2016/11/18'),
                ('56','CUCHARA MEDIDORA','2016/11/18'),
                ('57','ESPONJA','2016/11/18'),
                ('58','ATOMIZADOR (SPRAY)','2016/11/18'),
                ('59','CENTÍMETRO CUADRADO','2016/11/18'),
                ('61','TIRA','2016/11/18'),
                ('63','SUPOSITORIO','2016/11/18'),
                ('64','HISOPO','2016/11/18'),
                ('65','JERINGA','2016/11/18'),
                ('66','TABLETA','2016/11/18'),
                ('67','TABMINDER','2016/11/18'),
                ('68','TAMPON','2016/11/18'),
                ('69','TANQUE','2016/11/18'),
                ('70','PRUEBA','2016/11/18'),
                ('71','BANDEJA','2016/11/18'),
                ('72','TROCHE','2016/11/18'),
                ('73','TUBO','2016/11/18'),
                ('74','UNIDADES','2016/11/18'),
                ('75','VIAL','2016/11/18'),
                ('76','OBLEA','2016/11/18'),
                ('77','TERMO','2016/11/18'),
                ('78','SOBRE','2016/11/18'),
                ('79','PLUMA','2017/01/25');";

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

