<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Referencia y Contrarreferencia</title>
    <style>

        body {
            font-size: 14px;
            width: 100%;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
        p, h2, h4, div, header, main, footer{
            padding: 0;
            margin: 0;
        }
        header{
            width: 100%;

        }
        header .logos{
            display: inline-block;
            width: 25%;
        }

        header .logos img {
            width: 120px;

        }
        header .titulo{
            display: inline-block;
            width: 55%;
            text-align: center;
        }

        header .legal {
            display: inline-block;
            width: 20%;
            font-size: 10px;
            text-align: right;
        }

        #consecutivo {
            width: 100%;
            text-align: right;
            font-size: 12px;
            margin-top: -40px;

        }
        .cabecera-detalle {
            width: 100%;
         }
        .cabecera-detalle p {
            display: inline-block;
            width: 33%;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        table th
        {
            font-size: 9px;
            padding-bottom: 5px;
            border-bottom: 2px solid gray;
        }
        table td
        {
            border-bottom: 1px solid #C7C7C7;
            padding: 2px;
            font-size: 9px;
        }
        table p {
            text-align: justify;
            padding-right: 10px;
        }
        .align-left{
            text-align: left;
        }
        .align-rigth{
            text-align: right;
        }
        main, table, section {
            width: 100%;
        }

        .card{
            background-color: rgba(199,199,199, 0.5);
            padding: 5px;
            margin-top: 10px;
            border-radius: 3px;
        }
        .card p {
            font-size: 10px;
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
        }
        footer {
            position: fixed;
            bottom: 0;
            font-size: 10px;
            margin: 0 1cm 0 0.5cm;
        }




    </style>
</head>
<body>
    <header>
        <div class="logos">
            <img src="./images/capresoca.jpg" >
        </div>
        <div class="titulo">
            <h3>CENTRO REGULADOR DE REFERENCIA Y CONTRAREFERENCIA</h3>
        </div>
        <div class="legal">
            <p>CODIGO  RE-ASS-08</p>
            <p>CTRD  200.01.11</p>
            <p>VIGENCIA </p>
            <p>
                <script type="text/php">
                    if (isset($pdf)) {
                    $font = $fontMetrics->getFont("Helvetica", "lighter", "Arial");
                    $pdf->page_text(528, 755, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 8, array(0, 0, 0));
                    }
                </script>
            </p>
        </div>
    </header>
    <footer>
        <div style="width: 40%; display: inline-block; text-align: left">
            <img style="height: 35px" src="{{'./images/logo_super.png'}}" alt="Logo SuperSalud">
        </div>
        <div class="info_capresoca" style="width: 50%; display: inline-block; text-align: right;">
            <p>Calle 7 No. 19 – 34. Teléfonos: (8) 635 8162 – 635 8163</p>
            <p>635 8232 Email. capresocaeps@capresoca-casanare.gov.co</p>
            <p>Yopal Casanare</p>
        </div>
    </footer>
    <main>
        <section>
            <div id="consecutivo">
                <p>REFERENCIA No.</p>
                <h2 style="margin-right: 15px">{{ str_pad($referencia->consecutivo,6,"0",STR_PAD_LEFT)}}</h2>
                <p>{{ strftime('%d/%m/%Y %H:%M', strtotime($referencia->created_at)) }}</p>
            </div>
            <div class="card">
                <p><strong>DATOS DEL AFILIADO</strong></p>
                <div>
                    <p style="width: 90px">IDENTIFICACIÓN:</p><p style="width: 100px">{{$referencia->afiliado->tipo_id->abreviatura.' '.$referencia->afiliado->identificacion}} </p>
                    <p style="width: 70px">NOMBRE:</p><p style="width: 300px" >{{$referencia->afiliado->nombre_completo}} </p>
                    <p style="width: 70px">REGIMEN:</p><p>{{$referencia->regimen->nombre}} </p>
                </div>
                <div>
                    <p style="width: 90px">DIRECCION:</p><p style="width: 100px">{{$referencia->afiliado->direccion}} </p>
                    <p style="width: 70px">CORREO:</p><p style="width: 300px" >{{$referencia->afiliado->correo_electronico}} </p>
                    <p style="width: 70px">CELULAR:</p><p>{{$referencia->afiliado->celular}} </p>
                </div>
                <div>
                    <p style="width: 90px">SEXO:</p><p style="width: 100px">{{$referencia->afiliado->sexo->nombre}} </p>
                    <p style="width: 70px">MUNICIPIO: </p><p style="width: 300px" >{{$referencia->afiliado->municipio->nombre}} </p>
                    <p style="width: 70px">ESTADO: </p><p>{{$referencia->afiliado->estado_afiliado->nombre}} </p>
                </div>


            </div>
            <div class="card">
                <p><strong>DATOS DE LA REFERENCIA</strong></p>
                <div>
                    <p style="width: 90px">FECHA ORDEN:</p><p style="width: 100px">{{strftime('%d/%m/%Y %H:%M', strtotime($referencia->fecha_orden))}} </p>
                    <p style="width: 100px">FECHA SOLICITUD:</p><p style="width: 280px" >{{strftime('%d/%m/%Y %H:%M', strtotime($referencia->fecha_solicitud))}} </p>
                    <p style="width: 70px">ESTADO:</p><p>{{$referencia->estado}} </p>
                </div>
                <div>
                    <p style="width: 90px">CIUDAD:</p><p style="width: 100px">{{$referencia->entidadUno->municipio->nombre}} </p>
                    <p style="width: 100px">IPS ORIGEN:</p><p style="width: 280px" >{{$referencia->entidadUno->nombre}} </p>
                    <p style="width: 70px">CODIGO:</p><p>{{$referencia->entidadUno->cod_habilitacion}} </p>
                </div>
                <div>
                    <p style="width: 90px">ORIGEN:</p><p style="width: 100px">{{$referencia->tipo_origen}} </p>
                    <p style="width: 100px">SERV. SOLICITADO:</p><p style="width: 280px" >{{$referencia->servicio->nombre}} </p>
                    <p style="width: 70px">ALTO COSTO:</p><p>{{$referencia->alto_costo}} </p>
                </div>
                <div>
                    <p style="width: 90px">DIAGNOSTICO:</p><p>{{$referencia->cie10Uno->codigo.' '.$referencia->cie10Uno->descripcion}} </p>

                </div>


            </div>
            <br>
            {{--<div class="cabecera-detalle">--}}
                {{--<p><strong>Radicado Entidad:</strong></p>--}}
                {{--<p><strong>Fecha Radicado:</strong> {{ strftime('%d/%m/%Y', strtotime($radicado->fecha_radicado))  }}</p>--}}
                {{--<p style="margin-top: -4px; text-align: right"><strong>Contrato:</strong> {{ $radicado->contrato->numero_contrato.' ('.$radicado->contrato->tipo.')' }}</p>--}}
            {{--</div>--}}
            {{--<div class="cabecera-detalle">--}}
                {{--<p><strong>Cantidad Facturas :</strong> {{$radicado->facturas->count()}}</p>--}}
                {{--<p><strong>Periodo :</strong> {{ strftime('%d/%m/%Y', strtotime($radicado->periodo_inicio)).' - '.strftime('%d/%m/%Y', strtotime($radicado->periodo_fin)) }}</p>--}}
                {{--<p><strong>RIPS :</strong> {{ strftime('%d/%m/%Y', strtotime($radicado->periodo_inicio)).' - '.strftime('%d/%m/%Y', strtotime($radicado->periodo_fin)) }}</p>--}}
            {{--</div>--}}

        </section>
        <br>
        @if($referencia->bitacoras->count() > 0)
        <h2>Bitácora</h2>
        <br>
        <table>
            <tr>
                <th style="text-align: center;">No</th>
                <th>FECHA</th>
                <th>ACCION</th>
                <th style="width: 50%">OBSERVACION</th>
                <th>USUARIO</th>
            </tr>
            {{$i = 1}}
            <tr>
                <td style="text-align: center">{{$i}}</td>
                <td>{{strftime('%d/%m/%Y %H:%M', strtotime($referencia->fecha_solicitud))}}</td>
                <td>Solicitud Remisión</td>
                <td>
                    <p>
                        {{$referencia->observaciones}}
                    </p>
                </td>
                <td>{{$referencia->usuario->name}}</td>
            </tr>
            @foreach($referencia->bitacoras->sortBy('fecha') as $bitacora)
            <tr>
                <td style="text-align: center">{{++$i}}</td>
                <td>{{strftime('%d/%m/%Y %H:%M', strtotime($bitacora->fecha))}}</td>
                <td>{{$bitacora->accion->accion}}</td>
                <td>
                    <p>
                    @switch($bitacora->accion->id)
                        {{--@case(1,2,3,5,6,11)--}}
                        @case(1)
                           El afiliado es presentado a la IPS <strong>{{$bitacora->presentacion->entidad->nombre}}</strong>.
                        @break
                        @case(2)
                            El afiliado fue presentado a la IPS <strong>{{$bitacora->presentacion->entidad->nombre}}</strong> y aceptado por <strong>{{$bitacora->presentacion->medico_acepta}}</strong>.
                        @break
                        @case(3)
                            El afiliado fue presentado a la IPS <strong>{{$bitacora->presentacion->entidad->nombre}}</strong> y no fue aceptado.
                        @break
                        @case(5)
                            La IPS <strong>{{$bitacora->presentacion->entidad->nombre}}</strong> fue seleccionada para continuar el proceso de remisión.
                        @break
                        @case(6)
                            Se cancela la seleccion de la IPS <strong>{{$bitacora->presentacion->entidad->nombre}}</strong> para continuar el proceso de remisión.
                        @break
                        @case(11)
                            La IPS <strong>{{$bitacora->presentacion->entidad->nombre}}</strong> fue seleccionada para continuar el proceso de remisión.
                        @break


                        @case(4)
                            @if($bitacora->traslado->entidadTransportadora)
                                Se solicita traslado a la empresa <strong>{{$bitacora->traslado->entidadTransportadora->nombre}}</strong> hacia la IPS <strong>{{$bitacora->traslado->entidadDestino->nombre}}</strong> en la ciudad de <strong>{{$bitacora->traslado->entidadDestino->municipio->nombre}}</strong>.
                            @else
                                Se solicita traslado interno al paciente en la IPS <strong>{{$bitacora->traslado->entidadDestino->nombre}}</strong>.
                            @endif
                        @break
                        @case(8)
                            @if($bitacora->traslado->entidadTransportadora)
                                La empresa <strong>{{$bitacora->traslado->entidadTransportadora->nombre}}</strong> inicia viaje hacia  la IPS <strong>{{$bitacora->traslado->entidadDestino->nombre}}</strong> en la ciudad de <strong>{{$bitacora->traslado->entidadDestino->municipio->nombre}}</strong>.
                            @else
                                Se inicia el traslado interno al paciente en la IPS <strong>{{$bitacora->traslado->entidadDestino->nombre}}</strong>.
                            @endif
                        @break
                        @case(9)
                            @if($bitacora->traslado->entidadTransportadora)
                                La empresa <strong>{{$bitacora->traslado->entidadTransportadora->nombre}}</strong> llega a la IPS <strong>{{$bitacora->traslado->entidadDestino->nombre}}</strong> en la ciudad de <strong>{{$bitacora->traslado->entidadDestino->municipio->nombre}}</strong>.
                            @else
                                Se finaliza traslado del pacienta para atención intrahospitalaria en la IPS <strong>{{$bitacora->traslado->entidadDestino->nombre}}</strong>.
                            @endif
                        @break

                        @case(10)
                        @case(12)
                        @case(16)
                        @case(17)
                        @case(18)
                        @case(19)
                            Se cancela el proceso de remisión
                        @break



                        {{--@case(4,8,9,10,12)--}}
                        {{--@break--}}
                    @endswitch
                    </p>
                    <br>
                    <p>
                        {{$bitacora->observaciones}}
                    </p>
                </td>
                <td>{{$bitacora->usuario->name}}</td>
            </tr>
            @endforeach
        </table>
        @else
            <h2>No hay anotaciones en la bitácora</h2>
        @endif
        @if($referencia->estado_egreso != null)
        <div class="card" style="   margin-top: 20px">
            <p><strong>DATOS DE CIERRE</strong></p>
            <div>
                <p style="width: 90px">ESTADO:</p><p style="width: 100px">{{$referencia->estado_egreso}} </p>
                <p style="width: 40px">IPS:</p><p style="width: 300px" >{{$referencia->entidadTwo->nombre.' ('.$referencia->entidadTwo->municipio->nombre.')'}} </p>
                <p style="width: 70px">FECHA:</p><p>{{strftime('%d/%m/%Y %H:%M', strtotime($referencia->fecha_egreso))}} </p>
            </div>
            <div>
                <p style="width: 90px">DIAGNOSTICO:</p><p>{{$referencia->cie10Two->codigo.' '.$referencia->cie10Two->descripcion}} </p>
            </div>
        </div>
        @endif

        <div style="text-align: right; font-size: 9px">
            <p>{{ \Illuminate\Support\Facades\Auth::user() ? "Imprime: ". \Illuminate\Support\Facades\Auth::user()->name : ""}}</p>
        </div>
    </main>

</body>
</html>
