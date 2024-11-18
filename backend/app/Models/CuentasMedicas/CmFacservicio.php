<?php

namespace App\Models\CuentasMedicas;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Autorizaciones\AuAutorizacione;
use App\Models\RedServicios\RsCum;
use App\Models\RedServicios\RsCups;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CmFacservicio extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'ambito',
        'as_afiliado_id',
        'au_autorizacion_id',
        'cantidad',
        'causa_externa',
        'cie10_relacionado1',
        'cie10_principal',
        'cie10_relacionado2',
        'cie10_relacionado3',
        'cm_factura_id',
        'codigo',
        'copago',
        'documento',
        'fecha_servicio',
        'finalidad_consulta',
        'forma_cirugia',
        'nombre',
        'personal_atiende',
        'rs_cum_id',
        'rs_cups_id',
        'tipo',
        'tipo_diagnostico',
        'tipo_documento',
        'unidad_medida',
        'valor_unitario',
        'avalado',
        'recobro',
        'capita'
    ];

    protected $appends = ['valor_servicio_glosado','fac_paciente'];

    public function altosCostos () {
        return $this->hasMany(CmAltocostoFacservicio::class,'cm_facservicio_id');
    }

    public function glosas () {
        return $this->hasMany(CmGlosa::class,'cm_facservicio_id');
    }

    public function afiliado () {
        return $this->belongsTo(AsAfiliado::class,'as_afiliado_id');
    }

    public function autorizacion () {
        return $this->belongsTo(AuAutorizacione::class,'au_autorizacion_id');
    }

    public function cum () {
        return $this->belongsTo(RsCum::class,'rs_cum_id');
    }

    public function cups () {
        return $this->belongsTo(RsCups::class,'rs_cups_id');
    }

    public function factura () {
        return $this->belongsTo(CmFactura::class,'cm_factura_id');
    }

    public function getFacPacienteAttribute($key)
    {
        return ($this->attributes['tipo_documento'] . ' ' . $this->attributes['documento'] . ' ' . $this->attributes['nombre']);
    }

    public function getValorServicioGlosadoAttribute ()
    {
        $glosas = $this->glosas;
        if ($glosas) {
            $val = 0;
            foreach ($glosas as $glosa) {
                if ($glosa->of_facservicio !== 2) {
                    $val += $glosa->valor_glosa;
                }
            }
            return $val;
        }
    }


    public function scopeConGlosa($query)
    {
        return $query->whereHas('glosas');
    }

    public function scopeSinGlosa($query)
    {
        return $query->doesntHave('glosas');
    }


}
