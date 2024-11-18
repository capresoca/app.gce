<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefCupsConPriVezsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('refCupsConPriVez');

        Schema::create('refcupsconprives', function (Blueprint $table) {
            $table->string('codigo','15')->primary();
            $table->timestamps();
        });

        $sql = "INSERT INTO refcupsconprives(codigo) VALUES
                ('890201'),
                ('890203'),
                ('890204'),
                ('890205'),
                ('890206'),
                ('890207'),
                ('890208'),
                ('890209'),
                ('890210'),
                ('890211'),
                ('890212'),
                ('890213'),
                ('890214'),
                ('890215')";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refcupsconprives');
    }
}
