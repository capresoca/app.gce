<?php

namespace Tests\Feature\Api\ContratacionEstatal;

use App\Models\ContratacionEstatal\CeDetplanadquisicione;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class DetplanadquisicioneTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
//    public function testStore()
//    {
//        $detalles = factory(CeDetplanadquisicione::class)->make()->toArray();
//        $this->actingAsAdmin()
//            ->post('/api/ce_planadquisiciones/3/detalles', $detalles)
//            ->assertJson(['ok'])
//            ->assertStatus(201);
//    }

    public function testDelete () {
//        $detalles = factory(CeDetplanadquisicione::class)->make()->toArray();
//        $detalle = CeDetplanadquisicione::find('id', 8)->first();
        $this->actingAsAdmin()
            ->delete('/api/ce_planadquisiciones/1/detalles/'. 8)
            ->assertJson(['ok'])
            ->assertStatus(201);
    }
}
