<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixNullableUsuanulaToPgAnticipoCxpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pg_anticipo_cxps', function (Blueprint $table) {
            $table->dropForeign('pg_anticipo_cxps_gs_usuario_anula_id_foreign');
            $table->dropColumn('gs_usuario_anula_id');
            $table->dropColumn('fecha_anulacion');
        });

        Schema::table('pg_anticipo_cxps', function (Blueprint $table) {
            $table->integer('gs_usuario_anula_id')->nullable();
            $table->timestamp('fecha_anulacion')->nullable();
            $table->foreign('gs_usuario_anula_id')->references('id')->on('gs_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
