<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataConcurrenciasAsignadasInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Concurrencias Asignadas',
        ],[
            'nombre' => 'Concurrencias Asignadas',
            'gs_modulo_id' => 13,
            'documento' => 0,
            'ruta_router' => 'concurrenciasAsignadas',
            'icono' => 'fas fa-clipboard-list',
            'favorito' => null,
            'descripcion' => 'Listado de facturas radicadas',
            'ruta_componente' => '@/components/administrativo/auditoriaCuentas/concurrencias/ConcurrenciasAsignadas',
            'ruta_router_registro' => 'detalleConcurrencia',
            'titulo_registro' => 'Concurrencia del Paciente',
            'permisos' => 'ver,agregar,confirmar,imprimir',
            'view_menu' => 1
        ]);
    }
}
