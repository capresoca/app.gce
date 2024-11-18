<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataInCmConcondclinicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\CuentasMedicas\CmConcondclinica::insert([
            ['nombre' => 'Estado infeccioso-sépsis'],
            ['nombre' => 'Disfunción respiratoria'],
            ['nombre' => 'Disfunción metabólica'],
            ['nombre' => 'Disfunción inmunológica'],
            ['nombre' => 'Disfunción vascular'],
            ['nombre' => 'Disfunción cardiaca'],
            ['nombre' => 'Disfunción renal'],
            ['nombre' => 'Disfunción hepática'],
            ['nombre' => 'Disfunción nuerológica'],
            ['nombre' => 'Inestabilidad hemodinámica'],
            ['nombre' => 'Inestabilidad osteomuscular'],
            ['nombre' => 'Inestabilidad mental'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
