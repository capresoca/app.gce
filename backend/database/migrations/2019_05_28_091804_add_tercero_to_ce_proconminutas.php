<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTerceroToCeProconminutas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutas', function (Blueprint $table) {
            $table->integer('gn_tercero_id')->nullable();
            $table->foreign('gn_tercero_id')->references('id')->on('gn_terceros')->onDelete('RESTRICT')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_proconminutas', function (Blueprint $table) {
            //
        });
    }
}
