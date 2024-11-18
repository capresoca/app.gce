<div class="carta">
    <div class="logo-centro">
        <img src="./images/capresoca.jpg">
    </div>

    <p class="parrafo-carta">
        <br>
        <strong>Señor Afiliado,</strong><br><br>
        Con el fin de dar respuesta a la Circular Conjunta Externa 16 de 2013 expedida por el
        Ministerio de Salud y la Superintendencia Nacional de Salud, le solicitamos responder
        las siguientes preguntas de acuerdo con la lectura previa que usted realizó de la carta de
        desempeño y derechos y deberes en la cartilla física o cartilla publicada en la pagina web de CAPRESOCA EPS:
        <br>
        <br>
        NOMBRES Y APELLIDOS: <strong>{{$afitramite->afiliado->nombre_completo}}</strong><br>
        IDENTIFICACIÓN: <strong>{{$afitramite->afiliado->identificacion_completa }}</strong><br>
        EDAD: <strong>{{$afitramite->afiliado->edad_full}}</strong><br>
        DIRECCIÓN: <strong>{{$afitramite->afiliado->direccion}}</strong><br>
        MUNICIPIO: <strong>{{($afitramite->afiliado->municipio ? $afitramite->afiliado->municipio->nombre : 'NR') .' '
                                . ($afitramite->afiliado->municipio->departamento != null ? $afitramite->afiliado->municipio->departamento->nombre : '')}}</strong>
    </p>

    <p class="parrafo-carta">
        Lo invitamos para que a continuación diligencie la siguiente encuesta relacionada con la Carta de
        Derechos y Deberes del Afiliado y del Paciente y la Carta de Desempeño de CAPRESOCA EPS.

    </p>
    <div class="parrafo-carta">
        <table>
            <tr>
                <td>
                    <li>
                        ¿Previa al diligenciamiento del formulario de afiliación, la EPS le hizo entrega de la
                        Carta de Derechos y Deberes del Afiliado y del Paciente?
                    </li>
                </td>
                <td>
                    <div class="sino">SI <div class="check"></div></div>

                    <div class="sino">NO <div class="check"></div></div>
                </td>
            </tr>
            <tr>
                <td>
                    <li>¿Previa al diligenciamiento del formulario de afiliación, la EPS le hizo entrega de la
                        Carta de Desempeño donde se presenta de manera clara su puesto en el ranking?</li>
                </td>
                <td>
                    <div class="sino">SI <div class="check"></div></div>

                    <div class="sino">NO <div class="check"></div></div>
                </td>
            </tr>
            <tr>
                <td>
                    <li>¿Leyó el contenido de la Carta de Desempeño de la EPS?</li>
                </td>
                <td>
                    <div class="sino">SI <div class="check"></div></div>

                    <div class="sino">NO <div class="check"></div></div>
                </td>
            </tr>
            <tr>
                <td>
                    <li>¿Si tuvo alguna duda sobre el contenido de la información fue asesorado adecuadamente por la EPS?</li>
                </td>
                <td>
                    <div class="sino">SI <div class="check"></div></div>

                    <div class="sino">NO <div class="check"></div></div>
                </td>
            </tr>
            <tr>
                <td>
                    <li>¿Pertenece a algún grupo étnico?</li>
                </td>
            </tr>
            <tr>
                <td>
                    <div><div class="check"></div> Afrocolombiano </div>

                    <div><div class="check"></div> Gitanos</div>

                    <div><div class="check"></div> Indígenas</div>

                </td>
            </tr>
        </table>
    </div>

    <div class="parrafo-carta">
        <table class="firma">
            <tr>
                <td width="30mm"><strong>Firma</strong></td>
                <td width="60mm"><div class="border-bottom"></div></td>
                <td style="text-align: right" width="40mm"><strong>Huella</strong></td>
                <td width="20mm"><div class="huella"></div></td>
            </tr>
        </table>



    </div>


</div>


@foreach($afitramite->beneficiarios as $beneficiario)
    <div class="saltopagina"></div>
    <div class="carta">
        <div class="logo-centro">
            <img src="./images/capresoca.jpg">
        </div>

        <p class="parrafo-carta">
            <br>
            <strong>Señor Afiliado,</strong><br><br>
            Con el fin de dar respuesta a la Circular Conjunta Externa 16 de 2013 expedida por el
            Ministerio de Salud y la Superintendencia Nacional de Salud, le solicitamos responder
            las siguientes preguntas de acuerdo con la lectura previa que usted realizó de la carta de
            desempeño y derechos y deberes en la cartilla física o cartilla publicada en la pagina web de CAPRESOCA EPS:
            <br>
            <br>
            NOMBRES Y APELLIDOS: <strong>{{$beneficiario->nombre_completo}}</strong><br>
            IDENTIFICACIÓN: <strong>{{$beneficiario->identificacion_completa}}</strong><br>
            EDAD: <strong>{{$beneficiario->edad_full}}</strong><br>
            DIRECCIÓN: <strong>{{$beneficiario->direccion}}</strong><br>
            MUNICIPIO: <strong>{!!  (isset($beneficiario->municipio->nombre) ? $beneficiario->municipio->nombre : 'NR') .' '
                                . (isset($beneficiario->municipio->departamento) ? $beneficiario->municipio->departamento->nombre : '') !!}</strong>
        </p>

        <p class="parrafo-carta">
            Lo invitamos para que a continuación diligencie la siguiente encuesta relacionada con la Carta de
            Derechos y Deberes del Afiliado y del Paciente y la Carta de Desempeño de CAPRESOCA EPS.

        </p>
        <div class="parrafo-carta">
            <table>
                <tr>
                    <td>
                        <li>
                            ¿Previa al diligenciamiento del formulario de afiliación, la EPS le hizo entrega de la
                            Carta de Derechos y Deberes del Afiliado y del Paciente?
                        </li>
                    </td>
                    <td>
                        <div class="sino">SI <div class="check"></div></div>

                        <div class="sino">NO <div class="check"></div></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <li>¿Previa al diligenciamiento del formulario de afiliación, la EPS le hizo entrega de la
                            Carta de Desempeño donde se presenta de manera clara su puesto en el ranking?</li>
                    </td>
                    <td>
                        <div class="sino">SI <div class="check"></div></div>

                        <div class="sino">NO <div class="check"></div></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <li>¿Leyó el contenido de la Carta de Desempeño de la EPS?</li>
                    </td>
                    <td>
                        <div class="sino">SI <div class="check"></div></div>

                        <div class="sino">NO <div class="check"></div></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <li>¿Si tuvo alguna duda sobre el contenido de la información fue asesorado adecuadamente por la EPS?</li>
                    </td>
                    <td>
                        <div class="sino">SI <div class="check"></div></div>

                        <div class="sino">NO <div class="check"></div></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <li>¿Pertenece a algún grupo étnico?</li>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div><div class="check"></div> Afrocolombiano </div>

                        <div><div class="check"></div> Gitanos</div>

                        <div><div class="check"></div> Indígenas</div>

                    </td>
                </tr>
            </table>
        </div>

        <div class="parrafo-carta">
            <table class="firma">
                <tr>
                    <td width="30mm"><strong>Firma</strong></td>
                    <td width="60mm"><div class="border-bottom"></div></td>
                    <td style="text-align: right" width="40mm"><strong>Huella</strong></td>
                    <td width="20mm"><div class="huella"></div></td>
                </tr>
            </table>



        </div>


    </div>
@endforeach
