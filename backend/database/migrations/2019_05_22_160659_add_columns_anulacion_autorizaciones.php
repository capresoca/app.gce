<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsAnulacionAutorizaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_autorizaciones`
                        ADD COLUMN `user_anula_id` INT(10)");

        Schema::table('au_autorizaciones', function (Blueprint $table) {
            //


            $table->text('motivo_anula');
            $table->dateTime('fecha_anula')->useCurrent();
            $table->foreign('user_anula_id')->references('id')->on('gs_usuarios')->onDelete('RESTRICT')->onUpdate('NO ACTION');
        });
        DB::statement("ALTER TABLE `au_autaprobadas`
                        ADD COLUMN `user_anula_id` INT(10)");

        Schema::table('au_autaprobadas', function (Blueprint $table){
            $table->text('motivo_anula');
            $table->dateTime('fecha_anula')->useCurrent();
            $table->foreign('user_anula_id')->references('id')->on('gs_usuarios')->onDelete('RESTRICT')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('au_autorizaciones', function (Blueprint $table) {
//            //
//        });
    }
}
