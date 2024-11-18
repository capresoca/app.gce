<?php

namespace Tests\Unit\Compensacion;

use App\Capresoca\Compensacion\HistoricoABX;
use App\Capresoca\Compensacion\HistoricoACX;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CargeACXTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        HistoricoABX::run('Historico_ABX.txt');
    }


}
