<?php

namespace Tests\Feature\Api\Tesoreria;

use App\Models\Tesoreria\TsCuenta;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class CuentasTest extends TestCase
{
    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/cuentas')
            ->assertJson(['ok'])
            ->assertStatus(200);

    }

    public function testStore()
    {
        $cuenta = factory(TsCuenta::class)->make()->toArray();
        $cuenta['detalles'] = [
            [
              'cheque_inicial' => 1,
              'cheque_final' => 23,
              'estado' => 'Activa'
            ],
            [
                'cheque_inicial' => 24,
                'cheque_final' => 54,
                'estado' => 'Activa'
            ],
        ];
        $this->actingAsAdmin()
            ->post('/api/cuentas',$cuenta)
            ->assertJson(['ok'])
            ->assertStatus(201);
    }

    public function testEdit()
    {
        $cuenta = factory(TsCuenta::class)->create();

        $cuenta['detalles'] = [
            [
                'cheque_inicial' => 1,
                'cheque_final' => 23,
                'estado' => 'Activa'
            ],
            [
                'cheque_inicial' => 24,
                'cheque_final' => 54,
                'estado' => 'Activa'
            ],
        ];

        $cuenta->codigo = 'edi'. random_int(10,99);
        $this->actingAsAdmin()
            ->put('/api/cuentas/'.$cuenta->id,$cuenta->toArray())
            ->assertJson(['ok'])
            ->assertStatus(200);
    }
}
