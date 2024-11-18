<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefubicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('refubic');

        Schema::create('refubic', function (Blueprint $table) {
            $table->char('codigo', 2)->primary();
            $table->string('descrip',50);
            $table->char('Res744',2)->default(null);
            $table->timestamps();
        });

        $sql = "INSERT INTO refubic(codigo,descrip,Res744) VALUES
                ('1', 'Consulta externa', 'A'),
                ('2', 'Urgencias', 'U'),
                ('3', 'Hospitalización', 'H')";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refubic');
    }
}
