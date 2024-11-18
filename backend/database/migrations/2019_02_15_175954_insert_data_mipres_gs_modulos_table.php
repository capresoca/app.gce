<?php

use Illuminate\Database\Migrations\Migration;

class InsertDataMipresGsModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsModulo::updateOrCreate([
            'id' => 21,
        ],[
            'nombre' => 'MIPRESS',
            'color' => 'orange'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\GsModulo::find(21)->delete();
    }
}
