<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comdiario</title>
    <link rel="stylesheet" href="./css/comdiarioPdf.css">

</head>
<body>
<div class="main">
    <div class="header">
        <div class="logo">
            <img src="./images/capresoca.jpg" >
        </div>
        <div class="actual_fecha">
            <p>Fecha Actual: {{Date::today()->format('l, j F Y')}}</p>
            <script type="text/php" class="pages">
              if (isset($pdf)) {
                $font = $fontMetrics->getFont("Helvetica", "lighter");
                $pdf->page_text(505, 105, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
              }
            </script>
            {{--<span class="pages">Página 1 de 1</span>--}}
        </div>
    </div>
    <div class="title">
        <div>COMPROBANTE CONTABLE</div>
        <div>Consecutivo N° {{ $comdiario->consecutivo }}</div>
    </div>
    <div class="tablas">
        <table class="detail">
            <tbody>
            <tr>
                <td class="label">CÓDIGO: </td>
                <td class="field">{{ $comdiario->tipo ? $comdiario->tipo->codigo : '' }}</td>
                <td class="label">ESTADO: </td>
                <td class="field">{{ $comdiario->estado }}</td>
            </tr>
            <tr>
                <td class="label">COMPROBANTE: </td>
                <td class="field">{{ $comdiario->tipo ? $comdiario->tipo->nombre : '' }}</td>
                <td class="label">FECHA: </td>
                <td class="field">{{ $comdiario->fecha ? $comdiario->fecha : '' }}</td>
            </tr>
            <tr>
                <td class="label" style="vertical-align: top">DETALLE: </td>
                <td class="field" style="text-align: justify !important;">{{ $comdiario->detalle ? $comdiario->detalle : '' }}</td>
                <td class="label" style="vertical-align: top">DOCUMENTO: </td>
                <td class="field" style="vertical-align: top; text-align: justify !important;">000000000025572</td>
            </tr>
            </tbody>
        </table>
        <p>DETALLE DEL MOVIMIENTO</p>
        {{--Tabla 2--}}
        <table class="list">
            <tbody>
            <tr class="head">
                <td class="center" style="width: 10%; font-weight: bold">CÓDIGO</td>
                <td class="center" style="width: 50%; font-weight: bold">DETALLE</td>
                <td class="center valor" style="width: 25%; font-weight: 400">VALOR DÉBITO</td>
                <td class="center valor" style="width: 25%; font-weight: 400">VALOR CRÉDITO</td>
            </tr>
            @foreach($comdiario->detalles as $detalle)
                <tr class="list_row">
                    <td colspan="4">

                    </td>
                </tr>
                <tr class="list_row">
                    <td class="codigo">
                       <span>{{ $detalle->niif ? $detalle->niif->codigo : '' }}</span>
                    </td>
                    <td class="descripcion">
                        <span style="float: left !important; width: 100%">{{ $detalle->niif ? $detalle->niif->nombre : '' }}</span>
                        <div style="padding: 1mm 0; width: 100%">
                            <p> {{ $detalle->observacion != null ? $detalle->observacion : '' }}</p>
                            <p> {{ $detalle->niif ? ($detalle->niif->maneja_tercero != 0 ? $detalle->tercero['nombre_completo'] : '') : '' }}</p>
                            <p>{{ $detalle->niif ? ($detalle->niif->maneja_ccosto != 0 || $detalle->niif->maneja_ccosto != null ? ($detalle->cencosto ? $detalle->cencosto->nombre : '') : '') : '' }}</p>
                        </div>
                    </td>
                    <td class="derecha">
                        <span>
                            {{($detalle->debito !== null || $detalle->debito !== 0) ? '$ '. number_format($detalle->debito, 2, ',', '.') : "$ 0,00"}}
                        </span>
                    </td>
                    <td class="derecha">
                        <span>
                            {{($detalle->credito !== null || $detalle->debito !== 0 ) ? '$ '. number_format($detalle->credito, 2, ',', '.') : "$ 0,00"}}
                        </span>
                    </td>
                </tr>
            @endforeach
            <tr class="list_row">
                <td class="fijar" colspan="4" style="background: #000; padding: 0 0; margin-bottom: 0; border-bottom: 0 "></td>
                {{--<td  colspan="4" style="background: #000; padding: 0 0; margin-bottom: 0; border-bottom: 0 "></td>--}}
                {{--<td colspan="4" style="background: #000; padding: 0 0; margin-bottom: 0; border-bottom: 0 "></td>--}}
            </tr>
            <tr class="list_row">
                <td class="total" colspan="2">
                    <span>TOTAL NÚNMERO DE COMPROBANTE : {{$comdiario->consecutivo }}</span>
                </td>
                <td class="val_tot totUno">
                    <span>{{($comdiario->valor !== 0 || $comdiario->valor !== null) ? '$ '. number_format($comdiario->valor, 2, ',', '.') : "$ 0,00"}}</span>
                </td>
                <td class="val_tot totUno">
                    <span>{{($comdiario->valor !== 0 || $comdiario->valor !== null) ? '$ '. number_format($comdiario->valor, 2, ',', '.') : "$ 0,00"}}</span>
                </td>
            </tr>
            <tr class="list_row">
                <td class="total" colspan="2">
                    <span>TOTAL DEL COMPROBANTE : {{$comdiario->tipo ? $comdiario->tipo->codigo : ''}}</span>
                </td>
                <td class="val_tot">
                    <span>{{($comdiario->valor !== 0 || $comdiario->valor !== null) ? '$ '. number_format($comdiario->valor, 2, ',', '.') : "$ 0,00"}}</span>
                </td>
                <td class="val_tot">
                    <span>{{($comdiario->valor !== 0 || $comdiario->valor !== null) ? '$ '. number_format($comdiario->valor, 2, ',', '.') : "$ 0,00"}}</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="footer">
        <div>
            <span>Nombre reporte : CTRPComprobanteContable</span>
            <span>{{ $comdiario->usuario ? "Usuario : ". $comdiario->usuario->identification : ""}}</span>
            {{--<span>{{ 'Usuario : '. 47435472}}</span>--}}
        </div>
    </div>
</div>


</body>
</html>
