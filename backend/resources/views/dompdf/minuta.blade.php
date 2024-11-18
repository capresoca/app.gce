<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ESTUDIO PREVIO SERVICIOS DE SALUD</title>
    <link rel="stylesheet" href="./css/minuta.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<header>
    <table class="table-collapse" style="width: 100%">
        <tbody>
        <tr>
            <td width="25%">
                <div class="logo">
                    <img src="./images/capresoca.jpg">
                </div>
                <div style="display: block; font-weight: bolder !important;
                            font-size: 1.2em">NIT. 891.856.000-7</div>
            </td>
            <td>
                <div class="texto-cabecera"  style=" margin-top: 6mm">
                    <h2 style="padding: 0; margin-bottom: 0; margin-top: 0">
                        MINUTA DE {{$minuta->tipo_contrato->descripcion}}
                    </h2>
                    <span style="display: block; font-weight: lighter; font-size: 1.2em">
                        {{$minuta->tipo_contrato->codigo_documento}}
                    </span>
                    <span style="display: block; font-weight: lighter; font-size: 1.2em">{{$minuta->tipo_contrato->fecha_version}}</span>
                    <span style="display: block; font-weight: lighter; font-size: 1.2em">{{$minuta->tipo_contrato->version}}</span>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</header>
<footer class="footer_2">
    <table class="table-collapse">
        <tbody>
        <tr>
            <td class="footer_image">
                <img src="./images/logo_super.png" alt="logo_super">
            </td>
            <td style="text-align: right !important;">
                <div style="padding-right: 25px !important;">
                    <span style="display: block">Calle 7 No. 19 - 34. Teléfonos: (8) 635 8162 - 635 8163</span>
                    <span style="display: block">635 8232 Ext. 135 Email. <u>capresocaeps@capresoca.com.co</u></span>
                    <span>Yopal Casanare</span>
                </div>
            </td>
            <td class="text_center" width="12%">
                {{ '      ' }}
                <script type="text/php" class="pages">
                if (isset($pdf)) {
                $font = $fontMetrics->getFont("Helvetica", "lighter");
                $pdf->page_text(505, 745, "{PAGE_NUM} de {PAGE_COUNT}", $font, 8, array(0, 0, 0));
                }
                </script>
            </td>
        </tr>
        </tbody>
    </table>
</footer>
@if($minuta->estado === 'Registrado')
    <div class="watermark">BORRADOR</div>
@endif
<main>
    {!! $minuta->minuta !!}
</main>

</body>
</html>
