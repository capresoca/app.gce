<?php


namespace App\Capresoca\Aseguramiento\Auditorias;


use App\Capresoca\Aseguramiento\NovedadAutomaticaSinAnterior;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Aseguramiento\AsEstadoafiliado;
use App\Models\Aseguramiento\AsMsauditoria;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsTipafiliado;
use App\Models\General\GnMunicipio;
use App\Models\General\GnTipdocidentidade;
use App\Models\Temporales\TempMcontributivo;
use Illuminate\Support\Facades\DB;

class AuditorMaestroContributivo
{
    protected $periodo;
    protected $tipoDocumentos;
    protected $municipios;
    protected $tipoAfiliados;
    protected $estados;

    public function __construct($periodo)
    {
        $this->periodo = $periodo;
        $this->tipoAfiliados = AsTipafiliado::all();
        $this->estados = AsEstadoafiliado::all();
        $this->tipoDocumentos = GnTipdocidentidade::all();
        $this->municipios = GnMunicipio::where('gn_departamento_id', 11)->get();
    }

    public function run(){
        return [
            'Inicio Proceso' =>  date("Y-m-d H:i:s"),
            'Seriales BDUA Actualizados' => $this->actualizarSerial(),
            'Nuevos Ingresos' => $this->nuevosIngresos(),
            'Actualización Tipo Documento' => $this->actualizarTipoDocumento(),
            'Actualización Numero Documento' => $this->actualizarNumeroDocumento(),
            'Actualización Primer Nombre' => $this->actualizarNombreUno(),
            'Actualización Segundo Nombre' => $this->actualizarNombreDos(),
            'Actualización Primer Apellido' => $this->actualizarApellidoUno(),
            'Actualización Segundo Apellido' => $this->actualizarApellidoDos(),
            'Actualización Fecha de Nacimiento' =>$this-> actualizarFechaNacimiento(),
            'Actualización Estado' => $this->actualizarEstado(),
            'Actualización Tipo de Afiliado' => $this->actualizarTipoAfiliado(),
            'Actualización Regimen' => $this->cambiarRegimen(),
            'Fecha Afiliación Efectiva' => $this->fechaEfectiva(),
            'Fin Proceso' => date("Y-m-d H:i:s"),

        ];
    }

