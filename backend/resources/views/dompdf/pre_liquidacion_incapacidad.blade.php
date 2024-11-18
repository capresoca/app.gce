<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Incapacidad</title>
    <style>

        @page{
            margin: 0 0;
        }
        main, section, p, div, h1, h2, h3 {
            padding: 0;
            margin: 0;
        }

        body {
            font-size: 12px;
            width: 100%;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 2cm 2cm 3cm 2cm
        }
        header{
            width: 100%;
        }
        .logos {
            text-align: left;
            display: inline-block;
            width: 30%;
        }
        .documento {
            display: inline-block;
            width: 70%;
            text-align: right;
        }

        .logos img {
            width: 120px;
        }
        .logos p {

            font-size: 12px;
        }

        main {
            text-align: justify;
            margin-top: -50px;

        }
        footer {
            position: fixed;
            bottom: 1.5cm;
            font-size: 10px;
            margin: 0 1cm 0 0.5cm;
        }
        .info-afiliado{
            width: 100%;
        }

        .info-afiliado div {
            display: inline-block;
            width: 49%;
            padding: 3px 0 3px 0;
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
            border-bottom: 1px solid gray;
        }
        table td
        {
            border-bottom: 1px solid gray;
            padding: 5px 2px 5px 2px;
            font-size: 9px;
        }
        label{
            width: 400px;
        }

        input {
            width: 15px;
            height: 15px;
            vertical-align: bottom;
            margin-top: -7px;
            padding-left: 10px;


        }
        div.saltopagina{
            display:block;
            page-break-before:always;
        }
        #consecutivo {
            width: 100%;
            text-align: right;
            font-size: 12px;
            margin-top: -40px;

        }

        .tabla_liquidacion tr td {
            text-align: right;
        }
        .tabla_liquidacion tr td:before{
            content: "COP $";
        }



    </style>
