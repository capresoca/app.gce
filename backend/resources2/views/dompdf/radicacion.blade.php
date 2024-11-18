<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ "CUENTA MÉDICA RADICADA # $radicado->conseradicado" }}</title>
    <style>
        body {
            font-size: 14px;
            width: 100%;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
        p, h2, h4, div, header, main, footer{
            padding: 0;
            margin: 0;
        }
        header{
            width: 100%;
        }
        header .logos{
            display: inline-block;
            width: 25%;
        }

        header .logos img {
            width: 120px;
        }
        header .titulo{
            display: inline-block;
            width: 55%;
        }
        header .legal {
            display: inline-block;
            width: 20%;
            font-size: 10px;
            text-align: right;
        }

        #consecutivo {
            width: 100%;
            text-align: right;
            font-size: 12px;
            margin-top: -20px;
        }
        .cabecera-detalle {
            width: 100%;
         }
        .cabecera-detalle p {
            display: inline-block;
            width: 33%;
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
            padding: 2px;
            font-size: 9px;
        }
        .align-left{
            text-align: left;
        }
        .align-rigth{
            text-align: right;
        }
        main, table, section {
            width: 100%;
        }
        /*** OPTIONS CLASS ***/
        .table-collapse {
            border-collapse: collapse;
        }

        .celda {
            border: .2mm solid #000 !important;
        }

        .size_font {
            font-size: 2.7mm !important;
        }

        .color_grey {
            background-color: rgba(88, 88, 88, 0.2);
        }

        .border_bottom {
            border-bottom: 0.2mm solid #000 !important;
        }

        .border_top {
            border-top: 0.2mm solid #000 !important;
        }

        .borde_derecho {
            border-right: 0.2mm solid #000 !important;
        }

        .borde_izquierdo {
            border-left: 0.2mm solid #000 !important;
        }

        .none_border_bottom {
            border-bottom: none !important;
        }
        .none_border_top {
            border-top: none !important;
        }
        .none_borde_izquierdo {
            border-left: none !important;
        }

        .none_borde_derecho {
            border-right: none !important;
        }
        .text_center {
            text-align: center !important;
        }

        .text_right {
            text-align: right !important;
        }

        .text_left {
            text-align: left !important;
        }

        .text_justify {
            text-align: justify !important;
        }

        .negrilla {
            font-weight: bolder !important;
        }

        .negrilla-2 {
            font-weight: bold !important;
        }

        div.saltopagina {
            display:block;
            page-break-before:always;
        }
        /*******************/
    </style>
