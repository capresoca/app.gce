<?php

namespace Tests\Feature\Api\Aseguramiento;

use App\Models\Aseguramiento\AsAfiliado;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Api\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AfiliadoTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
//    public function testSuccessStoreAfiliado(){
//
//        DB::beginTransaction();
//        $afiliado = factory(AsAfiliado::class)->make()->toArray();
//        $this->actingAsAdmin()
//            ->post('/api/afiliados',$afiliado)
//            ->assertStatus(201);
//        DB::rollBack();
//    }
//
//
//    public function testFailedRequiredFields()
//    {
//        DB::beginTransaction();
//        $afiliado = factory(AsAfiliado::class)->make()->toArray();
//        $afiliado['fecha_nacimiento'] = today()->subYears(1)->toDateString();
//
//        $this->actingAsAdmin()
//            ->post('/api/afiliados',$afiliado)
//            ->assertJsonValidationErrors(['fecha_nacimiento'])
//            ->assertStatus(422);
//
//        $afiliado['gn_tipdocidentidad_id'] = 14;
//        $afiliado['identificacion'] = '';
//
//        $this->actingAsAdmin()
//            ->post('/api/afiliados',$afiliado)
//            ->assertJsonValidationErrors(['gn_tipdocidentidad_id','identificacion'])
//            ->assertStatus(422);
//
//        $afiliado['fecha_expedicion'] = '3434534';
//        $afiliado['ciudadania'] = 'nlk';
//        $afiliado['correo_electronico'] = 'lskdjf';
//        $afiliado['gn_municipio_id'] = 0;
//        $afiliado['as_parentesco_id'] = 0;
//        $afiliado['as_regimene_id'] = 0;
//        $afiliado['as_etnia_id'] = 0;
//        $afiliado['cabfamilia_id'] = 0;
//        $afiliado['as_pobespeciale_id'] = 0;
//        $afiliado['as_clasecotizante_id'] = 0;
//        $afiliado['as_condicion_discapacidades_id'] = 0;
//        $afiliado['as_arl_id'] = 0;
//        $afiliado['as_afp_id'] = 0;
//        $afiliado['rs_entidade_id'] = 0;
//        $afiliado['estado'] = 0;
//        $afiliado['as_ccf_id'] = 0;
//        $afiliado['gn_zona_id'] = 0;
//        $afiliado['gn_vereda_id'] = 0;
//        $afiliado['gn_zona_id'] = 2;
//        $afiliado['gn_vereda_id'] = null;
//
//        $this->actingAsAdmin()
//            ->post('/api/afiliados',$afiliado)
//            ->assertJsonValidationErrors([
//                'fecha_expedicion',
//                'ciudadania',
//                'gn_municipio_id',
//                'as_parentesco_id',
//                'as_regimene_id',
//                'as_etnia_id',
//                'cabfamilia_id',
//                'as_pobespeciale_id',
//                'as_clasecotizante_id',
//                'as_condicion_discapacidades_id',
//                'as_arl_id',
//                'as_afp_id',
//                'rs_entidade_id',
//                'estado',
//                'as_ccf_id',
//                'gn_vereda_id'
//            ])
//            ->assertStatus(422);
//
//        DB::rollBack();
//
//
//    }
//
//    public function testVeredaRequired()
//    {
//        $afiliado = factory(AsAfiliado::class)->make()->toArray();
//        $afiliado['ficha_sisben'] = '98435l';
//        $afiliado['puntaje_sisben'] = 67;
//        $afiliado['gn_zona_id'] = 2;
//
//        $this->actingAsAdmin()
//            ->post('/api/afiliados',$afiliado)
//            ->assertJsonValidationErrors('gn_vereda_id')
//            ->assertStatus(422);
//    }
//
//    public function testSearchAfiliado()
//    {
//        $this->actingAsAdmin()
//            ->get('api/afiliados/search/10/Odes')
//            ->assertStatus(200);
//    }
//
//    public function testShow()
//    {
//        $this->actingAsAdmin()
//            ->get('api/afiliados/266')
//            ->assertStatus(200);
//    }
//
//    public function testFindByTerceroId()
//    {
//        $this->actingAsAdmin()
//            ->get('api/afiliados/tercero/1313')
//            ->assertStatus(200);
//
//    }
//
//    public function testFailFindByTerceroId()
//    {
//        $this->actingAsAdmin()
//            ->get('api/afiliados/tercero/5000')
//            ->assertStatus(204);
//
//    }
//
//    public function testEdit()
//    {
//        $afiliado = factory(AsAfiliado::class)->create();
//        if(!$afiliado) {
//            self::assertTrue(false, 'No se pudo crear el afiliado');
//        }else{
//            $afiliado->correo_electronico = 'editado@editado.com';
//            $this->actingAsAdmin()
//                ->put('/api/afiliados/'.$afiliado->id,$afiliado->toArray())
//                ->assertStatus(202);
//        }
//    }

}
