<?php

namespace Tests\Feature\Api\AtencionUsuario;

use App\Models\AtencionUsuario\AuPqrsd;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class PqrsdTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {

        $pqrd = factory(AuPqrsd::class)->make()->toArray();
        factory(AuPqrsd::class,120)->create();
        $this->actingAsAdmin()
            ->post('/api/pqrsds', $pqrd)
            ->assertJson(['ok'])
            ->assertStatus(201);
    }

    public function testUpdate()
    {
        $prqrsd = AuPqrsd::first();

        $prqrsd->relato = 'EDITADO'.$prqrsd->relato;

        $this->actingAsAdmin()
            ->put('/api/pqrsds/'.$prqrsd->id,$prqrsd->toArray())
            ->assertStatus(202);
    }
}
