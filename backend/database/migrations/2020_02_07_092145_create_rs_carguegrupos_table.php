<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRsCarguegruposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_carguegrupos', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('tipo',['Asignación','Traslado'])->default(null);
            $table->integer('total_registros')->default(0);
            $table->mediumText('observacion')->nullable()->default(null);
            $table->integer('gs_usuario_id')->index();
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
        Schema::dropIfExists('rs_carguegrupos');
    }
}
