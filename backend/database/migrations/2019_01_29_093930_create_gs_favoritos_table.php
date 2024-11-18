<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGsFavoritosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('gs_favoritos');
        Schema::create('gs_favoritos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gs_usuario_id');
            $table->integer('gs_componente_id');
            $table->timestamps();

            $table->foreign('gs_usuario_id')->references('id')
                    ->on('gs_usuarios')->onDelete('restrict')->onUpdate('no action');

            $table->foreign('gs_componente_id')->references('id')
                    ->on('gs_componentes')->onDelete('restrict')->onUpdate('no action');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gs_favoritos');
    }
}
