<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCmFacturaMontosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_factura_montos', function (Blueprint $table) {
            $table->increments('id');
            $table->double('valor_rango1')->default(0);
            $table->double('valor_rango2')->default(0);
            $table->string('descrip',25);
            $table->timestamps();
        });

        $sql = "INSERT INTO cm_factura_montos(valor_rango1,valor_rango2,descrip) VALUES
                (0,500000,'$ 0 a 500.000'),
                (500000,1000000,'$ 500.000 a 1.000.000'),
                (1000000,2000000,'$ 1.000.000 a 2.000.000'),
                (2000000,3000000,'$ 2.000.000 a 3.000.000'),
                (3000000,5000000,'$ 3.000.000 a 5.000.000'),
                (5000000,10000000,'$ 5.000.000 a 10.000.000'),
                (10000000,20000000,'$ 10.000.000 a 20.000.000'),
                (20000000,99999999999,'> $ 20.000.000')";
        DB::statement($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_factura_montos');
    }
}
