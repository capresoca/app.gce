<?php

namespace App\Models\Aseguramiento;

use App\Traits\ToolsTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AsTramite extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $searchable = ['tipo_tramite', 'search'];
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    protected $appends = ['fecha_radicacion_array','consecutivo_pad'];

    public function afiliado_afiliacion(){
        return $this->belongsToMany(AsAfiliado::class, 'as_afitramites','as_tramite_id','as_afiliado_id');
    }

    public function afiliado_novedad(){
        return $this->belongsToMany(AsAfiliado::class, 'as_novtramites','as_tramite_id','as_afiliado_id');
    }

    public function pagador(){
        if($this->tipo_tramite == 'Afiliacion'){
            return $this->belongsToMany(AsPagadore::class, 'as_afitramites','as_tramite_id','as_pagadore_id');
        }

        return $this->belongsToMany(AsPagadore::class, 'as_novtramites','as_tramite_id','as_pagadore_id');
    }

    public function decautramites(){
        return $this->hasMany(AsDecauttramite::class, 'as_tramite_id');
    }

    public function anetramites(){
        return $this->hasMany(AsAnetramite::class, 'as_tramite_id');
    }

    public function novedad(){
        return $this->hasOne(AsNovtramite::class, 'as_tramite_id');
    }

    public function afiliacion(){
        return $this->hasOne(AsAfitramite::class,'as_tramite_id');
    }

    public function traslado(){
        return $this->hasOne(AsTraslatramite::class, 'as_tramite_id');
    }

    public function afiliacionPagador () {
        return $this->hasOne(AsFormpagadore::class, 'as_tramite_id');
    }

    public function solicitudTraslado(){
        return $this->hasOne(AsSoltraslado::class, 'as_tramite_id');
    }

    public function gsUsuario()
    {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }

    public function scopeBuscar($query, $data)
    {
        return $query
            ->whereHas('afiliado',function ($query) use ($data){
                $query->whereHas('tercero',function ($query) use ($data){
                    $query->where('identificacion', 'like', '%'.$data . '%')
                        ->orWhere('apellido1', 'like', '%'. $data . '%')
                        ->orWhere('apellido2', 'like', '%'. $data . '%')
                        ->orWhere('nombre1', 'like', '%' . $data . '%')
                        ->orWhere('nombre2', 'like', '%'. $data . '%');
                });
            });

    }

    public function getFechaRadicacionArrayAttribute(){

        if(empty($this->fecha_radicacion)) {
            return [];
        }

        return ToolsTrait::fechaArray($this->fecha_radicacion);
    }

    public function scopePendientesPorTipo($query, $tipoTramite, $case=null)
    {
        if (is_null($case)) {
            $case=$tipoTramite;
        }
        $newQuery = $query->where('tipo_tramite',$tipoTramite)
            ->whereNull('as_bduaarchivo_id');

        switch ($case){

            case 'Afiliacion':
                return $newQuery->whereHas('afiliacion');
                break;

            case 'MC' :
                return $newQuery->whereHas('afiliacion.afiliado', function ($query) {
                    $regimen_contributivo = 1;
                    $query->where('as_regimene_id',$regimen_contributivo);
                });

            case 'Novedad Subsidiado':
                    return $newQuery->whereHas('novedad.afiliado', function ($query){
                        $regimen_subsidiado = 2;
                        $query->where('as_regimene_id',$regimen_subsidiado);
                    });
                break;

            case 'Novedad Contributivo':
                    return $newQuery->whereHas('novedad.afiliado', function ($query) {
                        $regimen_contributivo = 1;
                        $query->where('as_regimene_id',$regimen_contributivo);
                    });
                break;

            case 'Afiliacion Aportante':
                    return $newQuery->whereHas('afiliacionPagador')->with('afiliacionPagador');
                break;
                
            case 'R4':
                    return $newQuery->whereHas('solicitudTraslado');
                break;
                
            case 'S4':
                    return $newQuery->whereHas('solicitudTraslado');
                break;
            case 'R40':
                    return $newQuery->whereHas('solicitudTraslado', function ($query) {
                        $regimen_subsidiado = 2;
                        $query->where('as_regimen_id',$regimen_subsidiado);
                    });
                break;
            case 'R4C':
                    return $newQuery->whereHas('solicitudTraslado', function ($query) {
                        $regimen_contributivo = 1;
                        $query->where('as_regimen_id',$regimen_contributivo);
                    });
                break;
                
            case 'S40':
                    return $newQuery->whereHas('solicitudTraslado', function ($query) {
                        $regimen_subsidiado = 2;
                        $query->where('as_regimen_id',$regimen_subsidiado);
                    });
                break;
            case 'S4C':
                return $newQuery->whereHas('solicitudTraslado', function ($query) {
                    $regimen_contributivo = 1;
                    $query->where('as_regimen_id',$regimen_contributivo);
                });
                break;
        }
    }

    public function scopeTrasladoPorTipo($query, $tipoTraslado) {
        $newQuery = $query->where('tipo_tramite',$tipoTraslado)
            ->whereNull('as_bduaarchivo_id');

        return $newQuery;
    }

    public static function boot(){
        parent::boot(); // TODO: Change the autogenerated stub

        AsTramite::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }

            $table->consecutivo = AsTramite::max('consecutivo') + 1;
        });
    }

    public function getConsecutivoPadAttribute()
    {
        return str_pad($this->consecutivo_reporte,8,'0',STR_PAD_LEFT);
    }
}
