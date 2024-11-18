<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReforiatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('reforiate');

        Schema::create('reforiate', function (Blueprint $table) {
            $table->char('codigo',2)->primary();
            $table->string('descrip',50);
            $table->timestamps();
        });

        $sql = "INSERT INTO reforiate(codigo,descrip) VALUES
                ('1', 'Accidente de trabajo'),
                ('2', 'Accidente de tránsito'),
                ('3', 'Enfermedad general'),
                ('4', 'Enfermedad profesional'),
                ('5', 'Evento catastrofico')";
        DB::statement($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reforiates');
    }
}
