<?php

namespace App\Models\Pagos;

use App\Models\Niif\NfNiif;
use App\Models\Niif\NfTipcomdiario;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class PgConfiguracione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
//    protected $guarded = [];
    protected $fillable = [
        'nf_niifaprovechamiento_id',
        'nf_niifdescuento_id',
        'nf_tipcomcxp_id',
        'nf_tipcomnotadebito_id',
        'nf_tipcomtraslado_id',
        'nf_tipcomnotacredito_id'
    ];

    public function tipcomp_ndebito(){
        return $this->belongsTo(NfTipcomdiario::class, 'nf_tipcomnotadebito_id');
    }

    public function tipcomp_ncredito(){
        return $this->belongsTo(NfTipcomdiario::class, 'nf_tipcomnotacredito_id');
    }

    public function tipcomp_traslado(){
        return $this->belongsTo(NfTipcomdiario::class, 'nf_tipcomtraslado_id');
    }

    public function tipcomp_cxp(){
        return $this->belongsTo(NfTipcomdiario::class, 'nf_tipcomcxp_id');
    }

    public function niif_descuento(){
        return $this->belongsTo(NfNiif::class, 'nf_niifdescuento_id');
    }

    public function niif_aprovechamiento(){
        return $this->belongsTo(NfNiif::class, 'nf_niifaprovechamiento_id');
    }

    public function rangos(){
        return $this->hasMany(PgRango::class, 'pg_configuracione_id');
    }
}
