<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefsersolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('refsersol');

        Schema::create('refsersol', function (Blueprint $table) {
            $table->char('codigo',2)->default('')->primary();
            $table->string('descrip')->default('');
            $table->timestamps();
        });

        $sql = "INSERT INTO refsersol(codigo,descrip) VALUES
                ('1', 'Posterior a la atencion de urgencias'),
                ('2', 'Servicios electivos')";

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refsersol');
    }
}
