<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterField11InPrObligacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_obligaciones` 
                            DROP FOREIGN KEY `pr_obligaciones_gn_tercero_id_foreign`");
        DB::statement("ALTER TABLE `pr_obligaciones` 
                            ADD COLUMN `fecha_vencimiento` DATE NOT NULL AFTER `fecha_documento`,
                            CHANGE COLUMN `gn_tercero_id` `gn_tercero_id` INT(11) NOT NULL");
        DB::statement("ALTER TABLE `pr_obligaciones` 
                            ADD CONSTRAINT `pr_obligaciones_gn_tercero_id_foreign`
                              FOREIGN KEY (`gn_tercero_id`)
                              REFERENCES `gn_terceros` (`id`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pr_obligaciones', function (Blueprint $table) {
            //
        });
    }
}
