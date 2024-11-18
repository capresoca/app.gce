<?php

namespace Tests\Feature\Api\RedServicios;

use App\Models\RedServicios\RsPlanescobertura;
use App\Models\RedServicios\RsCups;
use App\Models\RedServicios\RsCupsentidade;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class AddCupsContratoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
//    public function testExample()
//    {
//        $cupsentidad = RsCupsentidade::where('rs_entidad_id',36)->whereHas('cup',function ($query){
//            $query->whereNotNull('cm_mansoat_id');
//        })->get();
//
//        $codigos = $cupsentidad->map(function ($item){
//            return $item->id;
//        });
//
//        $grupo = [
//            'rs_salminimo_id'   => 2,
//            'porcentaje'        => -10,
//            'tipo_manual'       => 'SOAT',
//            'cups'              => $codigos
//        ];
//
//
//        $this->actingAsAdmin()
//                ->post('api/contratos/41/add-cup-masivo',$grupo)
//                ->assertJson(['ok']);
//
//
//    }

    public function testAddConTarifasIss()
    {
        $cupsentidad = RsCupsentidade::where('rs_entidad_id',36)->whereHas('cup',function ($query){
            $query->whereNotNull('cm_maniss_id');
        })->get();

        $codigos = $cupsentidad->map(function ($item){
            return $item->id;
        });
        $grupo = [
            'rs_salminimo_id'   => null,
            'porcentaje'        => 10,
            'tipo_manual'       => 'ISS',
            'cups'              => $codigos
        ];


        $this->actingAsAdmin()
            ->post('api/contratos/41/add-cup-masivo',$grupo)
            ->assertJson(['ok']);


    }
}
