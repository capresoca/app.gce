<?php

namespace Tests\Feature\Api\Contabilidad;

use App\Models\Niif\NfClascuenta;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class ClascuentaTest extends TestCase
{
    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/clascuentas')
            ->assertStatus(200);

    }

    public function testStore()
    {
        $clascuenta = factory(NfClascuenta::class)->make()->toArray();
        $this->actingAsAdmin()
            ->post('/api/clascuentas',$clascuenta)
            ->assertStatus(200);
    }

    public function testEdit()
    {
        $clascuenta = factory(NfClascuenta::class)->create();

        $clascuenta->naturaleza = 'Crédito';

        $this->actingAsAdmin()
            ->put('/api/clascuentas/'.$clascuenta->id,$clascuenta->toArray())
            ->assertStatus(200);
    }
}
