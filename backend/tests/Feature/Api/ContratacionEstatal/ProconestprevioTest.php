<?php

namespace Tests\Feature\Api\ContratacionEstatal;

use App\Models\ContratacionEstatal\CeEstpreactividade;
use App\Models\ContratacionEstatal\CeEstpreforpago;
use App\Models\ContratacionEstatal\CeEstpregarantia;
use App\Models\ContratacionEstatal\CeGarantia;
use App\Models\ContratacionEstatal\CeProconestprevio;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class ProconestprevioTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
//    public function testStore()
//    {
//
//        $ce_proconestprevio = factory(CeProconestprevio::class)->make()->toArray();
////        dd(factory(CeEstpreforpago::class)->make()->toArray());
//        $ce_proconestprevio['actividades'] = factory(CeEstpreactividade::class, 3)->make()->toArray();
//        $ce_proconestprevio['garantias'] = factory(CeEstpregarantia::class, 3)->make()->toArray();
//        $ce_proconestprevio['forPagos'] = factory(CeEstpreforpago::class, 3)->make()->toArray();
////        dd($ce_proconestprevio);
//        $this->actingAsAdmin()
//            ->post('/api/ce_proconestprevios', $ce_proconestprevio)
////            ->assertJson(['ok'])
//            ->assertStatus(200);
//    }

//    public function testUpdate()
//    {
////        $ce_proconestprevio = factory(CeProconestprevio::class)->make()->toArray();
//        $ce_proconestprevio = CeProconestprevio::where('id', '=', 1)->with('actividades', 'garantias', 'forPagos')->first();
//        $ce_proconestprevio->plazo = 180;
//        $ce_proconestprevio['actividades'] = factory(CeEstpreactividade::class, 1)->make()->toArray();
//        $ce_proconestprevio['garantias'] = factory(CeEstpregarantia::class)->make()->toArray();
//        $ce_proconestprevio['forpagos'] = factory(CeEstpreforpago::class)->make()->toArray();
//        $proconestprevio = $ce_proconestprevio->toArray();
//        $this->actingAsAdmin()
//            ->put('/api/ce_proconestprevios/'.$ce_proconestprevio->id, $proconestprevio)
//            ->assertJson(['ok'])
//            ->assertStatus(201);
////        dd($loco);
//    }
}
