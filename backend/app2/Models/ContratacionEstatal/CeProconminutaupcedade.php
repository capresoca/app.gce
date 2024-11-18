<?php

namespace App\Models\ContratacionEstatal;

use App\Models\RedServicios\RsRanupc;
use Illuminate\Database\Eloquent\Model;

class CeProconminutaupcedade extends Model
{
    protected $fillable = ['ce_proconminutageo_id', 'rs_ranupc_id', 'proporcion', 'valor'];
    protected $hidden = ['updated_at', 'created_at'];

    public function proconminutageo()
    {
        return $this->belongsTo(CeProconminutageo::class);
    }

    public function rangoupc()
    {
        return $this->belongsTo(RsRanupc::class, 'rs_ranupc_id');
    }
}
