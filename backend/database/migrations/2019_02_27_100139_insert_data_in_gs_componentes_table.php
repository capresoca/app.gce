<?php

use App\GsComponente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataInGsComponentesTable extends Migration
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
               'id' => 144
            ],
            [
                'id' => 144,
                'nombre' => 'Modificación Presupuesto Inicial De Ingresos',
                'gs_modulo_id' => 15,
                'documento' => 0,
                'ruta_router' => 'modificacionPresupuestoInicialDeIngresos',
                'icono' => 'fast_forward',
                'favorito' => null,
                'descripcion' => 'Presupuesto inicial de ingresos',
                'ruta_componente' => '@/components/administrativo/presupuesto/modificacionPresupuestoInicialDeIngresos/ModificacionPresupuestoInicialDeIngresos',
                'created_at' => null,
                'updated_at' => null,
                'ruta_router_registro' => 'registroModificacionPresupuestoInicialDeIngresos',
                'titulo_registro' => 'Registro Modificación Presupuesto Ingresos',
                'permisos' => 'ver,agregar,confirmar',
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
        DB::statement("delete from gs_componentes 
                            value (144,'Modificación Presupuesto Inicial De Ingresos', 
                            15, 0, 'modificacionPresupuestoInicialDeIngresos', 'fast_forward', 
                            null, 'Presupuesto inicial de ingresos', 
                            '@/components/administrativo/presupuesto/modificacionPresupuestoInicialDeIngresos/ModificacionPresupuestoInicialDeIngresos',
                            null, null, 'registroModificacionPresupuestoInicialDeIngresos',
                            'Registro Modificación Presupuesto Ingresos', 'ver,agregar', 1)");
    }
}
