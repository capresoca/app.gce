<?php

namespace Tests\Feature\Api\Contabilidad;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class NiifTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/niifs')
            ->assertStatus(200);

    }
}
