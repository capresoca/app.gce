<?php

namespace Tests\Feature\Api;

use App\Models\Reportes\Reporte;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReporteTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {

        $reporte = factory(Reporte::class)->make()->toArray();


        $this->actingAsAdmin()
            ->post('api/reportes/', $reporte)
            ->assertJson(['ok'])
            ->assertStatus(201);


    }
}