</head>
<body>
    <header>
        <div class="logos">
            <img src="./images/capresoca.jpg" >
            <p> NIT. 891.856.000-7</p>
        </div>
        <div class="documento">
            <p><strong>SOLICITUD PAGO DE INCAPACIDADES Y LICENCIAS</strong></p>
            <p>FO-AS-01</p>
            <p>2018-10-01</p>
            <p>V.02</p>
        </div>

        <script type="text/php">
            if (isset($pdf)) {
                $font = $fontMetrics->getFont("Helvetica", "Arial", "lighter");
                $pdf->page_text(540, 755, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
                setlocale(LC_ALL, "es_ES");
            }
        </script>
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
        @if($solicitud->estado == 'Registrada')
        <h3 style="text-align: center">SOLICITUD DE PAGO DE INCAPACIDADES Y LICENCIAS</h3>
        <br>
        <br>
        <p>El Yopal, {{$fecha}}</p>
        <br>
        <br>
        <p>Señores:</p>
        <p><strong>CAPRESOCA EPS</strong></p>
        <p>Ciudad</p>
        <br>
        <br>
        <p>Referencia: Reembolso por Incapacidad y Licencias.</p>
        <br>
        <br>
        <p>Muy cordialmente solicitamos a ustedes se realice el pago de la siguiente incapacidad </p>
        <br>
        <br>
        <table>
            <tr>
                <th>TIPO DOCUMENTO</th>
                <th>NÚMERO DE DOCUMENTO</th>
                <th>NOMBRE COMPLETO</th>
                <th>FECHA INICIO</th>
                <th>TIPO INCAPACIDAD</th>
            </tr>
            <tr>
                <td>{{$solicitud->afiliado->tipo_identificacion}}</td>
                <td>{{$solicitud->afiliado->identificacion}}</td>
                <td>{{$solicitud->afiliado->nombre_completo}}</td>
                <td>{{strftime('%d/%m/%Y', strtotime($solicitud->fecha_inicio))}}</td>
                <td>{{$solicitud->tipo_incapacidad->tipo}}</td>

            </tr>
        </table>
        <br>
        <br>
        <h3>DATOS DEL APORTANTE</h3>
        <br>
        <br>

        <div class="info-afiliado">
            <div>
                <p><strong>Razon Social:</strong> {{$solicitud->relacion_laboral->pagador->razon_social}}</p>
            </div>
            <div>
                <p><strong>Identificacion:</strong> {{$solicitud->relacion_laboral->pagador->identificacion}}</p>
            </div>
        </div>
        <div class="info-afiliado">
            <div>
                <p><strong>Dirección:</strong> {{$solicitud->relacion_laboral->pagador->tercero ? $solicitud->relacion_laboral->pagador->tercero->direccion : 'NR' }}</p>
            </div>
            <div>
                <p><strong>Teléfono:</strong> {{$solicitud->relacion_laboral->pagador->tercero ? $solicitud->relacion_laboral->pagador->tercero->telefono : 'NR' }}</p>
            </div>
        </div>
        <div class="info-afiliado">
            <div>
                <p><strong>Correo Electronico:</strong> {{ $solicitud->relacion_laboral->pagador->tercero ? $solicitud->relacion_laboral->pagador->tercero->email : 'NR' }}</p>
            </div>
            <div>
                <p><strong>Municipio: </strong>{{$solicitud->relacion_laboral->pagador->tercero ? $solicitud->relacion_laboral->pagador->tercero->municipio->nombre : 'NR'}}</p>
            </div>
        </div>
        <br>
        <br>

        <div style="width: 100%">
            <p><strong>Pagar a: </strong>{{$solicitud->pagar_a}}</p>
            <p><strong>Anexo Certificación Bancaria: </strong></p>
            <div style="width: 100%; padding-top: 10px">
                <p style="display: inline-block; width: 50%">
                    <label for="si">Sí<input type="checkbox" {{$solicitud->certbanco_id != null ? 'checked' : ''}} ></label>
                </p>
                <p style="display: inline-block; width: 40%">
                    <label for="No">No<input type="checkbox" {{$solicitud->certbanco_id == null ? 'checked' : ''}} ></label>
                </p>
            </div>

        </div>
        <div style="width: 100%;">
            <div style="text-align: center; width: 80%; margin-top: 30px; display: inline-block">
                <p style="margin: 0 25% 0 25%; border-top: black solid 1px; text-align: center "><strong>Firma Solicitante</strong></p>
                <p style="margin-left: -80px">CC:                </p>
            </div>
            <div style="display: inline-block;width: 10%; text-align: center">
                <div style="border: solid 2px black; width: 80px; height: 100px;"></div>
                <p>Huella</p>
            </div>
        </div>
        <div style="font-size: 8px; margin-top: 50px; text-align: right">
            <p>Imprime: {{ strtoupper($usuario)}}</p>
            <br>
            <p>Elaboró: {{ strtoupper($solicitud->usuario->name)}}</p>
        </div>
        <div class="saltopagina"></div>
        @endif
        <br>
        <div id="consecutivo">
            @if($solicitud->estado == 'Aprobada')
                <p>INCAPACIDAD No.</p>
            @else
                <p>SOLICITUD DE INCAPACIDAD No.</p>
            @endif
{{--            <h2 style="margin-right: 15px">{{ str_pad($solicitud->consecutivo,6,"0",STR_PAD_LEFT)}}</h2>--}}
            <h2 style="margin-right: 0 !important;">{{ $solicitud->numeroDocumento}}</h2>
            <p>{{ strftime('%d/%m/%Y %H:%M', strtotime($solicitud->created_at)) }}</p>
        </div>
        <br>
        <br>
        <h3 style="text-align: center">SOLICITUD DE PAGO DE INCAPACIDADES Y LICENCIAS</h3>
        <br>
        <br>
        <h3>Datos de la Incapacidad</h3>
        <br>
        <table>
            <tr>
                <th>Nombre de la Empresa</th>
                <td>{{$solicitud->relacion_laboral->pagador->razon_social}}</td>
            </tr>
            <tr>
                <th>NIT de la Empresa</th>
                <td>{{$solicitud->relacion_laboral->pagador->identificacion}}</td>
            </tr>
            <tr>
                <th>Nombres y Apellidos del Trabajor</th>
                <td>{{$solicitud->afiliado->nombre_completo}}</td>
            </tr>
            <tr>
                <th>Identificación del Trabajador</th>
                <td>{{$solicitud->afiliado->tipo_identificacion.' '.$solicitud->afiliado->identificacion}}</td>
            </tr>
            <tr>
                <th>Fecha Nacimiento Trabajador</th>
                <td>{{strftime('%d/%m/%Y', strtotime($solicitud->afiliado->fecha_nacimiento))}}</td>
            </tr>
            <tr>
                <th>Tipo de Incapacidad</th>
                <td>{{$solicitud->tipo_incapacidad->tipo}}</td>
            </tr>
            <tr>
                <th>Diagnóstico</th>
                <td>{{$solicitud->cie10->codigo.' '.$solicitud->cie10->descripcion}}</td>
            </tr>

            <tr>
                <th>Fecha de Inicio Incapacidad</th>
                <td>{{strftime('%d/%m/%Y', strtotime($solicitud->fecha_inicio))}}</td>
            </tr>
            <tr>
                <th>Fecha Fin Incapacidad</th>
                <td>{{strftime('%d/%m/%Y', strtotime($solicitud->fecha_fin))}}</td>
            </tr>
            <tr>
                <th>Total Días Incapacidad</th>
                <td>{{$solicitud->dias_incapacidad}}</td>
            </tr>
            <tr>
                <th>Total Días Incapacidad a Cargo de Empleador</th>
                @if($solicitud->estado == 'Aprobada')
                    <td>{{$solicitud->dias_incapacidad - $solicitud->dias_pagado}}</td>
                @else
                    @if($solicitud->tipo_incapacidad->prestacion == '1' || $solicitud->au_tipincapacidades_id == 16 || $solicitud->au_tipincapacidades_id == 6 || $solicitud->au_tipincapacidades_id == 7 )
                        <td>0</td>
                    @else
                        <td>2</td>
                    @endif
                @endif
            </tr>

            <tr>
                <th>Total Días Incapacidad a Cargo de Capresoca</th>
                @if($solicitud->estado == 'Aprobada')
                    <td>{{$solicitud->dias_pagado}}</td>
                @else
                    @if($solicitud->tipo_incapacidad->prestacion == '1')
                        <td>{{$solicitud->dias_incapacidad}}</td>
                    @else
                        @if($solicitud->au_tipincapacidades_id == 16 || $solicitud->au_tipincapacidades_id == 6 || $solicitud->au_tipincapacidades_id == 7 )

                            <td>{{$solicitud->dias_incapacidad }}</td>
                        @else
                            <td>{{$solicitud->dias_incapacidad - 2 }}</td>
                        @endif
                    @endif

                @endif
            </tr>
        </table>
        <br>
        <br>
        <h3>Datos de la @if($solicitud->estado != 'Aprobada') Pre-@endif Liquidación</h3>
        <br>
        <table class="tabla_liquidacion">
            <tr>
                <th>Salario Base de Cotización (IBC)</th>
                <td>{{ number_format($liquidacion['ibc'],2,',','.')}}</td>
            </tr>
            <tr>
                <th>Salario Diario</th>
                <td>{{ number_format(round($liquidacion['salario_dia'],-2,PHP_ROUND_HALF_ODD),2,',','.')}}</td>
            </tr>
            <tr>
                <th>Salario Diario a Reconocer (66.66%)</th>
                <td>{{ number_format(round($liquidacion['salario_reconocer'],-2,PHP_ROUND_HALF_ODD),2,',','.')}}</td>
            </tr>
            <tr>
                <th>Salario Diario a Reconocer Capresoca EPS</th>
                <td>{{ number_format(round($liquidacion['salario_pagar'],-2,PHP_ROUND_HALF_ODD),2,',','.')}}</td>
            </tr>
            <tr>
                <th>Total a Pagar Capresoca EPS</th>
                <td>{{ number_format( round($liquidacion['total_pagar'],-2,PHP_ROUND_HALF_ODD),2,',','.')}}</td>
            </tr>
        </table>
        <br>
        <br>
        <h3>Programación Pagos</h3>
        <br>
        <table class="tabla_liquidacion">
            <tr>
                <th>Dia</th>
                <th style="text-align: right">Valor</th>
            </tr>
            @foreach($programacion as $key => $value)
                <tr>
                    <th>{{$key}}</th>
                    <td>{{number_format(round($value,-2, PHP_ROUND_HALF_ODD),2,',','.')}}</td>
                </tr>
            @endforeach
        </table>

    </main>

</body>
</html>
