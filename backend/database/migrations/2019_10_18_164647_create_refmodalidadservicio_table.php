<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefmodalidadservicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('refmodalidadservicio');

        Schema::create('refmodalidadservicio', function (Blueprint $table) {
            $table->char('codigo',1)->primary();
            $table->string('descrip',20);
            $table->char('ind',1)->default('1');
            $table->char('super',1);
            $table->char('upc',1);
            $table->timestamps();
        });

        $sql = "INSERT INTO refmodalidadservicio(codigo,descrip,ind,super,upc) VALUES
                ('1', 'Ambulatorio', 1, 1,'A'),
                ('2', 'Urgencias', 1, 4,'U'),
                ('3', 'Hospitalario', 1, 2,'H'),
                ('4', 'Domiciliario', 1, 3,'D')";

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refmodalidadservicio');
    }
}
