<?php

namespace Tests\Feature\Api\Pagos;

use App\Models\Pagos\PgProveedore;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;
use Faker\Factory as Faker;

class ProveedoresTest extends TestCase
{
//    public function testStore()
//    {
//        $proveedore = factory(PgProveedore::class)->make()->toArray();
//        $this->actingAsAdmin()
//            ->post('/api/pg_proveedores',$proveedore)
////            ->assertJson(['ok'])
//            ->assertStatus(201);
//    }
//
//    public function testIndex()
//    {
//        $this->actingAsAdmin()
//            ->get('/api/pg_proveedores')
////            ->assertJson(['ok'])
//            ->assertStatus(200);
//
//    }
//
//    public function testEdit()
//    {
//        $faker = Faker::create();
//
//        $pg_proveedore = factory(PgProveedore::class)->create();
//        $pg_proveedore->plazo = $faker->randomNumber(2);
//        $this->actingAsAdmin()
//            ->put('/api/pg_proveedores/'.$pg_proveedore->id,$pg_proveedore->toArray())
////            ->assertJson(['ok'])
//            ->assertStatus(202);
//    }
}
