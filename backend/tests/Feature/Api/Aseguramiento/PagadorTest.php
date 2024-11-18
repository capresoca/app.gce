<?php

namespace Tests\Feature\Api\Aseguramiento;


use App\Http\Repositories\Aseguramiento\PagadorRepository;
use App\Models\Aseguramiento\AsPagadore;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class PagadorTest extends TestCase
{

//    public function testSearch()
//    {
//        $this->actingAsAdmin()
//            ->get('api/pagadores/search')
//            ->assertStatus(200);
//    }
//
//    public function testStore()
//    {
//        $pagador = factory(AsPagadore::class)->make()->toArray();
//        $this->actingAsAdmin()
//            ->post('api/pagadores', $pagador)
//            ->assertStatus(201);
//
//    }
//
//    public function testShow()
//    {
//        $pagador = factory(AsPagadore::class)->make()->toArray();
//        $repoPagador = new PagadorRepository();
//        $repoPagador->validar($pagador);
//        $pagadorGuardado = $repoPagador->guardar($pagador);
//        $this->actingAsAdmin()
//            ->get('api/pagadores/13')
//            ->assertStatus(200);
//
//
//    }


}
