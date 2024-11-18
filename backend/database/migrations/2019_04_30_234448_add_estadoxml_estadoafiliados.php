<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstadoxmlEstadoafiliados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('as_estadoafiliados', function (Blueprint $table) {
            $table->string('xml')->nullable();
        });

        $sql = "UPDATE as_estadoafiliados SET xml = 'ACTIVO' WHERE id = 3";
        DB::statement($sql);
        $sql = "UPDATE as_estadoafiliados SET xml = 'AFILIADO FALLECIDO' WHERE id = 8";
        DB::statement($sql);
        $sql = "UPDATE as_estadoafiliados SET xml = 'RETIRADO' WHERE id = 4";
        DB::statement($sql);
        $sql = "UPDATE as_estadoafiliados SET xml = 'SUSPENSIÓN POR MORA.' WHERE id = 5";
        DB::statement($sql);
        $sql = "UPDATE as_estadoafiliados SET xml = 'SUSPENDIDO' WHERE id = 9";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('as_estadoafiliados', function (Blueprint $table) {
            //
        });
    }
}
