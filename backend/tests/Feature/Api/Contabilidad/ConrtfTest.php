<?php

namespace Tests\Feature\Api\Contabilidad;

use App\Models\Niif\NfConrtf;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class ConrtfTest extends TestCase
{
    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/conrtfs')
            ->assertStatus(200);

    }

    public function testStoreSuccess()
    {
        $conrtf = factory(NfConrtf::class)->make()->toArray();
        $conrtf['manejo'] = 'Rangos';
        $conrtf['detalles'] = [
            [
                'valor_inicial' => 0,
                'valor_final' => 500,
                'porcentaje' => 4,
                'deducido' => 1900,
                'incremento' => 122
            ],
            [
                'valor_inicial' => 501,
                'valor_final' => 540,
                'porcentaje' => 6,
                'deducido' => 77,
            ]
        ];

        $this->actingAsAdmin()
            ->post('/api/conrtfs',$conrtf)
            ->assertStatus(201);
    }

    public function testStoreFail()
    {
        $conrtf = factory(NfConrtf::class)->make()->toArray();
        $conrtf['manejo'] = 'Rangos';
        $conrtf['detalles'] = [
            [
                'valor_inicial' => 0,
                'valor_final' => 500,
                'porcentaje' => 4,
                'deducido' => 1900,
                'incremento' => 122
            ],
            [
                'valor_inicial' => 490,
                'valor_final' => 540,
                'porcentaje' => 6,
                'deducido' => 77,
            ]
        ];

        $this->actingAsAdmin()
            ->post('/api/conrtfs',$conrtf)
            ->assertJsonValidationErrors(['detalles'])
            ->assertStatus(422);
    }

//    public function testEdit()
//    {
//        $conrtf = NfConrtf::findOrFail(32);
//
//        $conrtf->nombre = 'Concepto editado';
//
//        $conrtf->detalles;
//
//
//        $this->actingAsAdmin()
//            ->post('/api/conrtfs/', $conrtf->toArray())
//            ->assertStatus(200);
//    }

    public function testShow()
    {
        $this->actingAsAdmin()
            ->get('/api/conrtfs/' . 32)
            ->assertStatus(200);
    }
}
