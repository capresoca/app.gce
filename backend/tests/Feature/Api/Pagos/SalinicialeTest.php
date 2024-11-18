<?php

namespace Tests\Feature\Api\Pagos;


use App\Models\Pagos\PgSaliniciale;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class SalinicialeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $pg_saliniciale = factory(PgSaliniciale::class)->make()->toArray();
        $this->actingAsAdmin()
            ->post('/api/pg_saliniciales',$pg_saliniciale)
            ->assertJson(['ok'])
            ->assertStatus(201);
    }
}
