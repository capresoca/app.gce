<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrDetstrgastosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'pr_detstrgastos';


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create('pr_detstrgastos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pr_strgasto_id');
            $table->foreign('pr_strgasto_id')->references('id')->on('pr_strgastos')->onDelete('restrict');
            $table->integer('pr_rubro_id');
            $table->foreign('pr_rubro_id')->references('id')->on('pr_rubros')->onDelete('restrict');
            $table->integer('pr_tipo_gasto_id');
            $table->foreign('pr_tipo_gasto_id')->references('id')->on('pr_tipos_gastos')->onDelete('restrict');
            $table->double('valor_inicial');
            $table->double('valor_credito')->nullable();
            $table->double('valor_debito')->nullable();
            $table->double('valor_ejecutado')->nullable();
            $table->double('valor_recaudo')->nullable();
            $table->boolean('rubro_sin_presupuesto')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pr_detstrgastos');
    }
}
