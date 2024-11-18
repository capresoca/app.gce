<?php

namespace Tests\Feature\Api\Mipres;

use App\Models\Mipres\MpNotificacion;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class NotificacionesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStore()
    {
        $notificacion = factory(MpNotificacion::class)->make()->toArray();


        $response = $this->actingAsAdmin()
            ->post('/api/notificaciones-mipres', $notificacion)
            ->assertJson(['ok'])
            ->assertStatus(201);

    }
}
