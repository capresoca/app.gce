<?php

use App\Models\RedServicios\RsEntidade;
use App\Seguridad\GsRole;
use App\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'identification' => $faker->numerify('##########'),
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'rs_entidad_id' => RsEntidade::all()->random()->id,
        'tipo' => $faker->randomElement(['Funcionario', 'Entidad', 'Afiliado', 'Pagador']),
        'state' => 'Activo',
        'phone' => $faker->phoneNumber(),
        'roles' => GsRole::all()
    ];
});
