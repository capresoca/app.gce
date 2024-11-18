<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpFormfarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_formfarms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',10)->index();
            $table->string('descripcion',255);
        });

        $sql = "INSERT INTO mp_formfarms(codigo,descripcion) VALUES
                ('C28944','CREMA'),
                ('C29167','LOCION'),
                ('C29269','ENJUAGUE'),
                ('C42887','AEROSOL'),
                ('C42909','GRANULOS EFERVESCENTES'),
                ('C42912','ELIXIR'),
                ('C42932','CINTA ADHESIVA / PELICULA'),
                ('C42933','GAS'),
                ('C42938','GRANULOS CONVENCIONALES'),
                ('C42941','GOMA'),
                ('C42942','IMPLANTE'),
                ('C42948','GELES y JALEAS'),
                ('C42953','LIQUIDO (Diferentes a soluciones)'),
                ('C42966','UNGUENTO'),
                ('C42967','PASTA'),
                ('C42968','SISTEMAS TRASNDERMICOS'),
                ('C42969','PELLETS'),
                ('C42983','JABONES Y CHAMPU'),
                ('C42989','ESPRAY'),
                ('C42993','SUPOSITORIO / OVULO'),
                ('C42994','SUSPENSION'),
                ('C42996','JARABE'),
                ('C43000','TINTURA'),
                ('C47913','EMPLASTO'),
                ('C47914','TIRA'),
                ('C47915','SISTEMAS INTRAUTERINOS'),
                ('C64898','ESPUMA'),
                ('C69031','SISTEMAS OCULARES'),
                ('C91199','SISTEMAS DE ANILLOS VAGINALES'),
                ('COLFF001','TABLETAS DE LIBERACION NO MODIFICADA'),
                ('COLFF002','POLVOS PARA NO RECONSTITUIR'),
                ('COLFF003','POLVOS PARA RECONSTITUIR'),
                ('COLFF004','OTRAS SOLUCIONES'),
                ('COLFF005','OTRAS EMULSIONES'),
                ('COLFF006','CAPSULAS DE LIBERACION NO MODIFICADA'),
                ('COLFF007','CAPSULAS DE LIBERACION MODIFICADA'),
                ('COLFF008','TABLETAS DE LIBERACION MODIFICADA'),
                ('COLFF009','GRANULOS DE LIBERACION NO MODIFICADA'),
                ('COLFF010','GRANULOS DE LIBERACION MODIFICADA')";

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_formfarms');
    }
}

