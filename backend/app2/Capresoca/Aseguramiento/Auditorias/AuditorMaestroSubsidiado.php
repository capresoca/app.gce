<?php


namespace App\Capresoca\Aseguramiento\Auditorias;


use App\Capresoca\Aseguramiento\NovedadAutomaticaSinAnterior;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Aseguramiento\AsEstadoafiliado;
use App\Models\Aseguramiento\AsEtnia;
use App\Models\Aseguramiento\AsMsauditoria;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsPobespeciale;
use App\Models\Aseguramiento\AsTipafiliado;
use App\Models\General\GnMunicipio;
use App\Models\General\GnTipdocidentidade;
use App\Models\Temporales\TempMsubsidiado;
use Illuminate\Support\Facades\DB;

class AuditorMaestroSubsidiado
{
    protected $periodo;
    protected $tipoDocumentos;
    protected $municipios;
    protected $etnias;
    protected $pobEspecial;
    protected $tipoAfiliados;
    protected $estados;

    public function __construct($periodo)
    {
        $this->periodo = $periodo;
        $this->tipoDocumentos = GnTipdocidentidade::all();
        $this->municipios = GnMunicipio::all();
        $this->etnias = AsEtnia::all();
        $this->pobEspecial = AsPobespeciale::all();
        $this->tipoAfiliados = AsTipafiliado::all();
        $this->estados = AsEstadoafiliado::all();
    }
    public function run(){
        return [
            'Inicio del Proceso' =>  date("Y-m-d H:i:s"),
            'Seriales BDUA Actualizados' =>   $this->actualizarSerialBDUA(),
            'Nuevos Ingresos' => $this->nuevosIngresos(),
            'Retiros' => $this->posiblesRetiros(),
            'Actualización Estado' => $this->actualizarEstado(),
            'Actualización Tipo de Documento' => $this->actualizarTipoDocumento(),
            'Actualización Identificación' => $this->actualizarNumeroDocumento(),
            'Actualización Primer Nombre' => $this->actualizarNombreUno(),
            'Actualización Segundo Nombre' => $this->actualizarNombreDos(),
            'Actualización Primer Apellido' => $this->actualizarApellidoUno(),
            'Actualizacion Segundo Apellido' => $this->actualizarApellidoDos(),
            'Actualización Fecha Nacimiento' => $this->actualizarFechaNacimiento(),
            'Actualización Actualizacion Fecha SGSSS' => $this->actualizarFechaSgsss(),
            'Actualización Fecha Afiliacion EPS' => $this->actualizarFechaAfiliacion(),
            'Actualización Sexo' => $this->actualizarSexo(),
            'Actualización Zona' => $this->actualizarZona(),
            'Actualización Municipio' => $this->actualizarMunicipio(),
            'Actualizacion Regimen' => $this->movilidad(),
            'Fin del Proceso' => date("Y-m-d H:i:s"),

        ];
    }

    //Afiliados que no tienen Serial BDUA
    public function actualizarSerialBDUA(){
        //Busca afiliados que no tengan serial BDUA y realizar la busqueda en el MS para actualizarlo.
        $modificados = 0;
        $actualizables = AsAfiliado::select('as_afiliados.id', 'as_afiliados.serial_bdua','temp_msubsidiados.codigo_bdua', 'as_afiliados.tipo_identificacion', 'as_afiliados.identificacion')
            ->join('temp_msubsidiados', 'as_afiliados.identificacion', '=', 'temp_msubsidiados.numero_identificacion')
            ->whereColumn('as_afiliados.tipo_identificacion', 'temp_msubsidiados.tipo_identificacion')
            ->whereColumn('as_afiliados.nombre1', 'temp_msubsidiados.primer_nombre')
            ->whereColumn('as_afiliados.apellido1', 'temp_msubsidiados.primer_apellido')
            ->whereNull('as_afiliados.serial_bdua')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        foreach ($actualizables as $afiliado){
            $afiliado->serial_bdua = $afiliado->codigo_bdua;
            $afiliado->save();
            AsMsauditoria::create([
                'as_msubsidiado_id' => $this->periodo,
                'accion' => 'Actualizar Afiliado',
                'id_registro' => $afiliado->id,
                'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó serial BDUA',
                'ejecutada' => true
            ]);
            $modificados++;
        }
        return $modificados;

    }

