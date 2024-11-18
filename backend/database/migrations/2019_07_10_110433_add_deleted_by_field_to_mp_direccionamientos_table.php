<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedByFieldToMpDireccionamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_direccionamientos', function (Blueprint $table) {
            $table->integer('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('gs_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_direccionamientos', function (Blueprint $table) {
            $table->dropForeign('mp_direccionamientos_deleted_by_foreign');
            $table->dropColumn('deleted_by');
        });
    }
}
