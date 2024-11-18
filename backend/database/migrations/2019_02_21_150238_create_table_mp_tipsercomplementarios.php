<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMpTipsercomplementarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_tipsercomplementarios', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('codigo',5)->index();
            $table->string('descripcion',500);
            $table->string('fecha_mipres',12);

            $table->timestamps();

        });

        $sql = "INSERT INTO mp_tipsercomplementarios(codigo,descripcion,fecha_mipres) VALUES
                ('01','BLOQUEADORES SOLARES','2016/11/18'),
                ('02','CHAMPÚ Y LOCIONES CAPILARES ','2016/11/18'),
                ('03','MEDIAS DE COMPRESIÓN GRADUADA Y ANTIEMBÓLICAS','2016/11/18'),
                ('04','PAÑALES','2016/11/18'),
                ('05','ZAPATOS Y PLANTILLAS ORTOPÉDICAS','2016/11/18'),
                ('06','TRATAMIENTOS DE ORTODONCIA','2017/01/25'),
                ('07','TRATAMIENTOS DE PERIODONCIA','2017/01/25'),
                ('08','IMPLANTOLOGÍA DENTAL','2017/01/25'),
                ('09','LENTES DE CONTACTO','2017/01/25'),
                ('10','TRANSPORTE AMBULATORIO DIFERENTE A AMBULANCIA NO PBS-UPC','2017/03/22'),
                ('11','TRANSPORTE AMBULANCIA NO CUBIERTO POR EL PBS-UPC','2017/05/12'),
                ('12','HOSPEDAJE, ALIMENTACIÓN Y VIÁTICOS CONTEMPLADOS EN LEYES ESPECIALES DIFERENTES A LOS OTORGADOS A LAS COMUNIDADES INDÍGENAS.','2018/05/21');";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_tipsercomplementarios');
    }
}
