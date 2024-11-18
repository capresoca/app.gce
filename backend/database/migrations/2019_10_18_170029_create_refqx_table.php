<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefqxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('refqx');
        Schema::create('refqx', function (Blueprint $table) {
            $table->char('codigo',1)->primary();
            $table->string('descrip',20);
            $table->char('ind',1);
            $table->timestamps();
        });

        $sql = "INSERT INTO refqx(codigo,descrip,ind) VALUES
                ('1', 'Quirurgico',1),
                ('2', 'No quirurgico',1)";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refqx');
    }
}
