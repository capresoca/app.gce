<?php

namespace App\Models\RedServicios;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnMunicipio;
use App\RedServicios\RsServhabilitados;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RsAsignamasivo extends Model
{
    protected $fillable = ['observacion', 'tipo_proceso', 'rs_servhabilitado_anterior'];

    protected $appends = [
        'servicio_anterior',
        'servicios_asignados',
        'afiliados',
        'total_services',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'gs_usuario_id');
    }

    public function servicios_afiliados()
    {
        return $this->hasMany(RsAfiliadoServicio::class);
    }

    public static function allRelations()
    {
        return [
            'usuario',
            'servicios_afiliados.afiliado',
            'servicios_afiliados.servicio_habilitado',
        ];
    }

    public function getServicioAnteriorAttribute($key)
    {
        $servicio_anterior = collect();

        if ($this->rs_servhabilitado_anterior) {
            $servicio_habilitado = RsServhabilitados::find($this->rs_servhabilitado_anterior);
            $servicio = RsServicio::find($servicio_habilitado->rs_servicio_id);
            $entidad = RsEntidade::with('municipio')->where('id',$servicio_habilitado->rs_entidad_id)->first();
            $servicio_anterior->push([
                'servicio' => $servicio,
                'ips' => $entidad,
            ]);

            return $servicio_anterior->first();
        }

        return $servicio_anterior;
    }

    public function getServiciosAsignadosAttribute()
    {
        $servicios_asignados = collect();
        $afiservicios = DB::select("SELECT c.nombre AS servicioh,
                                                   z.as_afiliado_id, 
                                                   b.rs_entidad_id,
                                                   b.rs_servicio_id
                                        FROM rs_servhabilitados AS b
                                        left join rs_servicios  AS c ON c.id = b.rs_servicio_id
                                        LEFT JOIN
                                                (
                                                SELECT   a.id,
                                                            a.as_afiliado_id,
                                                            a.rs_servhabilitado_id
                                                FROM rs_afiliado_servicios AS a
                                                WHERE a.rs_asignamasivo_id = {$this->id}
                                                GROUP BY a.as_afiliado_id, a.rs_servhabilitado_id
                                                ) AS z ON z.rs_servhabilitado_id = b.id
                                        WHERE z.id IS NOT NULL");

        foreach ($afiservicios as $afiservicio) {
            $servicio = RsServicio::select('id','codigo','nombre')->find($afiservicio->rs_servicio_id);
            $entidad = RsEntidade::select('id',
                                        'nombre',
                                        'telefono_sede',
                                        'correo_electronico_sede',
                                        'direccion_sede',
                                        'gn_municipiosede_id')
                ->where('id', $afiservicio->rs_entidad_id)->first();
            $municipio = GnMunicipio::find($entidad->gn_municipiosede_id);
            $agregar_servicio_entidad = true;

            if ($servicios_asignados->count() > 0){
                foreach ($servicios_asignados as $servicios_asignado) {
                    if ($servicios_asignado['servicio']['id'] == $afiservicio->rs_servicio_id
                        && $servicios_asignado['ips']['id'] ==  $afiservicio->rs_entidad_id) {
                        $agregar_servicio_entidad = false;
                    }
                }
            }

            if ($agregar_servicio_entidad) {
                $servicios_asignados->push([
                    'servicio' => $servicio,
                    'ips' =>  [
                        'id' => $entidad['id'],
                        'nombre' => $entidad['nombre'],
                        'telefono_sede' => $entidad['telefono_sede'],
                        'correo_electronico_sede' => $entidad['correo_electronico_sede'],
                        'direccion_sede' => $entidad['direccion_sede'],
                        'municipio' => $municipio
                    ]
                ]);
            }
        }

        return $servicios_asignados;
    }

    public function getAfiliadosAttribute($key)
    {
        $afiliados = collect();
        $afiservicios = DB::select("SELECT c.nombre AS servicioh,
                                               z.as_afiliado_id, 
                                               b.rs_entidad_id,
                                               b.rs_servicio_id
                                                FROM rs_servhabilitados AS b
                                                left join rs_servicios  AS c ON c.id = b.rs_servicio_id
                                                LEFT JOIN
                                                        (
                                                        SELECT   a.id,
                                                                    a.as_afiliado_id,
                                                                    a.rs_servhabilitado_id
                                                        FROM rs_afiliado_servicios AS a
                                                        WHERE a.rs_asignamasivo_id = {$this->id}
                                                        GROUP BY a.as_afiliado_id, a.rs_servhabilitado_id
                                                        ) AS z ON z.rs_servhabilitado_id = b.id
                                                WHERE z.id IS NOT NULL
                                                GROUP BY z.as_afiliado_id");
        //
        foreach ($afiservicios as $key => $afiservicio) {
            $afiliado = AsAfiliado::find($afiservicio->as_afiliado_id);
            $afiliados->push([
                'id' => $afiliado['id'],
                'fecha_nacimiento' => $afiliado['fecha_nacimiento'],
                'fecha_afiliacion' => $afiliado['fecha_afiliacion'],
                'estado' => $afiliado['estado'],
                'mini_afiliado' => $afiliado['mini_afiliado'],
                'estado_afiliado' => $afiliado['estado_afiliado'],
            ]);
        }
        return $afiliados;
    }

    public function getTotalServicesAttribute($key)
    {
        $servicios = DB::select("SELECT c.nombre AS servicioh,
                                               z.as_afiliado_id, 
                                               b.rs_entidad_id,
                                               b.rs_servicio_id
                                    FROM rs_servhabilitados AS b
                                    left join rs_servicios  AS c ON c.id = b.rs_servicio_id
                                    LEFT JOIN
                                            (
                                            SELECT   a.id,
                                                        a.as_afiliado_id,
                                                        a.rs_servhabilitado_id
                                            FROM rs_afiliado_servicios AS a
                                            WHERE a.rs_asignamasivo_id = {$this->id}
                                            GROUP BY a.as_afiliado_id, a.rs_servhabilitado_id
                                            ) AS z ON z.rs_servhabilitado_id = b.id
                                    WHERE z.id IS NOT NULL
                                    GROUP BY  b.rs_servicio_id");

        $datos = collect();

        foreach ($servicios as $servicio) {
            $datos->push(['nombre' => $servicio->servicioh]);
        }

        return $datos;
    }

    public static function boot()
    {
        parent::boot();

        RsAsignamasivo::creating(function ($table) {
            if (Auth::user()) {
                $table->gs_usuario_id = Auth::user()->id;
            }
        });
    }
}
