<?php

namespace App\Models\Tesoreria;

use App\Models\Niif\NfCencosto;
use App\Models\Niif\NfNiif;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class TsCaja extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = ['codigo','nombre','tipo','saldo_inicial','nf_niif_id','nf_cencosto_id', 'estado','fecha_apertura'];

    protected $searchable = ['tipo'];

    public function niif(){
        return $this->belongsTo(NfNiif::class, 'nf_niif_id');
        
    }

    public function cencosto(){
        return $this->belongsTo(NfCencosto::class, 'nf_cencosto_id');
    }
}
