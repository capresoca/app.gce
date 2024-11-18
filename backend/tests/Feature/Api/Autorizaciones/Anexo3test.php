<?php

namespace Tests\Feature\Api\Autorizaciones;

use App\Models\Autorizaciones\AuAnexot3;
use App\Models\Autorizaciones\AuAnexot31;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class Anexo3test extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStore()
    {
        $anexo3 = factory(AuAnexot3::class)->make()->toArray();
        $anexo3['detalles'] = factory(AuAnexot31::class,4)->make()->toArray();

        $this->actingAsAdmin()
            ->post('api/autorizaciones/anexo3', $anexo3)
            ->assertJson(['ok']);

    }
}
