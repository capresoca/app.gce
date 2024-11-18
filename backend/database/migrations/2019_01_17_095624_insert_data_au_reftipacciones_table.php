<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataAuReftipaccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::delete('delete from au_reftipacciones');
        DB::statement("insert into au_reftipacciones (id, accion, tooltip, metodo, color, icon, `option`, created_at, updated_at, descripcion)
                          values 
                (1, 'Presentado', 'Presentar Afiliado', 'actualizarEstadopresentacion', 'orange lighten-2', 'bookmarks', '1', NULL, NULL, 'Afiliado Presentado'),
                (2, 'Aceptado', 'Aceptar', 'actualizarEstadopresentacion', 'green lighten-1', 'check_circle', '2', NULL, NULL, 'Afiliado Aceptado'),
                (3, 'No Aceptado', 'No Aceptar', 'actualizarEstadopresentacion', 'red darken-2', 'indeterminate_check_box', '3', NULL, NULL, 'Afiliado No Aceptado'),
                (4, 'Traslado', 'Solicitar Traslado', 'solicitudTraslado', 'deep-orange darken-2', 'airline_seat_flat', '4', NULL, NULL, 'Solicitud de Traslado'),
                (5, 'Seleccionado', 'Seleccionar IPS', 'actualizarEstadopresentacion', 'accent lighten-2', 'done_all', '5', NULL, NULL, 'IPS Destino'),
                (6, 'Deseleccionado', 'Deseleccionar IPS', 'actualizarEstadopresentacion', 'red darken-1', 'close', '6', NULL, NULL, 'Deseleccionada'),
                (7, 'Bitacora', 'Bitácora', 'bitacora', 'cyan darken-1', 'list_alt', '7', NULL, NULL, 'Observación'),
                (8, 'En Camino', 'En Camino', 'solicitudTraslado', 'purple darken-3', 'departure_board', '8', NULL, NULL, 'Traslado En Camino'),
                (9, 'Terminado', 'Terminar Traslado', 'solicitudTraslado', 'grey darken-3', 'person_pin', '9', NULL, NULL, 'Traslación Terminada'),
                (10, 'Cancelado', 'Cancelar Traslado', 'solicitudTraslado', 'pink darken-1', 'cancel', '10', NULL, NULL, ''),
                (11, 'Seleccionado', 'Seleccionar IPS', 'actualizarEstadopresentacion', 'accent lighten-2', 'done_all', '11', NULL, NULL, 'IPS Seleccionada'),
                (12, 'Fallecida', 'Cancelar por Fallecimiento', 'solicitudTraslado', 'orange darken-1', 'block', '12', NULL, NULL, 'Cancelada por Fallecimiento'),
                (13, 'Suspendida', 'Suspender', 'solicitudTraslado', 'indigo darken-1', 'remove_circle_outline', '13', NULL, NULL, 'Traslación Suspendida'),
                (14, NULL, 'Aceptar Solicitud Traslado', 'solicitudTraslado', 'teal darken-2', 'offline_pin', '14', NULL, NULL, 'Traslación Aceptada'),
                (15, NULL, 'Reanudar Traslación', 'solicitudTraslado', 'purple darken-4', 'commute', '15', NULL, NULL, 'Proceso En Camino Renaudado')"

        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete('delete from au_reftipacciones');
    }
}
