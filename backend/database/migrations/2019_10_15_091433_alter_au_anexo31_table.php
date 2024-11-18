<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAuAnexo31Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_anexot31s', function (Blueprint $table){
            $table->integer('nFac')->nullable()->default(null)->change();
            $table->foreign('nFac')->references('id')->on('cm_facturas')->onDelete('restrict');
            $table->integer('usuAsi')->nullable()->change();
        });
    }

}
