<?php

namespace App\Models\CuentasMedicas;

use App\Models\AuditorCuentas\AcAuditore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CmAuditorConcurrencia extends Model
{
    protected $table = 'cm_auditor_concurrencia';
    protected $fillable = [
        'id',
        'auditor_id',
        'concurrencia_id',
        'user_id'
    ];

    public static function boot()
    {
        parent::boot();

        CmAuditorConcurrencia::creating(function ($table){
            if(Auth::user()){
                $table->user_id  = Auth::user()->id;
            }
        });

    }
}
