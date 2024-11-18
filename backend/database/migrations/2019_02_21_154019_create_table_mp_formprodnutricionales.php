<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMpFormprodnutricionales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_formprodnutricionales', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('codigo',5)->index();
            $table->string('descripcion',500);
            $table->string('fecha_mipres',12);

            $table->timestamps();
        });

        $sql = "INSERT INTO mp_formprodnutricionales(codigo,descripcion,fecha_mipres) VALUES
                ('1','Bolsa','2016/11/18'),
                ('2','Bolsa ultrapack','2016/11/18'),
                ('3','Botella','2016/11/18'),
                ('4','EasyBag','2016/11/18'),
                ('5','Lata','2016/11/18'),
                ('6','LOC','2016/11/18'),
                ('7','LPC','2016/11/18'),
                ('8','LPM','2016/11/18'),
                ('9','Sobre','2016/11/18'),
                ('10','Tetraprisma','2016/11/18'),
                ('11','Ultrapack','2016/11/18'),
                ('12','Frasco','2018/10/01');";

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_formprodnutricionales');
    }
}
