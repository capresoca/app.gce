<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Aseguramiento\AsTipbduaarchivo;

class UpdtedClasesProcesadoresAseguramiento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AsTipbduaarchivo::where('id','3')->update([
            'sufijo' => 'FAC',
            'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\NCFAC'
        ]);
        AsTipbduaarchivo::where('id','25')->update([
            'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\NSNEG'
        ]);
        AsTipbduaarchivo::updateOrCreate([
            'sufijo' => 'NEG',
            'iniciales' => 'NC',
        ],
        [
            'nombre' => 'Novedades Contributivo Negado',
            'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\NCNEG',
            'tipo_tramite' => 'Novedad Contributivo'
        ]);
        AsTipbduaarchivo::updateOrCreate([
            'sufijo' => 'VAL',
            'iniciales' => 'NC',
        ],
        [
            'nombre' => 'Procesar Novedades Contributivo Validados',
            'clase_procesador' => 'App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\NCVAL',
            'tipo_tramite' => 'Novedad Contributivo'
        ]);
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
