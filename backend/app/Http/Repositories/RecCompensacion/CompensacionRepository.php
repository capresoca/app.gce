<?php


namespace App\Http\Repositories\RecCompensacion;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Date\Date;

/**
 * @author Jorge Eduardo Hernández Oropeza 12/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class CompensacionRepository
 * @package App\Http\Repositories\RecCompensacion
 */
class CompensacionRepository implements \App\Http\Repositories\Repository
{

    public function guardar($requestArray)
    {
        // TODO: Implement guardar() method.
    }

    public function validar($requestArray)
    {
        // TODO: Implement validar() method.
    }

    /**
     * @param $contenido
     * @param $nombreArchivo
     * @param $setDepartamentos
     * @param $setMunicipios
     * @return array|Collection
     */
    public function validarEstructuraRecaudoPlanillaA($contenido, $nombreArchivo, $setDepartamentos, $setMunicipios)
    {
        $errores = array();
        $tiposId = ["NI", "CC", "CE", "TI", "PA", "CD", "SC", "PE"];
        $clasesAportante = ["A", "B", "C", "D", "I"];
        $tipoPersona = ["N", "J"];
        $formaPresentacion = ["U", "S"];
        $opcionesSiNo = ["S", "N"];

        if (empty(trim(substr($contenido, 0, 200)))) {
            array_push($errores, 'Error liquidación recaudo planilla razon social.');
        }

        if (empty(trim(substr($contenido, 200, 2))) || !in_array(trim(substr($contenido, 200, 2)), $tiposId)) {
            array_push($errores, 'Error liquidación recaudo planilla con el tipo de documento.');
        }

        if (empty(trim(substr($contenido, 202, 16)))) {
            array_push($errores, 'Error liquidación recaudo planilla con el número de documento.');
        }

        //dd(trim(substr($contenido, 202, 16)),trim(substr($contenido, 218, 1)), $contenido);
        if (empty(trim(substr($contenido, 218, 1))) && (!is_numeric(trim(substr($contenido, 218, 1))))) {
            array_push($errores, 'Error liquidación recaudo planilla con el digito de verificación.');
        }

        if (empty(trim(substr($contenido, 269, 1))) || !in_array(trim(substr($contenido, 269, 1)), $clasesAportante)) {
            array_push($errores, 'Error liquidación recaudo planilla con la clase de aportante.');
        }

        if (empty(trim(substr($contenido, 270, 1))) || !is_numeric(substr($contenido, 270, 1))) {
            array_push($errores, 'Error liquidación recaudo planilla con la Naturaleza Juridica.');
        }

        if (empty(trim(substr($contenido, 271, 1))) || !in_array(trim(substr($contenido, 271, 1)), $tipoPersona)) {
            array_push($errores, 'Error liquidación recaudo planilla tipo de persona.');
        }

        if (empty(trim(substr($contenido, 272, 1))) || !in_array(trim(substr($contenido, 272, 1)), $formaPresentacion)) {
            array_push($errores, 'Error liquidación recaudo planilla forma presentación.');
        }

        if (empty(trim(substr($contenido, 318, 4))) && !is_numeric(trim(substr($contenido, 318, 4)))) {
            array_push($errores, 'Error liquidación recaudo planilla código dane.');
        }

        if (!empty(trim(substr($contenido, 531, 1))) && !(trim(substr($contenido, 531, 1)) === 0)) {
            $fecha = trim(substr($contenido, 521, 10));
            $validar_fecha = Validator::make(['fecha' => $fecha], ['fecha' => 'date_format:Y-m-d']);
            if (empty($fecha) || !$validar_fecha) {
                array_push($errores, 'Error liquidación recaudo planilla fecha inicio');
            }
            if (!is_numeric(trim(substr($contenido, 531, 1)))) {
                array_push($errores, 'Error liquidación recaudo planilla tipo acción');
            }
        }

        $fecha_terminacion = trim(substr($contenido, 532, 10));
        $validaa_terminacion = Validator::make(['fecha_terminacion' => $fecha_terminacion], ['fecha_terminacion' => 'date_format:Y-m-d']);
        if (!empty($fecha_terminacion) && !$validaa_terminacion) {
            array_push($errores, 'Error liquidación recaudo planilla fecha terminación actividades.');
        }

        if (!empty(trim(substr($contenido, 542, 2))) && !is_numeric(trim(substr($contenido, 542, 2)))) {
            array_push($errores, 'Error liquidación recaudo planilla código operador.');
        }

        if (empty(trim(substr($contenido, 544, 7)))) {
            array_push($errores, 'Error liquidación recaudo planilla con el periodo de pago.');
        } else {
            $periodoPagos = explode('-', trim(substr($contenido, 544, 7)));
            if (count($periodoPagos) != 2) {
                array_push($errores, "Error liquidación recaudo planilla con el periodo de pago: el contenido es mayor.");
            } else {
                if (!is_numeric($periodoPagos[0]) || !is_numeric($periodoPagos[1])) {
                    array_push($errores, 'Error liquidación recaudo planilla con el periodo de pago: el contenido es alfanumerico.');
                }
            }
        }

        if (empty(trim(substr($contenido, 551, 2))) || !is_numeric(trim(substr($contenido, 551, 2)))) {
            array_push($errores, 'Error liquidación recaudo planilla con el tipo aportante.');
        } else {
            $tipoAportante = (integer)trim(substr($contenido, 551, 2));
            if ($tipoAportante < 1 || $tipoAportante > 9) {
                array_push($errores, 'Error liquidación recaudo planilla con el tipo aportante: No se identifica el dato.');
            }
        }

        $fecha_matricula = trim(substr($contenido, 553, 10));
        $validmatricula = Validator::make(['fecha_matricula' => $fecha_matricula], ['fecha_matricula' => 'date_format:Y-m-d']);
        if (!empty($fecha_matricula) && !$validmatricula) {
            array_push($errores, 'Error liquidación recaudo planilla con la fecha matricula: No se identifica el formato.');
        }

        if (empty(trim(substr($contenido, 565, 1))) || !(in_array(trim(substr($contenido, 565, 1)), $opcionesSiNo))) {
            array_push($errores, 'Error liquidación recaudo planilla con la exoneración de pago.');
        }

        if ((trim(substr($contenido, 269, 1)) === 'D') && (empty(strtoupper(trim(substr($contenido, 566, 1)))) || !in_array(trim(substr($contenido, 566, 1)), $opcionesSiNo))) {
            array_push($errores, 'Error liquidación recaudo planilla aportante segun ley acoge beneficios 1429.');
        }

        $info = collect($errores);

        return $info;
    }

