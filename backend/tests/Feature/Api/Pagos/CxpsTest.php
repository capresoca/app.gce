<?php

namespace Tests\Feature\Api\Pagos;

use App\Models\Pagos\PgCxp;
use App\Models\Pagos\PgCxpdetalle;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class CxpsTest extends TestCase
{
//    public function testStore(){
//        $cxp = factory(PgCxp::class)->make()->toArray();
//        $detalles = [];
//        for ($i=0; $i<4; $i++) { array_push($detalles, factory(PgCxpdetalle::class)->make()->toArray()); }
//        $cxp['detalles'] =  $detalles;
//        $this->actingAsAdmin()
//            ->post('/api/pg_cxps',$cxp)
//            ->assertStatus(201);
//    }
//        $cxp['detalles'] =  factory(PgCxpdetalle::class)->make()->toArray();
//        dd($cxp['detalles']);
//    public function testSearch(){
////            ->assertJson(['ok'])
//        $this->actingAsAdmin()
//            ->get('/api/pg_cxps/search/')
//            ->assertJsonCount(3)
//            ->assertStatus(200);
//
//    }
}
