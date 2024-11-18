<?php

namespace Tests\Feature\Api\Contabilidad;


use App\Models\Niif\NfNivcuenta;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class NivcuentaTest extends TestCase
{
    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/nivcuentas')
            ->assertStatus(200);

    }

    public function testStore()
    {
        $nivcuenta = factory(NfNivcuenta::class)->make()->toArray();
        $this->actingAsAdmin()
            ->post('/api/nivcuentas',$nivcuenta)
            ->assertStatus(201);
    }

//    public function testEdit()
//    {
//        $nivcuenta = factory(NfNivcuenta::class)->create();
//        $nivcuenta->codigo = 'nc'. random_int(100,999);
//        $this->actingAsAdmin()
//            ->put('/api/nivcuentas/'.$nivcuenta->id,$nivcuenta->toArray())
//            ->assertStatus(200);
//    }
}
