<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPrConceptosAddRegimenColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pr_conceptos', function (Blueprint $table) {
            $table->enum('regimen', ['Contributivo', 'Subsidiado'])->nullable()->default(null);
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
            $table->dropColumn('regimen', ['Contributivo', 'Subsidiado'])->nullable();
        });
    }
}