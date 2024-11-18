<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCeTipocontratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ce_tipocontratos', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->boolean('servicios_salud')->default(false);
            $table->timestamps();
        });
        \App\Models\ContratacionEstatal\CeTipocontrato::insert([
           [
               'nombre' => 'Capitado',
               'descripcion' => 'Capitado',
               'servicios_salud' => true

           ],
            [
                'nombre' => 'Evento',
                'descripcion' => 'Evento',
                'servicios_salud' => true
            ],
            [
                'nombre' => 'Paquete Integral',
                'descripcion' => 'Paquete Integral',
                'servicios_salud' => true
            ],
            [
                'nombre' => 'Prestación de Servicios',
                'descripcion' => 'Prestación de Servicios',
                'servicios_salud' => false
            ],
            [
                'nombre' => 'Suministro o Venta de Medicamentos, Insumos y Otros',
                'descripcion' => 'Suministro o Venta de Medicamentos, Insumos y Otros',
                'servicios_salud' => false
            ],
            [
                'nombre' => 'Arrenamiento',
                'descripcion' => 'Arrenamiento',
                'servicios_salud' => false
            ],
            [
                'nombre' => 'Servicios de Transporte',
                'descripcion' => 'Servicios de Transporte',
                'servicios_salud' => false
            ],
            [
                'nombre' => 'Suministros',
                'descripcion' => 'Suministros',
                'servicios_salud' => false
            ]
        ]);


        Schema::table('ce_proconminutas', function (Blueprint $table){
           $table->integer('ce_tipocontrato_id')->nullable();
           $table->foreign('ce_tipocontrato_id')->references('id')->on('ce_tipocontratos')->onUpdate('NO ACTION')->onDelete('RESTRICT');
        });
        DB::statement("UPDATE ce_proconminutas SET ce_tipocontrato_id = (SELECT id FROM ce_tipocontratos WHERE nombre LIKE '%Capitado%') WHERE tipo = 'Capitado' ");
        DB::statement("UPDATE ce_proconminutas SET ce_tipocontrato_id = (SELECT id FROM ce_tipocontratos WHERE nombre LIKE '%Evento%') WHERE tipo = 'Evento' ");
        DB::statement("UPDATE ce_proconminutas SET ce_tipocontrato_id = (SELECT id FROM ce_tipocontratos WHERE nombre LIKE '%Paquete Integral%') WHERE tipo = 'Paquete Integral' ");

        Schema::table('ce_proconminutas', function (Blueprint $table){
            $table->dropColumn('tipo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ce_tipocontratos');
    }
}
