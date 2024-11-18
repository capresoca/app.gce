<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado Afiliación</title>
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
            margin: 3cm 2cm 3cm 2cm
        }
        header{
            position: fixed;
            top: 0;
            width: 100%;
        }
        .logos {
            text-align: center;
            margin-top: 30px;
        }

        .logos img {
            width: 120px;
        }
        .logos p {

            font-size: 12px;
        }

        main {
            text-align: justify;
        }
        footer {
            position: fixed;
            bottom: 1.5cm;
            font-size: 10px;
            margin: 0 1cm 0 0.5cm;
        }
        #info-afiliado{
            width: 100%;
            margin-bottom: 10px;
        }

        #info-afiliado p {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
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
            border-bottom: 1px solid gray;
            padding: 5px 2px 5px 2px;
            font-size: 9px;
        }



    </style>
</head>
<body>
    <header>
        <div class="logos">
            <img src="./images/capresoca.jpg" >
            <p> NIT. 891.856.000-7</p>
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
        <p style="margin-top: 10px;">130.05</p>
        <br>
        <br>
        <p>El Yopal, {{$fecha}}</p>
        <br>
        <br>
        <h2 style="text-align: center">CERTIFICADO DE AFILIACIÓN</h2>
        <br>
        <br>
        <p style="text-align: justify">
            <strong>{{$afiliado->nombre_completo}}</strong>
            identificado(a) con {{$afiliado->tipo_id->nombre. ' '.$afiliado->identificacion}},
            se encuentra registrado(a) en <strong>CAPRESOCA EPS</strong> con la siguiente información:
        </p>
        <br>
        <br>
        <h3>Informacion del Afiliado</h3>
        <br>
        <br>
        <div id="info-afiliado">
            <p style="width: 80px">Regimen:</p><p style="width: 170px">{{$afiliado->regimen->nombre}}</p>
            <p style="width: 100px">Fecha Afiliacion:</p><p style="width: 150px">{{strftime('%d/%m/%Y', strtotime($afiliado->fecha_afiliacion))}}</p>
            <p style="width: 80px">Fecha Retiro:</p><p style="width: 70px">{{ $afiliado->fecha_retiro != null ? strftime('%d/%m/%Y', strtotime($afiliado->fecha_retiro)) : 'NR'}}</p>
        </div>
        <div id="info-afiliado">
            <p style="width: 80px">Estado:</p><p style="width: 170px">{{$afiliado->estado_afiliado->nombre}}</p>
            <p style="width: 100px">Tipo de Afiliado:</p><p style="width: 150px">{{$afiliado->tipo_afiliado->nombre}}</p>
            <p style="width: 80px">{{$afiliado->as_regimene_id == 1 ? 'Rango:' : 'Sisben' }}</p><p style="width: 70px">{{$afiliado->as_regimene_id == 1 ? $afiliado->rango() : 'Nivel '.$afiliado->nivel_sisben}}</p>
        </div>
        <div id="info-afiliado">
            <p style="width: 80px">Direccion:</p><p style="width: 170px">{{$afiliado->direccion}}</p>
            <p style="width: 100px">Ciudad: </p><p style="width: 150px">{{ucwords(strtolower($afiliado->municipio->nombre)).' - '.ucfirst(strtolower($afiliado->municipio->departamento->nombre))}}</p>
            <p style="width: 80px">Telefono:</p><p style="width: 70px">{{$afiliado->celular}}</p>
        </div>
        <div id="info-afiliado">
            <p style="width: 80px">Ficha Sisben:</p><p style="width: 170px">{{$afiliado->ficha_sisben}}</p>
            <p style="width: 100px">Puntaje Sisben: </p><p style="width: 150px">{{$afiliado->puntaje_sisben}}</p>

        </div>
        <br>
        <br>
        @if($afiliado->as_regimene_id == 1 )
            <h3>Información del Aportante</h3>
            <br>
            <table>
                <tr>
                    <th>Documento</th>
                    <th>Razón Social</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                </tr>
                @if($afiliado->as_tipafiliado_id == 1)
                @foreach($afiliado->relaciones_laborales as $relacion)
                    <tr>
                        <td>{{$relacion->pagador->identificacion}}</td>
                        <td>{{$relacion->pagador->razon_social}}</td>
                        <td>{{$relacion->fecha_vinculacion}}</td>
                        <td>{{$relacion->fecha_retiro}}</td>
                    </tr>
                @endforeach
                @else
                    @foreach($afiliado->cabeza->relaciones_laborales as $relacion)
                        <tr>
                            <td>{{$relacion->pagador->identificacion}}</td>
                            <td>{{$relacion->pagador->razon_social}}</td>
                            <td>{{$relacion->fecha_vinculacion}}</td>
                            <td>{{$relacion->fecha_retiro}}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
        @endif
        <br>
        <br>
            @if($afiliado->cabeza || ($afiliado->beneficiarios_activos !== []))
                <h3>Información Núcleo Familiar</h3>
                <br>
                <table>
                    <tr>
                        <th>Tipo Afiliado</th>
                        <th>Identificación</th>
                        <th>Nombres y Apellidos</th>
                        <th>Parentesco</th>
                    </tr>
                    @if($afiliado->as_tipafiliado_id == 1 || $afiliado->as_tipafiliado_id == 2)
                        @foreach($afiliado->beneficiarios_activos as $beneficiario)
                            {{--@if($beneficiario->estado === 3)--}}
                                <tr>
                                    <td>{{$beneficiario->tipo_afiliado->nombre}}</td>
                                    <td>{{$beneficiario->tipo_id->abreviatura.' '.$beneficiario->identificacion }}</td>
                                    <td>{{$beneficiario->nombre_completo}}</td>
                                    <td>{{$beneficiario->parentesco->nombre}}</td>
                                </tr>
                            {{--@endif--}}
                        @endforeach
                    @else
                        <tr>
                            <td>{{$afiliado->cabeza->tipo_afiliado->nombre}}</td>
                            <td>{{$afiliado->cabeza->tipo_id->abreviatura.' '.$afiliado->cabeza->identificacion}}</td>
                            <td>{{$afiliado->cabeza->nombre_completo}}</td>
                            <td>
                                @if($afiliado->as_regimene_id == 1)
                                    Cotizante
                                @else
                                    Cabeza de Familia
                                @endif
                            </td>
                        </tr>
                        @foreach($afiliado->cabeza->beneficiarios_activos as $beneficiario)
                            {{--@if($beneficiario->estado === 3)--}}
                                <tr>
                                    <td>{{$beneficiario->tipo_afiliado->nombre}}</td>
                                    <td>{{$beneficiario->tipo_id->abreviatura.' '.$beneficiario->identificacion }}</td>
                                    <td>{{$beneficiario->nombre_completo}}</td>
                                    <td>{{$beneficiario->parentesco->nombre}}</td>
                                </tr>
                            {{--@endif--}}
                        @endforeach
                    @endif
                </table>

            @endif


        <br>
        <br>
        <p>
            <strong>
                Si al verificar su información encuentra inconsistencias, por favor acercarse a las oficinas de CAPRESOCA EPS
                en la Calle 7 # 19 - 34 o comunicarse a los telefonos (57) 8 6356363, (57) 8 6358162, (57) 8 6358163, (57) 8 6324068, (57) 8 6348614,
                línea de atención gratuita: 018000912880 para actualizar datos.
            </strong></p>
        <div style="text-align: center; width: 100%; margin-top: 120px;">
            <p style="margin: 0 25% 0 25%; border-top: black solid 1px; text-align: center "><strong>PROCESO MISIONAL DE ASEGURAMIENTO</strong></p>

        </div>
        <div style="font-size: 8px; margin-top: 50px;">
            <p><strong>{{ strtoupper($usuario)}}</strong></p>
            <p>Proyectó</p>
        </div>

    </main>

</body>
</html>
