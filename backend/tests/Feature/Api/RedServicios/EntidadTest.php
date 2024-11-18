<?php

namespace Tests\Feature\Api\RedServicios;

use App\Models\RedServicios\RsEntidade;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class EntidadTest extends TestCase
{

//    public function testIndex()
//    {
//        $this->actingAsAdmin()
//            ->get('/api/entidades')
//            ->assertStatus(200);
//
//    }
//
//    public function testSearch()
//    {
//        $this->actingAsAdmin()
//            ->get('/api/entidades/'.'IPS'.'/search/')
//            ->assertJsonCount(3)
//            ->assertStatus(200);
//
//    }
//
//    public function testStore()
//    {
//        $entidad = factory(RsEntidade::class)->make()->toArray();
//
//
//        $this->actingAsAdmin()
//            ->post('api/entidades', $entidad)
//            ->assertJson(['ok'])
//            ->assertStatus(201);
//
//    }
//
//    public function testStoreIDs()
//    {
//        $entidad = factory(RsEntidade::class)->make()->toArray();
//        $entidad['gn_tercero_id'] = 5;
//        $entidad['gerente_id'] = 6;
//        $entidad['replegal_id'] = 7;
//        $this->actingAsAdmin()
//            ->post('api/entidades', $entidad)
//            ->assertStatus(201);
//    }
//
//    public function testEdit()
//    {
//        $entidad = [];
//        $entidad['id'] = 1;
//        $entidad['tipo'] = 'IPS';
//        $entidad['cod_habilitacion'] = 'Edit123';
//        $entidad['gn_tercero_id'] = 5;
//        $entidad['gerente_id'] = 6;
//        $entidad['replegal_id'] = 7;
//        $this->actingAsAdmin()
//            ->post('api/entidades', $entidad)
//            ->assertStatus(201);
//    }

}
