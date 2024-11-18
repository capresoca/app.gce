<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefviasolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('refviasol');

        Schema::create('refviasol', function (Blueprint $table) {
            $table->char('codigo',1)->primary();
            $table->string('descrip',50);
            $table->timestamps();
        });

        $sql = "INSERT INTO refviasol(codigo,descrip) VALUES
                ('1', 'Presentación personal'),
                ('2', 'Buzón de sugerencias'),
                ('3', 'Por teléfono'),
                ('4', 'Por correo electrónico'),
                ('5', 'Por correspondencia'),
                ('6', 'Otras fuentes')";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refviasol');
    }
}
