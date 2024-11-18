<?php

namespace Tests\Feature\Api\Tesoreria;

use App\Models\Tesoreria\TsBanco;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class BancoTest extends TestCase
{
    public function testStore()
    {
        $banco = factory(TsBanco::class)->make()->toArray();
        $this->actingAsAdmin()
            ->post('/api/bancos',$banco)
            ->assertStatus(201);
    }

    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/bancos')
            ->assertStatus(200);

    }

    public function testEdit()
    {
        $banco = factory(TsBanco::class)->create();
        $banco->codigo = 'bc'. random_int(100,999);
        $this->actingAsAdmin()
            ->put('/api/bancos/'.$banco->id,$banco->toArray())
            ->assertStatus(200);
    }
}
