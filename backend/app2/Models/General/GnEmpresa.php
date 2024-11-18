<?php

namespace App\Models\General;

use App\Models\Niif\GnTercero;
use App\Models\Niif\NfComdiario;
use App\Models\Niif\NfNiif;
use App\Models\Niif\NfTipcomdiario;
use App\Models\TalentoHumano\ThCargosEmpleado;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GnEmpresa extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'codhabilitacion_contrib', 'codhabilitacion_subsid',
        'fecha_inicial', 'firma_certificados',
        'gerente', 'gn_cargo_id', 'gn_municipio_id',
        'gn_tercero_id', 'logo', 'logosimbolo',
        'nf_niifdeficit_id', 'nf_niifganacias_id',
        'nf_niifsuperavit_id', 'nf_tipcomdiacierre_id',
        'nf_tipcomdiadistribucion_id', 'nf_tipcomdiajustes_id',
        'nf_tipcomdiahomologacion_id', 'nf_tipcomdiatraslado_id',
        'nit_dian', 'rep_legal', 'revisor_fiscal',
        'tarjeta_profesional', 'tesoreria_distrital',
        'subgerente_servasistencial', 'subgerente_admin',
        'nf_niifdebitoprovisional_id', 'nf_niifcreditoprovisional_id',
        'nf_niifdebitoprosincontrato_id', 'nf_niifcreditoprosincontrato_id',
        'nf_comdiarioprovisional_id', 'nf_comdiarioprovisionalsincontrato_id',
        'nf_niifdebitoanterior_id', 'nf_niifcreditoanterior_id',
        'nf_niifdebitoanteriorsincontrato_id', 'nf_niifcreditoanteriorsincontrato_id'
    ];
    protected $hidden = ['created_at','updated_at'];

    public function debitoproAnterior () {
        return $this->belongsTo(NfNiif::class,'nf_niifdebitoanterior_id');
    }
    public function creditoproAnterior () {
        return $this->belongsTo(NfNiif::class,'nf_niifcreditoanterior_id');
    }
    public function debitoproAntsincontrato () {
        return $this->belongsTo(NfNiif::class,'nf_niifdebitoanteriorsincontrato_id');
    }
    public function creditoproAntsincontrato () {
        return $this->belongsTo(NfNiif::class,'nf_niifcreditoanteriorsincontrato_id');
    }

    public function subgerServasistencial () {
        return $this->belongsTo(GnTercero::class, 'subgerente_servasistencial');
    }

    public function subgerAdmin () {
        return $this->belongsTo(GnTercero::class, 'subgerente_admin');
    }

    public function representante () {
        return $this->belongsTo(GnTercero::class, 'rep_legal');
    }

    public function dian () {
        return $this->belongsTo(GnTercero::class, 'nit_dian');
    }

    public function tesoreria () {
        return $this->belongsTo(GnTercero::class, 'tesoreria_distrital');
    }

    public function municipio () {
        return $this->belongsTo(GnMunicipio::class, 'gn_municipio_id');
    }

    public function tercero () {
        return $this->belongsTo(GnTercero::class, 'gn_tercero_id');
    }

    public function terGerente () {
        return $this->belongsTo(GnTercero::class, 'gerente');
    }

    public function cargo () {
        return $this->belongsTo(ThCargosEmpleado::class, 'gn_cargo_id');
    }

    public function deficit () {
        return $this->belongsTo(NfNiif::class, 'nf_niifdeficit_id');
    }

    public function ganancias () {
        return $this->belongsTo(NfNiif::class, 'nf_niifganacias_id');
    }

    public function superavit () {
        return $this->belongsTo(NfNiif::class, 'nf_niifsuperavit_id');
    }

    public function comCierre () {
        return $this->belongsTo(NfTipcomdiario::class, 'nf_tipcomdiacierre_id');
    }

    public function comDistribucion () {
        return $this->belongsTo(NfTipcomdiario::class, 'nf_tipcomdiadistribucion_id');
    }

    public function comAjuste () {
        return $this->belongsTo(NfTipcomdiario::class, 'nf_tipcomdiajustes_id');
    }

    public function comHomologacion () {
        return $this->belongsTo(NfTipcomdiario::class, 'nf_tipcomdiahomologacion_id');
    }

    public function comTraslado () {
        return $this->belongsTo(NfTipcomdiario::class, 'nf_tipcomdiatraslado_id');
    }

    public function cuentaDebitoprovisional () {
        return $this->belongsTo(NfNiif::class,'nf_niifdebitoprovisional_id');
    }

    public function cuentaCreditoprovisional () {
        return $this->belongsTo(NfNiif::class,'nf_niifcreditoprovisional_id');
    }

    public function cuentadebitoSincontrato () {
        return $this->belongsTo(NfNiif::class, 'nf_niifdebitoprosincontrato_id');
    }

    public function cuentacreditoSincontrato () {
        return $this->belongsTo(NfNiif::class, 'nf_niifcreditoprosincontrato_id');
    }

    public function comdiarioSincontrato () {
        return $this->belongsTo(NfTipcomdiario::class, 'nf_comdiarioprovisionalsincontrato_id');
    }

    public function comdiarioConcontrato () {
        return $this->belongsTo(NfTipcomdiario::class, 'nf_comdiarioprovisional_id');
    }

    public static function allRelations () {
        return [
            'representante',
            'dian',
            'tesoreria',
            'tercero',
            'terGerente',
            'cargo',
            'deficit',
            'ganancias',
            'superavit',
            'comCierre',
            'comDistribucion',
            'comAjuste',
            'comHomologacion',
            'comTraslado',
            'subgerServasistencial',
            'subgerAdmin',
            'cuentaDebitoprovisional',
            'cuentaCreditoprovisional',
            'cuentadebitoSincontrato',
            'cuentacreditoSincontrato',
            'comdiarioSincontrato',
            'comdiarioConcontrato',
            'debitoproAnterior',
            'creditoproAnterior',
            'debitoproAntsincontrato',
            'creditoproAntsincontrato'
        ];
    }

}
