<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActoresFieldInAuPqrsdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_pqrsds', function (Blueprint $table) {
            $table->enum('actores',
                [
                    'Afiliado contra Capresoca',
                    'Afiliado contra IPS',
                    'Capresoca contra afiliado',
                    'Capresoca contra IPS',
                    'IPS contra afiliado'
                ])->default('Afiliado contra Capresoca');
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
            $table->dropColumn('actores');
        });
    }
}
