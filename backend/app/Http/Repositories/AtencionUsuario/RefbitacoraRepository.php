<?php
/**
 * Created by PhpStorm.
 * User: Jorge Eduardo
 * Date: 12/12/2018
 * Time: 12:01 PM
 */

namespace App\Http\Repositories\AtencionUsuario;
//use App\Http\Repositories\Repository;
use App\Http\Repositories\Repository;
use App\Models\CentroRegulador\AuRefbitacora;
use App\Models\CentroRegulador\AuReferencia;
use App\Models\CentroRegulador\AuRefpresentacione;
use App\Models\CentroRegulador\AuReftraslado;
use App\Traits\EnumsTrait;
use http\Env\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RefbitacoraRepository implements Repository
{
    public function guardar($requestArray)
    {
        if (isset($requestArray['presentacion'])) {
            return $this->createPresentacion($requestArray['presentacion']);
        }
    }

    public function guardarTraslado ($requestArray) {
        if (isset($requestArray['traslado'])) {
            return $this->createTraslado($requestArray['traslado']);
        }
    }

    public function createPresentacion ($presentacion) {
        if (!isset($presentacion['id'])) {
            $au_refpresentacion = AuRefpresentacione::create($presentacion);
        } else {
            $au_refpresentacion = AuRefpresentacione::findOrFail($presentacion['id']);
            $au_refpresentacion->update($presentacion);
        }
        $this->actualizarEstadoReferencia($au_refpresentacion);
        return $au_refpresentacion;
    }

    public function createTraslado ($traslado) {
        if (!isset($traslado['id'])) {
            $au_reftraslado = AuReftraslado::create($traslado);
        } else {
            $au_reftraslado = AuReftraslado::findOrFail($traslado['id']);
            $au_reftraslado->update($traslado);
        }
        $this->actualizarEstadoReferencia($au_reftraslado);
        return $au_reftraslado;
    }

    public function actualizarEstadoReferencia ($item) {
        try {
            if ($item) {
                $referencia = AuReferencia::where('id', $item->au_referencia_id)->first();
//                dd($referencia);
                $bitacoras = AuRefbitacora::where('au_referencia_id', $referencia->id)->get();
                if ($bitacoras) {
                    foreach ($bitacoras as $bitacora) {
                        $this->modificarEstado($bitacora, $referencia);
                    }
                }
                $referencia->save();
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => 'Se presenta un error la actualización de la Referencia.'
            ], 500);
        }
    }

    public function modificarEstado ($bitacora, $referencia) {
        $validar = $referencia->bitacoras
            ->where('au_tipaccion_id', '=', 2)
            ->where('au_refpresentacion_id', '<>', $referencia->au_refpresentacion_id)
            ->where('presentacion.estado', '=', 'Aceptado')
            ->where('presentacion.seleccionada', '=', 'No')
            ->count();
        $validarTwo = $referencia->bitacoras
            ->where('au_tipaccion_id', '=', 1)
            ->where('au_refpresentacion_id', '<>', $referencia->au_refpresentacion_id)
            ->where('presentacion.estado', '=', 'Presentado')
            ->where('presentacion.seleccionada', '=', 'No')
            ->count();
        switch ($bitacora->au_tipaccion_id) {
            case 1:
                $referencia->estado = 'Presentada';
                break;
            case 2:
                $referencia->estado = 'Aceptada';
                break;
            case 3:
                if ($validarTwo >= 1 && $validar === 0) {
                    $referencia->estado = 'Presentada';
                } elseif ($validar >= 1 && ($validarTwo === 0 || $validarTwo !== 0)) {
                    $referencia->estado = 'Aceptada';
                } else {
                    $referencia->estado = 'Iniciada';
                }
                break;
            case 4:
                $referencia->estado = 'Traslado Solicitado';
                break;
            case 5:
                $referencia->estado = 'Seleccionada';
                break;
            case 6:
                if ($validarTwo >= 1 && $validar === 0) {
                    $referencia->estado = 'Presentada';
                } elseif ($validar >= 1 && ($validarTwo === 0 || $validarTwo !== 0)) {
                    $referencia->estado = 'Aceptada';
                } else {
                    $referencia->estado = 'Iniciada';
                }
                break;
            case 8:
                $referencia->estado = 'Salida';
                break;
            case 9:
                $referencia->estado = 'Finalizada';
                break;
            case 10:
                if ($validarTwo >= 1 && $validar === 0) {
                    $referencia->estado = 'Presentada';
                } elseif ($validar >= 1 && ($validarTwo === 0 || $validarTwo !== 0)) {
                    $referencia->estado = 'Aceptada';
                } else {
                    $referencia->estado = 'Cancelada';
                }
                break;
            case 12:
                $referencia->estado = 'Cancelada';
                break;
            case 13:
                $referencia->estado = 'Suspendida';
                break;
//            case 14:
//                $referencia->estado = 'Traslado Aceptado';
//                break;
            case 15:
                $referencia->estado = 'Salida';
                break;
            case 16:
                $referencia->estado = 'Cancelada';
                break;
            case 17:
                $referencia->estado = 'Cancelada';
                break;
            case 18:
                $referencia->estado = 'Cancelada';
                break;
            case 19:
                $referencia->estado = 'Cancelada';
                break;
            case 20:
                $referencia->estado = 'Cancelada';
                break;
        }
    }

    /**
     * @param $requestArray
     * @throws ValidationException
     */
    public function validar($requestArray)
    {
        try{
            $rules = [
                'estado' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('au_refpresentaciones','estado'))
            ];
            if ($requestArray['estado'] === 'Presentado') {
                $rules = [
                    'rs_entidades_id' => 'required|exists:rs_entidades,id',
                    'fecha_presentacion' => 'required'
                ];
            }
            if ($requestArray['estado'] === 'Aceptado') {
                $rules = [
                    'medico_acepta' => 'required|max:500',
                    'fecha_aceptacion' => 'required'
                ];
            }

            $validator = Validator::make($requestArray, $rules);

            if ($validator->fails()) {
                throw new ValidationException($validator->errors());
            }
        }catch (ValidationException $validationException){
            throw $validationException;
        };

    }

    public function validarTraslado ($requestArray) {
            $validations = [
                'estado' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('au_reftraslados','estado'))
            ];

            if ($requestArray['estado'] === 'Solicitado') {
                $validations = [
                    'entidad_origen_id' => 'required|exists:rs_entidades,id',
                    'entidad_destino_id' => 'required|exists:rs_entidades,id',
                    'fecha_solicitud' => 'required',
                    'tipo_traslado' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('au_reftraslados', 'tipo_traslado')),
                    'tipo_ambulancia' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('au_reftraslados', 'tipo_ambulancia')),
                    'contacto' => 'required|max:500'
                ];

                if ($validations['tipo_traslado'] !== 'Interno') {
                    $validations = [
                        'entidad_transporta_id' => 'required|exists:rs_entidades,id',
                    ];
                }
            }

            if ($requestArray['estado'] === 'En Camino') {
                $validations = [
                    'fecha_traslado' => 'required',
                ];
            }

            if ($requestArray['estado'] === 'Terminado') {
                $validations = [
                    'fecha_llegada' => 'required',
                ];
            }
            return $validations;
    }

}
