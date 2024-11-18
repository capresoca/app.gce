<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGnArchivoIdFieldInAuRefbitacorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_refbitacoras', function (Blueprint $table) {
            $table->integer('gn_archivo_id')->nullable();
            $table->foreign('gn_archivo_id')->references('id')->on('gn_archivos')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('au_refbitacoras', function (Blueprint $table) {
            $table->dropForeign('au_refbitacoras_gn_archivo_id_foreign');
            $table->dropColumn('gn_archivo_id');
        });
    }
}
