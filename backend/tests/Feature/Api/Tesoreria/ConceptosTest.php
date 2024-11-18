<?php

namespace Tests\Feature\Api\Tesoreria;

use App\Models\Tesoreria\TsConcepto;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class ConceptosTest extends TestCase
{
    public function testStore()
    {
        $concepto = factory(TsConcepto::class)->make()->toArray();
        $this->actingAsAdmin()
            ->post('/api/ts_conceptos',$concepto)
            ->assertStatus(201);
    }

    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/ts_conceptos')
            ->assertStatus(200);

    }

    public function testEdit()
    {
        $concepto = factory(TsConcepto::class)->create();
        $concepto->codigo = 'cp'. random_int(100,999);
        $this->actingAsAdmin()
            ->put('/api/ts_conceptos/'.$concepto->id,$concepto->toArray())
            ->assertStatus(200);
    }
}
