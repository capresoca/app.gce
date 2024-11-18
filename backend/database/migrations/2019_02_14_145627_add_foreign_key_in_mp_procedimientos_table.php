<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyInMpProcedimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `mp_procedimientos` 
                        ADD CONSTRAINT `mp_procedimientos_rs_cups_id_foreign`
                          FOREIGN KEY (`rs_cups_id`)
                          REFERENCES `rs_cupss` (`id`)
                          ON DELETE NO ACTION
                          ON UPDATE NO ACTION;
                    ");
    }


}
