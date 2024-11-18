<?php

namespace Tests\Feature\Api\Contabilidad;

use App\Models\Niif\NfTipcomdiario;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class TipcomdiarioTest extends TestCase
{
    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/tipcomdiarios')
            ->assertStatus(200);

    }

    public function testStore()
    {
        $tipcomdiario = factory(NfTipcomdiario::class)->make()->toArray();
        $this->actingAsAdmin()
            ->post('/api/tipcomdiarios',$tipcomdiario)
            ->assertStatus(201);
    }

    public function testEdit()
    {
        $tipcomdiario = factory(NfTipcomdiario::class)->create();
        $tipcomdiario->codigo = 'tc'. random_int(100,999);
        $this->actingAsAdmin()
            ->put('/api/tipcomdiarios/'.$tipcomdiario->id,$tipcomdiario->toArray())
            ->assertStatus(200);
    }
}
