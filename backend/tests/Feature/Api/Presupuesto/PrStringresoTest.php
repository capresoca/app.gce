<?php

namespace Tests\Feature\Api\Presupuesto;

use App\Models\Presupuesto\PrStringreso;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class PrStringresoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
//    public function testIndex()
//    {
//        $this->actingAsAdmin()
//            ->get('/api/pr_stringresos')
//            ->assertStatus(200);
//    }

    public function testStore()
    {
        $stringreso = factory(PrStringreso::class)->make()->toArray();
        $stringreso['detalles'] = [
            [
                'pr_rubro_id' => 2,
                'pr_tipo_ingreso_id' => 4,
                'valor_inicial' => 400000
            ],
            [
                'pr_rubro_id' => 2,
                'pr_tipo_ingreso_id' => 4,
                'valor_inicial' => 400000
            ]
        ];
//        dd($stringreso);

        $this->actingAsAdmin()
            ->post('/api/pr_stringresos', $stringreso)
            ->assertJson(['ok']);
    }
}
