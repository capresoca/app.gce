<?php

namespace Tests\Feature\Api\Pagos;

use App\Models\Pagos\PgConpago;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class ConpagosTest extends TestCase
{
//    public function testStore()
//    {
//        $conpago = factory(PgConpago::class)->make()->toArray();
//        $this->actingAsAdmin()
//            ->post('/api/pg_conpagos',$conpago)
//            ->assertStatus(201);
//    }
//
//    public function testIndex()
//    {
//        $this->actingAsAdmin()
//            ->get('/api/pg_conpagos')
//            ->assertStatus(200);
//
//    }
//
//    public function testEdit()
//    {
//        $pg_conpago = factory(PgConpago::class)->create();
//        $pg_conpago->codigo = 'ed'. random_int(100,999);
//        $this->actingAsAdmin()
//            ->put('/api/pg_conpagos/'.$pg_conpago->id,$pg_conpago->toArray())
//            ->assertStatus(202);
//    }
}
