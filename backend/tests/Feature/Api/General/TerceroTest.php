<?php

namespace Tests\Feature\General;

use Faker\Generator as Faker;

use App\Models\Niif\GnTercero;
use Tests\Feature\Api\TestCase;

class TerceroTestActing extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testListTerceros()
    {
        $this->actingAsAdmin()
            ->get('/api/terceros')
            ->assertStatus(200);
    }


    public function testStoreTercero()
    {
        $terceroAGuardar = factory(GnTercero::class)->make()->toArray();
        $terceroAGuardar['gn_tipdocidentidad_id'] = 1;
        $terceroAGuardar['autorretenedor'] = ['Autorretenedor IVA', 'Autorretenedor ICA'];
        $terceroAGuardar['tipo_tercero'] = ['Afiliado'];
        $terceroAGuardar['direccion'] = 'lal sld';
        $this->actingAsAdmin()
            ->post('/api/terceros', $terceroAGuardar)
            ->assertStatus(201);
    }

    public function testFailTipoDoc()
    {
        $terceroAGuardar = factory(GnTercero::class)->make()->toArray();
        $terceroAGuardar['gn_tipdocidentidad_id'] = 20;
        $terceroAGuardar['fecha_nacimiento'] = '2000-04-20';
        $this->actingAsAdmin()
            ->post('/api/terceros', $terceroAGuardar)
            ->assertStatus(422);
    }

    public function testFailLongitudDoc()
    {
        $terceroAGuardar = factory(GnTercero::class)->make()->toArray();
        $terceroAGuardar['gn_tipdocidentidad_id'] = 1;
        $terceroAGuardar['identificacion'] = '123562365489';
        $terceroAGuardar['fecha_nacimiento'] = '2000-04-20';
        $this->actingAsAdmin()
            ->post('/api/terceros', $terceroAGuardar)
            ->assertStatus(422);

    }


    public function testFailEnumsTipos()
    {

        $terceroAGuardar = factory(GnTercero::class)->make()->toArray();
        //$terceroAGuardar = [];
        $terceroAGuardar['gn_tipdocidentidad_id'] = 1;
        $terceroAGuardar['fecha_nacimiento'] = '1990-04-20';
        $terceroAGuardar['tipo_retencion'] = 'Autorretenedor';
        $terceroAGuardar['tipo_contribuyente'] = 'otra cosa';
        $terceroAGuardar['tipo_persona'] = 'otra cosa';
        $terceroAGuardar['tipo_tercero'] = ['otra cosa'];
        $terceroAGuardar['autorretenedor'] = ['otra cosa', 'otra'];
        $this->actingAsAdmin()
            ->post('/api/terceros', $terceroAGuardar)
            ->assertJsonValidationErrors(['tipo_contribuyente', 'tipo_persona', 'autorretenedor.0', 'tipo_tercero.0'])
            ->assertStatus(422);

    }

    public function testFailAutorretendorSimplificado()
    {
        $terceroAGuardar = factory(GnTercero::class)->make()->toArray();
        $terceroAGuardar['fecha_nacimiento'] = today()->subYears(19)->toDateString();
        $terceroAGuardar['gn_tipdocidentidad_id'] = 1;
        $terceroAGuardar['tipo_contribuyente'] = 'No Responsables de IVA';
        $terceroAGuardar['tipo_retencion'] = 'Autorretenedor';

        $this->actingAsAdmin()
            ->post('/api/terceros', $terceroAGuardar)
            ->assertJsonValidationErrors(['tipo_retencion'])
            ->assertStatus(422);
    }

    public function testFailPorcentajeIca()
    {
        $terceroAGuardar = factory(GnTercero::class)->make()->toArray();
        $terceroAGuardar['fecha_nacimiento'] = today()->subYears(19)->toDateString();
        $terceroAGuardar['gn_tipdocidentidad_id'] = 1;
        $terceroAGuardar['porcentaje_ica'] = null;
        $terceroAGuardar['ica'] = 1;

        $this->actingAsAdmin()
            ->post('/api/terceros', $terceroAGuardar)
            ->assertJsonValidationErrors(['porcentaje_ica'])
            ->assertStatus(422);
    }


    public function testComplementos()
    {
        $complementos = [
            'items' => ['tipdocidentidad', 'zona', 'sexo', 'municipio', 'tipo_retencion', 'tipo_contribuyente', 'tipo_persona']
        ];
        $this->actingAsAdmin()
            ->post('/api/complementos', $complementos)
            ->assertStatus(200);
    }

    public function testEdit()
    {
        $tercero = factory(GnTercero::class)->make();
        $tercero->autorretenedor = ['Autorretenedor IVA', 'Autorretenedor ICA'];
        $tercero->tipo_tercero = ['Afiliado'];
        $tercero->autorretenedor = null;
        $tercero->save();
        $tercero->tipo_tercero = ['Afiliado'];
        $tercero->nombre1 = 'Editado';
        $this->actingAsAdmin()
            ->put('api/terceros/' . $tercero->id, $tercero->toArray())
            ->assertStatus(201);
    }

    public function testSearch()
    {
        $this->actingAsAdmin()
            ->get('api/terceros/search/5/Isma')
            ->assertStatus(200);
    }


}
