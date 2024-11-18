<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReforigenautorizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('reforigenautorizacion');

        Schema::create('reforigenautorizacion', function (Blueprint $table) {
            $table->char('codigo',1)->primary()->default('');
            $table->char('descrip',20)->default(null);
            $table->char('ind')->default('1');
            $table->timestamps();
        });

        $sql = "INSERT INTO reforigenautorizacion(codigo,descrip,ind) VALUES
                ('1', 'Autorizador', '1'),
                ('2', 'Comite.TC', '0'),
                ('3', 'Tutela', '1')";

        DB::statement($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reforigenautorizacion');
    }
}
