<?php

namespace Tests\Feature\Api\Contabilidad;


use App\Models\Niif\NfAnedeclaracione;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class AnedeclaraTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/anedeclaraciones')
            ->assertStatus(200);

    }

    public function testStore()
    {
        $anedeclara = factory(NfAnedeclaracione::class)->make()->toArray();
        $this->actingAsAdmin()
            ->post('/api/anedeclaraciones',$anedeclara)
            ->assertStatus(201);
    }

    public function testEdit()
    {
        $anedeclara = factory(NfAnedeclaracione::class)->create();
        $anedeclara->codigo = 'ed'. random_int(100,999);

        $this->actingAsAdmin()
            ->put('/api/anedeclaraciones/'.$anedeclara->id,$anedeclara->toArray())
            ->assertStatus(200);
    }


}
