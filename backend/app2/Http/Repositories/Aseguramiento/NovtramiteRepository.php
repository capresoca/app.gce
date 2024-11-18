<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30/07/2018
 * Time: 9:13 AM
 */

namespace App\Http\Repositories\Aseguramiento;


use App\Http\Repositories\Repository;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsClasecotizante;
use App\Models\Aseguramiento\AsCondicionDiscapacidade;
use App\Models\Aseguramiento\AsCondterminacione;
use App\Models\Aseguramiento\AsEstadoAfiliado;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\Aseguramiento\AsParentesco;
use App\Models\Aseguramiento\AsPobespeciale;
use App\Models\Aseguramiento\AsTipafiliado;
use App\Models\Aseguramiento\AsTipnovedade;
use App\Models\Aseguramiento\AsTramite;
use App\Models\General\GnEmpresa;
use App\Models\General\GnMunicipio;
use App\Models\General\GnTipdocidentidade;
use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCiiu;
use App\Models\RedServicios\RsEntidade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NovtramiteRepository implements Repository
{

    protected $afiliado;

    public function guardar($request)
    {
        DB::beginTransaction();
        $this->afiliado = AsAfiliado::find($request->as_afiliado_id);

        $estado = ($request->fosyga === true) ? 'Radicado' : 'Aprobado';

        $tramite = AsTramite::create([
            'tipo_tramite' => $this->afiliado->as_regimene_id === 1 ? 'Novedad Contributivo' : 'Novedad Subsidiado',
            'clase_tramite' => 'Manual',
            'estado' => $estado,
            'fecha_radicacion' => today()->toDateString(),
        ]);

        $empresa = GnEmpresa::all()->first();

        $novtramite_datos = [
            'as_tramite_id' => $tramite->id,
            'as_tipnovedade_id' => $request->as_tipnovedade_id,
            'as_afiliado_id' => $request->as_afiliado_id,
            'gn_municipio_id' => $this->afiliado->gn_municipio_id,
            'gn_tipdocidentidad_id' => $this->afiliado->gn_tipdocidentidad_id,
            'codigo_entidad' => $this->afiliado->regimene_id === 1 ? $empresa->codhabilitacion_contrib : $empresa->codhabilitacion_subsid,
            'identificacion' => $this->afiliado->identificacion,
            'apellido1' => $this->afiliado->apellido1,
            'apellido2' => $this->afiliado->apellido2,
            'nombre1' => $this->afiliado->nombre1,
            'nombre2' => $this->afiliado->nombre2,
            'fecha_nacimiento' => $this->afiliado->fecha_nacimiento,
            'fecha_ini_novedad' => $request->fecha_ini_novedad,
            'v1' => $request->v1,
            'v2' => $request->v2,
            'v3' => $request->v3,
            'v4' => $request->v4,
            'v5' => $request->v5,
            'v6' => $request->v6,
            'v7' => $request->v7
        ];

        $tipnovedad = AsTipnovedade::find($request->as_tipnovedade_id)->codigo;
        $novtramite_datos = $this->$tipnovedad($novtramite_datos);
        $novtramite = AsNovtramite::create($novtramite_datos);
        DB::commit();
        return $novtramite;

    }

    public function validar($requestArray)
    {
        return;
    }

    protected function N01($novtramite_datos)
    {
        $tipo_documento = GnTipdocidentidade::find($novtramite_datos['v1']);

        $novtramite_datos['v1'] = $tipo_documento->abreviatura;
        $fecha_nac = new Carbon($novtramite_datos['v3']);

        $novtramite_datos['v3'] = $fecha_nac->format('d/m/Y');

        $this->afiliado->gn_tipdocidentidad_id = $tipo_documento->id;
        $this->afiliado->tipo_identificacion = $tipo_documento->abreviatura;
        $this->afiliado->identificacion = $novtramite_datos['v2'];
        $this->afiliado->fecha_nacimiento = $fecha_nac->toDateString();
        $this->afiliado->save();

        return $novtramite_datos;
    }

    protected function N02($novtramite_datos)
    {
        $this->afiliado->nombre1 = $novtramite_datos['v1'];
        $this->afiliado->nombre2 = $novtramite_datos['v2'];

        $this->afiliado->save();

        return $novtramite_datos;
    }

    protected function N03($novtramite_datos)
    {
        $this->afiliado->apellido1 = $novtramite_datos['v1'];
        $this->afiliado->apellido2 = $novtramite_datos['v2'];

        $this->afiliado->save();

        return $novtramite_datos;
    }

    protected function N04($novtramite_datos)
    {
        $municipio = GnMunicipio::find($novtramite_datos['v1']);
        $novtramite_datos['v1'] = $municipio->departamento->codigo;
        $novtramite_datos['v2'] = substr($municipio->codigo, -3);

        $this->afiliado->gn_municipio_id = $municipio->id;

        $this->afiliado->save();

        return $novtramite_datos;
    }

    protected function N05($novtramite_datos)
    {
        $novtramite_datos['v1'] = $this->afiliado->cabeza->tipo_id->abreviatura;
        $novtramite_datos['v2'] = $this->afiliado->cabeza->identificacion;

        $cabeza = AsAfiliado::where('identificacion', $novtramite_datos['v2'])->first();
        $this->afiliado->cabfamilia_id = $cabeza->id;
        $this->afiliado->save();

        return $novtramite_datos;
    }

    protected function N06($novtramite_datos)
    {
        $actividad_economica = NfCiiu::find($novtramite_datos['v2']);

        $novtramite_datos['v4'] = $actividad_economica->codigo;
        $aportante = AsPagadore::find($novtramite_datos['v3']);

        $clase_cotizante = AsClasecotizante::find($novtramite_datos['v1']);
        $novtramite_datos['v1'] = $clase_cotizante->codigo;
        if ($novtramite_datos['v1'] === '3') { // Es independiente
            $novtramite_datos['v2'] = $this->afiliado->tipo_id->abreviatura;
            $novtramite_datos['v3'] = $this->afiliado->identificacion;

            $this->afiliado->gn_tipdocidentidad_id = $this->afiliado->tipo_id->id;
            $this->afiliado->identificacion = $this->afiliado->identificacion;
        } else {
            $novtramite_datos['v2'] = substr($aportante->tercero->tipo_id->abreviatura,0,2);
            $novtramite_datos['v3'] = $aportante->tercero->identificacion;

            $tercero = GnTercero::find($aportante->tercero->id);
            $tercero->gn_tipdocidentidad_id = $aportante->tercero->tipo_id->id;
            $tercero->identificacion = $aportante->tercero->identificacion;
            $tercero->save();
        }

        $this->afiliado->as_clasecotizante_id = $clase_cotizante->id;
        $this->afiliado->nf_ciiu_id = $actividad_economica->id;
        $this->afiliado->save();

        return $novtramite_datos;
    }

    protected function N07($novtramite_datos)
    {
        $cabeza = AsAfiliado::find($novtramite_datos['v3']);
        $novtramite_datos['v5'] = AsCondicionDiscapacidade::find($novtramite_datos['v2'])->codigo;
        $novtramite_datos['v4'] = AsParentesco::find($novtramite_datos['v1'])->codigo;
        $novtramite_datos['v3'] = $this->afiliado->tipo_afiliado->codigo;
        $novtramite_datos['v1'] = $cabeza->tipo_id->abreviatura;
        $novtramite_datos['v2'] = $cabeza->identificacion;
        return $novtramite_datos;
    }

    protected function N08($novtramite_datos)
    {
        $novtramite_datos['v4'] = $novtramite_datos['v2'];
        $novtramite_datos['v1'] = AsClasecotizante::find($novtramite_datos['v1'])->codigo;
        if ($novtramite_datos['v1'] == '3') {
            $novtramite_datos['v2'] = $this->afiliado->tipo_id->abreviatura;
            $novtramite_datos['v3'] = $this->afiliado->identificacion;
        } else {
            $aportante = AsPagadore::find($novtramite_datos['v3']);
            $novtramite_datos['v2'] = $aportante->tercero->tipo_id->abreviatura;
            $novtramite_datos['v3'] = $aportante->tercero->identificacion;
        }
        return $novtramite_datos;
    }

    protected function N09($novtramite_datos)
    {
        return $novtramite_datos;
    }

    protected function N10($novtramite_datos)
    {
        $novtramite_datos['v6'] = AsClasecotizante::find($novtramite_datos['v1'])->codigo;
        $novtramite_datos['v5'] = NfCiiu::find($novtramite_datos['v2'])->codigo;
        $aportante = AsPagadore::find($novtramite_datos['v3']);
        $novtramite_datos['v3'] = $this->afiliado->clase_cotizante->codigo;
        $novtramite_datos['v2'] = $aportante->tercero->tipo_id->abreviatura;
        $novtramite_datos['v1'] = $aportante->tercero->identificacion;
        return $novtramite_datos;
    }


    protected function N11($novtramite_datos)
    {
        $novtramite_datos['v3'] = AsClasecotizante::find($novtramite_datos['v2'])->codigo;
        $aportante = AsPagadore::find($novtramite_datos['v1']);
        $novtramite_datos['v1'] = $aportante->tercero->tipo_id->abreviatura;
        $novtramite_datos['v2'] = $aportante->tercero->identificacion;
        return $novtramite_datos;
    }


    protected function N12($novtramite_datos)
    {
        $novtramite_datos['v1'] = AsCondicionDiscapacidade::find($novtramite_datos['v1'])->abreviatura;
        return $novtramite_datos;
    }


    protected function N13($novtramite_datos)
    {
        $novtramite_datos['v1'] = AsCondterminacione::find($novtramite_datos['v1'])->codigo;
        $fecha = new Carbon($novtramite_datos['v2']);
        $novtramite_datos['v2'] = $fecha->format('d/m/Y');
        return $novtramite_datos;
    }


    protected function N14($novtramite_datos)
    {
        $novtramite_datos['v2'] = AsCondterminacione::find($novtramite_datos['v2'])->codigo;
        $novtramite_datos['v1'] = AsEstadoAfiliado::find($novtramite_datos['v1'])->codigo;
        return $novtramite_datos;
    }

    protected function N16($novtramite_datos)
    {
        return $novtramite_datos;
    }

    protected function N17($novtramite_datos)
    {
        if ($this->afiliado->gn_sexo_id === 1) {
            $novtramite_datos['v1'] = 'M';
            return $novtramite_datos;
        }
        $novtramite_datos['v1'] = 'F';
        return $novtramite_datos;
    }

    protected function N19($novtramite_datos)
    {
        if ($this->afiliado->gn_zona_id === 1) {
            $novtramite_datos['v1'] = 'R';

            return $novtramite_datos;
        }

        $novtramite_datos['v1'] = 'U';

        return $novtramite_datos;
    }

    protected function N20($novtramite_datos)
    {
        return $novtramite_datos;
    }

    protected function N21($novtramite_datos)
    {
        $novtramite_datos['v1'] = AsPobespeciale::find($novtramite_datos['v1'])->codigo;
        return $novtramite_datos;
    }

    protected function N25($novtramite_datos)
    {
        $novtramite_datos['v1'] = RsEntidade::find($novtramite_datos['v1'])->cod_habilitacion;
        return $novtramite_datos;
    }

    protected function N31($novtramite_datos)
    {
        $novtramite_datos['v1'] = AsPobespeciale::find($novtramite_datos['v1'])->codigo;
        return $novtramite_datos;
    }

    protected function N32($novtramite_datos)
    {
        $cabeza = AsAfiliado::find($novtramite_datos['v1']);
        $novtramite_datos['v4'] = AsCondicionDiscapacidade::find($novtramite_datos['v3'])->abreviatura;
        $novtramite_datos['v3'] = AsParentesco::find($novtramite_datos['v2'])->codigo;
        $novtramite_datos['v2'] = $cabeza->identificacion;
        $novtramite_datos['v1'] = $cabeza->tipo_id->abreviatura;
        return $novtramite_datos;
    }


    protected function N33($novtramite_datos)
    {
        $fecha = new Carbon($novtramite_datos['v1']);
        $novtramite_datos['v2'] = $fecha->format('d/m/Y');
        $novtramite_datos['v1'] = 4;
        return $novtramite_datos;
    }

    protected function N35($novtramite_datos)
    {
        $fecha_afiliacion = new Carbon($this->afiliado->fecha_afiliacion);
        $nueva_fecha_afiliacion = new Carbon($novtramite_datos['v2']);

        $novtramite_datos['v4'] = $nueva_fecha_afiliacion->format('d/m/Y');
        $novtramite_datos['v3'] = $fecha_afiliacion->format('d/m/Y');
        $novtramite_datos['v2'] = $this->afiliado->identificacion;
        $novtramite_datos['v1'] = $this->afiliado->tipo_id->abreviatura;
        return $novtramite_datos;
    }


}