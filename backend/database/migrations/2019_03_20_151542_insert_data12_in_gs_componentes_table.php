<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertData12InGsComponentesTable extends Migration
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
                'nombre' => 'RP',
                'gs_modulo_id' => 15,
                'documento' => 0,
                'ruta_router' => 'rp',
                'icono' => 'library_books',
                'favorito' => null,
                'descripcion' => 'Registro Presupuestal',
                'ruta_componente' => '@/components/administrativo/presupuesto/rp/Rp',
                'created_at' => null,
                'updated_at' => null,
                'ruta_router_registro' => 'registroRp',
                'titulo_registro' => 'Registro RP',
                'permisos' => 'ver,agregar,confirmar',
                'view_menu' => 1
            ]);
    }

}
