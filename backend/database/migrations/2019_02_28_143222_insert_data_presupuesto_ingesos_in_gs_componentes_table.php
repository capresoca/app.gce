<?php

use App\GsComponente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataPresupuestoIngesosInGsComponentesTable extends Migration
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
                'id' => 138
            ],
            [
                'id' => 138,
                'nombre' => 'Presupuesto Inicial De Ingresos',
                'gs_modulo_id' => 15,
                'documento' => 0,
                'ruta_router' => 'tablaPresupuestoInicialDeIngresos',
                'icono' => 'next_week',
                'favorito' => null,
                'descripcion' => 'Presupuesto inicial de ingresos',
                'ruta_componente' => '@/components/administrativo/presupuesto/presupuestoInicialDeIngresos/TablaPresupuestoInicialDeIngresos',
                'created_at' => null,
                'updated_at' => null,
                'ruta_router_registro' => 'presupuestoInicialDeIngresos',
                'titulo_registro' => 'Registro Presupuesto Inicial de Ingresos',
                'permisos' => 'ver,agregar,confirmar',
                'view_menu' => 1
            ]);
    }

}
