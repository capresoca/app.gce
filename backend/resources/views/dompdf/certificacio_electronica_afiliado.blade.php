<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado Afiliación</title>
    <style>
        @page {  margin: 0 0;  }
        main, section, p, div, h1, h2, h3 { padding: 0; margin: 0; }
        body {
            font-size: 3.5mm !important;
            width: 100%;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 3cm 2cm 3cm 2cm
        }
        header {
            position: fixed;
            top: 0;
            width: 100%;
        }
        .logos {
            text-align: center;
            margin-top: 30px;
        }
        .logos img { width: 120px; }
        .logos p { font-size: 12px; }
        main { text-align: justify; }
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
        .celda {
            border: .2mm solid #000 !important;
            padding-top: 0.4mm;
            padding-bottom: 0.4mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }
        table th {
            font-size: 9px;
            padding-bottom: 5px;
            border-bottom: 2px solid gray;
        }
        table td {
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
    <br>
    <p style="font-weight: bold !important; text-align: right !important;">
        CE-05-2020-1022341247<span style="color: red !important;">{{str_pad($consecutivo_electronico,3,"0",STR_PAD_LEFT)}}</span>
    </p>
    <br>
    <h1 style="text-align: center; font-size: 1.5em !important;">CERTIFICADO</h1>
    <br>
    <p style="text-align: justify">
        Que una vez verificada la base de datos de afiliados, hoy {{strftime('%A %d de %B', strtotime(date('Y-m-d')))}},
        la persona identificada con el documento {{ ' ' . $afiliado->tipo_id->abreviatura .' '. $afiliado->identificacion }}
        registra la siguiente información:
    </p>
    <br>
    <table class="collapse">
        <tbody>
        <tr>
            <td class="celda" style="width: 40% !important; padding-right: 0.5mm !important;">Nombres:</td>
            <td class="celda">{{$afiliado->nombre1 . ' ' . $afiliado->nombre2}}</td>
        </tr>
        <tr>
            <td class="celda" style="width: 40% !important; padding-right: 0.5mm !important;">Apellidos:</td>
            <td class="celda">{{$afiliado->apellido1 . ' ' . $afiliado->apellido2}}</td>
        </tr>
        <tr>
            <td class="celda" style="width: 40% !important; padding-right: 0.5mm !important;">Estado de Afiliación:</td>
            <td class="celda">{{$afiliado->estado_afiliado->nombre}}</td>
        </tr>
        <tr>
            <td class="celda" style="width: 40% !important; padding-right: 0.5mm !important;">Régimen:</td>
            <td class="celda">{{$afiliado->regimen->nombre}}</td>
        </tr>
        <tr>
            <td class="celda" style="width: 40% !important; padding-right: 0.5mm !important;">Régimen:</td>
            <td class="celda">{{strftime('%d/%m/%Y', strtotime($afiliado->fecha_afiliacion))}}</td>
        </tr>
        <tr>
            <td class="celda" style="width: 40% !important; padding-right: 0.5mm !important;">Municipio de Afiliación:</td>
            <td class="celda">{{$afiliado->municipio->codigo.'-'.ucwords(strtolower($afiliado->municipio->nombre))}}</td>
        </tr>
        <tr>
            <td class="celda" style="width: 40% !important; padding-right: 0.5mm !important;">Tipo de Afiliado:</td>
            <td class="celda">{{$afiliado->tipo_afiliado->nombre}}</td>
        </tr>
        <tr>
            <td class="celda" style="width: 40% !important; padding-right: 0.5mm !important;">IPS PRIMARIA:</td>
            <td class="celda">{{$afiliado->ips['nombre'] ?? 'XXX-IPS'}}</td>
        </tr>
        <tr>
            <td class="celda" style="width: 40% !important; padding-right: 0.5mm !important;">RANGO:</td>
            @if($afiliado->rango_contributivo['grupo'] === 'Rango A')
                <td class="celda">A</td>
            @elseif($afiliado->rango_contributivo['grupo'] === 'Rango B')
                <td class="celda">B</td>
            @elseif ($afiliado->rango_contributivo['grupo'] === 'Rango C')
                <td class="celda">C</td>
            @else
                <td class="celda">#</td>
            @endif
        </tr>
        </tbody>
    </table>
    <br>
    <br>
    <p style="font-weight: bold !important;">Información del aportante</p>
    <table class="collapse">
        <tr>
            <th class="celda" style="text-align: center !important;">Documento</th>
            <th class="celda" style="text-align: center !important;">Razón Social</th>
            <th class="celda" style="text-align: center !important;">Fecha Inicio</th>
            <th class="celda" style="text-align: center !important;">Fecha Fin</th>
        </tr>
        @if($afiliado->as_regimene_id == 1 )
            @if($afiliado->as_tipafiliado_id == 1)
                @foreach($afiliado->relaciones_laborales as $relacion)
                    <tr>
                        <td class="celda" style="text-align: center !important;">{{$relacion->pagador->identificacion}}</td>
                        <td class="celda" style="text-align: center !important;">{{$relacion->pagador->razon_social}}</td>
                        <td class="celda" style="text-align: center !important;">{{$relacion->fecha_vinculacion}}</td>
                        <td class="celda" style="text-align: center !important;">{{$relacion->fecha_retiro}}</td>
                    </tr>
                @endforeach
            @else
                @foreach($afiliado->cabeza->relaciones_laborales as $relacion)
                    <tr>
                        <td class="celda" style="text-align: center !important;">{{$relacion->pagador->identificacion}}</td>
                        <td class="celda" style="text-align: center !important;">{{$relacion->pagador->razon_social}}</td>
                        <td class="celda" style="text-align: center !important;">{{$relacion->fecha_vinculacion}}</td>
                        <td class="celda" style="text-align: center !important;">{{$relacion->fecha_retiro}}</td>
                    </tr>
                @endforeach
            @endif
        @else
            <tr>
                <td class="celda" style="text-align: center !important;">891.956.000-X</td>
                <td class="celda" style="text-align: center !important;">La mejor EPS de Casanare</td>
                <td class="celda" style="text-align: center !important;">{{\Carbon\Carbon::now()->format('Y/m/d')}}</td>
                <td class="celda" style="text-align: center !important;">{{'####/##/##'}}</td>
            </tr>
        @endif
    </table>
    <br>
    <br>
    <p style="text-align: justify">
        La presente certificación se expide a solicitud del(a)  interesado(a) en  Yopal Casanare, dirigida <strong>A QUIEN LE INTERESE</strong>,
        tiene un mes de validez y su veracidad se puede constatar en la página web de la EPS con el código CE-05-2020-1022341247{{str_pad($consecutivo_electronico,3,"0",STR_PAD_LEFT)}}.
    </p>
    <br>
    <p style="font-weight: normal !important; text-align: right !important; font-size: 0.9em !important;">
       <span><i>Observaciones:</i></span>
        <br>&nbsp;
        <span><i>Esta certificación no aplica para servicios médicos ni es válido para traslado a otra EPS.</i></span>
    </p>
    <br>
    <br>
    <p style="font-weight: bolder !important;">Cordialmente:</p>
    <br>
    <br>
    <br>
    <br>
    <div style="text-align: left; width: 100%">
        <p><strong>{{$usuario}}</strong></p>
        <p><strong>PROFESIONAL UNIVERSITARIO DE ASEGURAMIENTO</strong></p>
    </div>
</main>

</body>
</html>
