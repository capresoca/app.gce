<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUniqueIndexInAsAfiliadoAltocostosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `as_afiliado_altocostos` 
                    ADD UNIQUE INDEX `as_afiliado_altocostos_tipo_altocosto_unique` (`as_afiliado_id` ASC, `as_tipaltocosto_id` ASC)");
    }
}
