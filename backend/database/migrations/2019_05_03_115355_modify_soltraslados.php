
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifySoltraslados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        DB::statement("ALTER TABLE `as_soltraslados` 
        CHANGE COLUMN `estado` `estado` ENUM('Registrado', 'Radicado', 'Validado', 'Glosado', 'Aprobado', 'Negado') NOT NULL DEFAULT 'Registrado';");
        
        Schema::table('as_soltraslados', function (Blueprint $table) {
            if(!Schema::hasColumn('as_soltraslados', 'as_tramite_id')){
                $table->integer('as_tramite_id')->nullable();
                $table->foreign('as_tramite_id')->references('id')->on('as_tramites')->onDelete('restrict');
            }
            if(!Schema::hasColumn('as_regimen_id', 'as_tramite_id')){
                $table->integer('as_regimen_id')->nullable();
                $table->foreign('as_regimen_id')->references('id')->on('as_regimenes')->onDelete('restrict');
            }
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
