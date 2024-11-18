<?php

namespace Tests\Feature\Api\Autorizaciones;

use App\Models\Autorizaciones\AuAutdetalle;
use App\Models\Autorizaciones\AuAutorizacione;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class AutorizacionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $autorizacion = factory(AuAutorizacione::class)->make()->toArray();


        $this->actingAsAdmin()
            ->post('api/autorizaciones/', $autorizacion)
            ->assertStatus(201);
    }

    public function testUpdate()
    {
        $autorizacion = AuAutorizacione::with('detalles')->first()->toArray();

        $autorizacion['estado'] = 'Confirmada';

        $this->actingAsAdmin()
            ->put('api/autorizaciones/'.$autorizacion['id'], $autorizacion)
            ->assertStatus(202);
    }

    public function testFailValidation()
    {
        $autorizacion = factory(AuAutorizacione::class)->make()->toArray();

        $autorizacion['as_afiliado_id'] = '';


        $this->actingAsAdmin()
            ->post('api/autorizaciones/',$autorizacion)
            ->assertJsonValidationErrors('as_afiliado_id')
            ->assertStatus(422);

    }
}