</head>
<body>
    <header>
        <div class="logos">
            <img src="./images/capresoca.jpg" >
        </div>
        <div class="titulo">
            <h3>RADICACIÓN DE CUENTAS MÉDICAS</h3>
        </div>
        <div class="legal">
            <p>CODIGO  RE-ASS-08</p>
            <p>CTRD  200.01.11</p>
            <p>VIGENCIA </p>
            <p>
                <script type="text/php">
                    if (isset($pdf)) {
                    $font = $fontMetrics->getFont("Helvetica", "lighter", "Arial");
                    $pdf->page_text(540, 15, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
                    }
                </script>
            </p>
        </div>
    </header>
    <main>
        <section>
            <div id="consecutivo">
                <p>RADICADO No.</p>
                <h2 style="margin-right: 15px">{{$radicado->conseradicado}}</h2>
                <p>{{ strftime('%d/%m/%Y %H:%m', strtotime($radicado->fecha)) }}</p>
            </div>
        </section>
        <section>
            <p><strong>Entidad:</strong>
                {{ $radicado->nombre_entidad .' ('.$radicado->identidad.')'}}</p>
            <br>
            <div class="cabecera-detalle" style="margin-bottom: 8px">
                <p style="width: 30%"><strong>Radicado Entidad:</strong> {{$radicado->radicado_entidadrad}}</p>
                <p style="width: 38.5%"><strong>Fecha Radicado:</strong> {{ strftime('%d/%m/%Y', strtotime($radicado->fecha_radicado))  }}</p>
                <p style="width: 30%; text-align: right !important;"><strong>Contrato:</strong>{{ $radicado->contratorad }}</p>
            </div>
            <div class="cabecera-detalle" style="margin-bottom: 8px">
                <p style="width: 30%"><strong>Cantidad Facturas:</strong> {{count($facturas)}}</p>
                <p style="width: 38.5%"><strong>Ser. Prestado:</strong>
                    {{ strftime('%d/%m/%Y', strtotime($radicado->periodo_inicio)).' - '.strftime('%d/%m/%Y', strtotime($radicado->periodo_fin)) }}</p>
                <p style="width: 30%; text-align: right !important;"><strong>RIPS:</strong>
                    {{ strftime('%d/%m/%Y', strtotime($radicado->periodo_inicio)).' - '.strftime('%d/%m/%Y', strtotime($radicado->periodo_fin)) }}</p>
            </div>
            <div class="cabecera-detalle">
                <p><strong>Régimen:</strong>  {{$radicado->plan_beneficiorad}}</p>
            </div>

        </section>
        <br>
        <br>
        <table>
            <tr>
                <th style="text-align: center;">No</th>
                <th>FACTURA</th>
                <th>FECHA</th>
                <th style="width: 35%">AFILIADO</th>
                <th class="align-rigth">SUBTOTAL</th>
                <th class="align-rigth">COPAGOS</th>
                <th class="align-rigth">DESCUENTOS</th>
                <th class="align-rigth">TOTAL</th>
            </tr>
            {{$i = 0}}
            @foreach($facturas as $factura)
            <tr>
                <td style="text-align: center">{{++$i}}</td>
                <td>{{$factura->no_factura}}</td>
                <td>{{strftime('%d/%m/%Y', strtotime($factura->fecha))}}</td>
                <td>{{$factura->afiliado}}</td>
                <td class="align-rigth">{{'$'.number_format($factura->valor_compartido + $factura->valor_comision + $factura->valor_descuento + $factura->valor_neto,2,',','.')}}</td>
                <td class="align-rigth">{{'$'.number_format($factura->valor_compartido,2,',','.')}}</td>
                <td class="align-rigth">{{'$'.number_format($factura->valor_descuento,2,',','.')}}</td>
                <td class="align-rigth">{{ '$'.number_format($factura->valor_neto,2,',','.')}}</td>
            </tr>
            @endforeach
            <tr>
                <td><strong>TOTALES</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="align-rigth"><strong>{{'$'.number_format(($item->facturas->sum('valor_neto')+$item->facturas->sum('valor_compartido')+ $item->facturas->sum('valor_comision')+ $item->facturas->sum('valor_descuentos')),2,',','.')}}</strong></td>
                <td class="align-rigth"><strong>{{'$'.number_format($item->facturas->sum('valor_compartido'),2,',','.')}}</strong></td>
                <td class="align-rigth"><strong>{{'$'.number_format($item->facturas->sum('valor_descuentos'),2,',','.')}}</strong></td>
                <td class="align-rigth"><strong>{{'$'.number_format($item->facturas->sum('valor_neto'),2,',','.')}}</strong></td>
            </tr>
        </table>
        <br>
        <br>
        <br>
        <br>
        <table class="table-collapse">
            <tr>
                <td class="celda size_font negrilla" colspan="1" width="25%">Número de radicado</td>
                <td class="celda size_font" colspan="3">{{$radicado->conseradicado}}</td>
            </tr>
            @foreach($estados as $estado)
                <tr>
                    <td class="celda size_font negrilla"  width="25%">Fecha {{$estado->estado}}</td>
                    <td class="celda size_font">{{strftime('%d-%m-%Y', strtotime($estado->fecha_estado))}}</td>
                    <td class="celda size_font negrilla"  width="20%">Nombre Responsable:</td>
                    <td class="celda size_font">{{strtoupper($estado->usuario)}}</td>
                </tr>
            @endforeach
            <tr>
                <td class="celda size_font negrilla" colspan="1" width="25%">Tiempo transcurrido en días calendario</td>
                <td class="celda size_font" colspan="3"></td>
            </tr>
            <tr>
                <td class="celda size_font negrilla" colspan="1" width="25%">Observaciones</td>
                <td class="celda size_font" colspan="3"></td>
            </tr>
        </table>
        <div style="width: 100%; text-align: center; margin-top: 150px;">
            <h4 style="width: 50%; margin: 0 25% 0 25%; border-top: solid gray 1px;">{{$radicado->usuario}}</h4>
            <p>Recepción Cuentas Medicas</p>
        </div>
        <div style="text-align: right; font-size: 9px">
            <p>{{ \Illuminate\Support\Facades\Auth::user() ? "Imprime: ". \Illuminate\Support\Facades\Auth::user()->name : ""}}</p>
        </div>
    </main>
        <footer>

        </footer>
</body>
</html>
