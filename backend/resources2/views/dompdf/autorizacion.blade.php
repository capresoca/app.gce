<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorización</title>
</head>
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
        margin: 0.8cm
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
    }

    table th {
        font-size: 9px;
        padding-bottom: 5px;
        border: 2px solid gray;
    }

    table td {
        border: 1px solid gray;
        padding: 3px 2px 3px 2px;
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
        padding: 3px 2px 3px 2px;
    }

    footer {
        margin-top: -10px;
        width: 100%;
        font-size: 10px;
    }

    #autoriza {
        padding-top: 20px;
        text-align: center;
    }

    #autoriza div {
        width: 50%;
        display: inline-block;
    }

    .watermark {
        position: fixed;
        z-index: -1000;
        color: #d1d3ce;
        font-size: 50mm;
        font-weight: bold;
        top: 30mm;
        left: 50mm;
        transform: rotate(-45deg);
    }

    .parrafo_desc {
        overflow-wrap: break-word !important;
        text-align: justify !important;
    }

</style>
<body>
{{--@for($i = 1; $i <= 2; $i++)--}}
<header>
    <div class="logo" style="width: 20%">
        <img src="./images/capresoca.jpg">
    </div>
    <div style="width: 56%; text-align: center; ">
        <h4>AUTORIZACIÓN DE SERVICIOS MEDICOS ASISTENCIALES</h4>
    </div>
    <div style="width: 23%" id="legal">
        <p>RE_AU 09/11/2018 | CTRD: 310.36.03</p>
        <p>VERSION: 00 | VIGENCIA: 00</p>
        <p style="height: 10px">
            <script type="text/php">
                            if (isset($pdf)) {
                            $font = $fontMetrics->getFont('Helvetica', 'Arial', 'sans-serif');
                            $pdf->page_text(524, 63, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
                            }


            </script>
        </p>
    </div>
</header>
@if($autaprobada->ind === '2' && $imp === 1)
    <div class="watermark">ANULADA</div>
@endif
{{--@if($autaprobada->ind === '3' && $imp === 1)--}}
{{--    <div class="watermark">COPIA</div>--}}
{{--@endif--}}
<main>
    <section id="info_basica" style="margin-top: -16px">
        <table>
            <tbody>
            <tr>
                <td colspan="2">Nro. Anexo: {{ str_pad($autaprobada->id,8,'0',STR_PAD_LEFT) }}</td>
                <td colspan="2">Fecha autorización: {{strftime('%d/%m/%Y', strtotime($autaprobada->fS1)) }}</td>
                <td colspan="2">Fecha vencimiento:{{ $autaprobada->fecha_vencimiento }}</td>
            </tr>
            <tr>
                <td colspan="4">Nombre paciente: {{ $autaprobada->afiliado
                    ?  $autaprobada->afiliado->nombre_completo : 'NO REGISTRA' }}</td>
                <td colspan="1">Documento: {{ $autaprobada->afiliado
                    ?  $autaprobada->afiliado->identificacion_completa : 'NO REGISTRA' }}
                </td>
                <td colspan="1">Régimen: {{ $autaprobada->afiliado
                    ?  ($autaprobada->afiliado['as_regimene_id'] === 1 ? 'Contributivo' : 'Subsidiado')
                    : 'NO REGISTRA' }}</td>
            </tr>
            <tr>
                <td colspan="2">Municipio afiliación: {{ $autaprobada->afiliado
                    ? $autaprobada->afiliado->municipio->nombre : 'YOPAL' }}</td>
                <td colspan="2">Depto. Afiliación: {{ $autaprobada->afiliado
                    ? $autaprobada->afiliado->municipio->departamento->nombre : 'CASANARE' }}</td>
                <td colspan="2">Nivel SISBEN: {{ $autaprobada->afiliado
                    ?  $autaprobada->afiliado->nivel_sisben : 'NO REGISTRA' }}</td>
            </tr>
            <tr>
                <td colspan="4">IPS Referencia: {{ $autaprobada->entidad_ips
                    ? $autaprobada->entidad_ips->tercero->nombre_completo : 'NO REGISTRA' }}</td>
                <td colspan="2">Dirección: NO REGISTRA </td>
                {{--                    <td colspan="4">IPS Referencia: {{ $autaprobada->entidad_autorizada --}}
                {{--                    ? $autaprobada->entidad_autorizada->tercero->nombre_completo : 'NO REGISTRA' }}</td>--}}
                {{--                    <td colspan="2">Dirección:{{  $autaprobada->entidad_autorizada->direccion_sede--}}
                {{--                    ? $autaprobada->entidad_autorizada->direccion_sede : 'NO REGISTRA' }} --}}
                {{--                        {{ $autaprobada->entidad_autorizada->municipio --}}
                {{--                        ? $autaprobada->entidad_autorizada->municipio->nombre : 'NO REGISTRA' }} </td>--}}
            </tr>
            <tr>
                <td colspan="3">Diagnóstico: {{ $autaprobada->diagnostico_principal
                    ? $autaprobada->diagnostico_principal->codigo : 'NO REGISTRA'  }}</td>
                <td colspan="2">Nro. Solicitud: {{ $autaprobada->nSolicitud ? $autaprobada->nSolicitud : '' }}</td>
                <td>Alto Costo: {{ $autaprobada->diagnostico_principal
                    ? (($autaprobada->diagnostico_principal->as_tipaltocosto_id === null) ? 'No' : 'Si') : 'No' }}</td>
            </tr>
            </tbody>
        </table>
    </section>
    {{----}}
    <section id="detalles" style="margin-top: 10px">
        <table style="width: 100%">
            <tr>
                {{--                <td colspan="3">Prestador Asignado: <strong> {{$autaprobada->autorizacion->contrato
                                   ? $autaprobada->autorizacion->contrato->contrato->entidad->nombre : 'NO REGISTRA' }}</strong> </td>--}}
                {{--                <td colspan="3">Dirección: <strong> {{$autaprobada->autorizacion->contrato
                                    ? $autaprobada->autorizacion->contrato->contrato->entidad->direccion_sede : 'NO REGISTRA' }}
                                    {{$autaprobada->autorizacion->contrato ? $autaprobada->autorizacion->contrato->contrato->entidad->municipio->nombre : 'NO REGISTRA' }}</strong> </td>--}}

                <td colspan="4">Prestador Asignado: <strong> {{$entidad_autorizada
                ? $entidad_autorizada->nombre : 'NO REGISTRA' }}</strong></td>
                <td colspan="3">Dirección: <strong> {{$entidad_autorizada
                ? $entidad_autorizada->direccion_sede : 'NO REGISTRA' }} {{$entidad_autorizada
                ? $entidad_autorizada->municipio->nombre : 'NO REGISTRA' }}</strong></td>
            </tr>
            <tr>
                <td colspan="7">
{{--                    {{ 'Especialidad: ' . ($autaprobada->servicio_solicitado--}}
{{--                    ? $autaprobada->servicio_solicitado->descrip : 'NO REGISTRA') }}--}}
                </td>
            </tr>
            <tr style="font-weight: bold; text-align: center">
                <td >Nr. Autorización</td>
                <td style="width: 10%">Código</td>
                <td style="width: 30%">Descripción</td>
                <td>Cant.</td>
                <td style="width: 15%">{{$autaprobada->afiliado
                    ?  ($autaprobada->afiliado['as_regimene_id'] === 1 ? 'Cuota M.' : 'Copago')
                    : 'NO REGISTRA' }}</td>
                <td style="width: 15%">Valor EPS</td>
                <td>Observaciones</td>
            </tr>
            <tbody style="font-size: 8px">
            @foreach($autaprobada->detalles as $detalle)
                @if($detalle['pAut'] === $entidad_autorizada->id && (!isset($detalle['negacion'])))
                    <tr>
                        <td>
                            <span>{{  $detalle->id }}</span>
                        </td>
                        <td>
                            <span>{{ $detalle->servicio['homologo'] }}</span>
                        </td>
                        <td style="width: 40%">
                            <span>{{ $detalle->descrip }}</span>
                        </td>
                        <td style="width: 7%; text-align: center">
                            <span>{{ $detalle->cAut }}</span>
                        </td>
                        <td style="text-align: right">
                        <span>{{ $detalle->valor !== null
                        ? '$ '. (number_format((($detalle->copago)), 2, ',', '.')) : "$ 0,00" }}</span>
                        </td>
                        <td style="text-align: right">
                        <span>{{ $detalle->valor !== null
                        ? '$ '. (number_format(($detalle->valor * $detalle->cAut), 2, ',', '.')) : "$ 0,00" }}</span>
                        </td>
                        <td class="parrafo_desc" style="text-align: justify !important;">
                        <span>
                            {{ $detalle->obs ? $detalle->obs : 'NO REGISTRA'  }}
                        </span>
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr style="font-weight: bold;">
                <td colspan="4" style="border-top: double gray 3px">
                    <span>TOTAL</span>
                </td>
                <td style="border-top: double gray 3px">
                    <span style="float: right">
{{--                        {{'$ ' . number_format(($detalle->valor * $detalle->cAut) ---}}
{{--                        ($detalle->valor * $detalle->cAut), 2, ',', '.')}}--}}
                        {{'$ ' . number_format($valor_x_entidad['copago'], 2, ',', '.')}}
                    </span>
                </td>
                <td style="border-top: double gray 3px">
                    <span style="float: right">
                        {{ '$ ' . number_format($valor_x_entidad['valor'], 2, ',', '.')}}
                    </span>
                </td>
                <td style="border-top: double gray 3px"></td>
            </tr>
            {{--            @if($autaprobada->copago > 0 && $autaprobada->afiliado->as_regimen_id == 1)--}}
            {{--            <tr>--}}
            {{--                <td colspan="6" style="font-size: 14px"> <strong>Cuota Moderadora: {{ '$ ' . number_format($autaprobada, 2, ',', '.')}}</strong></td>--}}
            {{--            </tr>--}}
            {{--            @endif--}}
{{--            <tr>--}}
{{--                <td>--}}
{{--                    <span>Observaciones</span>--}}
{{--                </td>--}}
{{--                <td colspan="6">--}}
{{--                    <span>{{ '' }}</span>--}}
{{--                </td>--}}
{{--            </tr>--}}
            </tbody>
        </table>
    </section>
    <section>
        <p style="width: 100%; text-align: center; font-size: 10px; font-style: italic">Los servicios autorizados se
            encuentran sujetos a las normas vigentes y a la verificación por Auditoria Médica</p>
        <div id="autoriza">
            <div>
                <p><strong>{{ strtoupper($autaprobada->usuarioValida['name'])}}</strong></p>
                <p>Autoriza</p>
            </div>
            <div>
                <p><strong>{{ $recibido ?? $autaprobada->afiliado->nombre_completo}}</strong></p>
                <p>Recibe</p>
            </div>
        </div>
    </section>
</main>
<footer>
    <div style="text-align: right; padding-bottom: 10px;">
        <p>Imprime: {{$imprime['name']}}</p>
    </div>
    @if($autaprobada->ind === '2')
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
