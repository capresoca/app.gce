<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpNotificacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_notificacions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mp_direccionamiento_id')->unsigned();
            $table->foreign('mp_direccionamiento_id')->references('id')->on('mp_direccionamientos');
            $table->enum('tipo',['sms','telefónica','email','personal','otro']);
            $table->boolean('notificacion_exitosa')->default(1);
            $table->boolean('aceptada')->default(1);
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('mp_notificacions');
    }
}
