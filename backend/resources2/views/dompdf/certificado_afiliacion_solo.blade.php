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
        <br>
        <h2 style="text-align: center">CERTIFICADO DE AFILIACIÓN</h2>
        <br>
        <br>
        <br>
        <br>
        <br>
        <p style="text-align: justify">
            {{--@if($afiliado->gn_sexo_id == 2)--}}
                {{--El señor--}}
            {{--@else--}}
                {{--La señora--}}
            {{--@endif--}}
            <strong>{{$afiliado->nombre_completo}}</strong>
            identificado(a) con {{$afiliado->tipo_id->nombre. ' No. '.$afiliado->identificacion}},
            se encuentra registrado(a) en <strong>CAPRESOCA EPS</strong> desde el {{strftime('%d/%m/%Y', strtotime($afiliado->fecha_afiliacion))}} en el
            regimen <strong>{{$afiliado->regimen->nombre}}</strong> por el municipio de <strong>{{ucwords(strtolower($afiliado->municipio->nombre)).', '.ucfirst(strtolower($afiliado->municipio->departamento->nombre))}}</strong>  y su estado actual es <strong>{{strtoupper($afiliado->estado_afiliado->nombre)}}</strong>
        </p>
        <br>
        <br>


        <br>
        <br>
        <div style="text-align: center; width: 100%; margin-top: 120px;">
            <p style="margin: 0 25% 0 25%; border-top: black solid 1px; text-align: center "><strong>PROCESO MISIONAL DE ASEGURAMIENTO</strong></p>

        </div>
        <div style="font-size: 8px; margin-top: 50px;">
            <p><strong>{{ strtoupper($usuario)}}</strong></p>
            <p>Proyectó</p>
        </div>
        <br>
        <br>
        <br>
        <div>
            <p style="font-size: 10px">

                    Si al verificar su información encuentra inconsistencias, por favor acercarse a las oficinas de CAPRESOCA EPS
                    en la Calle 7 # 19 - 34 o comunicarse a los telefonos (57) 8 6356363, (57) 8 6358162, (57) 8 6358163, (57) 8 6324068, (57) 8 6348614,
                    línea de atención gratuita: 018000912880 para actualizar datos.
                </p>
        </div>

    </main>

</body>
</html>
