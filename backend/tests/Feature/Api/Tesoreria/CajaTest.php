<?php

namespace Tests\Feature\Api\Tesoreria;


use App\Models\Tesoreria\TsCaja;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class CajaTest extends TestCase
{
    public function testStore()
    {
        $caja = factory(TsCaja::class)->make()->toArray();
        $this->actingAsAdmin()
            ->post('/api/cajas',$caja)
            ->assertStatus(201);
    }

    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/cajas')
            ->assertStatus(200);

    }

    public function testEdit()
    {
        $caja = factory(TsCaja::class)->create();
        $caja->codigo = 'cj'. random_int(100,999);
        $this->actingAsAdmin()
            ->put('/api/cajas/'.$caja->id,$caja->toArray())
            ->assertStatus(200);
    }
}
