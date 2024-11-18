<?php

namespace Tests\Feature\Api\Contabilidad;

use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCencosto;
use App\Models\Niif\NfComdiario;
use App\Models\Niif\NfNiif;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class ComdiarioTest extends TestCase
{
    public function testStore(){
        $comdiario = factory(NfComdiario::class)->make()->toArray();
        $comdiario['detalles'] = [
            [
                'nf_niif_id' => NfNiif::where('nf_nivcuenta_id',5)->get()->random()->id,
                'gn_tercero_id' => GnTercero::all()->random()->id,
                'nf_cencosto_id' => NfCencosto::all()->random()->id,
                'debito' => 1900,
                'credito' => 0
            ],
            [
                'nf_niif_id' => NfNiif::where('nf_nivcuenta_id',5)->get()->random()->id,
                'gn_tercero_id' => GnTercero::all()->random()->id,
                'nf_cencosto_id' => NfCencosto::all()->random()->id,
                'debito' => 0,
                'credito' => 1900
            ]
        ];

        $this->actingAsAdmin()
            ->post('/api/comdiarios',$comdiario)
            ->assertStatus(201);
    }

    public function testSearch(){

        $this->actingAsAdmin()
            ->get('/api/comdiarios/search/')
            ->assertJsonCount(3)
            ->assertStatus(200);

    }
}
