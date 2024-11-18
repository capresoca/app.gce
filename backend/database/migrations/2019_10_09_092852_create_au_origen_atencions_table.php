<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuOrigenAtencionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('au_origen_atencions', function (Blueprint $table) {
            $table->increments('id');
            $table->char('codigo');
            $table->string('descripcion');
            $table->timestamps();
        });

        DB::statement("INSERT INTO `au_origen_atencions` (`codigo`, `descripcion`) VALUES ('1', 'Accidente de trabajo')");
        DB::statement("INSERT INTO `au_origen_atencions` (`codigo`, `descripcion`) VALUES ('2', 'Accidente de tránsito')");
        DB::statement("INSERT INTO `au_origen_atencions` (`codigo`, `descripcion`) VALUES ('3', 'Enfermedad general')");
        DB::statement("INSERT INTO `au_origen_atencions` (`codigo`, `descripcion`) VALUES ('4', 'Enfermedad profesional')");
        DB::statement("INSERT INTO `au_origen_atencions` (`codigo`, `descripcion`) VALUES ('5', 'Evento catastrofico')");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('au_origen_atencions');
    }
}
