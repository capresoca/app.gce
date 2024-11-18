<?php

namespace App\Http\Controllers\Riips;

use App\Http\Controllers\Controller;
use App\Mail\AccesoConcedidoEmail;
use App\Mail\AccesoDenegadoEmail;
use App\Models\Niif\GnTercero;
use App\Models\RedServicios\RsEntidade;
use App\Models\Riips\RsEntidadTemporale;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EntidadTemporaleController extends Controller {

	public function index($value = '') {
		return response()->json([
			'entidades' => RsEntidadTemporale::with(['RsTipentidade', 'GnTipodocidentidad', 'GnMunicipioExpedicion.Departamento', 'GnMunicipioSede.Departamento', 'GnMunicipioResidencia.Departamento', 'NfCiiu'])->get(),
		]);
	}

	public function store(Request $request) {
		try {
			DB::beginTransaction();
			$entidadTemporal = RsEntidadTemporale::where('id', $request->id)->first();
			if (is_null($entidadTemporal)) {
				return response()->json([
					'state' => 'error',
					'message' => 'La entidad temporal no existe. Verifique que la entidad ya este habilitada',
				]);
			}
			$tercero = GnTercero::where('gn_tipdocidentidad_id', $entidadTemporal->gn_tipdocidentidad_id)->where('identificacion', $entidadTemporal->identificacion)->first();
			if (is_null($tercero)) {
				$tercero = new GnTercero();
				$tercero->gn_tipdocidentidad_id = $entidadTemporal->gn_tipdocidentidad_id;
				$tercero->identificacion = $entidadTemporal->identificacion;
				$tercero->gn_munexpedicion_id = $entidadTemporal->gn_munexpedicion_id;
				$tercero->nombre1 = $entidadTemporal->nombre1;
				$tercero->nombre2 = $entidadTemporal->nombre2;
				$tercero->apellido1 = $entidadTemporal->apellido1;
				$tercero->apellido2 = $entidadTemporal->apellido2;
				$tercero->razon_social = $entidadTemporal->razon_social;
				$tercero->direccion = $entidadTemporal->direccion;
				$tercero->telefono_fijo = $entidadTemporal->telefono_fijo;
				$tercero->celular = $entidadTemporal->celular;
				$tercero->correo_electronico = $entidadTemporal->correo_electronico;
				$tercero->gn_municipio_id = $entidadTemporal->gn_municipio_id;
				$tercero->tipo_retencion = $entidadTemporal->tipo_retencion;
				$tercero->tipo_contribuyente = $entidadTemporal->tipo_contribuyente;
				$tercero->tipo_persona = $entidadTemporal->tipo_persona;
				$tercero->tipo_tercero = $entidadTemporal->tipo_tercero;
				$tercero->ica = $entidadTemporal->ica;
				$tercero->porcentaje_ica = $entidadTemporal->porcentaje_ica;
				$tercero->autorretenedor = $entidadTemporal->autorretenedor;
				$tercero->agente_declarante = $entidadTemporal->agente_declarante;
				$tercero->nf_ciiu_id = $entidadTemporal->nf_ciiu_id;
				$tercero->save();
			}
			$entidad = RsEntidade::where('cod_habilitacion', $entidadTemporal->cod_habilitacion)->first();
			$entidadPricipal = RsEntidade::where('gn_tercero_id', $tercero->id)->where('tipo_locacion', 'Principal')->count();
			if (is_null($entidad)) {
				$entidad = new RsEntidade();
				$entidad->gn_tercero_id = $tercero->id;
				$entidad->rs_tipentidade_id = $entidadTemporal->rs_tipentidade_id;
				$entidad->nombre = $entidadTemporal->nombre;
				$entidad->res_habilitacion = $entidadTemporal->res_habilitacion;
				$entidad->cod_habilitacion = $entidadTemporal->cod_habilitacion;
				$entidad->correo_electronico_sede = $entidadTemporal->correo_electronico_sede;
				$entidad->telefono_sede = $entidadTemporal->telefono_sede;
				$entidad->direccion_sede = $entidadTemporal->direccion_sede;
				$entidad->gn_municipiosede_id = $entidadTemporal->gn_municipiosede_id;
				if ($entidadPricipal == 0) {
					$entidad->tipo_locacion = 'Principal';
				} else {
					$entidad->tipo_locacion = 'Sede';
				}
				$entidad->gerente = $entidadTemporal->gerente;
				$entidad->replegal = $entidadTemporal->replegal;
				$entidad->naturaleza = $entidadTemporal->naturaleza;
				$entidad->complejidad = $entidadTemporal->complejidad;
				$entidad->save();
			}
			$usuario = User::where('email', $entidad->correo_electronico_sede)->first();
			if (is_null($usuario)) {
				$usuario = new User();
				$usuario->identification = $entidadTemporal->cod_habilitacion;
				$usuario->name = $entidadTemporal->nombre;
				$usuario->email = $entidad->correo_electronico_sede;
				$usuario->password = Hash::make($entidadTemporal->cod_habilitacion);
				$usuario->phone = $tercero->celular;
				$usuario->state = 'Activo';
				$usuario->tipo = 'Entidad';
				$usuario->rs_entidad_id = $entidad->id;
				$usuario->save();
			}
			$entidadTemporal->delete();
			$objDemo = new \stdClass();
			$objDemo->email = $entidad->correo_electronico_sede;
			$objDemo->password = $usuario->identification;
			$objDemo->entidad = $usuario->name;
			$objDemo->sender = 'Capresoca EPS';
			$objDemo->receiver = $usuario->name;
			Mail::to($entidad->correo_electronico_sede)->send(new AccesoConcedidoEmail($objDemo));
			DB::commit();
			return response()->json([
				'state' => 'save',
				'entidad' => $entidad,
				'tercero' => $tercero,
				'usuario' => $usuario,
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json([
				'message' => 'Error en el servidor' . $e->getMessage(),
				'errors' => $e->getMessage(),
			], 500);
		} catch (\Swift_TransportException $e) {
			DB::commit();
			return response()->json([
				'message' => 'Error al enviar el correo con la confirmacion de la inscripcion de la entidad. La entidad se pudo haber creado ' . $e->getMessage(),
				'errors' => $e->getMessage(),
			], 500);
		}
	}

	public function show($id) {
		//
	}

	public function update(Request $request, $id) {
		//
	}

	public function destroy(Request $request) {
		try {
			$entidadTemporal = RsEntidadTemporale::where('id', $request->id)->first();
			$envieEmail = $this->sendEmailAccesoDenegado($entidadTemporal->razon_social, $entidadTemporal->correo_electronico_sede, $request->motivo);
			$entidadTemporal->delete();
			if ($envieEmail) {
				return response()->json([
					'state' => 'delete',
				]);
			} else {
				return response()->json([
					'state' => 'deleteNoEmail',
				]);
			}
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Error en el servidor' . $e->getMessage(),
				'errors' => $e->getMessage(),
			], 500);
		}
	}

	public function sendEmailAccesoDenegado($entidad, $email, $motivo) {
		try {
			$objDemo = new \stdClass();
			$objDemo->motivo = $motivo;
			$objDemo->email = $email;
			$objDemo->entidad = $entidad;
			$objDemo->sender = 'Capresoca EPS';
			$objDemo->receiver = $entidad;
			Mail::to($email)->send(new AccesoDenegadoEmail($objDemo));
			return true;
		} catch (Exception $e) {
			return false;
		} catch (\Swift_TransportException $e) {
			return true;
		}
	}
}
