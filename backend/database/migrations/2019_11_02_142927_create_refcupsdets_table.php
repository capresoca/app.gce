<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefcupsdetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('refcupsdet');
        Schema::create('refcupsdet', function (Blueprint $table) {
            $table->string('codigo',14)->primary()->default('');
            $table->string('eMin',3);
            $table->string('eMax',3);
            $table->char('eMed',1);
            $table->timestamps();
        });

        $sql = "INSERT INTO refcupsdet(codigo,eMin,eMax,eMed) VALUES
                ('S12101',0,1,'M'),
                ('S12102',1,168,'M'),
                ('S12103',14,999,'A'),
                ('S12201',0,1,'M'),
                ('S12400',0,1,'M'),
                ('S12202',1,168,'M'),
                ('S12301',0,168,'M'),
                ('S12203',14,999,'A'),
                ('S12302',14,999,'A'),
                ('911118',0,18,'A'),
                ('890220',0,18,'A'),
                ('890229',0,18,'A'),
                ('890238',0,18,'A'),
                ('890245',0,18,'A'),
                ('890247',0,18,'A'),
                ('890252',0,18,'A'),
                ('890269',0,18,'A'),
                ('890275',0,18,'A'),
                ('890277',0,18,'A'),
                ('890279',0,18,'A'),
                ('890281',0,18,'A'),
                ('890283',0,18,'A'),
                ('890289',0,18,'A'),
                ('890320',0,18,'A'),
                ('890329',0,18,'A'),
                ('890338',0,18,'A'),
                ('890345',0,18,'A'),
                ('890347',0,18,'A'),
                ('890352',0,18,'A'),
                ('890375',0,18,'A'),
                ('890379',0,18,'A'),
                ('890381',0,18,'A'),
                ('890383',0,18,'A'),
                ('890385',0,18,'A'),
                ('890389',0,18,'A'),
                ('890429',0,18,'A'),
                ('890438',0,18,'A'),
                ('890445',0,18,'A'),
                ('890447',0,18,'A'),
                ('890469',0,18,'A'),
                ('890472',0,18,'A'),
                ('890475',0,18,'A'),
                ('890477',0,18,'A'),
                ('890481',0,18,'A'),
                ('890483',0,18,'A'),
                ('890783',0,18,'A'),
                ('890489',0,18,'A'),
                ('890369',0,18,'A'),
                ('890420',0,18,'A'),
                ('890452',0,18,'A'),
                ('890479',0,18,'A'),
                ('890485',0,18,'A'),
                ('890781',0,18,'A'),
                ('890272',0,18,'A'),
                ('890285',0,18,'A'),
                ('890372',0,18,'A'),
                ('890377',0,18,'A');";
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refcupsdet');
    }
}
