<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 6/02/2020
 * Time: 4:51 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2;


use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV3;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\Procesos\AsS2;
use App\Models\General\GnTipdocidentidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class S2 extends ProcesadorV3
{
    public function __construct(Request $request)
    {
        parent::__construct( $request );
        $this->campos = "serial,
                        codeps,
                        tipoIdentificacion,
                        identificacion,
                        primerApellido,
                        segundoApellido,
                        primerNombre,
                        segundoNombre,
                        codigoDepartamento,
                        codigoMunicipio,
                        fechaAfiliacion,
                        fechaUpc";
    }

    public function procesar()
    {
        $s2temporal = $this->cargarATablaTemporal('as_s2s');

        $respuesta = $this->cargarATabla('as_s2s');

        $this->createIndiceIdentificacion($s2temporal);
        
        $this->validarAfiliadoExiste($s2temporal);

        if(count($this->respuesta)){
            DB::statement("DROP TABLE {$s2temporal}");
            AsS2::where('as_svid_id', $respuesta->id)->delete();
            $respuesta->delete();
            return;
        }

        $this->solicitudesAcrear($s2temporal);

        $this->crearSolicitudes($s2temporal,$respuesta);
        DB::statement( "DROP TABLE IF EXISTS {$s2temporal}" );

    }

    private function solicitudesAcrear($s2temporal)
    {
        $afiliadosSolicitados = DB::select("SELECT 
        b.id,b.tipo_identificacion,b.identificacion,b.nombre_completo
        FROM {$s2temporal} AS a
        LEFT JOIN as_afiliados  AS b ON b.tipo_identificacion=a.tipoIdentificacion AND b.identificacion=a.identificacion
        left join as_epss as c on a.codeps = c.cod_habilitacion
        WHERE b.id IS not null");

        array_push($this->respuesta,[
            'tipo' => 'info',
            'titulo' => 'Afiliados que se estan solicitando en traslado',
            'afiliados' => $afiliadosSolicitados
        ]);

    }

    private function crearSolicitudes($s2temporal,$respuesta)
    {
        DB::statement("insert into as_soltraslados (
                id,
                bduaserial,
                as_afiliado_id,
                as_eps_id,
                fecha_solicita,
                fecha_apropiacion,
                respuesta,
                as_cautraslado_id,
                fecha_factible,
                nombre1S2,
                nombre2S2,
                apellido1S2,
                apellido2S2,
                cod_departamentoS2,
                cod_municipioS2,
                tip_docu_cabfamiliaS2,
                identificacion_cabfamiliaS2,
                estado,
                as_regimen_id,
                as_svid_id
                )
                SELECT 
                null,
                a.serial bduaserial, 
                b.id as_afiliado_id,
                c.id as_eps_id,
                concat_ws('-',substr(a.fechaAfiliacion,-4),substr(a.fechaAfiliacion,4,2),substr(a.fechaAfiliacion,1,2)) as fecha_solicita,
                concat_ws('-',substr(a.fechaUpc,-4),substr(a.fechaUpc,4,2),substr(a.fechaUpc,1,2)) as fecha_apropiacion,
                if(format(((UNIX_TIMESTAMP( (concat_ws('-',substr(a.fechaAfiliacion,-4),substr(a.fechaAfiliacion,4,2),substr(a.fechaAfiliacion,1,2)) as fecha_solicita) )-UNIX_TIMESTAMP(b.fecha_afiliacion))/(60*60*24)),0) < 365, 'Aprobado','Negado') as respuesta,
                if(format(((UNIX_TIMESTAMP( (concat_ws('-',substr(a.fechaAfiliacion,-4),substr(a.fechaAfiliacion,4,2),substr(a.fechaAfiliacion,1,2)) as fecha_solicita) )-UNIX_TIMESTAMP(b.fecha_afiliacion))/(60*60*24)),0) < 365, 1,14) as as_cautraslado_id,
                last_day(now()) fecha_factible,
                a.primerNombre nombre1S2,
                a.segundoNombre nombre2S2,
                a.primerApellido apellido1S2,
                a.segundoApellido apellido2S2,
                a.codigoDepartamento cod_departamentoS2,
                a.codigoMunicipio cod_municipioS2,
                a.tipoIdentificacion tip_docu_cabfamiliaS2,
                a.identificacion identificacion_cabfamiliaS2,
                'Validado' estado,
                b.as_regimene_id,
                {$respuesta->id}
                FROM {$s2temporal} AS a
                LEFT JOIN as_afiliados  AS b ON b.tipo_identificacion=a.tipoIdentificacion AND b.identificacion=a.identificacion
                left join as_epss as c on a.codeps = c.cod_habilitacion
                WHERE b.id IS not null");
    }
}
