<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GsComponente extends Model
{
    protected $fillable = [
        'nombre',
        'gs_modulo_id',
        'documento',
        'ruta_router',
        'icono',
        'favorito',
        'descripcion',
        'ruta_componente',
        'created_at',
        'updated_at',
        'ruta_router_registro',
        'titulo_registro',
        'permisos',
        'view_menu'
    ];

    public function GsPermisoRoles(){
        return $this->belongsTo('App\GsPermisoRole');
    }

    public function GsModulo(){
        return $this->belongsTo(GsModulo::class);
    }

    public function esFavorito($id_user)
    {
        return $this->favoritos()->where('gs_usuario_id',$id_user)->first() ? true : false;
    }

    public function favoritos()
    {
        return $this->hasMany(GsFavorito::class,'gs_componente_id');
    }

}
















