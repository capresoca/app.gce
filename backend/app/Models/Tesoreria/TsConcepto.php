<?php

namespace App\Models\Tesoreria;

use App\Models\Niif\NfNiif;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TsConcepto extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['codigo','nombre','tipo'];

    public function niif(){
        return $this->belongsTo(NfNiif::class, 'nf_niif_id');
    }
}
