<?php

namespace Tests\Feature\Api\Aseguramiento;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsClasecotizante;
use App\Models\Aseguramiento\AsCondicionDiscapacidade;
use App\Models\Aseguramiento\AsCondterminacione;
use App\Models\Aseguramiento\AsEstadoAfiliado;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\Aseguramiento\AsParentesco;
use App\Models\Aseguramiento\AsPobespeciale;
use App\Models\Aseguramiento\AsTipafiliado;
use App\Models\General\GnMunicipio;
use App\Models\General\GnSexo;
use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCiiu;
use App\Models\RedServicios\RsEntidade;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class NovtramiteTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
//    public function testValidacionN01()
//    {
//        $novtramite = factory(AsNovtramite::class)->make()->toArray();
//
//        $novtramite['as_tipnovedade_id'] = 19;
//        $novtramite['v1'] = 1;
//        $novtramite['v2'] = '123456612';
//        $novtramite['v3'] = '2000-01-05';
//        $novtramite['v4'] = '1';
//        $this->actingAsAdmin()
//            ->post('/api/novtramites',$novtramite)
//            ->assertStatus(201);
//
//        $novtramite['v1'] = 8;
//        $novtramite['v2'] = '1115910557';
//        $novtramite['v3'] = '2000-01-0';
//        $novtramite['v4'] = '18';
//
//        $this->actingAsAdmin()
//            ->post('/api/novtramites',$novtramite)
//            ->assertJsonValidationErrors(['v2','v3','v1','v4'])
//            ->assertStatus(422);
//
//    }
//
//    public function testValidacionN02()
//    {
//        $novtramite = factory(AsNovtramite::class)->make()->toArray();
//
//        $faker = Faker::create();
//
//
//        $novtramite['as_tipnovedade_id'] = 20;
//        $novtramite['v1'] = "$faker->firstName";
//        $novtramite['v2'] = "$faker->firstName $faker->firstName";
//
//
//        $this->actingAsAdmin()
//            ->post('/api/novtramites',$novtramite)
//            ->assertStatus(201);
//
//        $novtramite['v2'] = "$faker->firstName $faker->firstName $faker->firstName $faker->firstName $faker->firstName $faker->firstName";
//        $novtramite['v1'] = "$faker->firstName $faker->firstName";
//
//        $this->actingAsAdmin()
//            ->post('/api/novtramites',$novtramite)
//            ->assertJsonValidationErrors(['v2','v1'])
//            ->assertStatus(422);
//
//    }
//
//    public function testValidacionN03()
//    {
//        $novtramite = factory(AsNovtramite::class)->make()->toArray();
//
//        $faker = Faker::create();
//
//
//        $novtramite['as_tipnovedade_id'] = 21;
//        $novtramite['v1'] = "$faker->lastName";
//        $novtramite['v2'] = "$faker->lastName $faker->lastName";
//
//
//        $this->actingAsAdmin()
//            ->post('/api/novtramites',$novtramite)
//            ->assertStatus(201);
//
//        $novtramite['v1'] = "$faker->lastName $faker->lastName";
//        $novtramite['v2'] = "$faker->lastName $faker->lastName $faker->lastName $faker->lastName $faker->lastName $faker->lastName";
//
//        $this->actingAsAdmin()
//            ->post('/api/novtramites',$novtramite)
//            ->assertJsonValidationErrors(['v2','v1'])
//            ->assertStatus(422);
//
//    }
//
//    public function testValidacionN05()
//    {
//        $novtramite = factory(AsNovtramite::class)->make()->toArray();
//
//        $novtramite['as_tipnovedade_id'] = 23;
//
//        $this->actingAsAdmin()
//            ->post('/api/novtramites',$novtramite)
//            ->assertStatus(422);
//
//
//        $novtramite['as_afiliado_id'] = 248;
//
//
//        $this->actingAsAdmin()
//            ->post('/api/novtramites',$novtramite)
//            ->assertStatus(201);
//
//    }
//
//
//    public function testValidacionN06()
//    {
//        $novtramite = factory(AsNovtramite::class)->make()->toArray();
//
//        $novtramite['as_tipnovedade_id'] = 24;
//        $novtramite['as_afiliado_id'] = AsAfiliado::where('as_regimene_id',1)->where('as_tipafiliado_id',1)->get()->random()->id;
//
//        $novtramite['v1'] = AsClasecotizante::all()->random()->id;
//        $novtramite['v2'] = NfCiiu::all()->random()->id;
//        $novtramite['v3'] = AsPagadore::all()->random()->id;
//
//        $this->actingAsAdmin()
//            ->post('/api/novtramites',$novtramite)
//            ->assertStatus(201);
//
//        $novtramite['as_afiliado_id'] = 248;
//        $novtramite['v1'] = '3';
//
//        $this->actingAsAdmin()
//            ->post('/api/novtramites',$novtramite)
//            ->assertStatus(201);
//
//
//        $novtramite['as_afiliado_id'] = 250;
//        $novtramite['v1'] = '100';
//        $novtramite['v3'] = 1000;
//        $novtramite['v2'] = '120000';
//        $this->actingAsAdmin()
//            ->post('/api/novtramites',$novtramite)
//            ->assertStatus(422);
//    }
    public function testValidacionN07()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();

        $novtramite['as_tipnovedade_id'] = 25;
        $novtramite['as_afiliado_id'] = AsAfiliado::where('cabfamilia_id',null)
                                                    ->whereBetween('id',[577000,577500])->get()->random()->id;

        $novtramite['v1'] = AsParentesco::all()->random()->id;
        $novtramite['v2'] = AsCondicionDiscapacidade::all()->random()->id;
        $novtramite['v3'] = AsAfiliado::find(random_int(577000,577500))->id;
        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertStatus(201);

        $novtramite['as_afiliado_id'] = 250;
        $novtramite['v1'] = 5000;
        $novtramite['v2'] = 'XC';
        $novtramite['v3'] = 'XX';
        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertJsonValidationErrors(['v1','v2','v3'])
            ->assertStatus(422);


    }

    public function testValidacionN08()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();

        $novtramite['as_tipnovedade_id'] = 26;
        $novtramite['as_afiliado_id'] = AsAfiliado::where('cabfamilia_id','<>',null)->where('as_tipafiliado_id', '<>',1)->get()->random()->id;

        $novtramite['v1'] = AsClasecotizante::all()->random()->id;
        $novtramite['v3'] = AsPagadore::all()->random()->id;
        $novtramite['v2'] = NfCiiu::all()->random()->id;

        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertStatus(201);

        $novtramite['as_afiliado_id'] = 250;
        $novtramite['v1'] = '879';
        $novtramite['v3'] = 5000;
        $novtramite['v2'] = 'lkjasdf';
        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertStatus(422);

    }


    public function testValidacionN10()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $faker = Faker::create();
        $afiliado = AsAfiliado::where('as_tipafiliado_id',1)->where('as_clasecotizante_id','<>',null)->get()->random();
        $novtramite['as_tipnovedade_id'] = 28;
        $novtramite['as_afiliado_id'] = $afiliado->id;

        $novtramite['v1'] = AsClasecotizante::all()->random()->id;
        $novtramite['v2'] = NfCiiu::all()->random()->id;
        $novtramite['v3'] = AsPagadore::all()->random()->id;
        $novtramite['v4'] = $faker->date();

        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertStatus(201);
        $novtramite['as_afiliado_id'] = 260;
        $novtramite['v1'] = 400;
        $novtramite['v2'] = '879 934';
        $novtramite['v3'] = 129830;
        $novtramite['v4'] = 98743593;
        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertJsonValidationErrors(['v2','v3','v1','v4'])
            ->assertStatus(422);

    }

    public function testValidacionN11()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $afiliado = AsAfiliado::where('as_tipafiliado_id',1)->where('as_regimene_id',1)->get()->random();
        $novtramite['as_tipnovedade_id'] = 29;
        $novtramite['as_afiliado_id'] = $afiliado->id;

        $novtramite['v1'] = AsPagadore::all()->random()->id;
        $novtramite['v2'] = AsClasecotizante::all()->random()->id;

        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertStatus(201);

        $novtramite['as_afiliado_id'] = 6546546;
        $novtramite['v1'] = 4065460;
        $novtramite['v2'] = 1897345;
        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertJsonValidationErrors(['as_afiliado_id'])
            ->assertStatus(422);


    }

    public function testValidacionN12()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $novtramite['as_tipnovedade_id'] = 30;

        $novtramite['v1'] = AsCondicionDiscapacidade::all()->random()->id;
        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertStatus(201);

        $novtramite['as_afiliado_id'] = 260;
        $novtramite['v1'] = 400;
        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertJsonValidationErrors(['v1'])
            ->assertStatus(422);


    }

    public function testValidacionN13()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $novtramite['as_tipnovedade_id'] = 31;
        $afiliado = AsAfiliado::find(random_int(577000,577500));

        $novtramite['as_afiliado_id'] = $afiliado->id;

        $novtramite['v1'] = AsCondterminacione::all()->random()->id;
        $novtramite['v2'] = '2018-08-08';

        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertStatus(201);

        $novtramite['as_afiliado_id'] = 260;
        $novtramite['v1'] = 400;
        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertStatus(422);
    }

    public function testValidacionN14()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $novtramite['as_tipnovedade_id'] = 32;

        $novtramite['as_afiliado_id'] = 280;

        $novtramite['v1'] = 4;
        $novtramite['v2'] = 6;

        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertStatus(201);

        //Prueba de validación fallida para estado AF Novedad 14
        $novtramite['v1'] = 7;
        $novtramite['v2'] = 5;
        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertJsonValidationErrors(['v1'])
            ->assertStatus(422);

        //Prueba de validación fallida Contributivo pasar a estado Suspendido.

        $novtramite['v1'] = 9;
        $novtramite['as_afiliado_id'] = 300;
        $novtramite['v2'] = 8;

        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertJsonValidationErrors(['v1'])
            ->assertStatus(422);

    }

