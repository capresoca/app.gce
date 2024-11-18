<?php

namespace App\Models\CuentasMedicas;

use App\Models\Aseguramiento\AsTipaltocosto;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmAltocostoFacservicio extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'cm_altocosto_facservicios';

    protected $fillable = ['cm_factura_id','as_tipaltocosto_id', 'cm_facservicio_id'];

    public function factura()
    {
        return $this->belongsTo(CmFactura::class, 'cm_factura_id');
    }

    public function facservicio()
    {
        return $this->belongsTo(CmFacservicio::class,'cm_facservicio_id');
    }

    public function altoCosto()
    {
        return $this->belongsTo(AsTipaltocosto::class, 'as_tipaltocosto_id');
    }
}
