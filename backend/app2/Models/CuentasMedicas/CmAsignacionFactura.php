<?php

namespace App\Models\CuentasMedicas;

use App\Models\AuditorCuentas\AcAuditore;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class CmAsignacionFactura extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    protected $table = 'cm_asignacion_facturas';

    protected $fillable = ['ac_auditor_id','cm_factura_id','estado','gs_usuario_id','fecha_reasignacion'];
    protected $hidden = ['created_at','updated_at'];
    protected $searchable = ['search'];

    public function auditor()
    {
        return $this->belongsTo(AcAuditore::class,'ac_auditor_id');
    }

    public function usuario () {
        return $this->belongsToMany(User::class,'gs_usuario_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use($builder,$constraint){
                $query->whereHas('auditor.usuario',function ($query) use($builder,$constraint){
                    $query->where('name',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('identification',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('email',$constraint->getOperator(),$constraint->getValue());
                });
            });
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }
}
