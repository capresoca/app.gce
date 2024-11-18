<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefcupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        try {
//            Schema::dropIfExists('refcups');

//            Schema::create('refcups', function (Blueprint $table) {
//                $table->string('codigo',20)->primary();
//                $table->string('homologo',20)->index('index_homologo')->default('');
//                $table->string('descrip',250)->unique()->default('');
//                $table->char('genero',1)->default('');
//                $table->char('ambito',1);
//                $table->integer('lInf')->unsigned();
//                $table->integer('valor')->default(0)->unsigned();
//                $table->integer('lSup')->unsigned();
//                $table->char('estancia',1)->default('');
//                $table->char('unico',1)->default('');
//                $table->char('nivel',2)->default('');
//                $table->char('AT',1)->default('');
//                $table->char('pos',1);
//                $table->char('copago',1)->default('');
//                $table->char('altoCosto',2);
//                $table->char('cober',2);
//                $table->integer('rep')->default(0);
//                $table->char('esp',3);
//                $table->char('Qx',1);
//                $table->char('aut',1);
//                $table->char('freq',3);
//                $table->char('ind',1);
//                $table->date('fi');
//                $table->date('ff');
//                $table->timestamps();
//            });
//
//            DB::statement("ALTER TABLE `refcups`
//                            CHANGE COLUMN `rep` `rep` INT(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Repeticion en termino de meses' ");
//
//            $importPath = base_path().'/database/migrations/imports/refcups.csv';
//            $importPath = str_replace('\\','/',$importPath);
//            DB::statement("SET GLOBAL local_infile = `ON`");
//            DB::connection()->getPdo()
//                ->exec("
//                    LOAD DATA LOCAL INFILE '{$importPath}' INTO TABLE refcups FIELDS TERMINATED BY ','
//                    IGNORE 1 ROWS (`codigo`, `descrip`, `genero`, `ambito`, `lInf`, `valor`, `lSup`,
//                    `estancia`, `unico`, `nivel`, `AT`, `pos`, `copago`, `altoCosto`, `cober`, `rep`,
//                    `esp`, `Qx`, `aut`, `freq`, `ind`, `fi`, `ff`)
//                ");
//            DB::statement("SET GLOBAL local_infile = `OFF`");
//
//        } catch (Exception $exception) {
//            print ($importPath.'\\n');
//            print ($exception->getMessage());
//            Schema::dropIfExists('refcups');
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('refcups');
    }
}
