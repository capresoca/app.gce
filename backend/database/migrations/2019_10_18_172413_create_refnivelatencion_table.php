<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefnivelatencionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('refnivelatencion');

        Schema::create('refnivelatencion', function (Blueprint $table) {
            $table->char('codigo',4)->primary();
            $table->string('descrip',20);
            $table->timestamps();
        });

        $sql = "INSERT INTO refnivelatencion(codigo,descrip) VALUES
                ('1', 'Baja complejidad'),
                ('2', 'Media complejidad'),
                ('3', 'Alta complejidad'),
                ('4', 'Alto costo')";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refnivelatencion');
    }
}
