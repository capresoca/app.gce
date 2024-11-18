<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterGsUserIdInNfComdiariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `nf_comdiarios` ADD COLUMN `gs_usuario_id` INT(10) NULL AFTER `updated_at`");
        Schema::table('nf_comdiarios', function (Blueprint $table) {
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios');
        });
    }

    public function down()
    {
        Schema::table('nf_comdiarios', function (Blueprint $table) {
            $table->dropForeign('nf_comdiarios_gs_usuario_id_foreign');
            $table->dropColumn('gs_usuario_id');
        });
    }
}
