<?php

namespace Tests\Feature\Api\Tesoreria;

use App\Models\Niif\GnTercero;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class CompegresoTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStore()
    {
        $compegresoCaja = [
            'fecha' => $this->faker->date(),
            'gn_tercero_id' => GnTercero::first()->id,
            'forma_pago' => 'Efectivo',
            'ts_caja_id' => 1,
            'nf_cencosto_id' => null,
            'descripcion' => 'Comprobante de prueba',
            'rs_planescobertura_id' => null,
            'anticipo' => 0,
            'conceptos' => [
                [
                    'ts_concepto_egreso_id' => 1,
                    'detalles' => [
                        [
                            'pg_cxp_id' => 1,
                            'valor' => 2000
                        ]
                    ]
                ]
            ]
        ];

        $this->actingAsAdmin()
            ->post('/api/compegresos',$compegresoCaja)
            ->assertStatus(201);
    }
}
