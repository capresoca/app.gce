<?php

namespace App\Models\Tesoreria;

use App\Models\General\GnMunicipio;
use App\Models\Niif\NfCencosto;
use App\Models\Niif\NfNiif;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TsCuenta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'ts_banco_id',
        'codigo',
        'tipo_cuenta',
        'estado',
        'numero_cuenta',
        'fecha_apertura',
        'saldo_inicial',
        'control_auto',
        'nf_niif_id',
        'nf_cencosto_id',
        'gn_municipio_id'
    ];

    public function niif()
    {
        return $this->belongsTo(NfNiif::class, 'nf_niif_id');
    }

    public function cencosto()
    {
        return $this->belongsTo(NfCencosto::class, 'nf_cencosto_id');
    }

    public function municipio()
    {
        return $this->belongsTo(GnMunicipio::class, 'gn_municipio_id');
    }

    public function banco()
    {
        return $this->belongsTo(TsBanco::class, 'ts_banco_id');
    }

    public function detalles()
    {
        return $this->hasMany(TsCuedetalle::class, 'ts_cuenta_id');
    }
}


