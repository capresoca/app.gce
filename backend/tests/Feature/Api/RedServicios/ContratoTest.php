<?php

namespace Tests\Feature\Api\RedServicios;

use App\Models\RedServicios\RsPlanescobertura;
use Tests\Feature\Api\TestCase;

class ContratoTest extends TestCase
{
    public function testStore()
    {
        $contrato = factory(RsPlanescobertura::class)->make()->toArray();

        $this->actingAsAdmin()
            ->post('api/contratos/', $contrato)
            ->assertJson(['ok'])
            ->assertStatus(201);
    }

    public function testEdit()
    {
        $contrato = factory(RsPlanescobertura::class)->create()->toArray();
        $contrato['objeto'] = $contrato['objeto'].' --editado';

        $this->actingAsAdmin()
            ->put('api/contratos/'.$contrato['id'],$contrato)
            ->assertStatus(202);
    }
}
