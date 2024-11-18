<?php

namespace Tests\Feature\Api\Aseguramiento;

use App\Models\Aseguramiento\AsFormafiliacione;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class FormafiliacionTest extends TestCase
{
//    public function testStore()
//    {
//        $form = factory(AsFormafiliacione::class)->make()->toArray();
//        $this->actingAsAdmin()
//            ->post('/api/formafiliaciones',$form)
//            ->assertJson(['ok'])
//            ->assertStatus(201);
//    }
//
//    public function testAddBeneficiario()
//    {
//        $nucfamiliar = [
//            'as_beneficiario_id' => 1335,
//            'as_parentesco_id' => 8,
//            'rs_entidade_id' => 1,
//            'as_formafiliacion_id' => 1
//        ];
//
//        $this->actingAsAdmin()
//            ->post('/api/formafiliaciones/1/beneficiarios',$nucfamiliar)
//            ->assertJson(['ok'])
//            ->assertStatus(201);
//
//    }
//
//    public function testRadicar()
//    {
//        $form = factory(AsFormafiliacione::class)->create();
//        $form->estado = 'Radicado';
//        $form->fecha_radicacion = '2018-09-24';
//        $this->actingAsAdmin()
//            ->put('/api/formafiliaciones/1',$form->toArray())
//            ->assertStatus(202);
//    }
//
//
//    public function testAnularExitoso()
//    {
//        $form = factory(AsFormafiliacione::class)->create();
//        $form->estado = 'Anulado';
//        $form->fecha_anulacion = '2018-09-24';
//        $form->usuanula_id = 1;
//        $this->actingAsAdmin()
//            ->put('/api/formafiliaciones/1',$form->toArray())
//            ->assertStatus(202);
//    }

//    public function testIndex()
//    {
//        $this->actingAsAdmin()
//            ->get('/api/formafiliaciones')
//            ->assertJson(['ok'])
//            ->assertStatus(200);
//
//    }
//
//    public function testEdit()
//    {
//        $form = factory(AsFormafiliacione::class)->create();
//        $form->estado = 'Radicado';
//        $this->actingAsAdmin()
//            ->put('/api/formafiliaciones/'.$form->id,$form->toArray())
//            ->assertJson(['ok'])
//            ->assertStatus(200);
//    }
}
