<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use App\Models\Niif\NfNiif;
use App\Models\Niif\NfTipcomdiario;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CrConfiguracione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
      'nf_niif_cxc_id',
      'nf_niif_glosas_id',
      'nf_tipcomdiario_debito_id',
      'nf_tipcomdiario_credito_id',
      'nf_tipcomdiario_traslados_id',
      'nf_tipcomdiario_cxc_id',
      'nf_tipcomdiario_rglosas_id',
      'nf_tipcomdiario_cglosas_id',
      'nf_tipcomdiario_pcartera_id',
      'nf_tipcomdiario_responsabilidades_id',
      'nf_tipcomdiario_cdr_id',
      'nf_tipcomdiario_nmp_id',
      'nf_tipcomdiario_rap_id',
      'edad_inicial',
      'edad_rango1',
      'edad_rango2',
      'edad_rango3',
      'edad_rango4'
    ];
    public function niifcxc(){
      return $this->belongsTo(NfNiif::class,'nf_niif_cxc_id');
    }
    public function niifglosas(){
      return $this->belongsTo(NfNiif::class,'nf_niif_glosas_id');
    }
    public function tipodebito(){
      return $this->belongsTo(NfTipcomdiario::class,'nf_tipcomdiario_debito_id');
    }
    public function tipocredito(){
      return $this->belongsTo(NfTipcomdiario::class,'nf_tipcomdiario_credito_id');
    }
    public function tipotraslados(){
      return $this->belongsTo(NfTipcomdiario::class,'nf_tipcomdiario_traslados_id');
    }
    public function tipocxc(){
      return $this->belongsTo(NfTipcomdiario::class,'nf_tipcomdiario_cxc_id');
    }
    public function tiporglosas(){
      return $this->belongsTo(NfTipcomdiario::class,'nf_tipcomdiario_rglosas_id');
    }
    public function tipocglosas(){
      return $this->belongsTo(NfTipcomdiario::class,'nf_tipcomdiario_cglosas_id');
    }
    public function tipopcartera(){
      return $this->belongsTo(NfTipcomdiario::class,'nf_tipcomdiario_pcartera_id');
    }
    public function tiporesponsabilidades(){
      return $this->belongsTo(NfTipcomdiario::class,'nf_tipcomdiario_responsabilidades_id');
    }
    public function tipocdr(){
      return $this->belongsTo(NfTipcomdiario::class,'nf_tipcomdiario_cdr_id');
    }
    public function tiponmp(){
      return $this->belongsTo(NfTipcomdiario::class,'nf_tipcomdiario_nmp_id');
    }
    public function tiporap(){
      return $this->belongsTo(NfTipcomdiario::class,'nf_tipcomdiario_rap_id');
    }
}
