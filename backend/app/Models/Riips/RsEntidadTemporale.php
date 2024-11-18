<?php

namespace App\Models\Riips;

use App\Models\General\GnMunicipio;
use App\Models\General\GnTipdocidentidade;
use App\Models\Niif\NfCiiu;
use App\Models\RedServicios\RsTipentidade;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class RsEntidadTemporale extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    public function RsTipentidade (){
        return $this->belongsTo(RsTipentidade::class, 'rs_tipentidade_id');
    }
    public function GnTipodocidentidad (){
        return $this->belongsTo(GnTipdocidentidade::class,'gn_tipdocidentidad_id');
    }
    public function GnMunicipioExpedicion (){
        return $this->belongsTo(GnMunicipio::class,'gn_munexpedicion_id');
    }
    public function GnMunicipioResidencia (){
        return $this->belongsTo(GnMunicipio::class,'gn_municipio_id');
    }
    public function GnMunicipioSede (){
        return $this->belongsTo(GnMunicipio::class,'gn_municipiosede_id');
    }
    public function NfCiiu (){
        return $this->belongsTo(NfCiiu::class,'nf_ciiu_id');
    }
}
