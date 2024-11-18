<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorización</title>
</head>
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
        margin: 1.5cm
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
        border: 2px solid gray;
    }
    table td
    {
        border: 1px solid gray;
        padding: 5px 2px 5px 2px;
        font-size: 9px;
    }
    header {
        width: 100%;
    }
    header .logo img {
        width: 150px;
    }
    header div {
        display: inline-block;
    }
    #legal {
        font-size: 8px;
        text-align: right;
    }
    #legal p {
        width: 100%;
        border: 1px solid gray;
        padding: 5px 2px 5px 2px;
    }
    footer {
        width: 100%;
        font-size: 10px;
    }
    #autoriza{
        margin-top: 100px;
        text-align: center;
    }
    #autoriza div {
        width: 50%;
        display: inline-block;
    }
    .watermark{
        position: fixed;
        z-index: -1000;
        color: #d1d3ce;
        font-size: 30mm;
        font-weight: bold;
        top: 40mm;
        left: 20mm;
        transform: rotate(-45deg);
    }

</style>
<body>
{{--@for($i = 1; $i <= 2; $i++)--}}
    <header>
        <div class="logo" style="width: 20%">
            <img src="./images/capresoca.jpg" >
        </div>
        <div style="width: 56%; text-align: center; ">
            <h4>AUTORIZACIÓN DE SERVICIOS MEDICOS ASISTENCIALES</h4>
        </div>
        <div style="width: 23%" id="legal">
            <p>RE_AU 09/11/2018</p>
            <p>CTRD: 310.36.03</p>
            <p>VERSION: 00</p>
            <p>VIGENCIA: 00</p>
            <p style="height: 10px">
                <script type="text/php">
                            if (isset($pdf)) {
                            $font = $fontMetrics->getFont('Helvetica', 'Arial', 'sans-serif');
                            $pdf->page_text(524, 98, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
                            }
                            </script>
            </p>
        </div>
    </header>
    @if($autaprobada->estado === 'Anulada')
        <div class="watermark">ANULADA</div>
    @endif
    @if($autaprobada->estado === 'Usada')
        <div class="watermark">USADA</div>
    @endif
    <main>
        <section id="info_basica" style="margin-top: -20px">
            <table>
                <tbody>
                <tr>
                    <td colspan="2" >Autorización Nro: {{ str_pad($autaprobada->consecutivo,8,'0',STR_PAD_LEFT) }}</td>
                    <td colspan="2">Fecha autorización: {{strftime('%d/%m/%Y', strtotime($autaprobada->fecha)) }}</td>
s                   <td colspan="2">Fecha vencimiento: {{strftime('%d/%m/%Y', strtotime( \Carbon\Carbon::parse($autaprobada->fecha)->addMonths(2)))}}</td>

                </tr>
                <tr>
                    <td colspan="4">Nombre paciente: {{ $autaprobada->autorizacion->afiliado ?  $autaprobada->autorizacion->afiliado->nombre_completo : 'NO REGISTRA' }}</td>
                    <td colspan="2">Documento: {{ $autaprobada->autorizacion->afiliado ?  $autaprobada->autorizacion->afiliado->identificacion_completa : 'NO REGISTRA' }}</td>
                </tr>
                <tr>
                    <td colspan="2">Municipio afiliación: {{ $autaprobada->autorizacion->afiliado ? $autaprobada->autorizacion->afiliado->municipio->nombre : 'YOPAL' }}</td>
                    <td colspan="2">Depto. Afiliación: {{ $autaprobada->autorizacion->afiliado ? $autaprobada->autorizacion->afiliado->municipio->departamento->nombre : 'CASANARE' }}</td>
                    <td colspan="2">Nivel SISBEN: {{ $autaprobada->autorizacion->afiliado ?  $autaprobada->autorizacion->afiliado->nivel_sisben : 'NO REGISTRA' }}</td>
                </tr>
                <tr>
                    <td colspan="4">IPS Referencia: {{ $autaprobada->autorizacion->entidad_origen ? $autaprobada->autorizacion->entidad_origen->tercero->nombre_completo : 'NO REGISTRA' }}</td>
                    <td colspan="2">Dirección:{{  $autaprobada->autorizacion->entidad_origen->direccion_sede ? $autaprobada->autorizacion->entidad_origen->direccion_sede : 'NO REGISTRA' }} {{ $autaprobada->autorizacion->entidad_origen->municipio ? $autaprobada->autorizacion->entidad_origen->municipio->nombre : 'NO REGISTRA' }} </td>
                </tr>
                <tr>
                    <td colspan="3">Diagnóstico: {{ $autaprobada->autorizacion->cie10_principal ? $autaprobada->autorizacion->cie10_principal->codigo : 'NO REGISTRA'  }}</td>
                    <td colspan="2">Nro. Solicitud: {{ $autaprobada->solicitud ? $autaprobada->solicitud->consecutivo : '' }}</td>
                    <td>Alto Costo: {{ ($autaprobada->autorizacion->alto_costo === 0) ? 'No' : 'Si' }}</td>
                </tr>
                </tbody>
            </table>
        </section>
    {{----}}
    <section id="detalles" style="margin-top: 10px">
        <table style="width: 100%">

            <tr>
                <td colspan="6" style="text-align: center">
                    <p><strong> SERVICIOS AUTORIZADOS</strong></p>
                </td>
            </tr>
            <tr>
                <td colspan="3">Prestador Asignado: <strong> {{$autaprobada->autorizacion->contrato ? $autaprobada->autorizacion->contrato->contrato->entidad->nombre : 'NO REGISTRA' }}</strong> </td>
                <td colspan="3">Dirección: <strong> {{$autaprobada->autorizacion->contrato ? $autaprobada->autorizacion->contrato->contrato->entidad->direccion_sede : 'NO REGISTRA' }} {{$autaprobada->autorizacion->contrato ? $autaprobada->autorizacion->contrato->contrato->entidad->municipio->nombre : 'NO REGISTRA' }}</strong> </td>
            </tr>
            <tr>
                <td colspan="6">
                    {{ 'Especialidad: ' . ($autaprobada->autorizacion->servicio ? $autaprobada->autorizacion->servicio->servicio->nombre : 'NO REGISTRA') }}
                </td>
            </tr>
            <tr style="font-weight: bold; text-align: center">
                <td style="width: 10%">Código</td>
                <td style="width: 30%">Descripción</td>
                <td>Cantidad</td>
                <td style="width: 15%">Valor Usuario</td>
                <td style="width: 15%">Valor EPS</td>
                <td>Observaciones</td>
            </tr>
            <tbody style="font-size: 8px">
            @foreach($autaprobada->detalles as $detalle)
                <tr>
                    <td>
                        <span>{{ $detalle->codigo }}</span>
                    </td>
                    <td style="width: 40%">
                        <span>{{ $detalle->descripcion }}</span>
                    </td>
                    <td style="width: 7%; text-align: center">
                        <span>{{ $detalle->cantidad_aprobada }}</span>
                    </td>

                    <td style="text-align: right">
                        <span>{{ $detalle->valor_usuario !== null ? '$ '. (number_format(($detalle->valor_usuario * $detalle->cantidad_aprobada), 2, ',', '.')) : "$ 0,00" }}</span>
                    </td>
                    <td style="text-align: right">
                        <span>{{ $detalle->valor !== null ? '$ '. (number_format((($detalle->valor * $detalle->cantidad_aprobada) - ($detalle->valor_usuario * $detalle->cantidad_aprobada)), 2, ',', '.')) : "$ 0,00" }}</span>
                    </td>
                    <td style="text-align: justify !important;">
                        <span>
                            {{ $detalle->observaciones ? $detalle->observaciones : 'NO REGISTRA'  }}
                        </span>
                    </td>
                </tr>
            @endforeach
            <tr style="font-weight: bold;">
                <td  colspan="3" style="border-top: double gray 3px">
                    <span>TOTAL</span>
                </td>
                <td style="border-top: double gray 3px">
                    <span style="float: right">{{ '$ ' . number_format($autaprobada->valor_tot_user, 2, ',', '.')}}</span>
                </td>
                <td style="border-top: double gray 3px">
                    <span style="float: right">{{'$ ' . number_format(($autaprobada->valor_total - $autaprobada->valor_tot_user ), 2, ',', '.')}}</span>
                </td>
                <td style="border-top: double gray 3px"></td>
            </tr>
            @if($autaprobada->valor_tot_user == 0 && $autaprobada->valor_usuario > 0 && $autaprobada->autorizacion->as_regimen_id == 1)
            <tr>
                <td colspan="6" style="font-size: 14px"> <strong>Cuota Moderadora: {{ '$ ' . number_format($autaprobada->valor_usuario, 2, ',', '.')}}</strong></td>
            </tr>
            @endif
            <tr>
                <td>
                    <span>Observaciones</span>
                </td>
                <td  colspan="5" >
                    <span>{{ $autaprobada->autorizacion->observaciones ?  $autaprobada->autorizacion->observaciones : 'Ninguna' }}</span>
                </td>
            </tr>
            </tbody>
        </table>
    </section>
        <section>
            <p style="width: 100%; text-align: center; font-size: 10px; font-style: italic">Los servicios autorizados se encuentran sujetos a las normas vigentes y a la verificación por Auditoria Médica</p>
            <div id="autoriza">
                <div>
                    <p><strong>{{ strtoupper($autaprobada->usuario->name)}}</strong></p>
                    <p>Autoriza</p>
                </div>
                <div>
                    <p><strong>{{$autaprobada->autorizacion->afiliado->nombre_completo}}</strong></p>
                    <p>Recibe</p>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <div style="text-align: right; padding-bottom: 10px;">
            <p>Imprime: {{$imprime->name}}</p>
        </div>
        @if($autaprobada->estado === 'Anulada')
            <div style="text-align: right; padding-bottom: 10px;">
                <p>Anula: {{$autaprobada->anula->name}}</p>
                <p>Motivo: {{$autaprobada->motivo_anula}}</p>
            </div>
        @endif
        <div style="width: 40%; display: inline-block; text-align: left">
            <img style="height: 35px" src="{{'./images/logo_super.png'}}" alt="Logo SuperSalud">
        </div>
        <div class="info_capresoca" style="width: 60%; display: inline-block; text-align: right;">
            <p>Calle 7 No. 19 – 34. Teléfonos: (8) 635 8162 – 635 8163</p>
            <p>635 8232 Email. capresocaeps@capresoca-casanare.gov.co</p>
            <p>Yopal Casanare</p>
        </div>
    </footer>
    {{--@if($i !== 2)--}}
    {{--<div class="saltopagina"></div>--}}
    {{--@endif--}}
{{--@endfor--}}
</body>
</html>
