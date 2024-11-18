<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRsCancelportabilidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `rs_portabilidades`
                            CHANGE COLUMN `estado` `estado` ENUM('Registrado','Negado','Aceptado','Terminada','Cancelada') NOT NULL DEFAULT 'Registrado' COLLATE 'utf8mb4_unicode_ci' AFTER `gn_archivo_id`");
        Schema::create('rs_cancelportabilidades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rs_portabilidade_id')->comment('Fk - Id de la portabilidad cancelada');
            $table->longText('descripcion')->comment('Descripción del ¿Por qué cancela la portabilidad?.');
            $table->integer('gs_usuario_id')->comment('Fk -Id de quién cancela');
            $table->timestamps();
            $table->foreign('rs_portabilidade_id')->references('id')->on('rs_portabilidades')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rs_cancelportabilidades');
    }
}
