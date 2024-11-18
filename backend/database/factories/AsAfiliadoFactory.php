<?php

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAfp;
use App\Models\Aseguramiento\AsArl;
use App\Models\Aseguramiento\AsCcf;
use App\Models\Aseguramiento\AsClasecotizante;
use App\Models\Aseguramiento\AsCondicionDiscapacidade;
use App\Models\Aseguramiento\AsEtnia;
use App\Models\Aseguramiento\AsParentesco;
use App\Models\Aseguramiento\AsPobespeciale;
use App\Models\Aseguramiento\AsRegimene;
use App\Models\Aseguramiento\AsTipafiliado;
use App\Models\General\GnBarrio;
use App\Models\General\GnMunicipio;
use App\Models\General\GnSexo;
use App\Models\General\GnVereda;
use App\Models\RedServicios\RsEntidade;
use Faker\Generator as Faker;
$factory->define(AsAfiliado::class, function (Faker $faker) {
    return [
        'gn_tipdocidentidad_id' => 1,
        'identificacion' => $faker->numerify('########'),
        'fecha_expedicion' => $faker->date(),
        'nombre1' => $faker->firstName(),
        'nombre2' => $faker->firstName(),
        'apellido1' => $faker->lastName(),
        'apellido2' => $faker->lastName(),
        'nombre_completo' => '',
        'direccion' => $faker->address(),
        'telefono_fijo' => $faker->phoneNumber(),
        'celular' => $faker->e164PhoneNumber(),
        'correo_electronico' => $faker->email(),
        'gn_municipio_id' => GnMunicipio::all()->random()->id,
        'as_regimene_id' => AsRegimene::all()->random()->id,
        'as_etnia_id' => AsEtnia::all()->random()->id,
        'cabfamilia_id' => AsAfiliado::max('id'),
        'as_parentesco_id' => AsParentesco::all()->random()->id,
        'ficha_sisben' => $faker->numerify('#######'),
        'puntaje_sisben' => $faker->numerify('##'),
        'nivel_sisben' => $faker->numerify('#'),
        'upc' => $faker->numerify('##'),
        'ibc' => $faker->numerify('#######'),
        'as_pobespeciale_id' => AsPobespeciale::all()->random()->id,
        'as_clasecotizante_id' => AsClasecotizante::all()->random()->id,
        'as_condicion_discapacidades_id' => AsCondicionDiscapacidade::all()->random()->id,
        'as_arl_id' => AsArl::all()->random()->id,
        'as_afp_id' => AsAfp::all()->random()->id,
        'rs_entidade_id' => RsEntidade::all()->random()->id,
        'estado' => 1,
        'as_ccf_id' => AsCcf::all()->random()->id,
        'gn_zona_id' => 1,
        'gn_barrio_id' => GnBarrio::all()->random()->id,
        'gn_vereda_id' => GnVereda::all()->random()->id,
        'fecha_nacimiento' => today()->subYears(19)->toDateString(),
        'gn_sexo_id' => GnSexo::all()->random()->id,
        'as_tipafiliado_id' => AsTipafiliado::all()->random()->id,
        'fecha_afiliacion' => today()->toDateString(),
        'ciudadania' => $faker->randomElement(['Nacional','Extranjero'])
    ];

});



