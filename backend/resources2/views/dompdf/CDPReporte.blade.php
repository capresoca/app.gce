<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CDP</title>
    <link rel="stylesheet" href="./css/reportesPresupuestalesPdf.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            font-size: 3.5mm !important;
        }
    </style>
</head>
<body>
<main>
    <div class="header">
        <div class="logo">
            <img src="./images/capresoca.jpg" >
        </div>
        <div style="display: block; font-weight: bolder !important;
                        font-size: 1.2em">NIT. 891.856.000-7</div>
        <br>
        <br>
        <div style="padding-top: 0 !important;">
            <h3 style="padding: 0 !important; margin-top: 0 !important;">{{ 'CERTIFICADO DE DISPONIBILIDAD PRESUPUESTAL No.' . $prCdp->consecutivo }}</h3>
        </div>
        <br>
        {{--<div style="display: block; font-weight: bolder !important;--}}
                        {{--font-size: 1.2em">{{ $prCdp->presupuesto_inicial_gasto->entidadResolucion->tercero->nombre_completo }}</div>--}}
        {{--<div style="display: block; font-weight: bolder !important;--}}
                        {{--font-size: 1.2em">NIT. 891.856.000-7</div>--}}
    </div>
    <div style="margin-top: 20mm !important;">
        <table class="table-collapse">
            <thead>
            <tr>
                <th  class="basica-text" width="15%">Fecha: </th>
                <th class="basica-text">{{ strtoupper( \Jenssegers\Date\Date::parse($prCdp->fecha)->format('F d \d\e\l Y')) }}</th>
            </tr>
            <tr>
                <th class="basica-text">Dependencia: </th>
                <th class="basica-text">{{ $prCdp->dependencia->codigo_nombre }}</th>
            </tr>
            </thead>
        </table>
        <br>
        <div class="basica-text" style="font-size: 1.2em">El suscrito Jefe de la División de Presupuesto.</div>
    </div>
    <div class="sub-title" style="margin-bottom: 30px">
        <h3 style="padding: 0">
            CERTIFICA</h3>
    </div>
    <div class="body_2" style="margin-bottom: 20px">
         <span class="text_1">
             Que dentro del Presupuesto General de Rentas y Gastos de {{ $prCdp->presupuesto_inicial_gasto->entidadResolucion->tercero->nombre_completo }}
             del presente Período Fiscal, existe saldo disponible y no comprometido, para amparar el compromiso que se pretende asumir así:
        </span>
    </div>
    <div class="sub-title" style="margin-bottom: 30px">
        <h3 style="padding: 0">
            IMPUTACIÓN PRESUPUESTAL</h3>
    </div>
    <div class="tabla-1">
        <table class="table-collapse">
            <thead>
            <tr>
                <th class="celda text_center color_grey" width="18%">CÓDIGO</th>
                <th class="celda text_center color_grey">DESCRIPCIÓN</th>
                <th class="celda text_center color_grey" width="20%">VALOR</th>
                <th class="celda text_center color_grey" width="20%">SALDO</th>
            </tr>
            </thead>
            <tbody>
            @foreach($prCdp->detalles as $detalle)
            <tr>
                <td class="celda basica-text">{{ $detalle->rubro->codigo }}</td>
                <td class="celda basica-text">{{ $detalle->rubro->nombre }}</td>
                <td class="celda basica-text text_right">
                    {{ ($detalle->valor !== null || $detalle->valor !== 0)
                      ? '$'. number_format($detalle->valor, 2, ',', '.')
                      : "$ 0,00"}}</td>
                <td class="celda basica-text text_right">
                    {{
                    ($detalle->detStrgasto->valor_disponible !== null || $detalle->detStrgasto->valor_disponible !== 0)
                      ? '$'. number_format($detalle->detStrgasto->valor_disponible, 2, ',', '.')
                      : "$ 0,00"}}
                </td>

            </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text_right basica-text" style="padding-right: 2mm">Total Certificado </td>
                <td class="celda basica-text text_right">
                    {{
                    ($prCdp->valor_cdp !== null || $prCdp->valor_cdp !== 0)
                      ? '$'. number_format($prCdp->valor_cdp, 2, ',', '.')
                      : "$ 0,00"}}
                </td>
                <td class="basica-text"></td>
            </tr>
            </tbody>
        </table>
        <br>
        <br>
        <table class="table-collapse">
            <tr>
                <td class="basica-text text_left" width="18%">Objeto del Gasto:</td>
                <td class="basica-text text_left text_justify" colspan="3">{{ $prCdp->objecto ? $prCdp->objecto : 'Información no registrada.' }}</td>
            </tr>
        </table>
    </div>
    <div class="body_2" style="margin-top: 50mm">
         <span class="text_1">
             Vigencia de la presente Disponibilidad,{{' '. $prCdp->presupuesto_inicial_gasto->entidadResolucion->tercero->municipio->nombre_departamento
             . ', ' . $prCdp->presupuesto_inicial_gasto->entidadResolucion->tercero->municipio->nombre . ', ' . (strtoupper(\Jenssegers\Date\Date::parse($prCdp->fecha_vence)->format('F d \d\e\l \aÑ\o Y'))) }}
        </span>
    </div>
    <div style="width: 100%; text-align: center; margin-top: 50px;">
        <h3 style="width: 50%; margin: 0 25% 0 25%; border-top: solid gray 1px;">TECNICO ADTVO DE PRESUPUESTO (e)</h3>
        {{--<p>Recepción Cuentas Medicas</p>--}}
    </div>
</main>
</body>
</html>