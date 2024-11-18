<?php
namespace App\Models\Bdua;

use Illuminate\Database\Eloquent\Model;

class CtCargueLiquidacionPeriodo extends Model
{
    
    protected $table = 'ct_cargue_liquidacion_periodo';
    protected $primaryKey = 'consecutivo_periodo';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    protected $fillable = [
        'consecutivo_periodo',
    ];
}

