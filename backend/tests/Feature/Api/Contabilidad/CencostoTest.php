<?php

namespace Tests\Feature\Api\Contabilidad;


use App\Models\Niif\NfCencosto;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class CencostoTest extends TestCase
{
    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/cencostos')
            ->assertStatus(200);

    }

    public function testStore()
    {
        $cencosto = factory(NfCencosto::class)->make()->toArray();
        $this->actingAsAdmin()
            ->post('/api/cencostos',$cencosto)
            ->assertStatus(201);
    }

    public function testEdit()
    {
        $cencosto = factory(NfCencosto::class)->create();
        $cencosto->codigo = 'cc'. random_int(100,999);
        $this->actingAsAdmin()
            ->put('/api/cencostos/'.$cencosto->id,$cencosto->toArray())
            ->assertStatus(200);
    }
}
