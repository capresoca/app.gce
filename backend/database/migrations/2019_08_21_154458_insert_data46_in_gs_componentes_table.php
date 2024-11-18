<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertData46InGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Pacientes Hospitalarios',
        ],[
            'nombre' => 'Pacientes Hospitalarios',
            'gs_modulo_id' => 13,
            'documento' => 0,
            'ruta_router' => 'auPacientes',
            'icono' => 'fas fa-clipboard-list',
            'favorito' => null,
            'descripcion' => 'Listado de pacientes censados',
            'ruta_componente' => '@/components/administrativo/auditoriaCuentas/censos/AuditoriaPacientes',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,agregar,confirmar,imprimir',
            'view_menu' => 1
        ]);
    }
}
