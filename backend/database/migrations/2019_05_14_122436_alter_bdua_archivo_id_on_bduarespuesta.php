<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterBduaArchivoIdOnBduarespuesta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE as_bduarespuestas
        DROP FOREIGN KEY FK_as_bduarespuestas_as_bduaarchivos;");
        DB::statement("ALTER TABLE as_bduarespuestas
        CHANGE COLUMN as_bduaarchivo_id as_bduaarchivo_id INT(11) NULL ;");
        DB::statement("ALTER TABLE as_bduarespuestas
        ADD CONSTRAINT FK_as_bduarespuestas_as_bduaarchivos
        FOREIGN KEY (as_bduaarchivo_id)
        REFERENCES as_bduaarchivos (id);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
