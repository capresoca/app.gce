<?php
namespace App\Models\Enums;

class ETipoBdua extends AEnumerado {
    
    const MS_VAL						= 'N_1';
    const MS_NEG						= 'N_2';
    const MC_VAL						= 'N_3';
    const MC_NEG						= 'N_4';
    const NC_VAL						= 'N_5';
    const NC_NEG						= 'N_6';
    const NS_VAL						= 'N_7';
    const NS_NEG						= 'N_8';
    const R1_VAL						= 'N_9';
    const S1_VAL						= 'N_10';
    const S1_AUTOMATICO 				= 'N_11';
    const R1_AUTOMATICO 				= 'N_12';
    const S3                            = 'N_13';
    const S6                            = 'N_14';
    const S5                            = 'N_15';
    const S2                            = 'N_16';
    const R6                            = 'N_17';
    const R3                            = 'N_18';
    const R5                            = 'N_19';
    const R2                            = 'N_20';
    const S4                            = 'N_21';
    const R4                            = 'N_22';
    
    public static function getValores() {
        return [
            self::MS_VAL 		=> new ETipoBdua(1, 'MS.VAL'),
            self::MS_NEG 		=> new ETipoBdua(2, 'MS.NEG'),
            self::MC_VAL 		=> new ETipoBdua(3, 'MC.VAL'),
            self::MC_NEG		=> new ETipoBdua(4, 'MC.NEG'),
            self::NC_VAL		=> new ETipoBdua(5, 'NC.VAL'),
            self::NC_NEG		=> new ETipoBdua(6, 'NC.NEG'),
            self::NS_VAL		=> new ETipoBdua(7, 'NS.VAL'),
            self::NS_NEG		=> new ETipoBdua(8, 'NS.NEG'),
            self::R1_VAL		=> new ETipoBdua(9, 'R1.VAL'),
            self::S1_VAL            => new ETipoBdua(10,'S1.VAL'),
            self::S1_AUTOMATICO     => new ETipoBdua(11,'AUTOMATICOS-S1'),
            self::R1_AUTOMATICO     => new ETipoBdua(12,'AUTOMATICOS-R1'),
            self::S3                => new ETipoBdua(13,'S3'),
            self::S6                => new ETipoBdua(14,'S6'),
            self::S5                => new ETipoBdua(15,'S5'),
            self::S2                => new ETipoBdua(16,'S2'),
            self::R6                => new ETipoBdua(17,'R6'),
            self::R3                => new ETipoBdua(18,'R3'),
            self::R5                => new ETipoBdua(19,'R5'),
            self::R2                => new ETipoBdua(20,'R2'),
            self::S4                => new ETipoBdua(21,'S4'),
            self::R4                => new ETipoBdua(22,'R4'),
        ];
    }
}
