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
            font-size: 10pt;
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

       main div p{
           display: inline-block;
       }
       main div {
           margin-bottom: 3px;
       }
        .label{
            width: 27%;
        }
        .data_label{
            width: 71.5%;
            padding: 3px;
            background-color: #EFF1F1;
            border-radius: 3px;
        }
        #motivo {
            margin-top: 6px;
            width: 99%;
            min-height: 20px;
            padding: 3px;
            background-color: #EFF1F1;
            border-radius: 3px;
            text-align: justify;

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
            <p><strong>SOLICITUD PORTABILIDAD</strong></p>
            <p>CODIGO:RE-RS-06</p>
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
        <p>El Yopal, {{$fecha}}</p>
        <br>
        <h4>DATOS DEL AFILIADO</h4>
        <div>
            <p class="label">Nombres:</p>
            <p class="data_label">{{$portabilidad->afiliado->nombre1.' '.$portabilidad->afiliado->nombre2}}</p>
        </div>
        <div>
            <p class="label">Apellidos:</p>
            <p class="data_label">{{$portabilidad->afiliado->apellido1.' '.$portabilidad->afiliado->apellido2}}</p>
        </div>
        <div>
            <p class="label">Identificación:</p>
            <p class="data_label">{{$portabilidad->afiliado->tipo_identificacion.' '.$portabilidad->afiliado->identificacion}}</p>
        </div>
        <div>
            <p class="label">Municipio de Afiliación:</p>
            <p class="data_label">{{$portabilidad->afiliado->municipio->nombre.' '.$portabilidad->afiliado->municipio->departamento->nombre}}</p>
        </div>
        <div>
            <p class="label">IPS Asignada:</p>
            <p class="data_label">{{$portabilidad->ips ? $portabilidad->ips->nombre : 'No Registra'}}</p>
        </div>
        <br>
        <h4>DATOS DE LA PORTABILIDAD</h4>
        <div>
            <p class="label">Municipio al cual se traslada:</p>
            <p class="data_label">{{$portabilidad->municipio_receptor->nombre.' '.$portabilidad->municipio_receptor->departamento->nombre}}</p>
        </div>
        <p></p>
        <div>
            <p class="label">Fecha traslada al municipio:</p>
            <p class="data_label">{{$portabilidad->fecha_inicio}}</p>
        </div>
        <div>
            <p class="label">Fecha regresa del municipio:</p>
            <p class="data_label">{{$portabilidad->fecha_fin}}</p>
        </div>
        <p>Sírvase describir la razón por la cual va a permanecer en ese lugar:</p>
        <p id="motivo">{{$portabilidad->motivo ? $portabilidad->motivo : 'Sin Motivo'}}</p>
        <br>
        <h4>DATOS PARA LA NOTIFICACIÓN</h4>
        <div>
            <p class="label">Dirección del domicilio:</p>
            <p class="data_label">{{$portabilidad->direccion ? $portabilidad->direccion : 'No Registra' }}</p>
        </div>
        <div>
            <p class="label">Teléfono:</p>
            <p class="data_label">{{$portabilidad->telefono ? $portabilidad->telefono : 'No Registra'}}</p>
        </div>
        <div>
            <p class="label">Correo Electrónico:</p>
            <p class="data_label">{{
            ($portabilidad->email === null || trim($portabilidad->email) === "null")
                ? 'actualizar@actualizar.com' : $portabilidad->email}}</p>
        </div>
        <br>
        <table style="width: 100%">
            <tr>
                <td class="label" style="width: 13%"><b>Firma Solicitante</b></td>
                <td class="data_label" style="width: 25% !important;"></td>
                <td class="label" style="width: 12%"><b>N° Documento:</b></td>
                <td class="data_label" style="width: 20%"></td>
            </tr>
            <tr>
                <td colspan="4">
                    <div style="border: 0.5mm solid #0C0301 !important;
                    width: 10%; float: right !important; padding-right: 0; height: 8.5%;
                    text-align: center !important;">
                        <span style="font-size: 1.7mm">HUELLA</span>
                    </div>
                </td>
            </tr>
        </table>
        <br>
        <br>
        <p><strong>{{$portabilidad->afiliado->nombre_completo}}</strong></p>
        <p>{{$portabilidad->afiliado->tipo_identificacion.' '.$portabilidad->afiliado->identificacion}}</p>
        <br>
        <p style="font-size: 8px; text-align: justify; padding: 0px; background-color: #FAFCFC; border-radius: 2px">
            Recuerde que por cada miembro de su núcleo familiar que haya cambiado de municipio debe registrar la solicitud de portabilidad; a través de los siguientes canales:
            <br>
            <strong>CORREO ELECTRÓNICO:</strong> <email>portabilidad@capresoca-casanare.gov.co</email>
            <br>
            <strong>LÍNEA TELEFÓNICA:</strong> (8) 635 8163 Ext 128.
            <br>
            <strong>PRESENCIAL:</strong> Solicite el formulario de portabilidad en las oficinas de atención al usuario de su municipio, o descárguelo de la página web www.capresoca-casanare.gov.co/. Diligéncielo y radique su solicitud.

        </p>
    </main>

</body>
</html>
