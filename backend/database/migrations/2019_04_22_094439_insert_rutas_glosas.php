<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Aseguramiento\AsBduatipglosa;

class InsertRutasGlosas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AsBduatipglosa::where('id','23')->update([
            'clase_procesador' => 'App\Capresoca\Aseguramiento\Glosas\GN0003'
        ]);
        AsBduatipglosa::where('id','34')->update([
            'clase_procesador' => 'App\Capresoca\Aseguramiento\Glosas\GN0028'
        ]);
        AsBduatipglosa::where('id','24')->update([
            'clase_procesador' => 'App\Capresoca\Aseguramiento\Glosas\GN0030'
        ]);
        AsBduatipglosa::where('id','30')->update([
            'clase_procesador' => 'App\Capresoca\Aseguramiento\Glosas\GN0069'
        ]);
        AsBduatipglosa::where('id','25')->update([
            'clase_procesador' => 'App\Capresoca\Aseguramiento\Glosas\GN0095'
        ]);
        AsBduatipglosa::where('id','31')->update([
            'clase_procesador' => 'App\Capresoca\Aseguramiento\Glosas\GN0114'
        ]);
        AsBduatipglosa::where('id','29')->update([
            'clase_procesador' => 'App\Capresoca\Aseguramiento\Glosas\GN0161'
        ]);
        AsBduatipglosa::where('id','27')->update([
            'clase_procesador' => 'App\Capresoca\Aseguramiento\Glosas\GN0312'
        ]);
        AsBduatipglosa::where('id','33')->update([
            'clase_procesador' => 'App\Capresoca\Aseguramiento\Glosas\GN0313'
        ]);
        AsBduatipglosa::where('id','32')->update([
            'clase_procesador' => 'App\Capresoca\Aseguramiento\Glosas\GN0315'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        AsBduatipglosa::where('id','23')->update([
            'clase_procesador' => ''
        ]);
        AsBduatipglosa::where('id','34')->update([
            'clase_procesador' => ''
        ]);
        AsBduatipglosa::where('id','24')->update([
            'clase_procesador' => ''
        ]);
        AsBduatipglosa::where('id','30')->update([
            'clase_procesador' => ''
        ]);
        AsBduatipglosa::where('id','25')->update([
            'clase_procesador' => ''
        ]);
        AsBduatipglosa::where('id','31')->update([
            'clase_procesador' => ''
        ]);
        AsBduatipglosa::where('id','29')->update([
            'clase_procesador' => ''
        ]);
        AsBduatipglosa::where('id','27')->update([
            'clase_procesador' => ''
        ]);
        AsBduatipglosa::where('id','33')->update([
            'clase_procesador' => ''
        ]);
        AsBduatipglosa::where('id','32')->update([
            'clase_procesador' => ''
        ]);
    }
}
