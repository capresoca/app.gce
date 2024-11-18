<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Respuesta PQRS</title>
    <style>

        @page{
            margin: 0 0;
        }
        main, section, p, div, h1, h2, h3 {
            padding: 0;
            margin: 0;
        }

        body {
            font-size: 14px;
            width: 100%;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 3cm 2cm 2cm 2cm
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
        #contenido{

        }

        .watermark{
            position: fixed;
            z-index: -1000;
            color: #d1d3ce;
            font-size: 30mm;
            font-weight: bold;
            top: 100mm;
            left: 20mm;
            transform: rotate(-45deg);
        }
        #firmas {
            page-break-inside:avoid;
        }
        #firmas div p {
            padding: 0;
            margin: 0;
        }
        #firma_sandra {
            background: url('/images/firmas/SM_16072019_093450.png');
            height: 120px;
            background-repeat: no-repeat;
            margin-bottom: -45px;
        }
        #firma_proyecto {
            background: url('/images/firmas/6523156474654_1.png');
            height: 50px;
            background-repeat: no-repeat;
            margin-bottom: -20px;
        }
        .watermark{
            position: fixed;
            z-index: -1000;
            color: #a8aaa5;
            font-size: 35mm;
            font-weight: bold;
            top: 100mm;
            left: 0mm;
            transform: rotate(-45deg);
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
            }
            setlocale(LC_ALL, "es_ES");
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

        @if($pqrs->estado != 'Respondido')
            <div class="watermark">BORRADOR</div>
        @endif
        <p style="margin-top: 10px;">120.29.5.0</p>
        <br>
        <br>
        <p>El Yopal, {{$fecha}}</p>
        <br>
        <br>
        <p>Señor:</p>
        <p><strong>{{$pqrs->nombres.' '.$pqrs->apellidos}}</strong></p>
        <p>CC No {{$pqrs->identificacion}}</p>
        <p>{{$pqrs->direccion}}</p>
        <p>{{$pqrs->municipio->nombre.' - '.$pqrs->municipio->departamento->nombre}}</p>
        <br>
        <br>
        <p>ASUNTO: Respuesta Queja No. <strong>{{$pqrs->consecutivo_externo ? $pqrs->consecutivo_externo:  "NR"}}</strong>, Consecutivo Interno No. <strong>{{ str_pad($pqrs->consecutivo,6,"0",STR_PAD_LEFT)}}</strong> </p>
        <br>
        <br>
        <p id="contenido">
            {!!$pqrs->respuesta->respuesta!!}
        </p>
        <br>
        <p><strong>FRENTE A CUALQUIER DESACUERDO FRENTE A LA DECISIÓN TOMADA, PODRÁN ELEVAR CONSULTA ANTE EL ENTE TERRITORIAL DEPARTAMENTAL Y/O MUNICIPAL Y LA SUPERINTENDENCIA NACIONAL DE SALUD</strong></p>
        <br>
        <br>
        <p>Cordialmente.</p>
        <div id="firmas" >
            <div id="profesional">
                <div id="firma_sandra"></div>
                <p><strong>SANDRA MILENA TRASLAVIÑA VARGAS</strong></p>
                <p>Profesional universitario Atención al usuario</p>
            </div>
            <div id="proyecto" style="font-size: 8px; margin-top: 10px">
                <div id="firma_proyecto">                </div>
                <p><strong>PROYECTO: EFRAÍN HERNÁNDEZ</strong></p>
                <p>Técnico Atención al usuario.</p>
            </div>
        </div>
    </main>

</body>
</html>
