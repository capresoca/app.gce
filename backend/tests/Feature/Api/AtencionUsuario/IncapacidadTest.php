<?php

namespace Tests\Feature\Api\AtencionUsuario;

use App\Models\AtencionUsuario\AuIncapacidade;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class IncapacidadTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $incapacidad = factory(AuIncapacidade::class)->make()->toArray();

        $this->actingAsAdmin()
            ->post('api/incapacidades/', $incapacidad)
            ->assertJson(['ok'])
            ->assertStatus(201);


    }


    public function testEdit()
    {
        $incapacidad = factory(AuIncapacidade::class)->create()->toArray();

        $incapacidad['observaciones'] = $incapacidad['observaciones'].' EDITADO';


        $this->actingAsAdmin()
            ->put('api/incapacidades/'.$incapacidad['id'],$incapacidad)
            ->assertStatus(202);
    }

}
