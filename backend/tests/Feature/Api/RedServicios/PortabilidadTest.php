<?php

namespace Tests\Feature\Api\RedServicios;

use App\Models\RedServicios\RsPortabilidade;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class PortabilidadTest extends TestCase
{

//    public function testStore()
//    {
//        $portabilidad = factory(RsPortabilidade::class)->make()->toArray();
//
//
//        $this->actingAsAdmin()
//            ->post('api/portabilidades/', $portabilidad)
//            ->assertJson(['ok'])
//            ->assertStatus(201);
//    }
//
//    public function testEdit()
//    {
//        $portabilidad = factory(RsPortabilidade::class)->create()->toArray();
//
//        $portabilidad['observaciones'] = $portabilidad['observaciones'].' --editado';
//
//
//        $this->actingAsAdmin()
//            ->put('api/portabilidades/'.$portabilidad['id'],$portabilidad)
//            ->assertStatus(202);
//    }
}
