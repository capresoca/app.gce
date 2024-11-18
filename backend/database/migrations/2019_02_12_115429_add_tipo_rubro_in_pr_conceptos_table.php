<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoRubroInPrConceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pr_conceptos', function (Blueprint $table) {
            $table->enum('tipo_rubro',['Ingresos','Gastos']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pr_conceptos', function (Blueprint $table) {
            $table->dropColumn('tipo_rubro');
        });
    }
}
