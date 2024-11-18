<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>ENCUESTA ESTADO SALUD</title>
        <link rel="stylesheet" href="./css/encuestaPdf.css">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
    </head>
    <body>
        <div class="main">
            <div class="header">
                <div class="item-1">
                    <span style="clear: both">DECLARACIÓN DE SALUD</span>
                    <span style="clear: both; font-size: 2.5mm !important;display: block">ESTADO SALUD - CAPRESOCA E.P.S</span>
                    <span style="clear: both; font-size: 2.5mm !important; display: block">NIT. 891.856.000-7</span>
                </div>
                <div class="item-2">
                    <img src="./images/capresoca.jpg" >
                </div>
            </div>
            <div class="subtitulo-1">
                <h2>ESTADO DE SALUD PARA INICIO DE ATENCIÓN ANTICIPADA</h2>
            </div>
            <div class="info">
                <article class="justify-info">
                    <p>La encuesta pretende conocer mejor la salud de cada uno de nuestros afiliados y poder programar las actividades que contribuyan a mantenerlos sanos. Esta información es absolutamente confidencial y sólo será usada por Capresoca E.P.S para beneficio de sus afiliados.</p>
                </article>
            </div>
            <div class="tablas">
                <div class="subtitulo-2">
                    <h3>Información Básica del Afiliado</h3>
                </div>
                <table class="detail">
                    <tbody>
                    <tr>
                        <td class="label">Identificación: </td>
                        <td class="field" style="text-align: center; width: 20%">{{ $au_encuesta->afiliado->identificacion }}</td>
                        <td class="label">Tipo ID: </td>
                        <td class="field" style="text-align: center">{{ $au_encuesta->afiliado->tipo_identificacion }}</td>
                        <td class="label">Celular: </td>
                        <td class="field" style="text-align: center">{{ $au_encuesta->afiliado->celular ? $au_encuesta->afiliado->celular : 'No Registra' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nombre Completo: </td>
                        <td class="field" colspan="5">{{ $au_encuesta->afiliado->nombre_completo }}</td>
                    </tr>
                    <tr>
                        <td class="label">Fecha Encuesta: </td>
                        <td class="field" style="text-align: center">{{ substr($au_encuesta->fecha, 0, -8) }}</td>
                        <td class="label" >Municipio: </td>
                        <td class="field" style="width: 15%; text-align: center">{{ $au_encuesta->municipio->nombre }}</td>
                        <td class="label" style="width: 13.8% !important;">Departamento: </td>
                        <td class="field" style="text-align: center">{{ $au_encuesta->municipio->departamento->nombre }}</td>
                    </tr>
                    <tr>
                        <td class="label">Lugar: </td>
                        <td class="field" colspan="3">{{ $au_encuesta->lugar ? $au_encuesta->lugar : 'No Registra' }}</td>
                        <td class="label" style="padding: 1px">Régimen: </td>
                        <td class="field" style="text-align: center !important; position: fixed; width: 15%;">{{  $au_encuesta->afiliado->regimen->nombre }}</td>
                    </tr>
                    </tbody>
                </table>
                <p style="text-align: left; padding-top: 5px">
                    PREGUNTAS Y RESPUESTAS
                </p>
                <div class="subtitulo-2">
                    <h3 style="font-weight: lighter">Cuestionario digital.</h3>
                </div>
                {{--Tabla 2--}}
                <table class="list">
                    <tbody>
                    <tr class="head">
                        <td class="celda borde_izquierdo" style="padding-left: 0; padding-right: 1px">No.</td>
                        <td class="center celda" style="width: 88%">PREGUNTA</td>
                        <td class="center celda">Sí</td>
                        <td class="center celda borde_derecho">No</td>
                    </tr>
                    </tbody>
                    <tbody>
                    @foreach($au_encuesta->respuestas as $respuesta)
                        <tr>
                            <td class="celda borde_izquierdo" style="text-align: center; padding-right: 5px; padding-left: 5px; padding-top: 0">
                                {{ $respuesta->pregunta ? $respuesta->pregunta->numero : '##' }}
                                {{--($respuesta->pregunta->numero < 10 ? '0' . $respuesta->pregunta->numero :--}}
                            </td>
                            <td class="celda" style="float: left; padding-left: 1mm; padding-top: 0; position: fixed; text-align: justify">
                                {{$respuesta->pregunta ? ' '. $respuesta->pregunta->pregunta : ' No Registra' }}
                            </td>
                            <td class="center celda">{{$respuesta->respuesta === 1 ? 'X' : '' }}</td>
                            <td class="center celda borde_derecho">{{ $respuesta->respuesta === 0 ? 'X' : '' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 12px; padding-left: 5px; padding-right: 5px">
                    <article style="text-align: justify">
                        <p style="font-weight: lighter">Declaro que la información sobre la condición de salud consignada en este documento es veraz. En consecuencia, cualquier omisión o falsedad que se comprobase al respecto será considerada como un riesgo
                            para el manejo de nuestra salud y acepto las consecuencias jurídicas que se deriven de dicha omisión y autorizo expresamente
                            a Capresoca E.P.S, para solicitar y obtener en cualquier momento y de cualquier profesional de salud y/o centro hospitalario
                            o similar copia de la Historia clínica o información de mi estado de salud así como de los beneficiarios sobre los que
                            ejerzo representación legal.
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </body>
</html>