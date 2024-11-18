<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertModificacionGastosInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate(
            [
                'id' => 145
            ],
            [
                'id' => 145,
                'nombre' => 'Modificación Presupuesto Inicial De Gastos',
                'gs_modulo_id' => 15,
                'documento' => 0,
                'ruta_router' => 'modificacionPresupuestoInicialDeGastos',
                'icono' => 'fast_rewind',
                'favorito' => null,
                'descripcion' => 'Presupuesto inicial de gastos',
                'ruta_componente' => '@/components/administrativo/presupuesto/modificacionPresupuestoInicialDeIngresos/ModificacionPresupuestoInicialDeGastos',
                'created_at' => null,
                'updated_at' => null,
                'ruta_router_registro' => 'registroModificacionPresupuestoInicialDeGastos',
                'titulo_registro' => 'Registro Modificación Presupuesto Gastos',
                'permisos' => 'ver,agregar',
                'view_menu' => 1
            ]);
    }


}