    /**
     * @param $contenido
     * @param $nombreArchivo
     * @param $setPlanillasCargadas
     * @param $listaTipoAportante
     * @return Collection
     */
    public function validarEstructuraRecaudoPlanillaIEncabezado($contenido, $nombreArchivo, $setPlanillasCargadas, $listaTipoAportante)
    {

        $errores = collect();

        if (empty(trim(substr($contenido, 0, 5))) || !(trim(substr($contenido, 0, 5)) === "00000")) {
            $errores->push('Error liquidación recaudo planilla: t1 no cumple estructura.');
        }

        if (empty(trim(substr($contenido, 5, 1))) || !(trim(substr($contenido, 5, 1)) === "1")) {
            $errores->push('Error liquidación recaudo planilla: t1 no cumple estructura.');
        }

        if (empty(trim(substr($contenido, 6, 2))) || !(('03' === trim(substr($contenido, 6, 2))) || ('3' === trim(substr($contenido, 6, 2))))) {
            $errores->push('Error liquidación recaudo planilla: Formato de código.');
        }

        if (empty(trim(substr($contenido, 8, 16)))) {
            $errores->push('Error liquidación recaudo planilla: Nit administradora vació.');
        }

//        if (empty(trim(substr($contenido,24,1))) || (!is_numeric(trim(substr($contenido,24,1))))) {
//            $errores->push('Error liquidación recaudo planilla: Código formato.');
//        }

        if (empty(trim(substr($contenido, 25, 200)))) {
            $errores->push('Error liquidación recaudo planilla: Razón social aportante.');
        }

        if ((empty(trim(substr($contenido, 225, 2)))) || empty(trim(substr($contenido, 227, 16)))) {
            $errores->push('Error liquidación recaudo planilla: Tipo registro i.');
        } elseif (!stripos($nombreArchivo, (trim(substr($contenido, 225, 2) . '_' . substr($contenido, 227, 16))))) {
            $errores->push('Error liquidación recaudo planilla: No coincide id archivo contenido.');
        }

        // empty(trim(substr($contenido, 218, 1))) && (!is_numeric(trim(substr($contenido, 218, 1))))
        if ((empty(trim(substr($contenido, 243, 1)))) && (!is_numeric(trim(substr($contenido, 243, 1))))) {
            $errores->push('Error liquidación recaudo planilla: Digito verificación aportante.');
        }

        if ((empty(trim(substr($contenido, 244, 2)))) || !is_numeric(trim(substr($contenido, 244, 2)))) {
            $errores->push('Error liquidación recaudo planilla: Tipo aportante.');
        }
//        else {
//            $tipoAportante = (integer) trim(substr($contenido,244,2));
//            //dd(trim(substr($contenido,244,2)), $tipoAportante);
//            //dd($tipoAportante,$listaTipoAportante, array_search($tipoAportante, $listaTipoAportante));
//           // die;
//            if ($listaTipoAportante !== null && (!array_search($tipoAportante, $listaTipoAportante))) {
//                $errores->push('Error liquidación recaudo planilla: Tipo aportante.');
//            }
//        }

        $fechaPeriodo = trim(substr($contenido, 371, 7));
        $validar_fecha = Validator::make(['fecha' => "$fechaPeriodo-01"], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaPeriodo)) && ($validar_fecha->errors()->messages() !== [])) {
            $errores->push('Error liquidación recaudo planilla: Periodo de pago vacío o formato invalido.');
        }

        if (empty(trim(substr($contenido, 384, 1)))) {
            $errores->push('Error liquidación recaudo planilla: Campo tipo planilla vacío.');
        }

        $fechaPagoPlanilla = trim(substr($contenido, 385, 10));
        $validar_fechappago = Validator::make(['fecha' => $fechaPagoPlanilla], ['fecha' => 'date']);
        if ((!empty($fechaPagoPlanilla)) && ($validar_fechappago->errors()->count() > 0)) {
            $errores->push('Error liquidación recaudo planilla: Campo Fecha pago planilla formato invalido.');
        }

        $fechaPago = trim(substr($contenido, 395, 10));
        $validar_fechapago = Validator::make(['fecha' => $fechaPago], ['fecha' => 'date']);
        if ((!empty($fechaPago)) && ($validar_fechapago->errors()->count() > 0)) {
            $errores->push('Error liquidación recaudo planilla: Campo Fecha pago o formato invalido.');
        }

        if (empty(trim(substr($contenido, 415, 10))) || (!stripos($nombreArchivo, trim(substr($contenido, 415, 10))))) {
            $errores->push('Error liquidación recaudo planilla: Número de planilla archivo, se encuentra vacío o no se encuentra.');
        }

        if (empty(trim(substr($contenido, 425, 1)))) {
            $errores->push('Error liquidación recaudo planilla: Forma de presentación se encuentra vacío.');
        }

        if ((empty(trim(substr($contenido, 476, 5)))) || !is_numeric(trim(substr($contenido, 476, 5)))) {
            $errores->push('Error liquidación recaudo planilla: Número total de empleados se encuentra vació o no es un campo númerico.');
        }
        if ((empty(trim(substr($contenido, 481, 5)))) || !is_numeric(trim(substr($contenido, 481, 5)))) {
            $errores->push('Error liquidación recaudo planilla: Número total afiliados se encuentra vació o no es un campo númerico.');
        }
        if ((empty(trim(substr($contenido, 486, 2)))) || !is_numeric(trim(substr($contenido, 486, 2)))) {
            $errores->push('Error liquidación recaudo planilla: Código operador se encuentra vació o no es un campo númerico.');
        }
        if ((empty(trim(substr($contenido, 488, 1)))) || !is_numeric(trim(substr($contenido, 488, 1)))) {
            $errores->push('Error liquidación recaudo planilla: Modalisas planilla se encuentra vació o no es un campo númerico.');
        }
        if ((empty(trim(substr($contenido, 489, 4)))) || !is_numeric(trim(substr($contenido, 489, 4)))) {
            $errores->push('Error liquidación recaudo planilla: Días en mora se encuentra vació o no es un campo númerico.');
        }
        if ((empty(trim(substr($contenido, 493, 8)))) || !is_numeric(trim(substr($contenido, 493, 8)))) {
            $errores->push('Error liquidación recaudo planilla: No. Registros, salida tipo 2 se encuentra vació o no es un campo númerico.');
        }
        $fechaMatricula = trim(substr($contenido, 501, 10));
        $validar_fec_matricula = Validator::make(['fecha' => $fechaMatricula], ['fecha' => 'date']);
        if ((!empty($fechaMatricula)) && ($validar_fec_matricula->errors()->count() > 0)) {
            $errores->push('Error liquidación recaudo planilla: Fecha matricula mercantil se encuentra vació formato invalido.');
        }

        if (empty(trim(substr($contenido, 513, 1)))) {
            $errores->push('Error liquidación recaudo planilla: Exonerado de pago se encuentra vacío.');
        }

        if (empty(trim(substr($contenido, 514, 1)))) {
            $errores->push('Error liquidación recaudo planilla: Clase aportante se encuentra vacío.');
        }

        if (empty(trim(substr($contenido, 515, 1))) || (!is_numeric(trim(substr($contenido, 515, 1))))) {
            $errores->push('Error liquidación recaudo planilla: El campo de naturaleza juridica encuentra vacío. o no es un número.');
        }

        if (empty(trim(substr($contenido, 516, 1)))) {
            $errores->push('Error liquidación recaudo planilla: El campo tipo persona se encuentra vacío.');
        }
        $fechaActualizacione = trim(substr($contenido, 517, 10));
        $validar_fecactualizacion = Validator::make(['fecha' => $fechaActualizacione], ['fecha' => 'date_format:Y-m-d']);
        if ((empty($fechaActualizacione)) && ($validar_fecactualizacion->errors()->count() > 0)) {
            $errores->push('Error liquidación recaudo planilla: El campo fecha de actualización se encuentra vacío.');
        }

        return $errores;
    }

    /**
     * @param $contenido
     * @param $setDepartamentos
     * @param $setMunicipios
     * @return Collection
     */
    public function validarEstructuraRecaudoPlanillaITipo2Liquidacion($contenido, $setDepartamentos, $setMunicipios)
    {

        $errores = array();
        $secuencias = collect();

        $tiposId = ['NI', 'CC', 'CE', 'TI', 'PA', 'CD', 'SC', 'PE'];
        $clasesAportante = ["A", "B", "C", "D", "I"];
        $tipoPersona = ["N", "J"];
        $formaPresentacion = ["U", "S"];
        $opcionesSiNo = ["S", "N"];

        if (empty(trim(substr($contenido, 0, 5))) || (!is_numeric(trim(substr($contenido, 0, 5))))) {
            array_push($errores, substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 Secuencia.');
        } else {
            if (!$secuencias->containsStrict(trim(substr($contenido, 0, 5)))) {
                $secuencias->push(trim(substr($contenido, 0, 5)));
            } else {
                array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 Secuencia repetida.'));
            }
        }

        if (empty(trim(substr($contenido, 5, 1))) || (!is_numeric(trim(substr($contenido, 5, 1))))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 tipo registro.'));
        }

        if (empty(trim(substr($contenido, 6, 2))) || (!in_array(trim(substr($contenido, 6, 2)), $tiposId))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 tipo documento cotizante.'));
        }

        if (empty(trim(substr($contenido, 8, 16)))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 número documento cotizante.'));
        }

        if (empty(trim(substr($contenido, 24, 2))) || (!is_numeric(trim(substr($contenido, 24, 2))))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 tipo cotizante.'));
        }

        if (empty(trim(substr($contenido, 26, 2))) || (!is_numeric(trim(substr($contenido, 26, 2))))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 subtipo cotizante.'));
        }

        if (empty(trim(substr($contenido, 35, 20)))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 primer apellido.'));
        }

        if (empty(trim(substr($contenido, 85, 20)))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 primer nombre.'));
        }

        if (empty(trim(substr($contenido, 145, 2))) || (!is_numeric(trim(substr($contenido, 145, 2))))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 días cotizados.'));
        }

        if (empty(trim(substr($contenido, 147, 9))) || (!is_numeric(trim(substr($contenido, 147, 9))))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 salario básico.'));
        }

        if (empty(trim(substr($contenido, 156, 9))) || (!is_numeric(trim(substr($contenido, 156, 9))))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 ingreso base cotización.'));
        }

        if (empty(trim(substr($contenido, 165, 7))) || (!is_numeric(trim(substr($contenido, 165, 7))))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 tarifa.'));
        }

        if (empty(trim(substr($contenido, 172, 9))) || (!is_numeric(trim(substr($contenido, 172, 9))))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 cotización obligatoria.'));
        }

        if (empty(trim(substr($contenido, 181, 9))) || (!is_numeric(trim(substr($contenido, 181, 9))))) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 valor upc adicional.'));
        }

        $fechaIngreso = trim(substr($contenido, 193, 10));
        $validar_fechaIngreso = Validator::make(['fecha' => $fechaIngreso], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaIngreso)) && ($validar_fechaIngreso->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla: El campo fecha ingreso se encuentra vacío o formato inválido.'));
        }

        $fechaRetiro = trim(substr($contenido, 203, 10));
        $validar_fechaRetiro = Validator::make(['fecha' => $fechaRetiro], ['fecha' => 'date_format:Y-m-d']);

        if ((!empty($fechaRetiro)) && ($validar_fechaRetiro->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha retiro se encuentra vacío o formato inválido.'));
        }

        $fechaInicioVsp = trim(substr($contenido, 213, 10));
        $validar_fechaInicioVsp = Validator::make(['fecha' => $fechaInicioVsp], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaInicioVsp)) && ($validar_fechaInicioVsp->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha inicio vsp se encuentra vacío o formato inválido.'));
        }

        $fechaInicioSln = trim(substr($contenido, 223, 10));
        $validar_fechaInicioSln = Validator::make(['fecha' => $fechaInicioSln], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaInicioSln)) && ($validar_fechaInicioSln->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha inicio sln se encuentra vacío o formato inválido.'));
        }

        $fechafinSln = trim(substr($contenido, 233, 10));
        $validar_fechafinSln = Validator::make(['fecha' => $fechafinSln], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechafinSln)) && ($validar_fechafinSln->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha fin sln se encuentra vacío o formato inválido.'));
        }

        $fechainicioIge = trim(substr($contenido, 243, 10));
        $validar_fechainicioIge = Validator::make(['fecha' => $fechainicioIge], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechainicioIge)) && ($validar_fechainicioIge->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha inicio ige se encuentra vacío o formato inválido.'));
        }

        $fechafinIge = trim(substr($contenido, 253, 10));
        $validar_fechafinIge = Validator::make(['fecha' => $fechafinIge], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechafinIge)) && ($validar_fechafinIge->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha fin ige se encuentra vacío o formato inválido.'));
        }

        $fechaInicioLma = trim(substr($contenido, 263, 10));
        $validar_fechaInicioLma = Validator::make(['fecha' => $fechaInicioLma], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaInicioLma)) && ($validar_fechaInicioLma->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha inicio lma se encuentra vacío o formato inválido.'));
        }

        $fechaFinLma = trim(substr($contenido, 273, 10));
        $validar_fechaFinLma = Validator::make(['fecha' => $fechaFinLma], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaFinLma)) && ($validar_fechaFinLma->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha fin lma se encuentra vacío o formato inválido.'));
        }

        $fechaInicioVacLr = trim(substr($contenido, 283, 10));
        $validar_fechaInicioVacLr = Validator::make(['fecha' => $fechaInicioVacLr], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaInicioVacLr)) && ($validar_fechaInicioVacLr->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha inicio vac lr se encuentra vacío o formato inválido.'));
        }

        $fechaFinVacLr = trim(substr($contenido, 293, 10));
        $validar_fechaFinVacLr = Validator::make(['fecha' => $fechaFinVacLr], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaFinVacLr)) && ($validar_fechaFinVacLr->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha fin vac lr se encuentra vacío o formato inválido.'));
        }

        $fechaInicioVct = trim(substr($contenido, 303, 10));
        $validar_fechaInicioVct = Validator::make(['fecha' => $fechaInicioVct], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaInicioVct)) && ($validar_fechaInicioVct->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha inicio vct se encuentra vacío o formato inválido.'));
        }

        $fechaFinVct = trim(substr($contenido, 313, 10));
        $validar_fechaFinVct = Validator::make(['fecha' => $fechaFinVct], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaFinVct)) && ($validar_fechaFinVct->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha fin vct se encuentra vacío o formato inválido.'));
        }

        $fechaInicioIrl = trim(substr($contenido, 323, 10));
        $validar_fechaInicioIrl = Validator::make(['fecha' => $fechaInicioIrl], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaInicioIrl)) && ($validar_fechaInicioIrl->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha inicio irl se encuentra vacío o formato inválido.'));
        }

        $fechaFinIrl = trim(substr($contenido, 333, 10));
        $validar_fechaFinIrl = Validator::make(['fecha' => $fechaFinIrl], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaFinIrl)) && ($validar_fechaFinIrl->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha fin irl se encuentra vacío o formato inválido.'));
        }

        $fechaRadicacionExterior = trim(substr($contenido, 343, 10));
        $validar_fechaRadicacionExterior = Validator::make(['fecha' => $fechaRadicacionExterior], ['fecha' => 'date_format:Y-m-d']);
        if ((!empty($fechaRadicacionExterior)) && ($validar_fechaRadicacionExterior->errors()->messages() !== [])) {
            array_push($errores, (substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2: El campo fecha radicación exterior se encuentra vacío o formato inválido.'));
        }

        $info = collect($errores);

        return $info;
    }

    /**
     * @param $contenido
     * @param $setDepartamentos
     * @param $setMunicipios
     * @return Collection
     */
    public function validarEstructuraRecaudoPlanillaIPTipo2Liquidacion($contenido, $setDepartamentos, $setMunicipios)
    {

        $errores = array();
        $secuencias = collect();

        $tiposId = ['NI', 'CC', 'CE', 'TI', 'PA', 'CD', 'SC', 'RC', 'PE'];

        if (empty(trim(substr($contenido, 0, 7))) || (!is_numeric(trim(substr($contenido, 0, 7))))) {
            array_push($errores, substr($contenido, 0, 5) . ': Error liquidación recaudo planilla T2 Secuencia.');
        } else {
            if (!$secuencias->containsStrict(trim(substr($contenido, 0, 7)))) {
                $secuencias->push(trim(substr($contenido, 0, 7)));
            } else {
                array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 Secuencia repetida.'));
            }
        }

        if (empty(trim(substr($contenido, 7, 1))) || !(trim(substr($contenido, 7, 1)) === '2')) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 tipo registro.'));
        }

        if (empty(trim(substr($contenido, 8, 2))) || (!array_search(trim(substr($contenido, 8, 2)), $tiposId))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 tipo documento pensionado.'));
        }

        if (empty(trim(substr($contenido, 10, 16)))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 número documento pensionado.'));
        }

        if (empty(trim(substr($contenido, 26, 20)))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 primer apellido pensionado.'));
        }

        if (empty(trim(substr($contenido, 76, 20)))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 primer nombre pensionado.'));
        }

        if (empty(trim(substr($contenido, 244, 2))) || (!is_numeric(trim(substr($contenido, 244, 2))))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 tipo pension.'));
        }

        if (empty(trim(substr($contenido, 247, 1))) || (!is_numeric(trim(substr($contenido, 247, 1))))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 tipo pensionado.'));
        }

        if (empty(trim(substr($contenido, 260, 2))) || (!is_numeric(trim(substr($contenido, 260, 2))))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 número días cotizados.'));
        }

        if (empty(trim(substr($contenido, 262, 9))) || (!is_numeric(trim(substr($contenido, 262, 9))))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 ingreso base cotización.'));
        }

        if (empty(trim(substr($contenido, 271, 7))) || (!is_numeric(trim(substr($contenido, 271, 7))))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 tarifa.'));
        }

        if (empty(trim(substr($contenido, 278, 9))) || (!is_numeric(trim(substr($contenido, 278, 9))))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 cotización obligatoria.'));
        }

        if (empty(trim(substr($contenido, 287, 9))) || (!is_numeric(trim(substr($contenido, 287, 9))))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 valor upc adicional.'));
        }

        $fechaIngreso = trim(substr($contenido, 296, 10));
        $validar_fechaIngreso = Validator::make(['fecha' => $fechaIngreso], ['fecha' => 'date_format:Y-m-d']);
        if (empty($fechaIngreso) || (!$validar_fechaIngreso)) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla: El campo fecha ingreso se encuentra vacío o formato inválido'));
        }

        $fechaRetiro = trim(substr($contenido, 306, 10));
        $validar_fechaRetiro = Validator::make(['fecha' => $fechaRetiro], ['fecha' => 'date_format:Y-m-d']);
        if (empty($fechaRetiro) || (!$validar_fechaRetiro)) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2: El campo fecha retiro se encuentra vacío o formato inválido.'));
        }

        $fechaInicioVsp = trim(substr($contenido, 316, 10));
        $validar_fechaInicioVsp = Validator::make(['fecha' => $fechaInicioVsp], ['fecha' => 'date_format:Y-m-d']);
        if (empty($fechaInicioVsp) || (!$validar_fechaInicioVsp)) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2: El campo fecha inicio vsp se encuentra vacío o formato inválido.'));
        }

        if (empty(trim(substr($contenido, 326, 9))) || (!is_numeric(trim(substr($contenido, 326, 9))))) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2 valor mesada pensional.'));
        }

        $fechaRadicacionExterior = trim(substr($contenido, 335, 10));
        $validar_fechaRadicacionExterior = Validator::make(['fecha' => $fechaRadicacionExterior], ['fecha' => 'date_format:Y-m-d']);
        if (empty($fechaRadicacionExterior) || (!$validar_fechaRadicacionExterior)) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2: El campo fecha radicación exterior se encuentra vacío o formato inválido.'));
        }

        $fechaInicioSus = trim(substr($contenido, 345, 10));
        $validar_fechaInicioSus = Validator::make(['fecha' => $fechaInicioSus], ['fecha' => 'date_format:Y-m-d']);
        if (empty($fechaInicioSus) || (!$validar_fechaInicioSus)) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2: El campo fecha inicio sus se encuentra vacío o formato inválido.'));
        }

        $fechaFinSus = trim(substr($contenido, 355, 10));
        $validar_fechaFinSus = Validator::make(['fecha' => $fechaFinSus], ['fecha' => 'date_format:Y-m-d']);
        if (empty($fechaFinSus) || (!$validar_fechaFinSus)) {
            array_push($errores, (substr($contenido, 0, 7) . ': Error liquidación recaudo planilla T2: El campo fecha fin sus se encuentra vacío o formato inválido.'));
        }

        $info = collect($errores);

        return $info;
    }


    /**
     * @param $contenido
     * @return Collection
     */
    public function validarEstructuraTipo3Renglon31Aportantes($contenido)
    {
        $errores = collect();

        if (empty(trim(substr($contenido, 5, 1))) || !('3' === trim(substr($contenido, 5, 1)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 tipo registro.');
        }

        if (empty(trim(substr($contenido, 6, 13))) || !is_numeric(trim(substr($contenido, 6, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 ingreso base cotización.');
        }

        if (empty(trim(substr($contenido, 19, 13))) || !is_numeric(trim(substr($contenido, 19, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 total cotización obligatoria.');
        }

        if (empty(trim(substr($contenido, 32, 13))) || !is_numeric(trim(substr($contenido, 32, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 total upc adicional.');
        }

        return $errores;
    }

    /**
     * @param $contenido
     * @return Collection
     */
    public function validarEstructuraTipo3Renglon36Mora($contenido)
    {
        $errores = collect();

        if (empty(trim(substr($contenido, 5, 1))) || !('3' === trim(substr($contenido, 5, 1)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 tipo registro.');
        }

        if (empty(trim(substr($contenido, 6, 4))) || !is_numeric(trim(substr($contenido, 6, 4)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 36 número días mora liquidado.');
        }

        if (empty(trim(substr($contenido, 10, 11))) || !is_numeric(trim(substr($contenido, 10, 11)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 36 número valor mora cotizaciones.');
        }

        if (empty(trim(substr($contenido, 21, 12))) || !is_numeric(trim(substr($contenido, 21, 12)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 36 número valor mora upc adi.');
        }

        return $errores;
    }

    /**
     * @param $contenido
     * @return Collection
     */
    public function validarEstructuraTipo3Renglon39Totales($contenido)
    {
        $errores = collect();

        if (empty(trim(substr($contenido, 5, 1))) || !('3' === trim(substr($contenido, 5, 1)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 tipo registro.');
        }

        if (empty(trim(substr($contenido, 6, 13))) || !is_numeric(trim(substr($contenido, 6, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 39 total cotización.');
        }

        if (empty(trim(substr($contenido, 19, 13))) || !is_numeric(trim(substr($contenido, 19, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 39 total neto aportantes.');
        }

        if (empty(trim(substr($contenido, 32, 13))) || !is_numeric(trim(substr($contenido, 32, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 39 total upc adicional.');
        }

        return $errores;
    }

    /**
     * @param $contenido
     * @return Collection
     */
    public function validarEstructuraTipo3Renglon31AportesIP($contenido)
    {
        $errores = collect();

        if (empty(trim(substr($contenido, 5, 1))) || !('3' === trim(substr($contenido, 5, 1)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 tipo registro.');
        }

        if (empty(trim(substr($contenido, 6, 13))) || !is_numeric(trim(substr($contenido, 6, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 valor ibc.');
        }

        if (empty(trim(substr($contenido, 19, 13))) || !is_numeric(trim(substr($contenido, 19, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 valor cotización obligatoria.');
        }

        if (empty(trim(substr($contenido, 32, 13))) || !is_numeric(trim(substr($contenido, 32, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 valor aportes fosyga.');
        }

        return $errores;
    }

    /**
     * @param $contenido
     * @return Collection
     */
    public function validarEstructuraTipo3Renglon36MoraIP($contenido)
    {
        $errores = collect();

        if (empty(trim(substr($contenido, 5, 1))) || !('3' === trim(substr($contenido, 5, 1)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 tipo registro.');
        }

        if (empty(trim(substr($contenido, 6, 4))) || !is_numeric(trim(substr($contenido, 6, 4)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 36 número días mora liquidado.');
        }

        if (empty(trim(substr($contenido, 10, 11))) || !is_numeric(trim(substr($contenido, 10, 11)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 36 número valor mora cotizaciones.');
        }

        if (empty(trim(substr($contenido, 21, 11))) || !is_numeric(trim(substr($contenido, 21, 1)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 36 número valor mora upc adicional.');
        }

        return $errores;
    }

    /**
     * @param $contenido
     * @return Collection
     */
    public function validarEstructuraTipo3Renglon37AportesInteresesIP($contenido)
    {
        $errores = collect();

        if (empty(trim(substr($contenido, 5, 1))) || !('3' === trim(substr($contenido, 5, 1)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 tipo registro.');
        }

        if (empty(trim(substr($contenido, 6, 13))) || !is_numeric(trim(substr($contenido, 6, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 37 valor ibc.');
        }

        if (empty(trim(substr($contenido, 19, 13))) || !is_numeric(trim(substr($contenido, 19, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 37 número valor cotizaciones obligatoria.');
        }

        if (empty(trim(substr($contenido, 32, 13))) || !is_numeric(trim(substr($contenido, 32, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 37 número valor upc adicional.');
        }

        return $errores;
    }

    /**
     * @param $contenido
     * @return Collection
     */
    public function validarEstructuraTipo3Renglon39TotalesIP($contenido)
    {
        $errores = collect();

        if (empty(trim(substr($contenido, 5, 1))) || !('3' === trim(substr($contenido, 5, 1)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 31 tipo registro.');
        }

        if (empty(trim(substr($contenido, 6, 13))) || !is_numeric(trim(substr($contenido, 6, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 39 valor ibc.');
        }

        if (empty(trim(substr($contenido, 19, 13))) || !is_numeric(trim(substr($contenido, 19, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 39 número valor cotizaciones obligatoria.');
        }

        if (empty(trim(substr($contenido, 32, 13))) || !is_numeric(trim(substr($contenido, 32, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 39 total aportes fosyga.');
        }

        if (empty(trim(substr($contenido, 45, 13))) || !is_numeric(trim(substr($contenido, 45, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 39 total upc adicional.');
        }

        return $errores;
    }

    /**
     * @param $contenido
     * @return Collection
     */
    public function validarEstructuraTipo3Renglon4($contenido)
    {
        $errores = collect();

        $validarExtra = (empty(trim(substr($contenido, 1, 1))) && strpos('3-5-7', trim(substr($contenido, 1, 1))));

        if (empty(trim(substr($contenido, 1, 1))) || !is_numeric(trim(substr($contenido, 1, 1)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 4 indicador ugpp.');
        }

        if (empty(trim(substr($contenido, 2, 14)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 4 nomactougpp.');
        }

        $fechaApertura = trim(substr($contenido, 16, 10));
        $validar_fechaApertura = Validator::make(['fecha' => $fechaApertura], ['fecha' => 'date_format:Y-m-d']);
        if (($validarExtra && (empty($fechaApertura)) || ($validar_fechaApertura->errors()->messages() !== []))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 4 fecha apertura.');
        }

        if ($validarExtra && empty(trim(substr($contenido, 26, 20)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 4 nombre entidad liq.');
        }

        if (empty(trim(substr($contenido, 46, 13))) || !is_numeric(trim(substr($contenido, 46, 13)))) {
            $errores->push('Error liquidación recaudo planilla: T3 renglon 4 valor pagado.');
        }

        return $errores;
    }


    /**
     * @param $contenido
     * @param $nombreArchivo
     * @return Collection
     */
    public function validarEstructuraRecaudoPlanillaAP($contenido, $nombreArchivo)
    {
        $errores = collect();

        $tiposId = ['NI', 'CC', 'CE', 'TI', 'PA', 'CD', 'SC', 'PE'];
        $clasesPagador = ["A", "B", "I"];
        $tipoPersona = ["N", "J"];
        $formaPresentacion = ["U", "S"];

        if (empty(trim(substr($contenido, 0, 150)))) {
            $errores->push('Error liquidación recaudo planilla: El campo Razón social esta vacío.');
        }

        if (empty(trim(substr($contenido, 150, 2))) || (!in_array(trim(substr($contenido, 150, 2)),$tiposId))) {
            $errores->push('Error liquidación recaudo planilla: El campo Tipo de identificación esta vacío.');
        }

        if (empty(trim(substr($contenido, 152, 16)))) {
            $errores->push('Error liquidación recaudo planilla: Número Identificación esta vacío.');
        }

        if (empty(trim(substr($contenido, 168, 1))) || (!is_numeric(trim(substr($contenido, 168, 1))))) {
            $errores->push('Error liquidación recaudo planilla: Digito de verificación esta vacío.');
        }

        if (empty(trim(substr($contenido, 222, 1))) || (!in_array(trim(substr($contenido, 222, 1)), $formaPresentacion))) {
            $errores->push('Error liquidación recaudo planilla: Forma de presentación esta vacío.');
        } else {
            if (empty(trim(substr($contenido, 169, 10))) && (trim(substr($contenido, 222, 1)) === 'S')) {
                $errores->push('Error liquidación recaudo planilla: Código de sucursal no es válido.');
            }
            if (empty(trim(substr($contenido, 179, 40))) && (trim(substr($contenido, 222, 1)) === 'S')) {
                $errores->push('Error liquidación recaudo planilla: nombre de sucursal no es válido.');
            }
        }

        if (empty(trim(substr($contenido, 219, 1))) || (!in_array(trim(substr($contenido, 219, 1)), $clasesPagador))) {
            $errores->push('Error liquidación recaudo planilla: El campo clase pagador pensión esta vacío.');
        }

        if (empty(trim(substr($contenido, 220, 1))) || (!is_numeric(trim(substr($contenido, 220, 1))))) {
            $errores->push('Error liquidación recaudo planilla: El campo naturaleza juridica esta vacío.');
        }

        if (empty(trim(substr($contenido, 221, 1))) || (!in_array(trim(substr($contenido, 221, 1)), $tipoPersona))) {
            $errores->push('Error liquidación recaudo planilla: El campo tipo persona esta vacío.');
        }

        if (empty(trim(substr($contenido, 222, 1))) || (!in_array(trim(substr($contenido, 222, 1)), $formaPresentacion))) {
            $errores->push('Error liquidación recaudo planilla: El campo forma de presentación esta vacío.');
        }

        if (empty(trim(substr($contenido, 268, 4))) || (!is_numeric(trim(substr($contenido, 268, 4))))) {
            $errores->push('Error liquidación recaudo planilla: El campo código dane esta vacío.');
        }

        if (empty(trim(substr($contenido, 471, 2))) && (!is_numeric(trim(substr($contenido, 471, 2))))) {
            $errores->push('Error liquidación recaudo planilla: El campo código operador esta vacío.');
        }

        $tipoPagadorPension = trim(substr($contenido, 473, 7));
        $validar_fecha = Validator::make(['fecha' => $tipoPagadorPension . '-01'], ['fecha' => 'date_format:Y-m-d']);
        if (empty($tipoPagadorPension)) {
            $errores->push('Error liquidación recaudo planilla: El campo periodo pago esta vacío.');
        } elseif (!$validar_fecha) {
            $errores->push('Error liquidación recaudo planilla: El campo tipo pagador pensión formato inválido.');
        }

        if (empty(trim(substr($contenido, 480, 2))) || (!is_numeric(trim(substr($contenido, 480, 2))))) {
            $errores->push('Error liquidación recaudo planilla: El campo tipo pagador pension esta vacío y el formato no es númerico.');
        } else {
            $tipoAportante = (integer)trim(substr($contenido, 480, 2));
            if ($tipoAportante < 1 || $tipoAportante > 4) {
                $errores->push('Error liquidación recaudo planilla: El campo tipo aportante.');
            }
        }

        return $errores;
    }

    /**
     * @param $contenido
     * @param $nombreArchivo
     * @param $setDepartamentos
     * @param $setMunicipios
     * @return Collection
     */
    public function validarEstructuraRecaudoPlanillaIPEncabezado($contenido, $nombreArchivo, $setDepartamentos, $setMunicipios)
    {
        $errores = collect();

        $formaPresentacion = ['U', 'S'];

        if (empty(trim(substr($contenido, 0, 5))) || !(trim(substr($contenido, 0, 5)) === '00000')) {
            $errores->push('Error liquidación recaudo planilla T1: No cumple con la estructura esta vacío.');
        }

        if (empty(trim(substr($contenido, 5, 1))) || !(trim(substr($contenido, 5, 1)) === '1')) {
            $errores->push('Error liquidación recaudo planilla T1: Tipo registro I esta vacío.');
        }

        if (empty(trim(substr($contenido, 6, 2))) || !(trim(substr($contenido, 6, 2)) === '4' || trim(substr($contenido, 6, 2)) === '04')) {
            $errores->push('Error liquidación recaudo planilla T1: Código formato esta vacío.');
        }

        if (empty(trim(substr($contenido, 8, 16)))) {
            $errores->push('Error liquidación recaudo planilla T1: nit administradora de salud esta vacío.');
        }

        if ((trim(substr($contenido, 24, 1)) === "") || !is_numeric(trim(substr($contenido, 24, 1)))) {
            $errores->push('Error liquidación recaudo planilla T1: digito administradora de salud esta vacío.');
        }

        if (empty(trim(substr($contenido, 25, 150)))) {
            $errores->push('Error liquidación recaudo planilla T1: razón social pagador esta vacío.');
        }

        if (empty(trim(substr($contenido, 175, 2))) || empty(trim(substr($contenido, 227, 16)))) {
            $errores->push('Error liquidación recaudo planilla T1: tipo identificador pagador esta vacío.');
        } elseif (!strpos($nombreArchivo, (trim(substr($contenido, 175, 2)) . '_' . trim(substr($contenido, 177, 16))))) {
            $errores->push('Error liquidación recaudo planilla T1: identificación pagador archivo contenido esta vacío.');
        }

        if (empty(trim(substr($contenido, 193, 1))) || !is_numeric(trim(substr($contenido, 193, 1)))) {
            $errores->push('Error liquidación recaudo planilla T1: digito pagador esta vacío o no es númerico.');
        }

        if (empty(trim(substr($contenido, 194, 1)))) {
            $errores->push('Error liquidación recaudo planilla T1: clase aportante esta vacío.');
        }

        $periodoPago = trim(substr($contenido, 320, 7));
        $validar_periodoPago = Validator::make(['fecha' => $periodoPago . '-01'], ['fecha' => 'date']);
        if (empty($periodoPago) || ($validar_periodoPago->errors()->count() > 0)) {
            $errores->push('Error liquidación recaudo planilla T1: periodo pago esta vacío o formato inválido.');
        }

        $fechaPago = trim(substr($contenido, 327, 10));
        $validar_fechaPago = Validator::make(['fecha' => $fechaPago], ['fecha' => 'date']);
        if (empty($fechaPago) || ($validar_fechaPago->errors()->count() > 0)) {
            $errores->push('Error liquidación recaudo planilla T1: fecha pago esta vacío o formato inválido.');
        }

        if (empty(trim(substr($contenido, 337, 1))) || !in_array(substr($contenido, 337, 1), $formaPresentacion)) {
            $errores->push('Error liquidación recaudo planilla T1: forma de presentación vacío.');
        } else {
            if ((trim(substr($contenido, 337, 1)) === "S") && (empty(trim(substr($contenido, 338, 10))))) {
                $errores->push('Error liquidación recaudo planilla T1: Código sucursal vacío.');
            }
            if ((trim(substr($contenido, 337, 1)) === "S") && (empty(trim(substr($contenido, 348, 40))))) {
                $errores->push('Error liquidación recaudo planilla T1: Nombre sucursal vacío.');
            }
        }

        if (empty(trim(substr($contenido, 388, 7))) || !is_numeric(trim(substr($contenido, 388, 7)))) {
            $errores->push('Error liquidación recaudo planilla T1: número total pensionados esta vacío o no es númerico.');
        }

        if (empty(trim(substr($contenido, 395, 7))) || !is_numeric(trim(substr($contenido, 395, 7)))) {
            $errores->push('Error liquidación recaudo planilla T1: número total de afiliados esta vacío o no es númerico.');
        }

        if (empty(trim(substr($contenido, 402, 2))) || !is_numeric(trim(substr($contenido, 402, 2)))) {
            $errores->push('Error liquidación recaudo planilla T1: código operador esta vacío o no es númerico.');
        }

        if (empty(trim(substr($contenido, 404, 1)))) {
            $errores->push('Error liquidación recaudo planilla T1: tipo planilla esta vacío.');
        }

        if (empty(trim(substr($contenido, 405, 4))) || !is_numeric(trim(substr($contenido, 405, 4)))) {
            $errores->push('Error liquidación recaudo planilla T1: días mora encabezado esta vacío o no es númerico.');
        }

        if (empty(trim(substr($contenido, 409, 8))) || !is_numeric(trim(substr($contenido, 409, 8)))) {
            $errores->push('Error liquidación recaudo planilla T1: número registros salida tipo 2 esta vacío o no es númerico.');
        }

        $fechaActualizacion = trim(substr($contenido, 417, 10));
        $validar_fechaActualizacion = Validator::make(['fecha' => $fechaActualizacion], ['fecha' => 'date']);
        if ((!empty($fechaActualizacion)) && ($validar_fechaActualizacion->errors()->count() > 0)) {
            $errores->push('Error liquidación recaudo planilla T1: fecha actualización esta vacío o no es númerico.');
        }

        return $errores;
    }

    /**
     * @param Date $fecha
     * @return mixed
     */
    public function getDiaHabilSiguienteSemana (Date $fecha) {
        // $calendarInicioSemana = \IntlCalendar::createInstance();
        $calendarInicioSemana = \IntlCalendar::createInstance();
        $calendarInicioSemana->setTime((float) $fecha);
        $calendarInicioSemana->add(\IntlCalendar::FIELD_WEEK_OF_YEAR,1);
        $calendarInicioSemana->set(1,null,\IntlCalendar::DOW_MONDAY,0,0,0);

//        $calendarInicioSemana->put('time', $fecha);
//        $calendarInicioSemana->put(\IntlCalendar::FIELD_WEEK_OF_YEAR,1);
//        $calendarInicioSemana->put(\IntlCalendar::FIELD_DAY_OF_WEEK,\IntlCalendar::DOW_MONDAY);
//        $calendarInicioSemana->put(\IntlCalendar::FIELD_HOUR_OF_DAY,0);
//        $calendarInicioSemana->put(\IntlCalendar::FIELD_MINUTE,0);
//        $calendarInicioSemana->put(\IntlCalendar::FIELD_SECOND,0);
//        $calendarInicioSemana->put(\IntlCalendar::FIELD_MILLISECOND,0);
        //$calendarInicioSemana->setTime((float) $fecha);
       // $calendarInicioSemana->set(1,0,\IntlCalendar::DOW_MONDAY,0,0,0);

        $listaFestivos = DB::table('tb_dia_festivos')->select('fecha')
            ->whereYear('fecha','=', $calendarInicioSemana->get(\IntlCalendar::FIELD_YEAR))->get();

        $resultado = $calendarInicioSemana->getTime();
        $diaHabil = false;

        while (!$diaHabil) {
            if (in_array($resultado, $listaFestivos->toArray())) {
                $calendarInicioSemana->add(\IntlCalendar::FIELD_DAY_OF_WEEK,1);
                $resultado = $calendarInicioSemana->getTime();
            } else {
                $diaHabil = false;
            }
        }

        return $resultado;
    }

    /**
     * @param array $llavesEncabezado
     * @return array|null|[]
     */
    public function getMapaTipoArchivoXEncabezadoPila (array $llavesEncabezado) : array {

        $resultado = collect();
        $CONSECUTIVO_ENCABEZADO = 0;
        $TIPO_ARCHIVO = 1;

        $lists = DB::table('rec_recaudo_planilla_encabezados')
            ->select('consecutivo_recaudo','tipo_archivo')
            ->whereIn('consecutivo_recaudo', $llavesEncabezado)->get();

        foreach ($lists as $key => $list) {
            $resultado->put($list->consecutivo_recaudo, (string) $list->tipo_archivo);
        }

        return  $resultado->toArray();
    }



}