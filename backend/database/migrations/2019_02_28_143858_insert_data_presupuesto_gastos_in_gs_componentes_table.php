<?php

use App\GsComponente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataPresupuestoGastosInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        GsComponente::updateOrCreate(
            [
                'id' => 139
            ],
            [
                'id' => 139,
                'nombre' => 'Presupuesto Inicial De Gastos',
                'gs_modulo_id' => 15,
                'documento' => 0,
                'ruta_router' => 'tablaPresupuestoInicialDeGastos',
                'icono' => 'undo',
                'favorito' => null,
                'descripcion' => 'Presupuesto inicial de gastos',
                'ruta_componente' => '@/components/administrativo/presupuesto/presupuestoInicialDeGastos/TablaPresupuestoInicialDeGastos',
                'created_at' => null,
                'updated_at' => null,
                'ruta_router_registro' => 'presupuestoInicialDeGastos',
                'titulo_registro' => 'Registro Presupuesto Inicial De Gastos',
                'permisos' => 'ver,agregar,confirmar',
                'view_menu' => 1
            ]);
    }


}
