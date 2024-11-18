<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMpTipdispositivos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_tipdispositivos', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('codigo',5)->index();
            $table->string('descripcion',500);
            $table->string('fecha_mipres',12);

            $table->timestamps();
        });

        $sql = "INSERT INTO mp_tipdispositivos(codigo,descripcion,fecha_mipres) VALUES
                ('011','BOLSA>104 AL AÑO; EN CANCER COLON O RECTO','2018/01/03'),
                ('012','CARALLA>104 AL AÑO; EN CANCER COLON O RECTO','2018/01/03'),
                ('013','PINZA>104 AL AÑO; EN CANCER COLON O RECTO','2018/01/03'),
                ('014','PEGANTE>104 AL AÑO; EN CANCER COLON O RECTO','2018/01/03'),
                ('021','LANCETAS>100 X MES; DIABETES MELLITUS TIPO I MANEJO CON INSULINA','2017/01/04'),
                ('022','LANCETAS>50 X MES; DIABETES MELLITUS TIPO II MANEJO CON INSULINA','2017/01/04'),
                ('023','TIRILLAS >100 X MES; DIABETES MELLITUS TIPO I MANEJO CON INSULINA','2017/01/04'),
                ('024','TIRILLAS >50 X MES; DIABETES MELLITUS TIPO II MANEJO CON INSULINA','2017/01/04'),
                ('025','GLUCOMETRO>1 X AÑO; DIABETES MELLITUS TIPO I O II MANEJO CON INSULINA','2017/09/13'),
                ('031','STENT MEDICADO; LESION >3 MILIMETROS DE DIAMETRO O LESION <15 MILIMETROS DE LONGITUD','2017/03/01'),
                ('032','STENT MEDICADO; VASOS PEQUEÑOS MAYORES O IGUALES  A 3 MM DE DIAMETRO','2018/01/03'),
                ('033','STENT MEDICADO; LESIONES LARGAS MENORES O IGUALES  A 15 MM DE LONGITUD','2018/01/03'),
                ('041','FILTROS DE COLORES O PELICULAS PARA LENTES EXTERNOS','2016/11/18'),
                ('042','LENTES EXTERNOS FRECUENCIA >1 AL AÑO, PARA MENORES DE EDAD 12 AÑOS Y MENOS','2016/11/18'),
                ('043','LENTES EXTERNOS FRECUENCIA >1 EN CINCO AÑOS  PARA PACIENTES >12 AÑOS','2016/11/18'),
                ('044','LENTES EXTERNOS MATERIAL DIFERENTE A VIDRIO-PLASTICO O POLICARBONATO','2016/11/18'),
                ('045','LENTES DE CONTACTO','2017/03/01'),
                ('051','BOLSA DE KIT DE OSTOMIA; INDICACIONES DIFERENTES A CANCER COLON O RECTO','2018/01/03'),
                ('052','CARALLA DE KIT DE OSTOMIA; INDICACIONES DIFERENTES A CANCER COLON O RECTO','2018/01/03'),
                ('053','PINZA DE KIT DE OSTOMIA; INDICACIONES DIFERENTES A CANCER COLON O RECTO','2018/01/03'),
                ('054','PEGANTE DE KIT DE OSTOMIA; INDICACIONES DIFERENTES A CANCER COLON O RECTO','2018/01/03');";

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_tipdispositivos');
    }
}