//    public function testValidacionN17()
//    {
//        $novtramite = factory(AsNovtramite::class)->make()->toArray();
//        $novtramite['as_tipnovedade_id'] = 35;
//
//        $this->probar_guardar($novtramite);
//
//    }

//    public function testValidacionN19()
//    {
//        $novtramite = factory(AsNovtramite::class)->make()->toArray();
//        $novtramite['as_tipnovedade_id'] = 36;
//
//        $this->probar_guardar($novtramite);
//
//    }

    public function testValidacionN20()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $novtramite['as_tipnovedade_id'] = 37;
        $novtramite['as_afiliado_id'] = AsAfiliado::find(random_int(577000,577500))->id;

        $novtramite['v1'] = 1;
        $novtramite['v2'] = 26;

        $this->probar_guardar($novtramite);

    }

    public function testValidacionN21()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $novtramite['as_tipnovedade_id'] = 38;
        $novtramite['as_afiliado_id'] = AsAfiliado::find(random_int(577000,577500))->id;

        $novtramite['v1'] = AsPobespeciale::all()->random()->id;

        $this->probar_guardar($novtramite);
    }

    public function testValidacionN25()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $novtramite['as_tipnovedade_id'] = 40;

        $novtramite['v1'] = RsEntidade::all()->random()->id;

        $this->probar_guardar($novtramite);
    }


    public function testValidacionN31()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $novtramite['as_tipnovedade_id'] = 41;
        $novtramite['as_afiliado_id'] = AsAfiliado::find(random_int(577000,577500))->id;
        $novtramite['v1'] = AsPobespeciale::all()->random()->id;
        $novtramite['v2'] = 1;
        $novtramite['v3'] = 28;

        $this->probar_guardar($novtramite);
    }

    public function testValidacionN32()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $novtramite['as_tipnovedade_id'] = 42;

        $novtramite['v1'] = AsAfiliado::find(random_int(577000,577500))->id;
        $novtramite['v2'] = AsParentesco::all()->random()->id;
        $novtramite['v3'] = AsCondicionDiscapacidade::all()->random()->id;


        $this->probar_guardar($novtramite);
    }

    public function testValidacionN33()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $novtramite['as_tipnovedade_id'] = 43;

        $novtramite['v1'] = '2018-04-04';


        $this->probar_guardar($novtramite);
    }

    public function testValidacionN35()
    {
        $novtramite = factory(AsNovtramite::class)->make()->toArray();
        $novtramite['as_tipnovedade_id'] = 44;

        $novtramite['v1'] = '2018-08-20';

        $this->probar_guardar($novtramite);

    }

    protected function probar_guardar($novtramite){
        $this->actingAsAdmin()
            ->post('/api/novtramites',$novtramite)
            ->assertStatus(201);
    }

}
