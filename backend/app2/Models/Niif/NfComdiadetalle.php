<?php

namespace App\Models\Niif;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class NfComdiadetalle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'nf_comdiario_id',
        'nf_niif_id',
        'gn_tercero_id',
        'nf_cencosto_id',
        'debito',
        'credito',
        'nf_conrtf_id',
        'retencion',
        'observacion'
   ];
//    protected $guarded = ['conrtf','niif', 'tercero', 'cencosto'];

    public function niif(){
        return $this->belongsTo(NfNiif::class,'nf_niif_id');
    }

    public function tercero(){
        return $this->belongsTo(GnTercero::class,'gn_tercero_id');
    }

    public function cencosto(){
        return $this->belongsTo(NfCencosto::class, 'nf_cencosto_id');
    }

    public function conrtf(){
        return $this->belongsTo(NfConrtf::class, 'nf_conrtf_id');
    }

}
