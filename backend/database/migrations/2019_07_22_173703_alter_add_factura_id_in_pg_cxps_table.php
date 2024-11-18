<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddFacturaIdInPgCxpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pg_cxps', function (Blueprint $table) {
            $table->integer('cm_factura_id')->nullable();
            $table->foreign('cm_factura_id')->references('id')->on('cm_facturas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pg_cxps', function (Blueprint $table) {
            $table->dropForeign('pg_cxps_cm_factura_id_foreign');
            $table->dropColumn('cm_factura_id');
        });
    }
}
