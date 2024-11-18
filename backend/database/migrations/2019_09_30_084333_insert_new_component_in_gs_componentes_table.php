<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertNewComponentInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Carga de Recaudos',
        ],[
            'nombre' => 'Carga de Recaudos',
            'gs_modulo_id' => 3,
            'documento' => 0,
            'ruta_router' => 'cargaRecaudos',
            'icono' => 'fas fa-file-upload',
            'favorito' => null,
            'descripcion' => 'Carga de archivos de recaudos',
            'ruta_componente' => '@/components/misional/aseguramiento/recaudos/CargaRecaudo',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => '',
            'titulo_registro' => 'Cargar Archivos',
            'permisos' => 'ver,agregar',
            'view_menu' => 1
        ]);
    }
}
