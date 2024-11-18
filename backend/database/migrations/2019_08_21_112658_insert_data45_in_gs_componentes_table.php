<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertData45InGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Censos',
        ],[
            'nombre' => 'Censos',
            'gs_modulo_id' => 13,
            'documento' => 0,
            'ruta_router' => 'censos',
            'icono' => 'fas fa-street-view',
            'favorito' => null,
            'descripcion' => 'Carga de archivos',
            'ruta_componente' => '@/components/administrativo/auditoriaCuentas/censos/VCenso',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => 'auPacientes',
            'titulo_registro' => 'Listado de Pacientes',
            'permisos' => 'ver,agregar,confirmar,imprimir',
            'view_menu' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //\App\GsComponente::where('nombre', '=', 'Censos')->where('ruta_router', '=', 'censos')->first()->delete();
    }
}
