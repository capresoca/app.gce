<?php

namespace Tests\Feature\Api\RedServicios;

use App\Models\RedServicios\RsPlanescobertura;
use App\Models\RedServicios\RsNovcontrato;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class NovcontratoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $novcontrato = factory(RsNovcontrato::class)->make()->toArray();
        $contrato_id = RsPlanescobertura::all()->random()->id;

        $this->actingAsAdmin()
            ->post('api/contratos/'.$contrato_id.'/novcontratos', $novcontrato)
            ->assertStatus(201);
    }

    public function testEdit()
    {

        $novcontrato = RsNovcontrato::first();

        $novcontrato = $novcontrato->toArray();


        $this->actingAsAdmin()
            ->put('api/contratos/'.$novcontrato['rs_contrato_id'].'/novcontratos/'.$novcontrato['id'],  $novcontrato)
            ->assertStatus(202);
    }
}
