<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterValorEpsFieldInAuAudetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `au_autdetalles`
                            CHANGE COLUMN `tipo_valor` `tipo_valor` ENUM("Copago","Cuota Moderadora","Moderadora") 
                            NULL DEFAULT NULL COLLATE "utf8mb4_unicode_ci" AFTER `valor_usuario`');

        DB::statement('UPDATE `au_autdetalles` SET `tipo_valor`="Cuota Moderadora" WHERE  `tipo_valor`="Moderadora"');

        DB::statement('ALTER TABLE `au_autdetalles`
                            CHANGE COLUMN `tipo_valor` `tipo_valor` ENUM("Copago","Cuota Moderadora") 
                            NULL DEFAULT NULL COLLATE "utf8mb4_unicode_ci" AFTER `valor_usuario`');
    }

}
