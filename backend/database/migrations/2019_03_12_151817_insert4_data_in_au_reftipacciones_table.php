<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Insert4DataInAuReftipaccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("insert into au_reftipacciones (id, accion, tooltip, metodo, color, icon, `option`, created_at, updated_at, descripcion) 
                            values (20, 'Manejo En La Institución', null, 'cancelarProceso', 'pink darken-1', 'cancel', '20', NULL, NULL, 'Cancelado: Manejo En La Institución')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\CentroRegulador\AuReftipaccione::find(20)->delete();
    }
}
