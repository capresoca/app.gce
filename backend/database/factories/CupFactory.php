<?php

use App\Models\RedServicios\RsCups;
use App\Models\RedServicios\RsCupscategoria;
use Faker\Generator as Faker;

$factory->define(RsCups::class, function (Faker $faker) {
    return [
        'rs_cupscategoria_id' => RsCupscategoria::find(random_int(1,2704))->id,
        'codigo' => RsCups::latest()->first()->codigo + 1,
        'descripcion' => $faker->text,
        'genero' => $faker->randomElement(['Z','M','F']),
        'ambito' => $faker->randomElement(['A','H','U','D','Z']),
        'estancia' => $faker->randomElement(['E','Z']),
        'cobertura' => $faker->randomElement(['PBS','NOPBS','EXCLUSION','RIESGOS LABORALES','SALUD PUBLICA']),
        'duplicado' => $faker->randomElement(['A','D','Z']),
        'vida' => $faker->randomElement(['V','Z']),
        'cie10_relacionados' => 'A00-B99,C00-D48,D50-D89,E00-E90,F00-F99,G00-G99,H00-H59,H60-H95,I00-I99,J00-J99,K00-K93,L00-L99,M00-M99,N00-N99,O00-O99,P00-P96,Q00-Q99,S00-T98,V01-Y98'
    ];
});
