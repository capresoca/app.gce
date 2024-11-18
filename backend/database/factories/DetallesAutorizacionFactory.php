<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(\App\Models\Autorizaciones\AuAutdetalle::class, function (Faker $faker) {
    $cum =  \App\Models\RedServicios\RsCumcontratados::orderBy(DB::raw('RAND()'))->limit(1)->first();
    $cantidad_solicitada = random_int(1,12);
    return [
        'codigo' => $cum->codigo,
        'descripcion' => $cum->descripcion,
        'cantidad_solicitada' => $cantidad_solicitada,
        'cantidad_aprobada' => $cantidad_solicitada - (random_int(0,$cantidad_solicitada-1)),
        'valor' => $cum->tarifa,
        'valor_usuario' => $cum->tarifa * 0.1,
        'tipo_valor' => 'Copago',
        'observaciones' => $faker->text,
        'rs_cum_id' => $cum->id
    ];
});

//CREATE TABLE dummy (
//    `au_autsolicitud_id` INT(11) NULL DEFAULT NULL,
//	`au_autaprobada_id` INT(11) NULL DEFAULT NULL,
//	`au_autorizacion_id` INT(11) NULL DEFAULT NULL,
//	`codigo` VARCHAR(15) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
//	`descricion` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
//	`cantidad_solicitada` DOUBLE NULL DEFAULT NULL,
//	`cantidad_aprobada` DOUBLE NULL DEFAULT NULL,
//	`valor` DOUBLE NULL DEFAULT NULL,
//	`valor_usuario` DOUBLE NULL DEFAULT NULL,
//	`tipo_valor` ENUM('Copago','Cuota Moderadora') NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
//	`observaciones` LONGTEXT NULL COLLATE 'utf8mb4_unicode_ci',
//	`rs_cups_id` INT(11) NOT NULL,
//	`rs_cum_id` INT(11) NOT NULL
//)