<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFuenteFieldInAuPqrsdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_pqrsds', function (Blueprint $table) {
            $table->enum('fuente',['Secretaria de Salud','Municipio','Supersalud'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('au_pqrsds', function (Blueprint $table) {
            $table->dropColumn('fuente');
        });
    }
}
