<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAuAnexot3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_anexot3s',function (Blueprint $table){
           $table->integer('oj_tutela_id')->nullable();
           $table->foreign('oj_tutela_id')->references('id')->on('oj_tutelas')->onDelete('restrict');
        });
    }

}
