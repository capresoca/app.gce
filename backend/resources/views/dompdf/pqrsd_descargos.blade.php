<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Descargos</title>
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
        main{
            font-size: 11pt;
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
        #firma_proyecto {
            background: url('/images/firmas/6523156474654_2.png');
            height: 100px;
            background-repeat: no-repeat;
            margin-bottom: -45px;
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
        <div class="documento">
            <p><strong>INTERPOSICIÓN DE QUEJAS</strong></p>
            <p>CODIGO:RE-AU-06</p>
            <p>CTRD: 310.12</p>
            <p>VERSION: 00</p>
            <p>VIGENCIA:</p>
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
        @if($pqrs->estado != 'Respondido')
            <div class="watermark">BORRADOR</div>
        @endif
        <p>El Yopal, {{$fecha}}</p>
        <br>
        <br>
        <p>Señores:</p>
        <p><strong>{{$pqrs->entidad->nombre}}</strong></p>
        <p>{{ucwords(strtolower($pqrs->entidad->direccion_sede))}}</p>
        <p>{{ ucwords(strtolower($pqrs->entidad->municipio->nombre.' '.$pqrs->entidad->municipio->departamento->nombre)) }}</p>
        <br>
        <br>
        <p>
            En cumplimiento a la circular 009 de 1996 emitida por la Superintendencia Nacional de Salud, hago llegar copia
            del reclamo interpuesto ante esta oficina por el usuario (a) <strong>{{$pqrs->nombres.' '.$pqrs->apellidos}}</strong>, identificado (a)
            con <strong>{{$pqrs->tipo_documento->nombre}} No. {{$pqrs->identificacion}}</strong> afiliado (a) por el municipio de <strong>{{$pqrs->municipio->nombre}}</strong>,
            quien manifiesta su inconformismo por <strong>{{$pqrs->motivo->descripcion}}</strong>

        </p>
        <br>
        <br>
        <p>
            Respetuosamente solicitamos dar respuesta al reclamo del usuario (a) y tomar las medidas en caso de haber
            responsabilidad por parte suya, además de cumplir el protocolo de trámite de quejas y reclamos
        </p>
        <br>
        <br>
        <p>
            La respuesta deberá ser dirigida a la oficina de Atención al Usuario de CAPRESOCA EPS sede Yopal.
        </p>
        <br>
        <br>
        <p>
            El reclamo es de tipo <strong>{{$pqrs->tipo->descripcion}}</strong>, por lo tanto la respuesta no podrá superar {{$pqrs->tipo->plazo}} días hábiles a partir de la
            fecha de recepción y hacer llegar la respuesta y posible solución en el tiempo fijado.
        </p>
        <br>
        <br>
        <p>
            Hasta otra oportunidad,
        </p>
        <br>
        <br>
        <br>
        <br>
        <div id="firma_proyecto"></div>
        <p><strong>EFRAIN HERNANDEZ RAMIREZ</strong></p>
        <p>Oficina Atencion al Usuario</p>
    </main>

</body>
</html>
