<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRutasComponentesAntiguos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     
        // Creacion de migraciones que se enviaron por Email y que 
        \App\GsComponente::updateOrCreate([
            'id' => '96'
        ],[
            'nombre' => 'Auditores',
            'gs_modulo_id' => 13,
            'documento' => 0,
            'ruta_router' => 'auditores',
            'icono' => 'supervisor_account',
            'favorito' => 0,
            'descripcion' => 'Auditores',
            'ruta_componente' => '@/components/administrativo/auditoriaCuentas/auditores/Auditores',
            'ruta_router_registro' => 'registroAuditores',
            'titulo_registro' => 'Registro de auditores',
            'permisos' => 'ver,agregar',
            'view_menu' => 1,
        ]);
        \App\GsComponente::updateOrCreate([
            'id' => '90'
        ],[
            'nombre' => 'Productos',
            'gs_modulo_id' => 11,
            'documento' => 0,
            'ruta_router' => 'productos',
            'icono' => 'shopping_cart',
            'favorito' => 0,
            'descripcion' => 'Productos',
            'ruta_componente' => '@/components/administrativo/inventarios/Productos/Productos',
            'ruta_router_registro' => 'registroProducto',
            'titulo_registro' => 'Registro de Productos',
            'permisos' => 'ver,agregar',
            'view_menu' => 1,
        ]);
        \App\GsComponente::updateOrCreate([
            'ruta_router' => 'aseguradoras'
        ],[
            'nombre' => 'Aseguradoras',
            'gs_modulo_id' => 8,
            'documento' => 0,
            'ruta_router' => 'aseguradoras',
            'icono' => 'dashboard',
            'favorito' => 0,
            'descripcion' => 'Aseguradoras',
            'ruta_componente' => '@/components/administrativo/contratacionEstatal/aseguradoras/Aseguradoras',
            'ruta_router_registro' => 'registroAseguradoras',
            'titulo_registro' => 'Registro de aseguradoras',
            'permisos' => 'ver,agregar',
            'view_menu' => 1,
        ]);
        \App\GsComponente::updateOrCreate([
            'ruta_router' => 'tipoPoliza'
        ],[
            'nombre' => 'Tipo de polizas',
            'gs_modulo_id' => 8,
            'documento' => 0,
            'ruta_router' => 'tipoPoliza',
            'icono' => 'dashboard',
            'favorito' => 0,
            'descripcion' => 'Tipo de polizas',
            'ruta_componente' => '@/components/administrativo/contratacionEstatal/TipoPoliza/TipoPoliza',
            'ruta_router_registro' => 'registroTipoPoliza',
            'titulo_registro' => 'Registro de tipo de polizas',
            'permisos' => 'ver,agregar',
            'view_menu' => 1,
        ]);
        \App\GsComponente::updateOrCreate([
            'ruta_router' => 'abogados'
        ],[
            'nombre' => 'Abogados',
            'gs_modulo_id' => 8,
            'documento' => 0,
            'ruta_router' => 'abogados',
            'icono' => 'dashboard',
            'favorito' => 0,
            'descripcion' => 'Abogados',
            'ruta_componente' => '@/components/administrativo/contratacionEstatal/Abogados/Abogados',
            'ruta_router_registro' => 'registroAbogado',
            'titulo_registro' => 'Registro de Abogados',
            'permisos' => 'ver,agregar',
            'view_menu' => 1,
        ]);
        \App\GsComponente::updateOrCreate([
            'ruta_router' => 'interventores'
        ],[
            'nombre' => 'Interventores',
            'gs_modulo_id' => 8,
            'documento' => 0,
            'ruta_router' => 'interventores',
            'icono' => 'dashboard',
            'favorito' => 0,
            'descripcion' => 'Abogados',
            'ruta_componente' => '@/components/administrativo/contratacionEstatal/Interventores/Interventores',
            'ruta_router_registro' => 'registroInterventor',
            'titulo_registro' => 'Registro de interventores',
            'permisos' => 'ver,agregar',
            'view_menu' => 1,
        ]);
        \App\GsComponente::updateOrCreate([
            'ruta_router' => 'supervisores'
        ],[
            'nombre' => 'Supervisores',
            'gs_modulo_id' => 8,
            'documento' => 0,
            'ruta_router' => 'supervisores',
            'icono' => 'dashboard',
            'favorito' => 0,
            'descripcion' => 'Supervisores',
            'ruta_componente' => '@/components/administrativo/contratacionEstatal/Supervisores/Supervisores',
            'ruta_router_registro' => 'registroSupervisores',
            'titulo_registro' => 'Registro de Supervisores',
            'permisos' => 'ver,agregar',
            'view_menu' => 1,
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
