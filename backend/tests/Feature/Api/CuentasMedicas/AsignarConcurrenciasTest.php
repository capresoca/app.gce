<?php

namespace Tests\Feature\Api\CuentasMedicas;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class AsignarConcurrenciasTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {

        $asignaciones = [
            'pacientes' => [10,11],
            'auditores' => [1,2]
        ];

        $this->actingAsAdmin()
            ->post('/api/asignarConcurrencia',$asignaciones)
            ->assertJson(['ok'])
            ->assertStatus(201);

    }
}
