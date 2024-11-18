<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrDetstringresosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'pr_detstringresos';

    /**
     * Run the migrations.
     * @table cm_manisss
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create('pr_detstringresos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pr_stringreso_id');
            $table->foreign('pr_stringreso_id')->references('id')->on('pr_stringresos')->onDelete('restrict');
            $table->integer('pr_rubro_id');
            $table->foreign('pr_rubro_id')->references('id')->on('pr_rubros')->onDelete('restrict');
            $table->integer('pr_tipo_ingreso_id');
            $table->foreign('pr_tipo_ingreso_id')->references('id')->on('pr_tipo_ingresos')->onDelete('restrict');
            $table->double('valor_inicial');
            $table->double('valor_credito')->nullable();
            $table->double('valor_debito')->nullable();
            $table->double('valor_ejecutado')->nullable();
            $table->double('valor_recaudo')->nullable();
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
        Schema::dropIfExists('pr_detstringresos');
    }
}