    //Afiliados que no estan en CPSC pero si en BDUA
    public  function nuevosIngresos(){


        $afiliados = AsAfiliado::select('serial_bdua')->get()->pluck('serial_bdua')->toArray();
        $temporales = TempMsubsidiado::select('codigo_bdua')->where('as_msubsidiado_id', $this->periodo)->get()->pluck('codigo_bdua')->toArray();
        $nuevos = array_diff($temporales,$afiliados);
        $tempNuevos = TempMsubsidiado::whereIn('codigo_bdua', $nuevos)->get();

        $creados = 0;
        foreach ($tempNuevos as $nuevo){

            try{
                $afiliadoCoincidencias = AsAfiliado::where([
                    ['identificacion', $nuevo->identificacion],
                    ['tipo_identificacion', $nuevo->tipo_identificacion]
                ])
                    ->orWhere(function ($query) use ($nuevo){
                        $query->where('identificacion', $nuevo->identificacion)
                            ->where('fecha_nacimiento', date_format(date_create_from_format('d/m/Y',$nuevo->fecha_nacimiento),'Y-m-d'));
                    })
                    ->orWhere(function ($query) use ($nuevo){
                        $query->where('nombre1', $nuevo->primer_nombre)
                            ->where('apellido1', $nuevo->primer_apellido)
                            ->where('fecha_nacimiento', date_format(date_create_from_format('d/m/Y',$nuevo->fecha_nacimiento),'Y-m-d'));
                    })
                    ->get();

                if($afiliadoCoincidencias->count() == 0 ){
                    $nuevoAfiliado = AsAfiliado::create([
                        'gn_tipdocidentidad_id' => $this->tipoDocumentos->firstWhere('abreviatura',$nuevo->tipo_identificacion)->id,
                        'identificacion' =>  $nuevo->numero_identificacion,
                        'nombre1' => $nuevo->primer_nombre,
                        'nombre2' => $nuevo->segundo_nombre,
                        'apellido1' => $nuevo->primer_apellido,
                        'apellido2' => $nuevo->segundo_apellido,
                        'nombre_completo' => $nuevo->primer_nombre.' '.$nuevo->segundo_nombre.' '.$nuevo->primer_apellido.' '.$nuevo->segundo_apellido,
                        'direccion' => 'ACTUALIZAR' ,
                        'telefono_fijo' => 'ACTUALIZAR',
                        'celular' => 'ACTUALIZAR',
                        'correo_electronico' => 'actualizar@actualizar.com',
                        'gn_municipio_id' => $this->municipios->firstWhere('codigo',$nuevo->codigo_departamento.$nuevo->codigo_municipio)->id,
                        'as_regimene_id' => 2, //Subsidiado
                        'as_etnia_id' => $nuevo->pertenecia_etnica != '' ? $this->etnias->firstWhere('codigo',$nuevo->pertenecia_etnica)->id : null,
                        'ficha_sisben' => $nuevo->ficha_sisben,
                        'nivel_sisben' => $nuevo->nivel_sisben,
                        'as_pobespeciale_id' => $this->pobEspecial->firstWhere('codigo', $nuevo->grupo_poblacional)->id,
                        'estado' => $this->estados->firstWhere('codigo', $nuevo->estado)->id,
                        'gn_zona_id' => $nuevo->zona == 'U' ? 1 : 2 ,
                        'fecha_nacimiento' => date_format(date_create_from_format('d/m/Y',$nuevo->fecha_nacimiento),'Y-m-d'),
                        'gn_sexo_id' => $nuevo->sexo == 'F' ? 1 : 2 ,
                        'as_tipafiliado_id' => $this->tipoAfiliados->firstWhere('codigo_planos', $nuevo->tipo_afiliado)->id,
                        'fecha_afiliacion' => date_format(date_create_from_format('d/m/Y',$nuevo->fecha_afiliacion_eps),'Y-m-d'),
                        'fecha_sgsss' => date_format(date_create_from_format('d/m/Y', $nuevo->fecha_afiliacion_sistema), 'Y-m-d'),
                        'serial_bdua' => $nuevo->codigo_bdua,
                        'tipo_identificacion' => $nuevo->tipo_identificacion,
                        'gn_paise_id' => 45,

                    ]);

                    AsMsauditoria::create([
                        'as_msubsidiado_id' => $this->periodo,
                        'accion' => 'Crear Afiliado',
                        'id_registro' => $nuevoAfiliado->id,
                        'detalle' => $nuevoAfiliado->tipo_identificacion.' '.$nuevoAfiliado->identificacion.' | Se creó afiliado desde BDUA',
                        'ejecutada' => true
                    ]);
                    $creados++;

                }else{
                    AsMsauditoria::create([
                        'as_msubsidiado_id' => $this->periodo,
                        'accion' => 'Crear Afiliado',
                        'id_registro' => 0,
                        'detalle' => $nuevo->tipo_identificacion.' '.$nuevo->numero_identificacion. ' | No se pudo crear afiliado desde BDUA.| \n BDUA:'. json_encode($nuevo->toArray()).'| \n CAPRESOCA: '. json_encode($afiliadoCoincidencias->toArray()),
                        'ejecutada' => false
                    ]);
                }

            }catch (\Exception $exception){
                //return $exception;
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Crear Afiliado',
                    'id_registro' => 0,
                    'detalle' => $nuevo->tipo_identificacion.' '.$nuevo->numero_identificacion. ' | No se pudo crear afiliado desde BDUA.| \n BDUA:'. json_encode($nuevo->toArray()).'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }

        return $creados;
    }

    //Afiliados que estan en CPSC perono estan en BDUA
    public function posiblesRetiros(){
        $afiliados = AsAfiliado::select('serial_bdua')->where('as_regimene_id',2)->whereNotIn('estado',[1,2,8,4])->get()->pluck('serial_bdua')->toArray();
        $temporales = TempMsubsidiado::select('codigo_bdua')->where('as_msubsidiado_id', $this->periodo)->get()->pluck('codigo_bdua')->toArray();
        $retiros = array_diff($afiliados,$temporales);
        $afiliadosRetiros = AsAfiliado::whereIn('serial_bdua', $retiros)->get();
        $retiros = 0;
        foreach ($afiliadosRetiros as $afiliado){

            $relaciones = AsAfiliadoPagador::where('as_afiliado_id',$afiliado->id)->where('estado','Activo')->get();
            if($relaciones->count()){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | RETIRADO | El afiliado no se encontró en el archivo maestro, pero tiene relaciones laborales activas ',
                    'ejecutada' => false
                ]);
            }else{
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | RETIRADO | El afiliado no se encontró en el archivo maestro ',
                    'ejecutada' => true
                ]);
            }
            $afiliado->estado = 4;
            $afiliado->save();
            $retiros++;

        }
        return $retiros;

    }

    public function actualizarTipoDocumento(){

        $evoluciones = [
            'CNRC' => 1,
            'CNTI' => 1,
            'CNCC' => 1,

            'RCTI' => 1,
            'RCCC' => 1,

            'TICC' => 1,

            'CCTI' => 0,
            'CCRC' => 0,
            'CCCN' => 0,

            'TIRC' => 0,
            'TICN' => 0,

            'RCCN' => 0,

        ];
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.tipo_identificacion as cpsc', 'temp_msubsidiados.tipo_identificacion as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->whereColumn('as_afiliados.tipo_identificacion','!=', 'temp_msubsidiados.tipo_identificacion')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();

        foreach ($actualizables as $afiliado){
            try{
                if($afiliado->bdua == 'CN' || $afiliado->bdua == 'RC' || $afiliado->bdua == 'TI' || $afiliado->bdua == 'CC' ){
                    if($evoluciones[$afiliado->bdua.$afiliado->cpsc] == 1 ){
                        // Buscar la novedad, si existe colocar el de bdua, si no crear la novedad y dejar el de bdua
                        $tramites = AsNovtramite::where([
                            ['as_afiliado_id', $afiliado->id],
                            ['as_tipnovedade_id', 19]
                        ])
                            ->whereHas('tramite', function ($query){
                                $query->whereIn('estado', ['Radicado','Enviado','Registrado']);
                            })
                            ->get();
                        if($tramites->count() == 0){
                            //Generar novedad
                            try{

                                new NovedadAutomaticaSinAnterior($afiliado, 'N01', ['v1' => $this->tipoDocumentos->firstWhere('abreviatura',$afiliado->bdua)->id,'v2' => $afiliado->identificacion, 'v3' => $afiliado->fecha_nacimiento, 'v4' => 1]);

                                AsMsauditoria::create([
                                    'as_msubsidiado_id' => $this->periodo,
                                    'accion' => 'Generar Novedad',
                                    'id_registro' => $afiliado->id,
                                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se generó novedad de evolucion de tipo de documento CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                                    'ejecutada' => true
                                ]);
                            }catch (\Exception $exception){
                                AsMsauditoria::create([
                                    'as_msubsidiado_id' => $this->periodo,
                                    'accion' => 'Generar Novedad',
                                    'id_registro' => $afiliado->id,
                                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | No se pudo generar novedad de evolucion de tipo de documento | ERROR : '.$exception->getMessage(),
                                    'ejecutada' => false
                                ]);
                                continue;
                            }

                        }
                    }
                }

                $afiliado->gn_tipdocidentidad_id = $this->tipoDocumentos->firstWhere('abreviatura',$afiliado->bdua)->id;
                $afiliado->tipo_identificacion = $afiliado->bdua;
                $afiliado->save();

                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó Tipo de Documento del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar el tipo de documento). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                //return $exception;
                continue;
            }


        }

        return $actualizables->count();
    }

    public function actualizarNumeroDocumento(){
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.identificacion as cpsc', 'temp_msubsidiados.numero_identificacion as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->whereColumn('as_afiliados.identificacion','!=', 'temp_msubsidiados.numero_identificacion')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->identificacion = $afiliado->bdua;
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el Documento del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar el documento del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function actualizarNombreUno(){
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.nombre1 as cpsc', 'temp_msubsidiados.primer_nombre as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->whereColumn('as_afiliados.nombre1','!=', 'temp_msubsidiados.primer_nombre')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->nombre1 = $afiliado->bdua;
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el Primer Nombre del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar el Primer Nombre del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function actualizarNombreDos(){
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.nombre2 as cpsc', 'temp_msubsidiados.segundo_nombre as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->whereColumn('as_afiliados.nombre2','!=', 'temp_msubsidiados.segundo_nombre')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->nombre2 = $afiliado->bdua;
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el Segundo Nombre del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar el Segundo Nombre del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function actualizarApellidoUno(){
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.apellido1 as cpsc', 'temp_msubsidiados.primer_apellido as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->whereColumn('as_afiliados.apellido1','!=', 'temp_msubsidiados.primer_apellido')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->apellido1 = $afiliado->bdua;
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el Primer Apellido del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar el Primer Apellido del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function actualizarApellidoDos(){
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.apellido2 as cpsc', 'temp_msubsidiados.segundo_apellido as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->whereColumn('as_afiliados.apellido2','!=', 'temp_msubsidiados.segundo_apellido')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->apellido2 = $afiliado->bdua;
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el Segundo Apellido del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar el Segundo Apellido del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function actualizarFechaNacimiento(){
        $actualizables = AsAfiliado::select('as_afiliados.*',DB::raw('DATE_FORMAT(as_afiliados.fecha_nacimiento, "%d/%m/%Y") AS cpsc'), 'temp_msubsidiados.fecha_nacimiento as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->whereColumn(DB::raw('DATE_FORMAT(as_afiliados.fecha_nacimiento, "%d/%m/%Y")'),'!=', 'temp_msubsidiados.fecha_nacimiento')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        //return $actualizables;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->fecha_nacimiento = date_format(date_create_from_format('d/m/Y',$afiliado->bdua),'Y-m-d');
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó la fecha de nacimiento del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar la fecha de nacimiento del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function actualizarFechaSgsss(){
        $actualizables = AsAfiliado::select('as_afiliados.*',DB::raw('DATE_FORMAT(as_afiliados.fecha_sgsss, "%d/%m/%Y") AS cpsc'), 'temp_msubsidiados.fecha_afiliacion_sistema as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->whereColumn(DB::raw('DATE_FORMAT(as_afiliados.fecha_sgsss, "%d/%m/%Y")'),'!=', 'temp_msubsidiados.fecha_afiliacion_sistema')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        //return $actualizables;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->fecha_sgsss = date_format(date_create_from_format('d/m/Y',$afiliado->bdua),'Y-m-d');
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó la fecha de afiliacion al SGSSS del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar la fecha de afiliacion al SGSSS del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function actualizarFechaAfiliacion(){
        $actualizables = AsAfiliado::select('as_afiliados.*',DB::raw('DATE_FORMAT(as_afiliados.fecha_afiliacion, "%d/%m/%Y") AS cpsc'), 'temp_msubsidiados.fecha_afiliacion_eps as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->whereColumn(DB::raw('DATE_FORMAT(as_afiliados.fecha_afiliacion, "%d/%m/%Y")'),'!=', 'temp_msubsidiados.fecha_afiliacion_eps')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        //return $actualizables;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->fecha_afiliacion = date_format(date_create_from_format('d/m/Y',$afiliado->bdua),'Y-m-d');
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó la fecha de afiliacion a la EPS del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar la fecha de afiliacion a la EPS del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function actualizarSexo(){

        $actualizables = AsAfiliado::select('as_afiliados.*','gn_sexos.abreviatura as cpsc', 'temp_msubsidiados.sexo as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->join('gn_sexos', 'as_afiliados.gn_sexo_id', '=', 'gn_sexos.id')
            ->whereColumn('gn_sexos.abreviatura','!=', 'temp_msubsidiados.sexo')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->gn_sexo_id = $afiliado->bdua == 'F' ? 1 : 2 ;
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el Sexo del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar el Sexo del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function actualizarZona(){

        $actualizables = AsAfiliado::select('as_afiliados.*','gn_zonas.codigo as cpsc', 'temp_msubsidiados.zona as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->join('gn_zonas', 'as_afiliados.gn_zona_id', '=', 'gn_zonas.id')
            ->whereColumn('gn_zonas.codigo','!=', 'temp_msubsidiados.zona')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->gn_zona_id = $afiliado->bdua == 'U' ? 1 : 2 ;
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó la Zona del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar la Zona del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function actualizarMunicipio(){

        $actualizables = AsAfiliado::select('as_afiliados.*','gn_municipios.codigo as cpsc', DB::raw('concat_ws("",temp_msubsidiados.codigo_departamento,temp_msubsidiados.codigo_municipio) as bdua'))
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->join('gn_municipios', 'as_afiliados.gn_municipio_id', '=', 'gn_municipios.id')
            ->whereColumn('gn_municipios.codigo','!=',  DB::raw('concat_ws("",temp_msubsidiados.codigo_departamento,temp_msubsidiados.codigo_municipio)'))
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        //return $actualizables;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->gn_municipio_id = $this->municipios->firstWhere('codigo',$afiliado->bdua)->id;
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el Municipio del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar el municipio del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function actualizarGrupoPoblacional(){

    }

    public function movilidad(){
        $movilidad = AsAfiliado::select('as_afiliados.*')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->where('as_afiliados.as_regimene_id', 1)
            ->whereNotIn('as_afiliados.estado', [1,2])
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($movilidad as $afiliado){
            $relacionesActivas = AsAfiliadoPagador::where('as_afiliado_id', $afiliado->id)
                ->where('estado', 'Activo')
                ->get();
            if($relacionesActivas->count() > 0){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el regimen del Afiliado. Afiliado Pendiente MOVILIDAD al contributivo',
                    'ejecutada' => true
                ]);
            }else{
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el regimen del Afiliado',
                    'ejecutada' => true
                ]);
                $actualizados++;

            }
            $afiliado->as_regimene_id = 2;
            $afiliado->save();
        }
        return $actualizados;
    }

    public function actualizarEstado(){
        // EStado como esté en el adres
        $actualizables = AsAfiliado::select('as_afiliados.*','as_estadoafiliados.codigo as cpsc', 'temp_msubsidiados.estado as bdua')
            ->join('temp_msubsidiados', 'as_afiliados.serial_bdua', '=', 'temp_msubsidiados.codigo_bdua')
            ->join('as_estadoafiliados', 'as_afiliados.estado', '=', 'as_estadoafiliados.id')
            ->whereColumn('as_estadoafiliados.codigo','!=', 'temp_msubsidiados.estado')
            ->where('temp_msubsidiados.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->estado = $this->estados->firstWhere('codigo',$afiliado->bdua)->id;
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el ESTADO del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar el ESTADO del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }
}