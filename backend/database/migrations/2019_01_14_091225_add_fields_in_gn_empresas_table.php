<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInGnEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gn_empresas', function (Blueprint $table) {
            $table->integer('nf_niifdebitoprosincontrato_id')->nullable();
            $table->foreign('nf_niifdebitoprosincontrato_id')->references('id')->on('nf_niifs')->onDelete('restrict');
            $table->integer('nf_niifcreditoprosincontrato_id')->nullable();
            $table->foreign('nf_niifcreditoprosincontrato_id')->references('id')->on('nf_niifs')->onDelete('restrict');
            $table->integer('nf_comdiarioprovisional_id')->nullable();
            $table->foreign('nf_comdiarioprovisional_id')->references('id')->on('nf_tipcomdiarios')->onDelete('restrict');
            $table->integer('nf_comdiarioprovisionalsincontrato_id')->nullable();
            $table->foreign('nf_comdiarioprovisionalsincontrato_id')->references('id')->on('nf_tipcomdiarios')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('gn_empresas', function (Blueprint $table) {
            $table->dropForeign('gn_empresas_nf_comdiarioprovisional_id_foreign');
            $table->dropForeign('gn_empresas_nf_comdiarioprovisionalsincontrato_id_foreign');
            $table->dropForeign('gn_empresas_nf_niifcreditoprosincontrato_id_foreign');
            $table->dropForeign('gn_empresas_nf_niifdebitoprosincontrato_id_foreign');
            $table->dropColumn('nf_niifdebitoprosincontrato_id');
            $table->dropColumn('nf_niifcreditoprosincontrato_id');
            $table->dropColumn('nf_comdiarioprovisional_id');
            $table->dropColumn('nf_comdiarioprovisionalsincontrato_id');
        });
    }
}