    public function actualizarSerial(){
        //Busca afiliados que no tengan serial BDUA y realizar la busqueda en el MS para actualizarlo.
        $modificados = 0;
        $actualizables = AsAfiliado::select('as_afiliados.*','temp_mcontributivos.SERIAL')
            ->join('temp_mcontributivos', 'as_afiliados.identificacion', '=', 'temp_mcontributivos.NUMERO_IDENTIFICACION')
            ->whereColumn('as_afiliados.tipo_identificacion', 'temp_mcontributivos.TIPO_IDENTIFICACION')
            ->whereColumn('as_afiliados.nombre1', 'temp_mcontributivos.PRIMER_NOMBRE')
            ->whereColumn('as_afiliados.apellido1', 'temp_mcontributivos.PRIMER_APELLIDO')
            ->whereNull('as_afiliados.serial_bdua')
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
            ->get();
        foreach ($actualizables as $afiliado){
            $afiliado->serial_bdua = $afiliado->SERIAL;
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

    public  function nuevosIngresos(){

        $afiliados = AsAfiliado::select('serial_bdua')->get()->pluck('serial_bdua')->toArray();
        $temporales = TempMcontributivo::select('SERIAL')->where('as_msubsidiado_id', $this->periodo)->get()->pluck('SERIAL')->toArray();
        $nuevos = array_diff($temporales,$afiliados);
        $tempNuevos = TempMcontributivo::whereIn('SERIAL', $nuevos)->get();

        $creados = 0;
        foreach ($tempNuevos as $nuevo){
            // return ucfirst(strtolower($nuevo->ESTADO));
            try{
                $afiliadoCoincidencias = AsAfiliado::where([
                    ['identificacion', $nuevo->NUMERO_IDENTIFICACION],
                    ['tipo_identificacion', $nuevo->TIPO_IDENTIFICACION]
                ])
                    ->orWhere(function ($query) use ($nuevo){
                        $query->where('identificacion', $nuevo->NUMERO_IDENTIFICACION)
                            ->where('fecha_nacimiento', date_format(date_create_from_format('d/m/Y',$nuevo->NACIMIENTO_FECHA),'Y-m-d'));
                    })
                    ->orWhere(function ($query) use ($nuevo){
                        $query->where('nombre1', $nuevo->PRIMER_NOMBRE)
                            ->where('apellido1', $nuevo->PRIMER_APELLIDO)
                            ->where('fecha_nacimiento', date_format(date_create_from_format('d/m/Y',$nuevo->NACIMIENTO_FECHA),'Y-m-d'));
                    })
                    ->get();

                if($afiliadoCoincidencias->count() == 0 ){
                    $nuevoAfiliado = AsAfiliado::create([
                        'gn_tipdocidentidad_id' => $this->tipoDocumentos->firstWhere('abreviatura',$nuevo->TIPO_IDENTIFICACION)->id,
                        'identificacion' =>  $nuevo->NUMERO_IDENTIFICACION,
                        'nombre1' => $nuevo->PRIMER_NOMBRE,
                        'nombre2' => $nuevo->SEGUNDO_NOMBRE,
                        'apellido1' => $nuevo->PRIMER_APELLIDO,
                        'apellido2' => $nuevo->SEGUNDO_APELLIDO,
                        'nombre_completo' => $nuevo->PRIMER_NOMBRE.' '.$nuevo->SEGUNDO_NOMBRE.' '.$nuevo->PRIMER_APELLIDO.' '.$nuevo->SEGUNDO_APELLIDO,
                        'direccion' => 'ACTUALIZAR' ,
                        'telefono_fijo' => 'ACTUALIZAR',
                        'celular' => 'ACTUALIZAR',
                        'correo_electronico' => 'actualizar@actualizar.com',
                        'gn_municipio_id' => $this->municipios->firstWhere('nombre',$nuevo->MUNI) ? $this->municipios->firstWhere('nombre',$nuevo->MUNI)->id : 400,
                        'as_regimene_id' => 1, //Contributivo
                        //'as_etnia_id' => $nuevo->pertenecia_etnica != '' ? $this->etnias->firstWhere('codigo',$nuevo->pertenecia_etnica)->id : null,
                        //'ficha_sisben' => $nuevo->ficha_sisben,
                        'nivel_sisben' => -1,
                        'as_pobespeciale_id' => 4,
                        'estado' => $this->estados->firstWhere('xml', $nuevo->ESTADO)->id,
                        'gn_zona_id' => 1,
                        'fecha_nacimiento' => date_format(date_create_from_format('d/m/Y',$nuevo->NACIMIENTO_FECHA),'Y-m-d'),
                        'gn_sexo_id' => 2 ,
                        'as_tipafiliado_id' => $this->tipoAfiliados->firstWhere('codigo_planos', ucfirst(strtolower($nuevo->TIPO_AFILIADO))) ? $this->tipoAfiliados->firstWhere('codigo_planos', ucfirst(strtolower($nuevo->TIPO_AFILIADO)))->id : 1,
                        'fecha_afiliacion' => date_format(date_create_from_format('d/m/Y',$nuevo->AFILIACION_ENTIDAD_FECHA),'Y-m-d'),
                        'fecha_sgsss' => date_format(date_create_from_format('d/m/Y', $nuevo->FECHA_AFILIACION_EFECTIVA), 'Y-m-d'),
                        'serial_bdua' => $nuevo->SERIAL,
                        'tipo_identificacion' => $nuevo->TIPO_IDENTIFICACION,
                        'gn_paise_id' => 45,

                    ]);

                    AsMsauditoria::create([
                        'as_msubsidiado_id' => $this->periodo,
                        'accion' => 'Crear Afiliado',
                        'id_registro' => $nuevoAfiliado->id,
                        'detalle' => $nuevoAfiliado->tipo_identificacion.' '.$nuevoAfiliado->identificacion.' | Se creó afiliado desde XML BDUA',
                        'ejecutada' => true
                    ]);
                    $creados++;

                }else{
                    AsMsauditoria::create([
                        'as_msubsidiado_id' => $this->periodo,
                        'accion' => 'Crear Afiliado',
                        'id_registro' => 0,
                        'detalle' => $nuevo->tipo_identificacion.' '.$nuevo->numero_identificacion. ' | No se pudo crear afiliado desde XML BDUA.| \n BDUA:'. json_encode($nuevo->toArray()).'| \n CAPRESOCA: '. json_encode($afiliadoCoincidencias->toArray()),
                        'ejecutada' => false
                    ]);
                }

            }catch (\Exception $exception){
                // return $exception;
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Crear Afiliado',
                    'id_registro' => 0,
                    'detalle' => $nuevo->tipo_identificacion.' '.$nuevo->numero_identificacion. ' | No se pudo crear afiliado desde XML BDUA.| \n BDUA:'. json_encode($nuevo->toArray()).'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }

        return $creados;
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
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.tipo_identificacion as cpsc', 'temp_mcontributivos.TIPO_IDENTIFICACION as bdua')
            ->join('temp_mcontributivos', 'as_afiliados.serial_bdua', '=', 'temp_mcontributivos.SERIAL')
            ->whereColumn('as_afiliados.tipo_identificacion','!=', 'temp_mcontributivos.TIPO_IDENTIFICACION')
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
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
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.identificacion as cpsc', 'temp_mcontributivos.NUMERO_IDENTIFICACION as bdua')
            ->join('temp_mcontributivos', 'as_afiliados.serial_bdua', '=', 'temp_mcontributivos.SERIAL')
            ->whereColumn('as_afiliados.identificacion','!=', 'temp_mcontributivos.NUMERO_IDENTIFICACION')
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
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
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.nombre1 as cpsc', 'temp_mcontributivos.PRIMER_NOMBRE as bdua')
            ->join('temp_mcontributivos', 'as_afiliados.serial_bdua', '=', 'temp_mcontributivos.SERIAL')
            ->whereColumn('as_afiliados.nombre1','!=', 'temp_mcontributivos.PRIMER_NOMBRE')
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
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
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.nombre2 as cpsc', 'temp_mcontributivos.SEGUNDO_NOMBRE as bdua')
            ->join('temp_mcontributivos', 'as_afiliados.serial_bdua', '=', 'temp_mcontributivos.SERIAL')
            ->whereColumn('as_afiliados.nombre2','!=', 'temp_mcontributivos.SEGUNDO_NOMBRE')
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
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
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.apellido1 as cpsc', 'temp_mcontributivos.PRIMER_APELLIDO as bdua')
            ->join('temp_mcontributivos', 'as_afiliados.serial_bdua', '=', 'temp_mcontributivos.SERIAL')
            ->whereColumn('as_afiliados.apellido1','!=', 'temp_mcontributivos.PRIMER_APELLIDO')
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
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
        $actualizables = AsAfiliado::select('as_afiliados.*','as_afiliados.apellido2 as cpsc', 'temp_mcontributivos.SEGUNDO_APELLIDO as bdua')
            ->join('temp_mcontributivos', 'as_afiliados.serial_bdua', '=', 'temp_mcontributivos.SERIAL')
            ->whereColumn('as_afiliados.apellido2','!=', 'temp_mcontributivos.SEGUNDO_APELLIDO')
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
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
        $actualizables = AsAfiliado::select('as_afiliados.*',DB::raw('DATE_FORMAT(as_afiliados.fecha_nacimiento, "%d/%m/%Y") AS cpsc'), 'temp_mcontributivos.NACIMIENTO_FECHA as bdua')
            ->join('temp_mcontributivos', 'as_afiliados.serial_bdua', '=', 'temp_mcontributivos.SERIAL')
            ->whereColumn(DB::raw('DATE_FORMAT(as_afiliados.fecha_nacimiento, "%d/%m/%Y")'),'!=', 'temp_mcontributivos.NACIMIENTO_FECHA')
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
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

    public function actualizarEstado(){
        // EStado como esté en el adres
        $actualizables = AsAfiliado::select('as_afiliados.*','as_estadoafiliados.nombre as cpsc', 'temp_mcontributivos.ESTADO as bdua')
            ->join('temp_mcontributivos', 'as_afiliados.serial_bdua', '=', 'temp_mcontributivos.SERIAL')
            ->join('as_estadoafiliados', 'as_afiliados.estado', '=', 'as_estadoafiliados.id')
            ->whereColumn('as_estadoafiliados.xml','!=', 'temp_mcontributivos.ESTADO')
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->estado = $this->estados->firstWhere('xml',$afiliado->bdua)->id;
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

    public function cambiarRegimen(){
        $movilidad = AsAfiliado::select('as_afiliados.*')
            ->join('temp_mcontributivos', 'as_afiliados.serial_bdua', '=', 'temp_mcontributivos.SERIAL')
            ->where('as_afiliados.as_regimene_id', 2)
            ->whereNotIn('as_afiliados.estado', [1,2])
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($movilidad as $afiliado){
            $relacionesActivas = AsAfiliadoPagador::where('as_afiliado_id', $afiliado->id)
                ->where('estado', 'Activo')
                ->get();
            if($relacionesActivas->count() == 0 ){
                if($afiliado->as_tipafiliado_id == 1){
                    AsMsauditoria::create([
                        'as_msubsidiado_id' => $this->periodo,
                        'accion' => 'Actualizar Afiliado',
                        'id_registro' => $afiliado->id,
                        'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Afiliado no presenta ninguna relacion laboral activa. Se actualizó el regimen del Afiliado',
                        'ejecutada' => false
                    ]);
                }else{
                    AsMsauditoria::create([
                        'as_msubsidiado_id' => $this->periodo,
                        'accion' => 'Actualizar Afiliado',
                        'id_registro' => $afiliado->id,
                        'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el regimen del Afiliado',
                        'ejecutada' => false
                    ]);
                }

            }else{

                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el regimen del Afiliado',
                    'ejecutada' => true
                ]);

            }
            $afiliado->as_regimene_id = 1;
            $afiliado->save();
            $actualizados++;
        }
        return $actualizados;
    }

    public function actualizarTipoAfiliado(){

        $actualizables = AsAfiliado::select('as_afiliados.*','as_tipafiliados.nombre as cpsc', 'temp_mcontributivos.TIPO_AFILIADO as bdua')
            ->join('temp_mcontributivos', 'as_afiliados.serial_bdua', '=', 'temp_mcontributivos.SERIAL')
            ->join('as_tipafiliados', 'as_afiliados.as_tipafiliado_id', '=', 'as_tipafiliados.id')
            ->whereColumn(DB::raw('UPPER(as_tipafiliados.nombre)'),'!=', 'temp_mcontributivos.TIPO_AFILIADO')
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
            ->get();
        $actualizados = 0;
        foreach ($actualizables as $afiliado){
            try{
                $afiliado->as_tipafiliado_id = $this->tipoAfiliados->firstWhere('nombre', ucfirst(strtolower($afiliado->bdua))) ? $this->tipoAfiliados->firstWhere('nombre', ucfirst(strtolower($afiliado->bdua)))->id : 1;
                $afiliado->save();
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => $afiliado->id,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó el tipo de afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar el tipo de afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }

    public function fechaEfectiva(){
        $actualizables = AsAfiliado::select('as_afiliados.*',DB::raw('DATE_FORMAT(as_afiliados.fecha_afiliacion, "%d/%m/%Y") AS cpsc'), 'temp_mcontributivos.FECHA_AFILIACION_EFECTIVA as bdua')
            ->join('temp_mcontributivos', 'as_afiliados.serial_bdua', '=', 'temp_mcontributivos.SERIAL')
            ->whereColumn(DB::raw('DATE_FORMAT(as_afiliados.fecha_afiliacion, "%d/%m/%Y")'),'!=', 'temp_mcontributivos.FECHA_AFILIACION_EFECTIVA')
            ->where('temp_mcontributivos.as_msubsidiado_id', $this->periodo)
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
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->identificacion.' | Se actualizó la fecha de afiliacion del Afiliado | CPSC: '.$afiliado->cpsc.' BDUA: '.$afiliado->bdua,
                    'ejecutada' => true
                ]);
                $actualizados++;
            }catch (\Exception $exception){
                AsMsauditoria::create([
                    'as_msubsidiado_id' => $this->periodo,
                    'accion' => 'Actualizar Afiliado',
                    'id_registro' => 0,
                    'detalle' => $afiliado->tipo_identificacion.' '.$afiliado->numero_identificacion. ' | No se pudo actualizar la fecha de afiliacion del Afiliado). CPSC: '. $afiliado->cpsc.' BDUA: '. $afiliado->bdua.'| ERROR: '.$exception->getMessage(),
                    'ejecutada' => false
                ]);
                continue;
            }
        }
        return $actualizados;
    }
}