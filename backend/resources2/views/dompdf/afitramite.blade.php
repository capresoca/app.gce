<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Afitramite</title>
    <link rel="stylesheet" href="./css/afitramitePdf.css">
</head>
<body>
<script type="text/php">
      if (isset($pdf)) {
        $font = $fontMetrics->getFont("Arial", "bold");
        $pdf->page_text(555, 10.5, "Pagina {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
      }
</script>
@if(!$afitramite->afitramite)
    <div class="watermark">BORRADOR</div>
@endif
<div class="main">
    <div class="header">
        <div class="celda logo">
            <img src="./images/capresoca.jpg" >
        </div>
        <div class="celda rad fecha">
            <div class="border-bottom rad-title">
                Fecha de Radicación
            </div>
        </div>
        <div id="fecha-rad">
        @if($afitramite->afitramite)
            @component('components.tabla_fecha',['fecha'=> $afitramite->afitramite->tramite->fecha_radicacion_array])
                @endcomponent
            @endif
        </div>
        <div class="celda rad numero">
            <div class="border-bottom rad-title">
                No. de Radicación
            </div>
            <strong>{{str_pad($afitramite->no_radicado,6,"0",STR_PAD_LEFT)}}</strong>
        </div>
    </div>

    <div class="tit-datos-tramite">
        <span class="titulo-seccion">I. DATOS DEL TRÁMITE</span>
        <span id="advertencia-instrucciones">(Lea las instrucciones que se encuentran anexas al formulario antes de diligenciarlo)</span>
    </div>

    <div id="fila-3">
        <div id="tipo-tramite" class="celda">
            <div class="tit-campo">
                1. Tipo de Trámite
            </div>
            <div id="ops-tipo-tramite">
                <span class="op-check">A. Afiliacion</span>
                <div class="check">X</div>
                <span class="op-check">B. Reporte de Novedades</span>
                <div class="check"></div>
            </div>
        </div>

        <div id="tipo-afiliacion" class="celda">
            <div class="txt" id="txt-2">2. Tipo de Afiliación</div>
            <div class="txt" id="txt-2-a">
                A. Individual:
            </div>
            <div class="txt" id="txt-2-a-1">
                -Cotizante o Cabeza de Familia
                <div class="check">
                    {{$afitramite->as_tipafiliacione_id ===1 ? 'X' : '' }}
                </div>
            </div>
            <div class="txt" id="txt-2-a-2">
                -Beneficiario o afiliado adicional
                <div class="check">

                </div>
            </div>
            <div class="txt" id="txt-2-b">
                B. Colectiva
                <div class="check">
                    {{$afitramite->as_tipafiliacione_id ===2 ? 'X' : '' }}
                </div>
            </div>
            <div class="txt" id="txt-2-c">
                C. Institucional
                <div class="check">
                    {{$afitramite->as_tipafiliacione_id ===3 ? 'X' : '' }}
                </div>
            </div>
            <div class="txt" id="txt-2-d">
                D. De Oficio
                <div class="check">
                    {{$afitramite->as_tipafiliacione_id ===4 ? 'X' : '' }}
                </div>
            </div>
        </div>


        <div id="regimen" class="celda">
            <div  class="tit-campo">
                3. Régimen
            </div>
            <div class="txt" id="txt-3-a">
                A. Contributivo
                <div class="check">
                    {{$afitramite->as_regimene_id ===1 ? 'X' : '' }}
                </div>
            </div>

            <div class="txt" id="txt-3-b">
                B. Subsidiado
                <div class="check" id="check-3-b">
                    {{$afitramite->as_regimene_id ===2 ? 'X' : '' }}
                </div>
            </div>

        </div>

    </div>
    <div id="fila-4">
        <div id="tipo-afiliado" class="celda">
            <div  class="tit-campo">
                4. Tipo de afiliado
            </div>
            <div class="txt" id="txt-4-a">
                A. Cotizante
                <div class="check">
                    {{$afitramite->as_tipafiliado_id ===1 ? 'X' : '' }}
                </div>
            </div>
            <div class="txt" id="txt-4-b">
                B. Cabeza de familia
                <div class="check">
                    {{$afitramite->as_tipafiliado_id ===2 ? 'X' : '' }}
                </div>
            </div>
            <div class="txt" id="txt-4-c">
                C. Beneficiario
                <div class="check">
                    {{$afitramite->as_tipafiliado_id ===3 ? 'X' : '' }}
                </div>
            </div>
        </div>
        <div id="tipo-cotizante" class="celda">
            <div  class="tit-campo">
                5. Tipo de cotizante
            </div>
            <div class="txt" id="txt-5-a">
                A. Dependiente
                <div class="check">
                    {{$afitramite->as_tipcotizante_id ===1 ? 'X' : '' }}
                </div>
            </div>
            <div class="txt" id="txt-5-b">
                B. Independiente
                <div class="check">
                    {{$afitramite->as_tipcotizante_id ===2 ? 'X' : '' }}
                </div>
            </div>
            <div class="txt" id="txt-5-c">
                C. Pensionado
                <div class="check">
                    {{$afitramite->as_tipcotizante_id ===3 ? 'X' : '' }}
                </div>
            </div>
            <div class="txt" id="txt-5-d">
                Código
            </div>
            <div class="txt" id="txt-5-e">
                (A registrar por la Eps)
            </div>
            <div class="txt" id="check-5-e">
                {{$afitramite->claseCotizante ? $afitramite->claseCotizante->codigo : ''}}
            </div>
        </div>

    </div>
    <div id="fila-5">
        <div id="tit-a">
            A. AFILIACION
        </div>
        <div id="tit-ii">
            II. DATOS BÁSICOS DE IDENTIFICACIÓN (del cotizante o cabeza de familia)
        </div>
    </div>
    <div id="fila-6">
        <div class="tit-campo">
            6. Apellidos y nombres
        </div>
        <div class="dato txt" id="dato-6-1">
            {{$afitramite->afiliado->apellido1}}
        </div>
        <div class="dato txt" id="dato-6-2">
            {{$afitramite->afiliado->apellido2}}
        </div>
        <div class="dato txt" id="dato-6-3">
            {{$afitramite->afiliado->nombre1}}
        </div>
        <div class="dato txt" id="dato-6-4">
            {{$afitramite->afiliado->nombre2}}
        </div>
        <div class="txt" id="txt-6-1">
            Primer apellido
        </div>
        <div class="txt celda-laterales" id="txt-6-2">
            Segundo apellido
        </div>
        <div class="txt celda-laterales" id="txt-6-3">
            Primer nombre
        </div>
        <div class="txt" id="txt-6-4">
            Segundo nombre
        </div>
    </div>
    <div id="fila-7">
        <div class="celda" id="celda-7">
            <div class="tit-campo">
                7. Tipo de documento de identidad
            </div>
            <div class="minicelda" id="check-7-1">
                {{$afitramite->afiliado->tipo_id->abreviatura}}
            </div>
        </div>
        <div class="celda" id="celda-8">
            <div class="tit-campo">
                8. Numero de documento de identidad
            </div>
            <div class="txt" id="txt-8-1">
                {{$afitramite->afiliado->identificacion}}
            </div>
        </div>
        <div class="celda" id="celda-9">
            <div class="tit-campo">
                9. Sexo
            </div>
            <div class="txt" id="txt-9-2">
                Femenino
                <div class="check">{{$afitramite->afiliado->gn_sexo_id === 1 ? 'X' : ''}}</div>
            </div>
            <div class="txt" id="txt-9-1">
                Masculino
                <div class="check">{{$afitramite->afiliado->gn_sexo_id === 2 ? 'X' : ''}}</div>
            </div>
        </div>
        <div class="celda" id="celda-10">
            <div class="tit-campo">
                10. Fecha de nacimiento
            </div>
        </div>
        <div id="fecha-10">
            @component('components.tabla_fecha',['fecha'=> $afitramite->afiliado->fecha_nacimiento_array])
            @endcomponent
        </div>
    </div>
    <div id="fila-8">
        <div id="tit-iii">
            III. DATOS COMPLEMENTARIOS
        </div>
        <div id="tit-iii-1">
            Datos Personales
        </div>
    </div>

    <div id="fila-9">
        <div class="celda" id="celda-11">
            <div class="tit-campo">11.Etnia</div>
            <div class="minicelda" id="check-11-1">
                @if($afitramite->afiliado->etnia)
                    {{$afitramite->afiliado->etnia->codigo}}
                @endif
            </div>
        </div>
        <div class="celda" id="celda-12">
            <div class="tit-campo">12.Discapacidad</div>
            <div class="txt" id="txt-12-1">
                Tipo
            </div>
            <div id="check-12-1">

                <div class="check">
                    F
                    <span>{{$afitramite->afiliado->as_tipo_discapacidade_id ===1 ? 'X': ''}}</span>
                </div>
                <div class="check">
                    N
                    <span>{{$afitramite->afiliado->as_tipo_discapacidade_id ===2 ? 'X': ''}}</span>
                </div>

                <div class="check">
                    M
                    <span>{{$afitramite->afiliado->as_tipo_discapacidade_id ===3 ? 'X': ''}}</span>
                </div>

            </div>

            <div class="txt" id="txt-12-2">
                Condición
            </div>
            <div id="check-12-2">

                <div class="check">
                    T
                    <span>{{$afitramite->afiliado->as_condicion_discapacidades_id === 1 ? 'X': ''}}</span>
                </div>
                <div class="check">
                    P
                    <span>{{$afitramite->afiliado->as_condicion_discapacidades_id === 2 ? 'X': ''}}</span>
                </div>

            </div>
        </div>
        <div class="celda" id="celda-13">
            <div class="tit-campo">13. Puntaje SISBÉN</div>
            <div class="minicelda" id="check-13-1">
                {{$afitramite->afiliado->puntaje_sisben}}
            </div>
        </div>
        <div class="celda" id="celda-14">
            <div class="tit-campo">14. Grupo de población especial</div>
            <div class="minicelda" id="check-13-2">
                @if($afitramite->afiliado->poblacion_especial)
                    {{$afitramite->afiliado->poblacion_especial->codigo}}
                @endif
            </div>
        </div>
    </div>
    <div id="fila-10">
        <div class="celda" id="celda-15">
            <div class="tit-campo">
                15. Administradora de Riesgos Laborales - ARL
            </div>
            <div class="txt" id="txt-15-1">
                {{!$afitramite->afiliado->arl ? '' :$afitramite->afiliado->arl->nombre}}
            </div>
        </div>

        <div class="celda" id="celda-16">
            <div class="tit-campo">
                16. Administradora de Pensiones
            </div>
            <div class="txt" id="txt-15-2">
                {{!$afitramite->afiliado->afp ? '' :$afitramite->afiliado->afp->nombre}}
            </div>
        </div>
        <div class="celda" id="celda-17">
            <div class="tit-campo">
                17. Ingreso base de cotización - IBC
            </div>
            <div class="txt" id="txt-15-2">
                {{$afitramite->afiliado->ibc}}
            </div>
        </div>

    </div>

    <div id="fila-11">
        <div class="tit-campo">18. Residencia</div>
        <hr id="separador-residencia">
        <div class="txt" id="dato-18-1">{{$afitramite->afiliado->direccion}}</div>
        <div class="txt" id="txt-18-1">Dirección</div>
        <div class="txt" id="dato-18-2">{{$afitramite->afiliado->telefono_fijo}}</div>
        <div class="celda-laterales txt" id="txt-18-2">Teléfono Fijo</div>
        <div class="txt" id="dato-18-3">{{$afitramite->afiliado->celular}}</div>
        <div class="celda-laterales txt" id="txt-18-3">Teléfono Celular</div>
        <div class="txt" id="dato-18-4">{{$afitramite->afiliado->correo_electronico}}</div>
        <div class="txt" id="txt-18-4">Correo Electrónico</div>

    </div>
    <div id="fila-12">
        <div class="txt" id="dato-18-5">
            {{$afitramite->afiliado->municipio->nombre}}
        </div>
        <div class="txt" id="txt-18-5">
            Municipio/Distrito
        </div>
        <div class="txt celda-laterales" id="txt-18-6">

        </div>
        <div class="txt" id="dato-18-7">
            @if($afitramite->afiliado->vereda)
                {{$afitramite->afiliado->vereda->nombre}}
            @endif
        </div>
        <div class="txt celda-laterales" id="txt-18-7">
            Localidad/Comuna
            <div class="txt" id="txt-18-7-1">Zona</div>
            <div class="txt" id="txt-18-7-2">
                Urbano
                <div class="check txt" id="check-18-7-2">
                    {{$afitramite->afiliado->gn_zona_id === 1 ? 'X' : ''}}
                </div>
            </div>
            <div class="txt" id="txt-18-7-3">
                Rural
                <div class="check txt" id="check-18-7-3">
                    {{$afitramite->afiliado->gn_zona_id === 2 ? 'X' : ''}}
                </div>
            </div>
        </div>
        <div class="txt" id="dato-18-8">
            {{$afitramite->afiliado->municipio->departamento->nombre}}
        </div>
        <div class="txt celda-laterales" id="txt-18-8">
            Departamento
        </div>

    </div>
    <div id="fila-13">
        <div id="tit-iv">
            IV. DATOS DE IDENTIFICACIÓN DE LOS MIEMBROS DEL NUCLEO FAMILIAR
        </div>
        <div id="tit-iv-1">
            Datos básicos de identificación del cónyuge o compañero(a) permanente cotizante
        </div>
    </div>
    <div id="fila-14">
        <div id="fila-14">
            <div class="tit-campo">
                19. Apellidos y nombres
            </div>
            @if($afitramite->conyuge)
            <div class="dato txt" id="dato-14-1">
                {{$afitramite->conyuge->apellido1}}
            </div>
            <div class="dato txt" id="dato-14-2">
                {{$afitramite->conyuge->apellido2}}
            </div>
            <div class="dato txt" id="dato-14-3">
                {{$afitramite->conyuge->nombre1}}
            </div>
            <div class="dato txt" id="dato-14-4">
                {{$afitramite->conyuge->nombre2}}
            </div>
            @endif
            <div class="txt" id="txt-14-1">
                Primer apellido
            </div>
            <div class="txt celda-laterales" id="txt-14-2">
                Segundo apellido
            </div>
            <div class="txt celda-laterales" id="txt-14-3">
                Primer nombre
            </div>
            <div class="txt" id="txt-14-4">
                Segundo nombre
            </div>
        </div>
    </div>
    <div id="fila-15">
        <div class="celda" id="celda-20">
            <div class="tit-campo">
                20. Tipo de documento de identidad
            </div>
            <div class="minicelda" id="check-20-1">
                @if($afitramite->conyuge)
                {{$afitramite->conyuge->tipo_id->abreviatura}}
                @endif
            </div>
        </div>
        <div class="celda" id="celda-21">
            <div class="tit-campo">
                21. Numero de documento de identidad
            </div>
            <div class="txt" id="txt-21-1">
                @if($afitramite->conyuge)
                {{$afitramite->conyuge->identificacion}}
                @endif
            </div>
        </div>
        <div class="celda" id="celda-22">
            <div class="tit-campo">
                22. Sexo
            </div>
            <div class="txt" id="txt-22-2">
                Femenino
                @if($afitramite->conyuge)
                <div class="check">{{$afitramite->conyuge->gn_sexo_id === 1 ? 'X' : ''}}</div>
                @endif
            </div>
            <div class="txt" id="txt-22-1">
                Masculino
                @if($afitramite->conyuge)
                <div class="check">{{$afitramite->conyuge->gn_sexo_id === 2 ? 'X' : ''}}</div>
                @endif
            </div>
        </div>
        <div class="celda" id="celda-23">
            <div class="tit-campo">
                23. Fecha de nacimiento
            </div>
        </div>
        <div id="fecha-23">
            @if($afitramite->conyuge)
                @component('components.tabla_fecha',['fecha'=> $afitramite->conyuge->fecha_nacimiento_array])
                @endcomponent
            @else
                @component('components.tabla_fecha',['fecha'=> []])
                @endcomponent
            @endif
        </div>
    </div>
    <div id="fila-16">
        <div class="tit-campo">
            Datos básicos de identificación de los beneficiarios y de los afiliados adicionales
        </div>
    </div>
    <div>
        <div class="tit-campo">
            24. Apellidos y nombres
        </div>
        <table class="collapse" id="tabla-beneficiarios">
            <tbody>
                <tr class="head">
                    <td class="td-init"></td>
                    <td>Primer apellido</td>
                    <td>Segundo apellido</td>
                    <td>Primer nombre</td>
                    <td class="td-fin last">Segundo nombre</td>
                </tr>
                @if(!$afitramite->beneficiarios->count())
                    @for($i = 1; $i<=4; $i++)

                        <tr class="datos">
                            <td>{{'B'.$i}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="last"></td>
                        </tr>
                    @endfor
                @else
                    @foreach($afitramite->beneficiarios as $beneficiario)
                        <tr class="datos">
                            <td>B{{$loop->iteration}}</td>
                            <td>{{$beneficiario->apellido1}}</td>
                            <td>{{$beneficiario->apellido2}}</td>
                            <td>{{$beneficiario->nombre1}}</td>
                            <td class="last">{{$beneficiario->nombre2}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <table class="collapse" id="tabla-beneficiarios">
            <tbody>
            <tr class="head">
                <td class="td-init" rowspan="2"></td>
                <td rowspan="2">25. Tipo de documento de identidad</td>
                <td rowspan="2">26. Número del documento de identidad</td>
                <td colspan="2">27. Sexo</td>
                <td rowspan="2" colspan="10" width="24%" class="last">28. Fecha de nacimiento</td>
            </tr>
            <tr>
                <td id="fem">Femenino</td>
                <td id="mas">Masculino</td>
            </tr>
            @if(!$afitramite->beneficiarios->count())
                @for($i = 1; $i<=4; $i++)

                    <tr class="datos">
                        <td>{{'B'.$i}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @component('components.columnas_fecha',['fecha'=> []])
                        @endcomponent
                        <td class="last"></td>
                    </tr>
                @endfor
            @endif
            @foreach($afitramite->beneficiarios as $beneficiario)
                <tr class="datos">
                    <td>B{{$loop->iteration}}</td>
                    <td>{{$beneficiario->tipo_id->abreviatura}}</td>
                    <td>{{$beneficiario->identificacion}}</td>
                    <td>{{$beneficiario->gn_sexo_id ===1 ? 'X' : ''}}</td>
                    <td>{{$beneficiario->gn_sexo_id ===2 ? 'X' : ''}}</td>
                    <td ></td>
                    @component('components.columnas_fecha',['fecha'=> $beneficiario->fecha_nacimiento_array])
                    @endcomponent
                    <td class="last"></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div id="fila-16">
            <div class="tit-campo">
                Datos complementarios
            </div>
        </div>
        <table class="collapse" id="tabla-beneficiarios">
            <tbody>
            <tr>
                <td class="td-init" rowspan="3"></td>
                <td rowspan="3">29. Parentesco</td>
                <td rowspan="3">30. Etnia</td>
                <td class="last" width="30%" colspan="5">31. Discapacidad</td>
            </tr>
            <tr>
                <td colspan="3" id="fem">Tipo</td>
                <td colspan="2" id="mas" class="last">Condicion</td>
            </tr>
            <tr>
                <td>F</td>
                <td>N</td>
                <td>M</td>
                <td>T</td>
                <td class="last">P</td>
            </tr>
            @if(!$afitramite->beneficiarios->count())
                @for($i = 1; $i<=4; $i++)

                    <tr class="datos">
                        <td>{{'B'.$i}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                @endfor
            @endif

            @foreach($afitramite->beneficiarios as $beneficiario)
                <tr class="datos">
                    <td>B{{$loop->iteration}}</td>
                    <td>{{!$beneficiario->parentesco ? '' : $beneficiario->parentesco->codigo}}</td>
                    <td>
                        {{!$beneficiario->etnia ? '' :$beneficiario->etnia->codigo}}
                    </td>
                    <td>{{$beneficiario->as_tipo_discapacidade_id ===1 ? 'X' : ''}}</td>
                    <td>{{$beneficiario->as_tipo_discapacidade_id ===2 ? 'X' : ''}}</td>
                    <td>{{$beneficiario->as_tipo_discapacidade_id ===3 ? 'X' : ''}}</td>
                    <td>{{$beneficiario->as_condicion_discapacidades_id ===1 ? 'X' : ''}}</td>
                    <td class="last">{{$beneficiario->as_condicion_discapacidades_id ===2 ? 'X' : ''}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @if($afitramite->beneficiarios->count() >= 10)
        <div class="saltopagina"></div>
    @endif

        <table class="collapse" id="tabla-beneficiarios">
            <tbody>
            <tr>
                <td rowspan="3"></td>
                <td colspan="5">32. Datos de residencia</td>
                <td class="last"  rowspan="3">33. Valor de la UPC del afiliado adicional (A registrar por la EPS)</td>
            </tr>
            <tr>
                <td rowspan="2" >Municipio/Distrito</td>
                <td colspan="2">Zona</td>
                <td rowspan="2">Departamento</td>
                <td rowspan="2">Teléfono Fijo y/o celular</td>
            </tr>
            <tr>
                <td>Urbana</td>
                <td>Rural</td>
            </tr>

            @if(!$afitramite->beneficiarios->count())
                @for($i = 1; $i<=4; $i++)

                    <tr class="datos">
                        <td>{{'B'.$i}}</td>
                        <td width="25%"></td>
                        <td width="7%"></td>
                        <td width="7%"></td>
                        <td></td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                @endfor

            @endif
            @foreach($afitramite->beneficiarios as $beneficiario)
                <tr class="datos">
                    <td>B{{$loop->iteration}}</td>
                    <td width="25%">{{ !$beneficiario->municipio?'':$beneficiario->municipio->nombre }}</td>
                    <td width="7%">{{$beneficiario->gn_zona_id === 1 ? 'X' : ''}}</td>
                    <td width="7%">{{$beneficiario->gn_zona_id === 2 ? 'X' : ''}}</td>
                    <td>{{ !$beneficiario->municipio?'':$beneficiario->municipio->departamento->nombre }}</td>
                    <td>{{ $beneficiario->celular }}</td>
                    <td class="last">{{ $beneficiario->upc }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div id="fila-16">
            <div class="tit-campo">
                Selección de la IPS Primaria
            </div>
        </div>

        <table class="collapse" id="tabla-beneficiarios">
            <tbody>
            <tr>
                <td></td>
                <td>34. Nombre de la institución Prestadora de Servicios de Salud - IPS</td>
                <td class="last" width="15%">Codigo de la IPS (A registrar por la EPS)</td>
            </tr>
            <tr>
                <td>C</td>
                <td>{{!$afitramite->ips?'':$afitramite->ips->nombre_completo}}</td>
                <td class="last">{{!$afitramite->ips?'':$afitramite->ips->cod_habilitacion}}</td>
            </tr>
            @if(!$afitramite->beneficiarios->count())
                @for($i = 1; $i<4; $i++)

                    <tr class="datos">
                        <td>{{'B'.$i}}</td>
                        <td></td>
                        <td class="last"></td>
                    </tr>
                @endfor

            @endif
            @foreach($afitramite->beneficiarios as $beneficiario)
                @if($beneficiario->rs_entidade_id !== $afitramite->rs_ips_id)
                    <tr class="datos">
                        <td>B{{$loop->iteration}}</td>
                        <td>{{!$beneficiario->ips ? '':$beneficiario->ips->nombre_completo}}</td>
                        <td class="last">{{!$beneficiario->ips ? '':$beneficiario->ips->cod_habilitacion}}</td>
                    </tr>
                @endif
            @endforeach

            </tbody>
        </table>
        <div id="fila-17">
            <div class="tit-campo" id="tit-v">
                V. DATOS DE IDENTIFICACIÓN DEL EMPLEADOR Y OTROS APORTANTES DE LAS ENTIDADES RESPONSABLES DE LA AFILIACIÓN COLECTIVA, INSTITUCIONAL O DE OFICIO
            </div>
        </div>
        <div id="fila-18">
            <div class="celda" id="celda-35">
                <div class="tit-campo">
                    35. Nombre o razón social
                </div>
                <div id="dato-35">
                    {{$afitramite->pagador ? $afitramite->pagador->nombre_completo: ''}}
                </div>
            </div>
            <div class="celda" id="celda-36">
                <div class="tit-campo">
                    36. Tipo de documento de identificación
                </div>
                <div id="dato-36">
                    {{$afitramite->pagador ? $afitramite->pagador->tipo_id->abreviatura : ''}}
                </div>
            </div>
            <div class="celda" id="celda-37">
                <div class="tit-campo">
                    37. Número del documento de identificación
                </div>
                <div id="dato-37">
                    {{$afitramite->pagador ? $afitramite->pagador->identificacion : ''}}
                </div>
            </div>
            <div class="celda" id="celda-38">
                <div class="tit-campo">
                    38. Tipo de aportante o pagador pensiones (A registrar por la EPS)
                </div>
                <div id="dato-37">
                    {{$afitramite->pagador ? $afitramite->pagador->tipo_aportante->codigo : ''}}
                </div>
            </div>
        </div>
        <div id="fila-18">
            <div class="tit-campo">
                39. Ubicación
            </div>
            <div>
                @if($afitramite->pagador)
                    <div class="dato-39" style="width: 61mm;">
                        {{$afitramite->pagador->direccion}}

                    </div>
                    <div class="dato-39" style="width: 30mm">
                        {{$afitramite->pagador->telefono_fijo}}

                    </div>
                    <div class="dato-39" style="width: 40mm">
                        {{$afitramite->pagador->correo_electronico}}

                    </div>
                    <div class="dato-39" style="width: 37mm">
                        {{$afitramite->pagador->municipio->nombre}}

                    </div>
                    <div class="dato-39" style="width: 33mm">
                        {{$afitramite->pagador->municipio->departamento->nombre}}
                    </div>
                @endif
            </div>
            <div>

                <div class="txt-39" id="txt-39-1">
                    Dirección
                </div>
                <div class="txt-39" id="txt-39-2">
                    Teléfono
                </div>
                <div class="txt-39" id="txt-39-3">
                    Correo electrónico
                </div>
                <div class="txt-39" id="txt-39-4">
                    Municipio/Distrito
                </div>
                <div class="txt-39" id="txt-39-5">
                    Departamento
                </div>
            </div>
        </div>
        {{--<div class="saltopagina"></div>--}}

        <div id="tit-b">
            B. REPORTE DE NOVEDADES
        </div>

        <div id="fila-20">
            <div class="tit-campo" id="tit-40">40. Tipo de Novedad</div>
        </div>

        <div id="fila-21">
            <div id="col-40-1">
                <div>
                    <div class="check"></div>
                    <span>1. Modificación de datos básicos de identificación.</span>
                </div>
                <div>
                    <div class="check"></div>
                    <span>2. Corrección de datos básicos de identificación.</span>
                </div>
                <div>
                    <div class="check"></div>
                    <span>3. Actualización del documento de identidad.</span>
                </div>
                <div>
                    <div class="check"></div>
                    <span>4. Actualización y corrección de datos complementarios.</span>
                </div>
                <div>
                    <div class="check"></div>
                    <span>5. Terminación de la inscripción con la EPS.</span>
                    <div id="txt-40-5">
                        Código
                        <div class="check"></div>
                    </div>
                </div><div>
                    <div class="check"></div>
                    <span>6. Reinscripción a la EPS.</span>
                </div>
                <div>
                    <div class="check"></div>
                    <span>7. Inclusión de beneficiarios o de afiliados adicionales.</span>
                </div>
                <div>
                    <div class="check"></div>
                    <span>8. Exclusión de beneficiarios o de afiliados adicionales.</span>
                </div>
                <div>
                    <div class="check"></div>
                    <span>9. Inicio de relación laboral o adquisición de condiciones para cotizar.</span>
                </div>
                <div>
                    <div class="check"></div>
                    <span>10.Terminación de la relación laboral o pérdida de las condiciones para seguir cotizando.</span>
                </div>

            </div>
            <div id="col-40-2">
                <div>
                    <div class="check"></div>
                    <span>11. Vinculación a una entidad autorizada para hacer afiliaciones colectivas</span>
                </div>
                <div>
                    <div class="check"></div>
                    <span>12. Desvinculación de una entidad autorizada para hacer afiliaciones colectivas</span>
                </div>
                <div>
                    <div class="check" ></div>
                    <span id="span-40-13">13. Movilidad:</span>
                    <div id="txt-40-13">
                        <div>

                            <div class="check"  id="check-40-13-1"></div>
                            <span>A. Régimen Contributivo</span>
                        </div>
                        <div>

                            <div class="check" id="check-40-13-2"></div>
                            <span>B. Régimen Subsidiado</span>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="check" ></div>
                    <span id="span-40-13">14. Traslado: </span>
                    <div id="txt-40-13">
                        <div>

                            <div class="check"  id="check-40-13-1"></div>
                            <span>A. Mismo Régimen</span>
                        </div>
                        <div>

                            <div class="check" id="check-40-13-2"></div>
                            <span>B. Diferente Régimen</span>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="check"></div>
                    <span>15. Reporte de fallecimiento.</span>
                </div>
                <div>
                    <div class="check"></div>
                    <span>16. Reporte del tramite de protección al cesante.</span>
                </div>
                <div>
                    <div class="check"></div>
                    <span>17.Reporte de la calidad de Pre-pensionado</span>
                </div>

                <div>
                    <div class="check"></div>
                    <span>18. Reporte de la calidad de pensionado.</span>
                </div>

            </div>
        </div>

        <div id="fila-22">
            <div class="tit-campo" id="tit-vi">
                VI. DATOS PARA EL REPORTE DE LA NOVEDAD
            </div>
        </div>

        <div id="fila-23">
            <div class="tit-campo">
                41. Datos básicos de identificación
            </div>
            <div id="dato-41-1"></div>
            <div id="dato-41-2"></div>
            <div id="dato-41-3"></div>
            <div id="dato-41-4"></div>
            <div id="txt-41-1">Primer Apellido</div>
            <div id="txt-41-2">Segundo Apellido</div>
            <div id="txt-41-3">Primer Nombre</div>
            <div id="txt-41-4">Segundo Nombre</div>
        </div>

        <div id="fila-24">
            <div id="celda-41-5">
                <div id="txt-41-5">
                    Tipo de documento de indentidad
                    <div class="check"></div>
                </div>
            </div>
            <div id="celda-41-6">
                <div id="dato-41-6"></div>
                <div id="txt-41-6">Número de documento de identidad</div>
            </div>
            <div id="celda-41-7">
                <div id="txt-41-7">Sexo &nbsp;&nbsp; Masculino <div class="check"></div> Femenino <div class="check"></div></div>
            </div>

            <div id="celda-41-8">
                <div id="txt-41-8">Fecha de nacimiento</div>
                <div id="txt-41-8-1">
                    @component('components.tabla_fecha',['fecha' => []])
                    @endcomponent
                </div>
            </div>

            <div id="celda-41-9">
                <div class="tit-campo">42. Fecha</div>
                <div id="txt-41-9-1">
                    @component('components.tabla_fecha', ['fecha' => []])
                    @endcomponent
                </div>
            </div>

        </div>

        <div id="fila-25">
            <div id="celda-43">
                <div class="tit-campo">43. EPS anterior</div>
            </div>
            <div id="celda-44">
                <div class="tit-campo">44. Motivo de traslado</div>
                <div id="txt-44">Código <div class="check"></div></div>
            </div>
            <div id="celda-45">
                <div class="tit-campo">45. Caja de Compensación Familiar o Pagador de Pensiones</div>
            </div>
        </div>

        <div id="fila-22">
            <div class="tit-campo" id="tit-vi">
                VII. DECLARACIONES Y AUTORIZACIONES
            </div>
        </div>

        <div id="fila-46">
            <div class="decl">
                <div class="check"></div>
                46. Declaración de dependencia económica de los beneficiarios y afiliados adicionales.
            </div>
            <div class="decl">
                <div class="check"></div>
                47. Declaración de la no obligación de afiliarse al Régimen Contributivo, Especial o de Excepción.
            </div>
            <div class="decl">
                <div class="check"></div>
                48. Declaración de existencia de razones de fuerza mayor o caso fortuito que impiden la entrega de los documentos de los beneficiarios.
            </div>
            <div class="decl">
                <div class="check"></div>
                49. Declaración de no internación del cotizante, cabeza de familia, beneficiario o afiliados adicionales en una institución Prestadora de Serviios de Salud.
            </div>
            <div class="decl">
                <div class="check"></div>
                50. Autorización para que la EPS solicite y obtenga datos y copia de la historia clínica del cotizante o cabeza de familia y de sus beneficiarios o afiliados adicionales.
            </div>
            <div class="decl">
                <div class="check"></div>
                51. Autorización para que la EPS reporte la información que se genere de la afiliación o del reporte de novedades a la base de datos de afiliados vigente y a las 5 entidades públicas que por sus funciones la requieran.
            </div>
            <div class="decl">
                <div class="check"></div>
                52. Autorización para que la EPS maneje los datos personales del cotizante o cabeza de familia y de sus beneficiarios o afiliados adicionales, de acuerdo con lo previsto en la Ley 1581 de 2012 y el Decreto 1377 de 2013.
            </div>
            <div class="decl">
                <div class="check"></div>
                53. Autorización para que la EPS envíe información al correo electrónico o al celular como mensajes de texto.
            </div>
        </div>

        <div id="fila-22">
            <div class="tit-campo" id="tit-vi">
                VIII. FIRMAS
            </div>
        </div>

        <div id="fila-47">
            <div id="celda-54">
                54. El cotizante, cabeza de familia o beneficiario
            </div>
            <div id="celda-55">
                55. El empleador, aportante o entidad responsable de la afiliación colectiva, intitucional o de Oficio
            </div>
        </div>

        <div id="fila-22">
            <div class="tit-campo" id="tit-vi">
                IX. ANEXOS
            </div>
        </div>

        <div id="fila-48">
            <div class="decl" id="txt-56">
                <div class="check"></div>
                <div id="txt-56-1">
                    56. Anexo copia del documento de identidad:
                </div>
                <div id="txt-56-2">
                    <div class="check">CN</div>
                    <div class="check">RC</div>
                    <div class="check">TI</div>
                    <div class="check">CC</div>
                    <div class="check">PA</div>
                    <div class="check">CE</div>
                    <div class="check">CD</div>
                    <div class="check">SC</div>
                </div>
                <div id="txt-56-3">
                    Cantidad:
                </div>
                <div id="txt-56-4">
                    <div class="check"></div>
                    <div class="check"></div>
                    <div class="check"></div>
                    <div class="check"></div>
                    <div class="check"></div>
                    <div class="check"></div>
                    <div class="check"></div>
                    <div class="check"></div>
                    &nbsp;&nbsp;&nbsp;&nbsp;Total: <div class="check"></div>
                </div>
            </div>
            <div class="decl">
                <div class="check"></div>
                57. Copia del dictamen de incapacidad permanente emirido por la autoridad competente.
            </div>
            <div class="decl">
                <div class="check"></div>
                58. Copia del registro civil de matrimonio, o de la Escritura pública, acta de conciliación o sentencia judicial que declara la unión marital.
            </div>
            <div class="decl">
                <div class="check"></div>
                59. Copia de la escritura pública o sentencia judicial que declare el divorcio, sentencia judicial que declare la separación de cuerpos y escritura pública, acta de
                conciliación o sentencia judicial que declare la terminación date unión marital.
            </div>
            <div class="decl">
                <div class="check"></div>
                60. Copia del certificado de adopción o acta de entrega del menor.
            </div>
            <div class="decl">
                <div class="check"></div>
                61. Copia de la orden judicial o detecto administrativo de custodia.
            </div>
            <div class="decl">
                <div class="check"></div>
                62. Documento en que conste la pérdida de la patria potestad, o el certificado de defunción de los padres ola declaración suscrita por el cotizante sobre la ausencia de
                los dos padres.
            </div>
            <div class="decl">
                <div class="check"></div>
                63. Copia de la autorización de traslado por parte de la Superintendencia Nacional de Salud.
            </div>
            <div class="decl">
                <div class="check"></div>
                64. Certificacion de vinculación a una entidad autorizada para realizar afiliaciones colectivas.
            </div>
            <div class="decl">
                <div class="check"></div>
                65. Copia del acto administrativo o providencia de las autoridades competentes en la que conste la calidad de beneficiario o se ordene la afiliación de oficio.
            </div>
        </div>

        <div id="fila-22">
            <div class="tit-campo" id="tit-vi">
                X. DATOS A SER DILIGENCIADOS POR LA ENTIDAD TERRITORIAL
            </div>
        </div>

        <div id="fila-49">
            <div id="celda-66">
                <div class="tit-campo">66. Identificación de la Entidad Territorial</div>
                <div id="txt-66-1">
                    Código de municipio
                    <div id="check-66-1"></div>
                </div>
                <div id="txt-66-2">
                    Código de departamento
                    <div id="check-66-2"></div>
                </div>
            </div>
            <div id="celda-67">
                <div class="tit-campo">67. Datos del SISBÉN</div>
                <div id="txt-67-1">
                    Número de ficha
                    <div id="txt-67-1-1">
                        <div id="check-67-1"></div>
                    </div>
                </div>
                <div id="txt-67-2">
                    Puntaje
                    <div id="txt-67-1-2">
                        <div id="check-67-2"></div>
                    </div>
                </div>
                <div id="txt-67-3">
                    <div id="txt-67-3-1">
                        Nivel
                        <div id="check-67-3"></div>
                    </div>
                </div>
            </div>

            <div id="celda-68">
                <div class="tit-campo">
                    68. Fecha radicación
                </div>
                <div id="txt-68">
                    @component('components.tabla_fecha', ['fecha' => []])
                    @endcomponent
                </div>
            </div>
            <div id="celda-69">
                <div class="tit-campo">
                    69. Fecha de validación
                </div>
                <div id="txt-69">
                    @component('components.tabla_fecha', ['fecha' => []])
                    @endcomponent
                </div>
            </div>
        </div>

        <div id="fila-50">
            <div class="tit-campo">70. Datos del funcionario que realiza la validación</div>
            <div id="txt-70-1">
                Primer apellido
            </div>
            <div id="txt-70-2">
                Segundo apellido
            </div>
            <div id="txt-70-3">
                Primer nombre
            </div>
            <div id="txt-70-4">
                Segundo nombre
            </div>
        </div>

        <div id="fila-51">
            <div id="celda-70-5">
                <div id="txt-70-5">
                    Tipo documento de identidad
                </div>
                <div id="check-70-5"></div>
            </div>

            <div id="celda-70-6">
                <div id="txt-70-6">
                    Número del documento de identidad
                </div>
            </div>

            <div id="celda-71">
                <div class="tit-campo">71. Firma del funcionario</div>
            </div>

        </div>

        <div id="fila-52">
            <div class="filas-52">OBSERVACIONES:</div>
            <div class="filas-52"></div>
            <div class="filas-52"></div>
            <div class="filas-52"></div>
        </div>

        <div id="fila-53">
            Recuerde que con la firma del formulario, el afiliado manifiesta la veracidad de la información registrada y de las declaraciones contenidas en el capitulo VII del formulado.
        </div>
    </div>
    <div class="saltopagina"></div>
    @include('dompdf.deberesyderechos')
    <div class="saltopagina"></div>
    @include('dompdf.soporte_carnetizacion')
</div>


</body>
</html>
