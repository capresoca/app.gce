<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIndexUniqueInAsTipaltoscostosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `as_tipaltocostos` 
                    CHANGE COLUMN `codigo` `codigo` CHAR(2) NULL DEFAULT NULL ,
                    ADD UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC),
                    ADD UNIQUE INDEX `codigo_UNIQUE` (`codigo` ASC)");

        DB::statement("UPDATE `as_tipaltocostos` SET `codigo` = '18', `descripcion` = 'Artritis Reumatoide' WHERE (`id` = '1')");
        DB::statement("UPDATE `as_tipaltocostos` SET `codigo` = '70', `descripcion` = 'Cancer' WHERE (`id` = '2')");
        DB::statement("UPDATE `as_tipaltocostos` SET `codigo` = '60', `descripcion` = 'Infección por VIH' WHERE (`id` = '6')");
        DB::statement("INSERT INTO `as_tipaltocostos` (`codigo`, `nombre`, `descripcion`) VALUES ('50', 'Gran Quemado', 'Gran Quemado')");
        DB::statement("UPDATE `as_tipaltocostos` SET `codigo` = '17', `descripcion` = 'Hemofilia' WHERE (`id` = '5')");
        DB::statement("INSERT INTO `as_tipaltocostos` (`codigo`, `nombre`, `descripcion`) VALUES ('80', 'Trauma Mayor', 'Trauma Mayor')");
        DB::statement("INSERT INTO `as_tipaltocostos` (`codigo`, `nombre`, `descripcion`) VALUES ('40', 'Trasplantes', 'Trasplantes')");
        DB::statement("INSERT INTO `as_tipaltocostos` (`codigo`, `nombre`, `descripcion`) VALUES ('30', 'Patologias Congenitas', 'Patologias Congenitas')");
        DB::statement("INSERT INTO `as_tipaltocostos` (`codigo`, `nombre`, `descripcion`) VALUES ('90', 'Reemplazos Articulares', 'Reemplazos Articulares')");
        DB::statement("INSERT INTO `as_tipaltocostos` (`codigo`, `nombre`, `descripcion`) VALUES ('14', 'Unidad Cuidados Intensivos', 'Unidad Cuidados Intensivos')");
        DB::statement("INSERT INTO `as_tipaltocostos` (`codigo`, `nombre`, `descripcion`) VALUES ('10', 'Enfermedad Cardiovascular', 'Enfermedad Cardiovascular')");
        DB::statement("INSERT INTO `as_tipaltocostos` (`codigo`, `nombre`, `descripcion`) VALUES ('20', 'Sistema Nervioso Central', 'Sistema Nervioso Central')");
        DB::statement("UPDATE `as_tipaltocostos` SET `codigo` = '12', `descripcion` = 'Insuficiencia Renal' WHERE (`id` = '4')");
        DB::statement("UPDATE `as_tipaltocostos` SET `codigo` = '19', `descripcion` = 'Enfermedades Huerfanas' WHERE (`id` = '3')");

        DB::statement("ALTER TABLE `au_anexot31s` 
                            ADD COLUMN `si_copago` INT(11) NULL DEFAULT NULL COMMENT 'NULL = Registrada, 1 = Si Copago, 2 = No Copago' AFTER `gs_usuariovalida_id`");
    }
}
