<?php

namespace Tests\Feature\Api\OficinaJuridica;


use App\Models\OficinaJuridica\OjJuzgado;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class JuzgadoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
        public function testStore()
    {
        $oj_juzgado = factory(OjJuzgado::class)->make()->toArray();
        $this->actingAsAdmin()
            ->post('/api/oj_juzgados', $oj_juzgado)
//            ->assertJson(['ok'])
            ->assertStatus(201);
    }
}
