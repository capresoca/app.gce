<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAsTipaltocostos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_tipaltocostos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->nullable();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        $sqlTipos = "INSERT INTO as_tipaltocostos (id, nombre) VALUES (1, 'Artritis'), (2, 'Cancer'), (3, 'Enfermedades Huerfanas'), (4, 'Enfermedad Renal Crónica'), (5, 'Hemofilia'), (6, 'VIH')";
        DB::statement($sqlTipos);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('as_tipaltocostos');
    }
}
