<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificación Red de Servicios</title>
    <style>
        @page {
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

        header {
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

        .info-afiliado {
            width: 100%;
        }

        #info-afiliado {
            width: 100%;
            margin-bottom: 10px;
        }

        #info-afiliado p {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        table th {
            font-size: 9px;
            padding-bottom: 5px;
            border-bottom: 1px solid gray;
        }

        table td {
            border-bottom: 1px solid gray;
            padding: 5px 2px 5px 2px;
            font-size: 9px;
        }

        label {
            width: 400px;
        }

        input {
            width: 15px;
            height: 15px;
            vertical-align: bottom;
            margin-top: -7px;
            padding-left: 10px;


        }

        div.saltopagina {
            display: block;
            page-break-before: always;
        }

        #consecutivo {
            width: 100%;
            text-align: right;
            font-size: 12px;
            margin-top: -40px;

        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        table th {
            font-size: 9px;
            padding-bottom: 5px;
        }

        table td {
            border-bottom: 2px solid gray;
            padding: 5px 2px 5px 2px;
            font-size: 9px;
        }


    </style>
</head>
<body>
<header>
    <div class="logos">
        <img src="./images/capresoca.jpg">
        <p> NIT. 891.856.000-7</p>
    </div>
    <div class="documento">
        <p><strong>ASIGNACION RED DE SERVICIOS</strong></p>
        <p>Código: FRS - 08</p>
        <p>Versión: 1.0 </p>
        <p>Vigencia: 2010</p>
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

    <h3>Información del Afiliado</h3>
    <br>
    <br>
    <div id="info-afiliado">
        <p style="width: 80px">Documento:</p>
        <p style="width: 170px">{{$afiliado->identificacion_completa}}</p>
        <p style="width: 100px">Nombres:</p>
        <p style="width: 300px">{{$afiliado->nombre_completo}}</p>
    </div>
    <div id="info-afiliado">
        <p style="width: 80px">Régimen:</p>
        <p style="width: 170px">{{$afiliado->regimen->nombre}}</p>
        <p style="width: 100px">Fecha Afiliación:</p>
        <p style="width: 150px">{{strftime('%d/%m/%Y', strtotime($afiliado->fecha_afiliacion))}}</p>
        <p style="width: 80px">Fecha Retiro:</p>
        <p style="width: 70px">{{ $afiliado->fecha_retiro != null ? strftime('%d/%m/%Y', strtotime($afiliado->fecha_retiro)) : 'NR'}}</p>
    </div>
    <div id="info-afiliado">
        <p style="width: 80px">Estado:</p>
        <p style="width: 170px">{{$afiliado->estado_afiliado->nombre}}</p>
        <p style="width: 100px">Tipo de Afiliado:</p>
        <p style="width: 150px">{{$afiliado->tipo_afiliado->nombre}}</p>
        <p style="width: 80px">{{$afiliado->as_regimene_id == 1 ? 'Rango:' : 'Sisbén' }}</p>
        <p style="width: 70px">{{$afiliado->as_regimene_id == 1 ? $afiliado->rango() : 'Nivel '.$afiliado->nivel_sisben}}</p>
    </div>
    <div id="info-afiliado">
        <p style="width: 80px">Ciudad:</p>
        <p style="width: 370px">{{ucwords(strtolower($afiliado->municipio->nombre))}}</p>

    </div>
    <br>
    <h3>Red de Servicios</h3>
    <br>
    @if($afiliado->estado_afiliado->nombre == 'Activo')
        <table>
            <tr>
                <th>Servicio</th>
                <th>Prestador</th>
                <th>Direccion</th>
                <th>Telefono</th>
                <th>Ciudad</th>
            </tr>
            @foreach($afiliado->servicios_habilitados as $servhabilitado)
                <tr>
                    <td>{{$servhabilitado->servicio->nombre}}</td>
                    <td>{{$servhabilitado->entidad->nombre}}</td>
                    <td>{{$servhabilitado->entidad->direccion_sede}}</td>
                    <td>{{$servhabilitado->entidad->telefono_sede}}</td>
                    <td>{{$servhabilitado->entidad->municipio->nombre}}</td>
                </tr>
            @endforeach
        </table>
    @else
        <h3 style="color: red">SIN RED DE SERVICIOS ({{$afiliado->estado_afiliado->nombre}})</h3>
    @endif
    <br>
    <br>
    <p style="font-size: 10px">
        <strong>
            Si al verificar su información encuentra inconsistencias, por favor acercarse a las oficinas de CAPRESOCA
            EPS
            en la Calle 7 # 19 - 34 o comunicarse a los telefonos (57) 8 6356363, (57) 8 6358162, (57) 8 6358163, (57) 8
            6324068, (57) 8 6348614,
            línea de atención gratuita: 018000912880 para actualizar datos.
        </strong></p>
    <div style="text-align: center; width: 100%; margin-top: 80px;">
        <p style="width: 50%; margin: 0 auto; border-top: black solid 1px; text-align: center "><strong>PROCESO MISIONAL DE ASEGURAMIENTO</strong></p>
    </div>
    <div style="font-size: 8px; margin-top: 30px;">
        <p><strong>{{ strtoupper($usuario)}}</strong></p>
        <p>Proyectó</p>
    </div>
</main>

</body>
</html>
