<?php

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsClasecotizante;
use App\Models\Aseguramiento\AsFormafiliacione;
use App\Models\Aseguramiento\AsRegimene;
use App\Models\Aseguramiento\AsTipafiliacione;
use App\Models\Aseguramiento\AsTipafiliado;
use App\Models\RedServicios\RsEntidade;
use Faker\Generator as Faker;


$factory->define(AsFormafiliacione::class, function (Faker $faker) {
    return [
        'as_regimene_id' => AsRegimene::all()->random()->id,
        'as_tipafiliacione_id' => AsTipafiliacione::all()->random()->id,
        'as_tipafiliado_id' => AsTipafiliado::all()->random()->id,
        'as_afiliado_id' => AsAfiliado::where('as_regimene_id',1)->limit(100)->get()->random()->id,
        'as_clasecotizante_id' => AsClasecotizante::all()->random()->id,
        'rs_ips_id' => RsEntidade::all()->random()->id,
        'ibc' => $faker->numerify('######'),
        'ficha_sisben' => $faker->lexify('?????'),
        'puntaje_sisben' => $faker->numerify('##'),
        'gs_usuario_id' => 1,
        'estado' => 'Registrado'
    ];
});
