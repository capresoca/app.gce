<?php

namespace App\Models\Tesoreria;

use App\Models\Niif\NfTipcomdiario;
use App\Models\Niif\NfNiif;
use App\Models\Tesoreria\TsCaja;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TsParametro extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['maneja_caja','modificar_fecha_documentos','consecutivos_cheques','concecutivos_recivos_caja','mensaje_caja','afecta_presupuesto_doc_tesoreria','dia_aprovechamiento','ts_cajas_id','nf_comdiario_caja_id','nf_comdiario_egreso_id','nf_comdiario_consignacion_id','nf_comdiario_notas_id','nf_comdiario_inversiones_id','nf_comdiario_aprovecha_id','nf_niif_inversiones_id','nf_niif_inversionesing_id','nf_niif_cxp_id','nf_niif_gasto_id','nf_niif_debito_id','nf_niif_credito_id','contabiliacion_tasa','tipo_tasa','tasa'];

    public function cajas(){
        return $this->belongsTo(TsCaja::class, 'ts_cajas_id');
    }
    public function tipocaja(){
      return $this->belongsTo(NfTipcomdiario::class, 'nf_comdiario_caja_id');
    }

    public function tipoegreso(){
      return $this->belongsTo(NfTipcomdiario::class, 'nf_comdiario_egreso_id');
    }

    public function tipoconsignacion(){
      return $this->belongsTo(NfTipcomdiario::class, 'nf_comdiario_consignacion_id');
    }

    public function tiponotas(){
      return $this->belongsTo(NfTipcomdiario::class, 'nf_comdiario_notas_id');
    }

    public function tipoinversiones(){
      return $this->belongsTo(NfTipcomdiario::class, 'nf_comdiario_inversiones_id');
    }

    public function tipoaprovecha(){
      return $this->belongsTo(NfTipcomdiario::class, 'nf_comdiario_aprovecha_id');
    }

    public function niifinversiones(){
      return $this->belongsTo(NfNiif::class, 'nf_niif_inversiones_id');
    }

    public function niifinversionesing(){
      return $this->belongsTo(NfNiif::class, 'nf_niif_inversionesing_id');
    }

    public function niifcxp(){
      return $this->belongsTo(NfNiif::class, 'nf_niif_cxp_id');
    }

    public function niifgasto(){
      return $this->belongsTo(NfNiif::class, 'nf_niif_gasto_id');
    }

    public function niifdebito(){
      return $this->belongsTo(NfNiif::class, 'nf_niif_debito_id');
    }

    public function niifcredito(){
      return $this->belongsTo(NfNiif::class, 'nf_niif_credito_id');
    }


}
