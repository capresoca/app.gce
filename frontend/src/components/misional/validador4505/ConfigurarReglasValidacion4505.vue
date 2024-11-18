<template>
  <div class="container">
    <v-container class="pl-0 pr-0">
      <v-layout row wrap>
        <v-flex xs12>
          <v-toolbar class="elevation-1 toolbar--dense">
            <v-icon>build</v-icon>
            <v-toolbar-title class="text-uppercase">Configuración de Reglas de Validación </v-toolbar-title>
          </v-toolbar>
          <!--<h3 class="display-1"></h3>-->
        </v-flex>
      </v-layout>
    </v-container >
    <v-container>
      <v-card flat icon>
        <v-card-text>
          <v-container>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.existePrestadorServicioSaludInicioSesion"
                  label="Codigo del prestador de servicios de salud es igual al codigo del usuario que inicia sesion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.existePrestadorServicioSaludInicioSesion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.longitudIdentificacionAfiliado"
                  label="Longitud de la identificacion con el tipo de identificacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.longitudIdentificacionAfiliado)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.primerApellidoAfiliado"
                  label="El primer apellido del afiliado sea igual al primer apellido en BDUA"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.primerApellidoAfiliado)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.segundoApellidoAfiliado"
                  label="El segundo apellido del afiliado sea igual al segundo apellido en BDUA"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.segundoApellidoAfiliado)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.primerNombreAfiliado"
                  label="El primer nombre del afiliado sea igual al primer nombre en BDUA"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.primerNombreAfiliado)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.segundoNombreAfiliado"
                  label="El segundo nombre del afiliado sea igual al segundo nombre en BDUA"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.segundoNombreAfiliado)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaNacimientoAfiliado"
                  label="La fecha de nacimiento sea igual a la fecha de nacimiento en BDUA"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaNacimientoAfiliado)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.generoAfiliado"
                  label="El genero sea igual a el genero en BDUA"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.generoAfiliado)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.pertenenciaEtnica"
                  label="El dato exista entre el rango de valores permitidos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.pertenenciaEtnica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.ocupacion"
                  label="El codigo de la ocupacion exista"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.ocupacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.nivelEducativo"
                  label="El nivel educativo exista entre el rango de valores permitidos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.nivelEducativo)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadGestacion"
                  label="Curce de dato de gestacion y la edad del afiliado"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.gestacionGenero"
                  label="Cruce del dato de la gestacion contra el genero del afiliado"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.gestacionGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.sifilisGestacionalCongenitaEdad"
                  label="Cruce de sifilis gestacional o congenita con la edad del afiliado"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.sifilisGestacionalCongenitaEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.sifilisGestacionalCongenitaEdadGeneroGestacion"
                  label="Cruce de sifilis gestacional o congenita con la edad del afiliado y genero del afiliado"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.sifilisGestacionalCongenitaEdadGeneroGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.sifilisGestacionalCongenitaGenero"
                  label="Cruce de sifilis gestacional o congenita con el genero del afiliado"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.sifilisGestacionalCongenitaGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.sifilisGestacionalCongenitaGestacion"
                  label="Cruce de sifilis gestacional o congenita con la gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.sifilisGestacionalCongenitaGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.sifilisGestacionalCongenitaInfeccionTransmisionSexual"
                  label="Cruce de sifilis gestacional o congenita con Infecciones de Transmision Sexual "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.sifilisGestacionalCongenitaInfeccionTransmisionSexual)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hipertensionInducidaGestacionEdadSi"
                  label="Cuando el dato es '1'  la edad debe ser mayor a 10 años y menor a 60"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hipertensionInducidaGestacionEdadSi)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hipertensionInducidaGestacionGenero"
                  label="Cruce de hipertension inducida por la gestacion con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hipertensionInducidaGestacionGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hipertensionInducidaGestacionGestacion"
                  label="Cruce de hipertension inducida por la gestacion con la gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hipertensionInducidaGestacionGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hipotiroidismoCongenitoEdadSi"
                  label="Cuando el dato es '1'  la edad debe ser mayor a 10 años y menor a 60"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hipotiroidismoCongenitoEdadSi)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hipotiroidismoCongenitoResultadoTSH"
                  label="Cruce hipotiroidismo Congenito con el resultado TSH Neonatal "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hipotiroidismoCongenitoResultadoTSH)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hipotiroidismoCongenitoTratamiento"
                  label="Cruce hipotiroidismo Congenito con el Tratamiento Para Hipotiroidismo Congenito"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hipotiroidismoCongenitoTratamiento)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.sintomaticoRespiratorioBaciloscopiaDiagnostico"
                  label="Cruce dato Sintomatico Respiratorio con Baciloscopia de Diagnostico"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.sintomaticoRespiratorioBaciloscopiaDiagnostico)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.lepraTratamientoLepra"
                  label="Cruce dato lepra con tratamiento de lepra"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.lepraTratamientoLepra)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.obsidadDesnutricionProteicoEdadSi"
                  label="Cuando el dato es '1' cruce con la edad mayor o igual a 18 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.obsidadDesnutricionProteicoEdadSi)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.obsidadDesnutricionProteicoFechaDiagnostico"
                  label="Cruce de obesidad Desnutricion Proteico Calorica con la fecha del diagnostico desnutricion proteico calorica"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.obsidadDesnutricionProteicoFechaDiagnostico)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.obsidadDesnutricionProteicoPeso"
                  label="Cruce de obesidad Desnutricion Proteico Calorica con el peso"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.obsidadDesnutricionProteicoPeso)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.obsidadDesnutricionProteicoTall"
                  label="Cruce de obesidad Desnutricion Proteico Calorica con la talla"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.obsidadDesnutricionProteicoTall)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.victimaMaltratoEdadMenorSiMenor"
                  label="Si el dato es '2' cruce con la edad sea menor a 18 años y 3 meses"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.victimaMaltratoEdadMenorSiMenor)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.victimaMaltratoMujerVictima"
                  label="Cruce victima de maltrato con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.victimaMaltratoMujerVictima)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.victimaMaltratoFechaConsultaMujerOMenorVictima"
                  label="Cruce victima de matrato con fecha consulta Mujero o Menor victima de maltrato"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.victimaMaltratoFechaConsultaMujerOMenorVictima)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.victimaMaltratoEdadGeneral"
                  label="Cruce victima de maltrato con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.victimaMaltratoEdadGeneral)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.victimaViolenciaSexualFechaConsulta"
                  label="Cruce victima de Violencia sexual con la fecha de consulta"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.victimaViolenciaSexualFechaConsulta)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.enfermedadMentalDiagnostico"
                  label="Cruce de enfermedad mental con Paciente con diagnostico de: ansiedad, Depresion...."
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.enfermedadMentalDiagnostico)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.canverCervixGenero"
                  label="Cruce cancer de cervix con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.canverCervixGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.pesoKilogramos"
                  label="Dato de peso exista entre los limites permitidos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.pesoKilogramos)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.pesoFechaConsulta"
                  label="Cruce del peso con la fecha del peso"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.pesoFechaConsulta)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tallaCentimetros"
                  label="Dato de la talla exista entre los limites permitidos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tallaCentimetros)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tallaFechaConsulta"
                  label="Cruce de la talla con la fecha de la talla"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tallaFechaConsulta)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaProbablePartoGenero"
                  label="Cruce fecha probable de parto con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaProbablePartoGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaProbablePartoGestacion"
                  label="Cruce fecha probable de parto con la gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaProbablePartoGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaProbablePartoEdad"
                  label="Cruce fecha probable de parto con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaProbablePartoEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadGestacionalNacerDiferenteCero"
                  label="Cuando el dato es diferente de '0' Cruce edad gestacional al nacer con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadGestacionalNacerDiferenteCero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadGestacionalNacerEdad"
                  label="Cruce edad gestacional al nacer con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadGestacionalNacerEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadGestacionalNacer43Semanas"
                  label="Cruce edad gestacion al nacer con la edad menor de 43 de semanas"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadGestacionalNacer43Semanas)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.bcgEdadDiferenteNoAplica"
                  label="Cuando es diferente a No aplica Cruce BCG con la edad "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.bcgEdadDiferenteNoAplica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.bcgMenor6Anios"
                  label="Cruce BCG con la edad menor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.bcgMenor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.bcgMayor6Anios"
                  label="Cruce BCG con la edad mayor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.bcgMayor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hepatitisBMenorNoAplica"
                  label="Cruce Hepatitis B menor de 1 año diferente con la edad cuando es No Aplica"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hepatitisBMenorNoAplica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hepatitisBMenor1AnioMenor6Anios"
                  label="Cruce Hepatitis B menor de 1 año con la edad menor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hepatitisBMenor1AnioMenor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hepatitisBMenor1AnioMayor6Anios"
                  label="Cruce Hepatitis B menor de 1 año con la edad mayor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hepatitisBMenor1AnioMayor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.pentavalenteMenor1AnioNoAplica"
                  label="Cuando el dato es igual a No Aplica. Cruce pentavalente con la edad menor de 1 año"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.pentavalenteMenor1AnioNoAplica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.pentavalenteMenor1AnioMenor6Anios"
                  label="Cruce pentavalente con la edad menor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.pentavalenteMenor1AnioMenor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.pentavalenteMenor1AnioMayor6Anios"
                  label="Cruce pentavalente con la edad mayor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.pentavalenteMenor1AnioMayor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.pentavalenteMenor4Meses"
                  label="Cruce pentavalente con la edad menor de 4 meses"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.pentavalenteMenor4Meses)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.pentavalenteMenor6Meses"
                  label="Cruce pentavalente con la edad menor de 6 meses"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.pentavalenteMenor6Meses)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.polioMenor1AnioNoAplica"
                  label="Cuando el dato es No Aplica. Cruce de Polio con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.polioMenor1AnioNoAplica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.polioMenor1AnioMenor6Anios"
                  label="Cruce Polio con la edad menor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.polioMenor1AnioMenor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.polioMenor1AnioMayor6Anios"
                  label="cruce Polio con la edad Mayor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.polioMenor1AnioMayor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.polioMenor1AnioMeses"
                  label="Cruce Polio con la edad en 4, 6, 8, 12, 18 meses"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.polioMenor1AnioMeses)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.DPTMenor5AnioNoAplica"
                  label="Cuando el dato es igual a No Aplica. Cruce DPT con la edad "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.DPTMenor5AnioNoAplica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.DPTMenor5AnioMenor6Anios"
                  label="Cruce DPT con la edad menor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.DPTMenor5AnioMenor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.DPTMenor5AnioMayor6Anios"
                  label="Cruce DPT con la edad Mayor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.DPTMenor5AnioMayor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.DPTMenor5AnioMeses"
                  label="Cruce DPT con la edad en 18, 60 meses"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.DPTMenor5AnioMeses)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.rotavirusNoAplica"
                  label="Cuando el dato es igual a No Aplica. Cruce Rotavirus con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.rotavirusNoAplica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.rotavirusMenor6Anios"
                  label="Cruce Rotavirus con la edad menor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.rotavirusMenor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.rotavirusMeses"
                  label="Cruce Rotavirus con la edad en meses"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.rotavirusMeses)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.neumococoNoAplica"
                  label="Cuando el dato es igual a No Aplica. Cruce Neumoco con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.neumococoNoAplica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.neumococoMayor6Anios"
                  label="Cruce Neumoco con la edad mayor a 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.neumococoMayor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.neumococoMenor6Anios"
                  label="Cruce Neumococo con la edad Menor a 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.neumococoMenor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.neumococoMeses"
                  label="Cruce neumoco con la edad en 4, 6 meses "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.neumococoMeses)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.influenzaNiniosNoAplica"
                  label="Cuando el dato es igual a No Aplica. Cruce Influenza niños con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.influenzaNiniosNoAplica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.influenzaNiniosMayor6Anios"
                  label="Cruce influenza niños con la edad mayor a 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.influenzaNiniosMayor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.influenzaNiniosMenor6Anios"
                  label="Cruce Influenza Niños con la edad Menor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.influenzaNiniosMenor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.influenzaNiniosMeses"
                  label="Cruce Influenza Niños con la edad en 4, 6 meses"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.influenzaNiniosMeses)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fiebreAmarilla1AnioNiniosNoAplica"
                  label="Cuando el dato es igual a No Aplica. Cruce Fiebre Amarilla niños de 1 año con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fiebreAmarilla1AnioNiniosNoAplica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fiebreAmarilla1AnioNiniosMayor6Anios"
                  label="Cruce Fiebre amarilla niños de 1 año con la edad mayores de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fiebreAmarilla1AnioNiniosMayor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fiebreAmarilla1AnioNiniosMenor6Anios"
                  label="Cruce Fiebre amarilla niños de 1 año con la edad menores de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fiebreAmarilla1AnioNiniosMenor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fiebreAmarilla1AnioNiniosMeses"
                  label="Cruce Fiebre amarilla niños de 1 año con la edad en meses "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fiebreAmarilla1AnioNiniosMeses)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hepatitisANoAplica"
                  label="Cuando el dato es No Aplica. Cruce Hepatitis A con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hepatitisANoAplica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hepatitisAMayor6Anios"
                  label="Cruce Hepatitis A con la edad Mayor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hepatitisAMayor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hepatitisAMenor6Anios"
                  label="Cruce Hepatitis A con la edad Menor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hepatitisAMenor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hepatitisAMeses"
                  label="Cruce Hepatitis A con la edad en meses"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hepatitisAMeses)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tripleViralNiniosNoAplica"
                  label="Cuando el dato es igual a No Aplica. Cruce Triple Viral Niños con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tripleViralNiniosNoAplica)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tripleViralNiniosMayor6Anios"
                  label="Cruce Triple Viral Niños con la edad mayor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tripleViralNiniosMayor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tripleViralNiniosMenor6Anios"
                  label="Cruce Triple Viral Niños con la edad menor de 6 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tripleViralNiniosMenor6Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tripleViralNiniosMeses"
                  label="Cruce Triple Viral Niños con la edad en meses"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tripleViralNiniosMeses)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.VPhMayor9Anios"
                  label="Cruce Virus del Papiloma Humano (VPH) con la edad mayor de 9 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.VPhMayor9Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.VPhGenero"
                  label="Cruce Virus del Papiloma Humano con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.VPhGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.TdOTtGenero"
                  label="Cruce TD o TT Mujeres en Edad Fértil 15 a 49 años con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.TdOTtGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.TdOTtMayor9Anios"
                  label="Cruce TD o TT Mujeres en Edad Fértil 15 a 49 años con la edad mayor de 9 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.TdOTtMayor9Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.placaBacterianaEdad"
                  label="Cruce Control Placa Bacteriana con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.placaBacterianaEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaAtencionPartoCesaria"
                  label="Cruce fecha atencion parto o cesarea con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaAtencionPartoCesaria)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaPartoGenero"
                  label="Cruce fecha de atencion parto o cesarea con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaPartoGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaAtencionPartoMenorFechaSalida"
                  label="Cruce fecha atencion parto o cesarea con la fecha de salida atencion parto o cesarea"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaAtencionPartoMenorFechaSalida)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaAtencionPartoNullFechaSalida"
                  label="Cruce fecha tencion parto o cesarioa con la fecha de salida de atencion parto o cesarea difrente de nulo"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaAtencionPartoNullFechaSalida)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaPartoGestacion"
                  label="Cruce Fecha de atencion parto o cesaria con la gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaPartoGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaAtencionPartoVariablesExtras"
                  label="Cruce fecha de atencion parto o cesaria con variables extras"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaAtencionPartoVariablesExtras)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaSalidaAtencionPartoCesaria"
                  label="Cruce fecha de salida atencion parto o cesarea con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaSalidaAtencionPartoCesaria)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaSalidaPartoGenero"
                  label="Cruce fecha de salida de atencion parto o cesarea con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaSalidaPartoGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaSalidaPartoGestacion"
                  label="Cruce fecha de salida atencion parto o cesarea con la gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaSalidaPartoGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaSalidaPartoMayorFechaAtencionParto"
                  label="Cruce fecha de salida atencion parto o cesarea mayor de fecha de atencion parto o cesarea"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaSalidaPartoMayorFechaAtencionParto)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaSalidaPartoNullFechaAtencionParto"
                  label="Cruce Fecha de salida atencion parto o cesarea con fecha de atencion parto o cesarea cuando es nulo"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaSalidaPartoNullFechaAtencionParto)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaSalidaAtencionPartoVariablesExtras"
                  label="Cruce fecha de salida atencion parto o cesarea con variables extras"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaSalidaAtencionPartoVariablesExtras)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaConsejeriaLactanciaMaternaDiferente1885"
                  label="Cruce fecha de consejeria lactancia materna con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaConsejeriaLactanciaMaternaDiferente1885)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaConsejeriaLactanciaMaternaGenero"
                  label="Cruce fecha de consejeria lactancia materna con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaConsejeriaLactanciaMaternaGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaControlRecienNacidoDiferente1885"
                  label="Cruce Fecha control recien nacido con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaControlRecienNacidoDiferente1885)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaPlanificacionFamiliarPrimeraVez"
                  label="Cruce fecha planificacion familiar primera vez con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaPlanificacionFamiliarPrimeraVez)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.suministroMetodoAnticonseptivoFechaSuministro"
                  label="Cruce suministro de metodo Anticonceptivo con la fecha del suministro del metodo anticonceptivo"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.suministroMetodoAnticonseptivoFechaSuministro)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.suministroMetodoAnticonseptivoEdad"
                  label="Cruce Suministro de metodo anticonceptivo con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.suministroMetodoAnticonseptivoEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaSuministroMetodoAnticonceptivoSMA"
                  label="Cruce fecha suministro metodo anticonceptivo con el suministro del metodo anticonceptivo"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaSuministroMetodoAnticonceptivoSMA)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaSuministroMetodoAnticonceptivoPFPV"
                  label="Fecha del suministro de metodo anticonceptivo con la fecha planificacion familiar primera vez"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaSuministroMetodoAnticonceptivoPFPV)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.controlPrenatalPrimeraVezEdad"
                  label="Cruce Control Prenatal Primera Vez con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.controlPrenatalPrimeraVezEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.controlPrenatalPrimeraVezGenero"
                  label="Cruce control prenatal primera vez con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.controlPrenatalPrimeraVezGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.controlPrenatalPrimeraVezGestacion"
                  label="Cruce control prenatal primera vez con la gestacion "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.controlPrenatalPrimeraVezGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.controlPrenatalPrimeraVezMenorUCP"
                  label="Cruce Control prenatal primera vez sea menor que el Ultimo Control Prenatal"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.controlPrenatalPrimeraVezMenorUCP)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadControlPrenatalPrimeraVez"
                  label="Cruce Control prenatal primera vez con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadControlPrenatalPrimeraVez)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.controlPrenatalEdad"
                  label="Cruce control prenatal con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.controlPrenatalEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.controlPrenatalControles"
                  label="Numero de controles prenatales"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.controlPrenatalControles)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadControlPrenatal"
                  label="Cruce control prenatal con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadControlPrenatal)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.controlPrenatalGenero"
                  label="Cruce control prenatal con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.controlPrenatalGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.controlPrenatalGestacion"
                  label="Cruce control prenatal con la gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.controlPrenatalGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaUltimoControlPrenatalEdad"
                  label="Cruce fecha ultimo control prenatal con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaUltimoControlPrenatalEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaUltimoControlPrenatalControlPrenatalPrimeraVez"
                  label="Fecha ultimo control prenatal mayor que fecha control prenatal primera vez"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaUltimoControlPrenatalControlPrenatalPrimeraVez)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaUltimoControlPrenatal"
                  label="Cruce fecha ultimo control prenatal con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaUltimoControlPrenatal)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.ultimoControlPrenatalGenero"
                  label="Cruce Ultimo control prenatal con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.ultimoControlPrenatalGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.ultimoControlPrenatalGestacion"
                  label="Cruce ultimo control prenatal con la gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.ultimoControlPrenatalGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.suministroAcidoFolicoUltimoControlPrenatalEdad"
                  label="Cruce Suministro de Acido Folico en el ultimo control prenatal con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.suministroAcidoFolicoUltimoControlPrenatalEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadSuministroAcidoFolicoUltimoControlPrenatal"
                  label="Cruce Edad con Suministro de acido folico en el ultimo control prenatal con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadSuministroAcidoFolicoUltimoControlPrenatal)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.suministroAcidoFolicoUCPGenero"
                  label="Cruce suministro de acido folico en el ultimo control prenatal con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.suministroAcidoFolicoUCPGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.suministroAcidoFolicoUcpGestacion"
                  label="Cruce suministro de acido folico en el ultimo control prenatal con la gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.suministroAcidoFolicoUcpGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.sulfatoFerrosoUltimoControlPrenatalEdad"
                  label="Cruce suministro de sulfato ferroso en el ultimo control prenatal con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.sulfatoFerrosoUltimoControlPrenatalEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadSulfatoFerrosoUltimoControlPrenatal"
                  label="Cruce Edad con el suministro de sulfato ferroso en el ultimo control prenatal"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadSulfatoFerrosoUltimoControlPrenatal)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.suministroSulfatoFerrosoUCPGenero"
                  label="Cruce suministro de sulfato ferroso en el ultimo control prenatal con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.suministroSulfatoFerrosoUCPGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.suministroSulfatoFerrosoUcpGestacion"
                  label="Cruce suministro de sulfato ferroso en el ultimo control prenatal con la gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.suministroSulfatoFerrosoUcpGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.carbonatoCalcioUltimoControlPrenatalEdad"
                  label="Cruce suministro de carbonato de calcio en el ultimo control prenatal con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.carbonatoCalcioUltimoControlPrenatalEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadCarbonatoCalcioUltimoControlPrenatal"
                  label="Cruce suministro de carbonato de calcio en el ultimo control prenatal con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadCarbonatoCalcioUltimoControlPrenatal)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.suministroCarbonatoCalcioUCPGenero"
                  label="Cruce suministro de carbonato de calcio en el ultimo control prenatal con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.suministroCarbonatoCalcioUCPGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.suministroCarbonatoCalcioGestacion"
                  label="Cruce suministro de carbonato de calcio en el ultimo control prenatal con la gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.suministroCarbonatoCalcioGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaDiagnosticoDesnutricionProteicoCaloricaObesidad"
                  label="Cruce fecha diagnostico descripcion Proteico calorica con Obesidad o Desnutrición Proteico Calórica "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaDiagnosticoDesnutricionProteicoCaloricaObesidad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaDiagnosticoDesnutricionProteicoCaloricaPeso"
                  label="Cruce fecha diagnostico descripcion Proteico calorica con el peso "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaDiagnosticoDesnutricionProteicoCaloricaPeso)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaDiagnosticoDesnutricionProteicoCaloricaTalla"
                  label="Cruce fecha diagnostico descripcion Proteico calorica con la talla"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaDiagnosticoDesnutricionProteicoCaloricaTalla)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaConsultaMujerMenorVictimaMaltrato"
                  label="Cruce fecha consulta mujer o menor victima de maltrato con mujero o menor victima de maltrato"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaConsultaMujerMenorVictimaMaltrato)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaConsultaVictimaViolenciaSexual"
                  label="fecha consulta victima de violencia sexual"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaConsultaVictimaViolenciaSexual)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaConsultaCrecimientoDesarrolloPrimeraVez"
                  label="Cruce fecha de consulta crecimiento y desarrollo primera vez con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaConsultaCrecimientoDesarrolloPrimeraVez)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaSuministroSulfatoFerrosoUltimaConsultaMenor10Anios"
                  label="Cruce Suministro de Sulfato Ferroso en la Última Consulta del Menor de 10 años con la edad menor de 10 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaSuministroSulfatoFerrosoUltimaConsultaMenor10Anios)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.suministroSulfatoFerrosoUltimaConsultaMenor10AniosEdad"
                  label="Cruce Suministro de Sulfato Ferroso en la Última Consulta del Menor de 10 años con la edad menor de 10 años"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.suministroSulfatoFerrosoUltimaConsultaMenor10AniosEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaSuministroVitaminaAUltimaConsultaMenor10AniosDiferente0"
                  label="Cruce Suministro de Vitamina A  Última Consulta del Menor de 10 años deiferente de '0'"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaSuministroVitaminaAUltimaConsultaMenor10AniosDiferente0)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.suministroVitaminaAUltimaConsultaMenor10AniosEdad"
                  label="Cruce Suministro de Vitamina A  Última Consulta del Menor de 10 años con edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.suministroVitaminaAUltimaConsultaMenor10AniosEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.consultaJovenPrimeraVezEdad"
                  label="Cruce consulta joven primera vez con edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.consultaJovenPrimeraVezEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadConsultaJovenPrimeraVez"
                  label="Cruce edad Con Consulta Joven primera vez"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadConsultaJovenPrimeraVez)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.consultaAdultoPrimeraVezEdad"
                  label="Cruce Consulta adulto primera vez con edad "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.consultaAdultoPrimeraVezEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadConsultaAdultoPrimeraVez"
                  label="Cruce edad con consulta adulto primera vez"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadConsultaAdultoPrimeraVez)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.consultaAdultoPrimeraVezRangosEdad"
                  label="Concuslta adulto primera vez con rangos de edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.consultaAdultoPrimeraVezRangosEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.preservativosEntregadosPacConITS"
                  label="Numero de preservativos entregados a pacientes con ITS"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.preservativosEntregadosPacConITS)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.asesoriaPreTestElisaVIHMenorPostAsesoriaMismoTest"
                  label="Fecha asesoria pre test Elisa VIH menor que asesoria Post test Elisa VIH"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.asesoriaPreTestElisaVIHMenorPostAsesoriaMismoTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.asesoriaPreTestElisaVIHMenorFechaTomaTest"
                  label="Fecha de asesria pre Test Elisa VIH menor que fecha toma test Elisa VIH"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.asesoriaPreTestElisaVIHMenorFechaTomaTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.asesoriaPostTestMayorPreAsesoriaMismoTest"
                  label="Fecha asesoria Post test Elisa VIH Mayor Fecha Asesoria Pre test Elisa VIH"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.asesoriaPostTestMayorPreAsesoriaMismoTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.asesoriaPostTestElisaVIHMayorFechaTomaTest"
                  label="Fecha asesoria Post test Elisa VIH Mayor fecha toma Test Elisa VIH"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.asesoriaPostTestElisaVIHMayorFechaTomaTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.diagnosticoPacienteEnfermedadMental"
                  label="Cruce Paciente con Diagnóstico de: Ansiedad, Depresión y otros con enfermedad mental"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.diagnosticoPacienteEnfermedadMental)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaAntigenoSuperficieHepatitisBGestantesEdad"
                  label="Cruce fecha antigeno de superficie Hepatitis B en gestantes con edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaAntigenoSuperficieHepatitisBGestantesEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaAntigenoSuperficieHepatitisBGestantesResultado"
                  label="Cruce fecha antigeno de superficie Hepatitis B en gestantes con resultado Antigeno de Superficie Hepatitis B en gestantes"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaAntigenoSuperficieHepatitisBGestantesResultado)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaAntigenoSuperficieHepatitisBGestantesGenero"
                  label="Cruce Fecha antigeno superficie Hepatitis B en gestantes con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaAntigenoSuperficieHepatitisBGestantesGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaAntigenoSuperficieHepatitisBGestantesGestacion"
                  label="Cruce Fecha antigeno superficie Hepatitis B en gestantes con gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaAntigenoSuperficieHepatitisBGestantesGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoAntigenoSuperficieHepatitisBGestantesEdad"
                  label="Cruce resultado antigeno superficie Hepatitis B en gestantes con edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoAntigenoSuperficieHepatitisBGestantesEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoAntigenoSuperficieHepatitisBGestantesFechaAntigeno"
                  label="Cruce resultado antigeno superficie Hepatitis B en gestantes con fecha de toma antigeno Superficie Hepatitis B en Gestantes"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoAntigenoSuperficieHepatitisBGestantesFechaAntigeno)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoAntigenoSuperficieHepatitisBGestantesGenero"
                  label="Cruce resultado antigeno superficie Hepatitis B en gestantes con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoAntigenoSuperficieHepatitisBGestantesGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoAntigenoSuperficieHepatitisBGestantesGestacion"
                  label="Cruce resultado antigeno superficie Hepatitis B en gestantes con gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoAntigenoSuperficieHepatitisBGestantesGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaSerologiaSifilisResultadoSerologiaSifilis"
                  label="Cruce fecha de serologia para sifilis con resultado serologia para sifilis"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaSerologiaSifilisResultadoSerologiaSifilis)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoSerologiaSifilisFechaSerologiaSifilis"
                  label="Cruce Resultado serologia para sifilis con fecha de serologia para sifilis"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoSerologiaSifilisFechaSerologiaSifilis)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaTomaElisaVIHResultadoElisaVIH"
                  label="Cruce fecha toma Elisa VIH con resultado Elisa VIH"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaTomaElisaVIHResultadoElisaVIH)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaTomaElisaVIHMayorFechaAsesoriaPreTest"
                  label="Cruce fecha toma Elisa VIH mator que fecha asesoria Pre Test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaTomaElisaVIHMayorFechaAsesoriaPreTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaTomaElisaVIHMayorFechaAsesoriaPostTest"
                  label="Cruce fecha toma Elisa VIH mayor que fecha Asesoria Post Test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaTomaElisaVIHMayorFechaAsesoriaPostTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoElisaVIHFechaTomaTest"
                  label="Cruce Resultado Elisa VIH con fecha toma Test Elisa VIH"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoElisaVIHFechaTomaTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaTSHNeonatalResultadoTest"
                  label="Cruce Fecha TSH Neonatal con resultado Test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaTSHNeonatalResultadoTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoTSHNeonatalFechaTomaTest"
                  label="Cruce resultado THS Neonatal con fecha toma test TSH Neonatal"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoTSHNeonatalFechaTomaTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaTamizajeCancerCuelloUterinoDiferenteCero"
                  label="Tamizaje cancer de cuello uterino con edad cuando es diferente de '0'"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaTamizajeCancerCuelloUterinoDiferenteCero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tamizajeCancerCuelloUterinoGenero"
                  label="Cruce tamizaje cancer de cuello uterino con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tamizajeCancerCuelloUterinoGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaCitologiaCervicoUterinaDB"
                  label="Codiggo habilitacion IPS Citología Cervicouterina en la base de datos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaCitologiaCervicoUterinaDB)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaCitologiaCervicoUterina1845"
                  label="Cruce fecha citologia cervico uterina con edad cuando es diferente 1845"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaCitologiaCervicoUterina1845)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.citologiaCervicoUterinaGenero"
                  label="Cruce citologia cervico uterina con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.citologiaCervicoUterinaGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaCalidadMuestraCitologiaCervicouterinaBethesdaDiferenteCero"
                  label="Calidad en la muestra de citologia Cervicouterina con edad. cuando diferente de cero "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaCalidadMuestraCitologiaCervicouterinaBethesdaDiferenteCero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoCitologiaCervicoUterinaFechaCitologia"
                  label="Resultado citologia cervico uterina con fecha de citologia"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoCitologiaCervicoUterinaFechaCitologia)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.citologiaCervicoUterinaSegunBethesdaGenero"
                  label="Cruce citologia cervico uterina segun behtesda con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.citologiaCervicoUterinaSegunBethesdaGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaCalidadMuestraCitologiaCervicouterinaDiferenteCero"
                  label="Calidad muestra citologia cervico uterina con edad diferente de cero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaCalidadMuestraCitologiaCervicouterinaDiferenteCero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.calidadMuestraCitologiaCervicouterinaResultados"
                  label="Calidad muestra citologia cervico uterina con resultados"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.calidadMuestraCitologiaCervicouterinaResultados)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.calidadMuestraCitologiaCervicoUterinaGenero"
                  label="Calidad muestra citologia cervico uterina con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.calidadMuestraCitologiaCervicoUterinaGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaCodigoHabilitacionIpsCitologiaCervicouterina"
                  label="codigo habilitacion IPS Citologia Cervicouterina con edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaCodigoHabilitacionIpsCitologiaCervicouterina)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaCitologiaCervicoUterinaGenero"
                  label="Codigo Habilitacion IPS Citologia Cervicouterina con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaCitologiaCervicoUterinaGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaCitologiaCervicoUterinaMuestraTest"
                  label="Codigo Habilitacion IPS Citologia Cervicouterina con muestra del test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaCitologiaCervicoUterinaMuestraTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaColposcopia"
                  label="Fecha de colposcopia con la edad "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaColposcopia)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechacolposcopiaGenero"
                  label="Fecha de colposcopia con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechacolposcopiaGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechacolposcopiaCodigoIpsTomaTest"
                  label="fecha de colposcopia con codigo IPS de toma del test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechacolposcopiaCodigoIpsTomaTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoHabilitacionIPSColposcopiaDB"
                  label="Codiggo habilitacion IPS Colposcopia en la base de datos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoHabilitacionIPSColposcopiaDB)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaCodigoHabilitacionIPSColposcopia"
                  label="codigo de habilitacion IPS Colposcopia con edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaCodigoHabilitacionIPSColposcopia)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaColposcopiaGenero"
                  label="Codigo IPS Toma colposcopia de genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaColposcopiaGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaColposcopiaFechaColposcopia"
                  label="Codigo de IPS toma colposcopia fecha colposcopia"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaColposcopiaFechaColposcopia)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.edadFechaBiopsiaCervical"
                  label="Fecha Biopsia Cervica con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.edadFechaBiopsiaCervical)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaBiopsiaCervicalGenero"
                  label="Fecha Biopsia Cervical con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaBiopsiaCervicalGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaBiopsiaCervicalResultadoTest"
                  label="Fecha biopsia Cervical con resultado del test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaBiopsiaCervicalResultadoTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaResultadoBiopsiaCervical"
                  label="Resultado de Biopsia Cervical con edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaResultadoBiopsiaCervical)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoBiopsiaCervicalGenero"
                  label="Resultado Biopsia Cervical con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoBiopsiaCervicalGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoBiopsiaCervicalFechaTest"
                  label="Resultado biopsia cervical con la fecha de test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoBiopsiaCervicalFechaTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoHabilitacionIpsBiopsiaCervicalDB"
                  label="Codigo de habilitacion IPS biopsia cervical con la base de datos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoHabilitacionIpsBiopsiaCervicalDB)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaCodigoHabilitacionIpsBiopsiaCervical"
                  label="Codigo de habilitacion IPS Biopsia cervical con edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaCodigoHabilitacionIpsBiopsiaCervical)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaBiopsiaCervicalGenero"
                  label="Codigo IPS toma Biopsia Cervical con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaBiopsiaCervicalGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaBiopsiaCervicalResultadoTest"
                  label="Codigo IPs toma Biopsia Cervical con resultado test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaBiopsiaCervicalResultadoTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaMamografiaGenero"
                  label="FEcha Mamografia con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaMamografiaGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaMamografia"
                  label="Fecha de mamografia diferente de 1845"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaMamografia)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaMamografiaEdad"
                  label="Fecha de mamografia con edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaMamografiaEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaResultadoMamografia"
                  label="Fecha de resultado mamografia"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaResultadoMamografia)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoMamografiaGenero"
                  label="Resultado de mamografia con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoMamografiaGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoMamografiaFechaEdad"
                  label="Resultado de mamografia con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoMamografiaFechaEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoMamografiaFechaTest"
                  label="resultado de mamografia con la fecha de test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoMamografiaFechaTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoHabilitacionIPSTomaMamografiaDB"
                  label="Codigo de habilitacion IPS toma mamografia con la base de datos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoHabilitacionIPSTomaMamografiaDB)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaCodigoHabilitacionIPSTomaMamografia"
                  label="fecha codigo de habilitacion IPS toma mamografia"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaCodigoHabilitacionIPSTomaMamografia)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaMamografiaGenero"
                  label="Codigo IPS toma mamografia con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaMamografiaGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaMamografiaEdad"
                  label="Codigo ips toma mamografia con edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaMamografiaEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaMamografiaResultadoTest"
                  label="codigo ips toma mamografia con resultado test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaMamografiaResultadoTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechatomaBiopsiaSenoBACAFGenero"
                  label="Fecha toma biopsia seno BACAF con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechatomaBiopsiaSenoBACAFGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaResultadoBiopsiaSenoGenero"
                  label="Fecha resultado biopsia seno genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaResultadoBiopsiaSenoGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaResultadoBiopsiaSenoResultado"
                  label="fecha resultado de biopsia seno resultado"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaResultadoBiopsiaSenoResultado)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaResultadoBiopsiaSenoMayorFechaTomaTest"
                  label="Fecha resultado biopsia seno mayor que fecha de toma de test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaResultadoBiopsiaSenoMayorFechaTomaTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fResultadoBiopsiaSenoGenero"
                  label="Resultado biopsia seno con genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fResultadoBiopsiaSenoGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoBiopsiaSenoResultadoTest"
                  label="Cruce Resultado Biopsia Seno con la fecha de resultado Biopsia Seno"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoBiopsiaSenoResultadoTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaBiopsiaSenoDB"
                  label="Codigo de la IPS donde se toma la biopsia de seno existe en la base de datos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaBiopsiaSenoDB)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaBiopsiaSenoGenero"
                  label="Cruce Codigo Ips Toma Biopsia Seno con el genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaBiopsiaSenoGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.codigoIpsTomaBiopsiaSenoResultadoTomaTest"
                  label="Cruce Codigo Ips Toma Biopsia Seno Con el Resultado de toma test"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.codigoIpsTomaBiopsiaSenoResultadoTomaTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hemoglobina"
                  label="El dato de hemoglobina existe entre el rango de valores permitidos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hemoglobina)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaCreatininaCreatinina"
                  label="Cruce de fecha Creatinina con Creatinina"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaCreatininaCreatinina)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.creatininaFechaCreatinina"
                  label="Cruce de Creatinina con Fecha de Creatinina"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.creatininaFechaCreatinina)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.creatinina"
                  label="El dato de creatinina existe entre el rango de valores permitidos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.creatinina)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaHemoglobinaGlicosilada"
                  label="Cruce fecha Hemoglobina Glicosilada con Hemoglobina Glicosilada "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaHemoglobinaGlicosilada)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hemoglobinaGlicosilada"
                  label="El dato de Hemoglobina Glicosilada Existe entre el rango de valores permitidos"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hemoglobinaGlicosilada)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.hemoglobinaGlicosiladaFechaHemoglobinaGlicosilada"
                  label="Cruce de Hemoglobina Glicosilada con la Fecha Hemoglobina Glicosilada"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.hemoglobinaGlicosiladaFechaHemoglobinaGlicosilada)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.fechaTomaBaciloscopiaDiagnosticoResultadoTest"
                  label="Cruce fecha toma Baciloscopia de Diagnostico con Baciloscopia de Diagnostico"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.fechaTomaBaciloscopiaDiagnosticoResultadoTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.resultadoBaciloscopiaDiagnosticoFechaTest"
                  label="Cruce del resultado Baciloscopia De Diagnostico con la Fecha Toma de Baciloscopia de Diagnostico"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.resultadoBaciloscopiaDiagnosticoFechaTest)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tratamientoParaHipotiroidismoCongenitoHipotiroidismo"
                  label="Cruce tratamiento para hipotiroidismo congenita con Hipotiroidismo Congenito"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tratamientoParaHipotiroidismoCongenitoHipotiroidismo)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tratamientoSifilisGestacionalGenero"
                  label="Cruce Tratamiento Sifilis Gestacional con Genero"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tratamientoSifilisGestacionalGenero)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tratamientoSifilisGestacionalGestacion"
                  label="Cruce Tratamiento Sifilis Gestacional con Gestacion"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tratamientoSifilisGestacionalGestacion)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tratamientoSifilisGestacionalCongenitaOGestacional"
                  label="Cruce Tratamiento Sifilis Congenita con Sifilis gestacional o Congenita "
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tratamientoSifilisGestacionalCongenitaOGestacional)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tratamientoSifilisGestacionalEdad"
                  label="Cruce Tratamiento Sifilis Gestacional con la edad"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tratamientoSifilisGestacionalEdad)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <v-switch
                  v-model="reglas.tratamientoSifilisCongenitaGestacionalCongenita"
                  label="Tratamiento Sifilis Congenita con Sifilis Gestacional o Congenita"
                  color="success"
                  :value="true"
                  hide-details
                ></v-switch>
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-btn color="primary" @click="mostrarAyuda(ayudas.tratamientoSifilisCongenitaGestacionalCongenita)" flat icon small>
                  <v-icon>help</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
          </v-container>
        </v-card-text>
      </v-card>
    </v-container>
    <v-container>
      <v-speed-dial
        v-model="fab"
        bottom
        right
        direction="bottom"
        open-on-hover
        fixed
        class="mb-4"
      >
        <v-tooltip left slot="activator">
          <v-btn
            slot="activator"
            v-model="fab"
            color="blue darken-2"
            dark
            fab
            @click="submit"
          >
            <v-icon>save</v-icon>
            <!--<v-icon>close</v-icon>-->
          </v-btn>
          <span>Configurar reglas de validación</span>
        </v-tooltip>
      </v-speed-dial>
      <!--<v-layout row wrap>-->
        <!--<v-flex xs12>-->
          <!--<v-btn color="primary" :left="true" @click="submit">Configurar reglas de validacion</v-btn>-->
        <!--</v-flex>-->
      <!--</v-layout>-->
    </v-container >
    <v-dialog
      v-model="dialogHelp"
    >

      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title>
          Ayuda y descripcion de la validacion
        </v-card-title>
        <div v-show="bottomNav==0" class="headline pa-5">
          <div class="text-xs-center"><div v-html="help.descripcion"></div></div>
        </div>
        <div v-show="bottomNav==1" class="headline pa-5">
          <div class="text-xs-center"><div v-html="help.valido"></div></div>
        </div>
        <div v-show="bottomNav==2" class="headline pa-5">
          <div class="text-xs-center"><div v-html="help.invalido"></div></div>
        </div>
        <v-bottom-nav
          :active.sync="bottomNav"
          :color="color"
          :value="true"
          absolute
          shift>
          <v-btn dark>
            <span>Descripcion</span>
            <v-icon>assignment</v-icon>
          </v-btn>
          <v-btn dark>
            <span>Ejemplo valido</span>
            <v-icon>done_all</v-icon>
          </v-btn>
          <v-btn dark>
            <span>Ejemplo No Valido</span>
            <v-icon>warning</v-icon>
          </v-btn>
        </v-bottom-nav>
        <!--</v-card-text>-->
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="white"
            flat icon
            @click="dialogHelp = false"
          >
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  export default {
    name: 'ConfigurarReglasValidacion',
    data () {
      return {
        fab: false,
        dialogHelp: false,
        help: {
          descripcion: '',
          valido: '',
          invalido: ''
        },
        bottomNav: 0,
        reglas: {
          existePrestadorServicioSaludInicioSesion: false,
          longitudIdentificacionAfiliado: false,
          primerApellidoAfiliado: false,
          segundoApellidoAfiliado: false,
          primerNombreAfiliado: false,
          segundoNombreAfiliado: false,
          fechaNacimientoAfiliado: false,
          generoAfiliado: false,
          pertenenciaEtnica: false,
          ocupacion: false,
          nivelEducativo: false,
          edadGestacion: false,
          gestacionGenero: false,
          sifilisGestacionalCongenitaEdad: false,
          sifilisGestacionalCongenitaEdadGeneroGestacion: false,
          sifilisGestacionalCongenitaGenero: false,
          sifilisGestacionalCongenitaGestacion: false,
          sifilisGestacionalCongenitaInfeccionTransmisionSexual: false,
          hipertensionInducidaGestacionEdadSi: false,
          hipertensionInducidaGestacionGenero: false,
          hipertensionInducidaGestacionGestacion: false,
          hipotiroidismoCongenitoEdadSi: false,
          hipotiroidismoCongenitoResultadoTSH: false,
          hipotiroidismoCongenitoTratamiento: false,
          sintomaticoRespiratorioBaciloscopiaDiagnostico: false,
          lepraTratamientoLepra: false,
          obsidadDesnutricionProteicoEdadSi: false,
          obsidadDesnutricionProteicoFechaDiagnostico: false,
          obsidadDesnutricionProteicoPeso: false,
          obsidadDesnutricionProteicoTall: false,
          victimaMaltratoEdadMenorSiMenor: false,
          victimaMaltratoMujerVictima: false,
          victimaMaltratoFechaConsultaMujerOMenorVictima: false,
          victimaMaltratoEdadGeneral: false,
          victimaViolenciaSexualFechaConsulta: false,
          enfermedadMentalDiagnostico: false,
          canverCervixGenero: false,
          pesoKilogramos: false,
          pesoFechaConsulta: false,
          tallaCentimetros: false,
          tallaFechaConsulta: false,
          fechaProbablePartoGenero: false,
          fechaProbablePartoGestacion: false,
          fechaProbablePartoEdad: false,
          edadGestacionalNacerDiferenteCero: false,
          edadGestacionalNacerEdad: false,
          edadGestacionalNacer43Semanas: false,
          bcgEdadDiferenteNoAplica: false,
          bcgMenor6Anios: false,
          bcgMayor6Anios: false,
          hepatitisBMenorNoAplica: false,
          hepatitisBMenor1AnioMenor6Anios: false,
          hepatitisBMenor1AnioMayor6Anios: false,
          pentavalenteMenor1AnioNoAplica: false,
          pentavalenteMenor1AnioMenor6Anios: false,
          pentavalenteMenor1AnioMayor6Anios: false,
          pentavalenteMenor4Meses: false,
          pentavalenteMenor6Meses: false,
          polioMenor1AnioNoAplica: false,
          polioMenor1AnioMenor6Anios: false,
          polioMenor1AnioMayor6Anios: false,
          polioMenor1AnioMeses: false,
          DPTMenor5AnioNoAplica: false,
          DPTMenor5AnioMenor6Anios: false,
          DPTMenor5AnioMayor6Anios: false,
          DPTMenor5AnioMeses: false,
          rotavirusNoAplica: false,
          rotavirusMenor6Anios: false,
          rotavirusMeses: false,
          neumococoNoAplica: false,
          neumococoMayor6Anios: false,
          neumococoMenor6Anios: false,
          neumococoMeses: false,
          influenzaNiniosNoAplica: false,
          influenzaNiniosMayor6Anios: false,
          influenzaNiniosMenor6Anios: false,
          influenzaNiniosMeses: false,
          fiebreAmarilla1AnioNiniosNoAplica: false,
          fiebreAmarilla1AnioNiniosMayor6Anios: false,
          fiebreAmarilla1AnioNiniosMenor6Anios: false,
          fiebreAmarilla1AnioNiniosMeses: false,
          hepatitisANoAplica: false,
          hepatitisAMayor6Anios: false,
          hepatitisAMenor6Anios: false,
          hepatitisAMeses: false,
          tripleViralNiniosNoAplica: false,
          tripleViralNiniosMayor6Anios: false,
          tripleViralNiniosMenor6Anios: false,
          tripleViralNiniosMeses: false,
          VPhMayor9Anios: false,
          VPhGenero: false,
          TdOTtGenero: false,
          TdOTtMayor9Anios: false,
          placaBacterianaEdad: false,
          edadFechaAtencionPartoCesaria: false,
          fechaPartoGenero: false,
          fechaAtencionPartoMenorFechaSalida: false,
          fechaAtencionPartoNullFechaSalida: false,
          fechaPartoGestacion: false,
          fechaAtencionPartoVariablesExtras: false,
          edadFechaSalidaAtencionPartoCesaria: false,
          fechaSalidaPartoGenero: false,
          fechaSalidaPartoGestacion: false,
          fechaSalidaPartoMayorFechaAtencionParto: false,
          fechaSalidaPartoNullFechaAtencionParto: false,
          fechaSalidaAtencionPartoVariablesExtras: false,
          edadFechaConsejeriaLactanciaMaternaDiferente1885: false,
          fechaConsejeriaLactanciaMaternaGenero: false,
          edadFechaControlRecienNacidoDiferente1885: false,
          edadFechaPlanificacionFamiliarPrimeraVez: false,
          suministroMetodoAnticonseptivoFechaSuministro: false,
          suministroMetodoAnticonseptivoEdad: false,
          fechaSuministroMetodoAnticonceptivoSMA: false,
          fechaSuministroMetodoAnticonceptivoPFPV: false,
          controlPrenatalPrimeraVezEdad: false,
          controlPrenatalPrimeraVezGenero: false,
          controlPrenatalPrimeraVezGestacion: false,
          controlPrenatalPrimeraVezMenorUCP: false,
          edadControlPrenatalPrimeraVez: false,
          controlPrenatalEdad: false,
          controlPrenatalControles: false,
          edadControlPrenatal: false,
          controlPrenatalGenero: false,
          controlPrenatalGestacion: false,
          fechaUltimoControlPrenatalEdad: false,
          fechaUltimoControlPrenatalControlPrenatalPrimeraVez: false,
          edadFechaUltimoControlPrenatal: false,
          ultimoControlPrenatalGenero: false,
          ultimoControlPrenatalGestacion: false,
          suministroAcidoFolicoUltimoControlPrenatalEdad: false,
          edadSuministroAcidoFolicoUltimoControlPrenatal: false,
          suministroAcidoFolicoUCPGenero: false,
          suministroAcidoFolicoUcpGestacion: false,
          sulfatoFerrosoUltimoControlPrenatalEdad: false,
          edadSulfatoFerrosoUltimoControlPrenatal: false,
          suministroSulfatoFerrosoUCPGenero: false,
          suministroSulfatoFerrosoUcpGestacion: false,
          carbonatoCalcioUltimoControlPrenatalEdad: false,
          edadCarbonatoCalcioUltimoControlPrenatal: false,
          suministroCarbonatoCalcioUCPGenero: false,
          suministroCarbonatoCalcioGestacion: false,
          fechaDiagnosticoDesnutricionProteicoCaloricaObesidad: false,
          fechaDiagnosticoDesnutricionProteicoCaloricaPeso: false,
          fechaDiagnosticoDesnutricionProteicoCaloricaTalla: false,
          fechaConsultaMujerMenorVictimaMaltrato: false,
          fechaConsultaVictimaViolenciaSexual: false,
          edadFechaConsultaCrecimientoDesarrolloPrimeraVez: false,
          edadFechaSuministroSulfatoFerrosoUltimaConsultaMenor10Anios: false,
          suministroSulfatoFerrosoUltimaConsultaMenor10AniosEdad: false,
          edadFechaSuministroVitaminaAUltimaConsultaMenor10AniosDiferente0: false,
          suministroVitaminaAUltimaConsultaMenor10AniosEdad: false,
          consultaJovenPrimeraVezEdad: false,
          edadConsultaJovenPrimeraVez: false,
          consultaAdultoPrimeraVezEdad: false,
          edadConsultaAdultoPrimeraVez: false,
          consultaAdultoPrimeraVezRangosEdad: false,
          preservativosEntregadosPacConITS: false,
          asesoriaPreTestElisaVIHMenorPostAsesoriaMismoTest: false,
          asesoriaPreTestElisaVIHMenorFechaTomaTest: false,
          asesoriaPostTestMayorPreAsesoriaMismoTest: false,
          asesoriaPostTestElisaVIHMayorFechaTomaTest: false,
          diagnosticoPacienteEnfermedadMental: false,
          fechaAntigenoSuperficieHepatitisBGestantesEdad: false,
          fechaAntigenoSuperficieHepatitisBGestantesResultado: false,
          fechaAntigenoSuperficieHepatitisBGestantesGenero: false,
          fechaAntigenoSuperficieHepatitisBGestantesGestacion: false,
          resultadoAntigenoSuperficieHepatitisBGestantesEdad: false,
          resultadoAntigenoSuperficieHepatitisBGestantesFechaAntigeno: false,
          resultadoAntigenoSuperficieHepatitisBGestantesGenero: false,
          resultadoAntigenoSuperficieHepatitisBGestantesGestacion: false,
          fechaSerologiaSifilisResultadoSerologiaSifilis: false,
          resultadoSerologiaSifilisFechaSerologiaSifilis: false,
          fechaTomaElisaVIHResultadoElisaVIH: false,
          fechaTomaElisaVIHMayorFechaAsesoriaPreTest: false,
          fechaTomaElisaVIHMayorFechaAsesoriaPostTest: false,
          resultadoElisaVIHFechaTomaTest: false,
          fechaTSHNeonatalResultadoTest: false,
          resultadoTSHNeonatalFechaTomaTest: false,
          fechaTamizajeCancerCuelloUterinoDiferenteCero: false,
          tamizajeCancerCuelloUterinoGenero: false,
          edadFechaCitologiaCervicoUterina1845: false,
          citologiaCervicoUterinaGenero: false,
          fechaCalidadMuestraCitologiaCervicouterinaBethesdaDiferenteCero: false,
          resultadoCitologiaCervicoUterinaFechaCitologia: false,
          citologiaCervicoUterinaSegunBethesdaGenero: false,
          fechaCalidadMuestraCitologiaCervicouterinaDiferenteCero: false,
          calidadMuestraCitologiaCervicouterinaResultados: false,
          calidadMuestraCitologiaCervicoUterinaGenero: false,
          fechaCodigoHabilitacionIpsCitologiaCervicouterina: false,
          codigoIpsTomaCitologiaCervicoUterinaGenero: false,
          codigoIpsTomaCitologiaCervicoUterinaMuestraTest: false,
          edadFechaColposcopia: false,
          fechacolposcopiaGenero: false,
          fechacolposcopiaCodigoIpsTomaTest: false,
          codigoHabilitacionIPSColposcopiaDB: false,
          fechaCodigoHabilitacionIPSColposcopia: false,
          codigoIpsTomaColposcopiaGenero: false,
          codigoIpsTomaColposcopiaFechaColposcopia: false,
          edadFechaBiopsiaCervical: false,
          fechaBiopsiaCervicalGenero: false,
          fechaBiopsiaCervicalResultadoTest: false,
          fechaResultadoBiopsiaCervical: false,
          resultadoBiopsiaCervicalGenero: false,
          resultadoBiopsiaCervicalFechaTest: false,
          codigoHabilitacionIpsBiopsiaCervicalDB: false,
          fechaCodigoHabilitacionIpsBiopsiaCervical: false,
          codigoIpsTomaBiopsiaCervicalGenero: false,
          codigoIpsTomaBiopsiaCervicalResultadoTest: false,
          fechaMamografiaGenero: false,
          fechaMamografia: false,
          fechaMamografiaEdad: false,
          fechaResultadoMamografia: false,
          resultadoMamografiaGenero: false,
          resultadoMamografiaFechaEdad: false,
          resultadoMamografiaFechaTest: false,
          codigoHabilitacionIPSTomaMamografiaDB: false,
          fechaCodigoHabilitacionIPSTomaMamografia: false,
          codigoIpsTomaMamografiaGenero: false,
          codigoIpsTomaMamografiaEdad: false,
          codigoIpsTomaMamografiaResultadoTest: false,
          fechatomaBiopsiaSenoBACAFGenero: false,
          fechaResultadoBiopsiaSenoGenero: false,
          fechaResultadoBiopsiaSenoResultado: false,
          fechaResultadoBiopsiaSenoMayorFechaTomaTest: false,
          fResultadoBiopsiaSenoGenero: false,
          resultadoBiopsiaSenoResultadoTest: false,
          codigoIpsTomaBiopsiaSenoDB: false,
          codigoIpsTomaBiopsiaSenoGenero: false,
          codigoIpsTomaBiopsiaSenoResultadoTomaTest: false,
          hemoglobina: false,
          fechaCreatininaCreatinina: false,
          creatininaFechaCreatinina: false,
          creatinina: false,
          fechaHemoglobinaGlicosilada: false,
          hemoglobinaGlicosilada: false,
          hemoglobinaGlicosiladaFechaHemoglobinaGlicosilada: false,
          fechaTomaBaciloscopiaDiagnosticoResultadoTest: false,
          resultadoBaciloscopiaDiagnosticoFechaTest: false,
          tratamientoParaHipotiroidismoCongenitoHipotiroidismo: false,
          tratamientoSifilisGestacionalGenero: false,
          tratamientoSifilisGestacionalGestacion: false,
          tratamientoSifilisGestacionalCongenitaOGestacional: false,
          tratamientoSifilisGestacionalEdad: false,
          tratamientoSifilisCongenitaGestacionalCongenita: false,
          codigoIpsTomaCitologiaCervicoUterinaDB: false
        },
        ayudas: {
          existePrestadorServicioSaludInicioSesion: {},
          longitudIdentificacionAfiliado: {},
          primerApellidoAfiliado: {},
          segundoApellidoAfiliado: {},
          primerNombreAfiliado: {},
          segundoNombreAfiliado: {},
          fechaNacimientoAfiliado: {},
          generoAfiliado: {},
          pertenenciaEtnica: {},
          ocupacion: {},
          nivelEducativo: {},
          edadGestacion: {},
          gestacionGenero: {},
          sifilisGestacionalCongenitaEdad: {},
          sifilisGestacionalCongenitaEdadGeneroGestacion: {},
          sifilisGestacionalCongenitaGenero: {},
          sifilisGestacionalCongenitaGestacion: {},
          sifilisGestacionalCongenitaInfeccionTransmisionSexual: {},
          hipertensionInducidaGestacionEdadSi: {},
          hipertensionInducidaGestacionGenero: {},
          hipertensionInducidaGestacionGestacion: {},
          hipotiroidismoCongenitoEdadSi: {},
          hipotiroidismoCongenitoResultadoTSH: {},
          hipotiroidismoCongenitoTratamiento: {},
          sintomaticoRespiratorioBaciloscopiaDiagnostico: {},
          lepraTratamientoLepra: {},
          obsidadDesnutricionProteicoEdadSi: {},
          obsidadDesnutricionProteicoFechaDiagnostico: {},
          obsidadDesnutricionProteicoPeso: {},
          obsidadDesnutricionProteicoTall: {},
          victimaMaltratoEdadMenorSiMenor: {},
          victimaMaltratoMujerVictima: {},
          victimaMaltratoFechaConsultaMujerOMenorVictima: {},
          victimaMaltratoEdadGeneral: {},
          victimaViolenciaSexualFechaConsulta: {},
          enfermedadMentalDiagnostico: {},
          canverCervixGenero: {},
          pesoKilogramos: {},
          pesoFechaConsulta: {},
          tallaCentimetros: {},
          tallaFechaConsulta: {},
          fechaProbablePartoGenero: {},
          fechaProbablePartoGestacion: {},
          fechaProbablePartoEdad: {},
          edadGestacionalNacerDiferenteCero: {},
          edadGestacionalNacerEdad: {},
          edadGestacionalNacer43Semanas: {},
          bcgEdadDiferenteNoAplica: {},
          bcgMenor6Anios: {},
          bcgMayor6Anios: {},
          hepatitisBMenorNoAplica: {},
          hepatitisBMenor1AnioMenor6Anios: {},
          hepatitisBMenor1AnioMayor6Anios: {},
          pentavalenteMenor1AnioNoAplica: {},
          pentavalenteMenor1AnioMenor6Anios: {},
          pentavalenteMenor1AnioMayor6Anios: {},
          pentavalenteMenor4Meses: {},
          pentavalenteMenor6Meses: {},
          polioMenor1AnioNoAplica: {},
          polioMenor1AnioMenor6Anios: {},
          polioMenor1AnioMayor6Anios: {},
          polioMenor1AnioMeses: {},
          DPTMenor5AnioNoAplica: {},
          DPTMenor5AnioMenor6Anios: {},
          DPTMenor5AnioMayor6Anios: {},
          DPTMenor5AnioMeses: {},
          rotavirusNoAplica: {},
          rotavirusMenor6Anios: {},
          rotavirusMeses: {},
          neumococoNoAplica: {},
          neumococoMayor6Anios: {},
          neumococoMenor6Anios: {},
          neumococoMeses: {},
          influenzaNiniosNoAplica: {},
          influenzaNiniosMayor6Anios: {},
          influenzaNiniosMenor6Anios: {},
          influenzaNiniosMeses: {},
          fiebreAmarilla1AnioNiniosNoAplica: {},
          fiebreAmarilla1AnioNiniosMayor6Anios: {},
          fiebreAmarilla1AnioNiniosMenor6Anios: {},
          fiebreAmarilla1AnioNiniosMeses: {},
          hepatitisANoAplica: {},
          hepatitisAMayor6Anios: {},
          hepatitisAMenor6Anios: {},
          hepatitisAMeses: {},
          tripleViralNiniosNoAplica: {},
          tripleViralNiniosMayor6Anios: {},
          tripleViralNiniosMenor6Anios: {},
          tripleViralNiniosMeses: {},
          VPhMayor9Anios: {},
          VPhGenero: {},
          TdOTtGenero: {},
          TdOTtMayor9Anios: {},
          placaBacterianaEdad: {},
          edadFechaAtencionPartoCesaria: {},
          fechaPartoGenero: {},
          fechaAtencionPartoMenorFechaSalida: {},
          fechaAtencionPartoNullFechaSalida: {},
          fechaPartoGestacion: {},
          fechaAtencionPartoVariablesExtras: {},
          edadFechaSalidaAtencionPartoCesaria: {},
          fechaSalidaPartoGenero: {},
          fechaSalidaPartoGestacion: {},
          fechaSalidaPartoMayorFechaAtencionParto: {},
          fechaSalidaPartoNullFechaAtencionParto: {},
          fechaSalidaAtencionPartoVariablesExtras: {},
          edadFechaConsejeriaLactanciaMaternaDiferente1885: {},
          fechaConsejeriaLactanciaMaternaGenero: {},
          edadFechaControlRecienNacidoDiferente1885: {},
          edadFechaPlanificacionFamiliarPrimeraVez: {},
          suministroMetodoAnticonseptivoFechaSuministro: {},
          suministroMetodoAnticonseptivoEdad: {},
          fechaSuministroMetodoAnticonceptivoSMA: {},
          fechaSuministroMetodoAnticonceptivoPFPV: {},
          controlPrenatalPrimeraVezEdad: {},
          controlPrenatalPrimeraVezGenero: {},
          controlPrenatalPrimeraVezGestacion: {},
          controlPrenatalPrimeraVezMenorUCP: {},
          edadControlPrenatalPrimeraVez: {},
          controlPrenatalEdad: {},
          controlPrenatalControles: {},
          edadControlPrenatal: {},
          controlPrenatalGenero: {},
          controlPrenatalGestacion: {},
          fechaUltimoControlPrenatalEdad: {},
          fechaUltimoControlPrenatalControlPrenatalPrimeraVez: {},
          edadFechaUltimoControlPrenatal: {},
          ultimoControlPrenatalGenero: {},
          ultimoControlPrenatalGestacion: {},
          suministroAcidoFolicoUltimoControlPrenatalEdad: {},
          edadSuministroAcidoFolicoUltimoControlPrenatal: {},
          suministroAcidoFolicoUCPGenero: {},
          suministroAcidoFolicoUcpGestacion: {},
          sulfatoFerrosoUltimoControlPrenatalEdad: {},
          edadSulfatoFerrosoUltimoControlPrenatal: {},
          suministroSulfatoFerrosoUCPGenero: {},
          suministroSulfatoFerrosoUcpGestacion: {},
          carbonatoCalcioUltimoControlPrenatalEdad: {},
          edadCarbonatoCalcioUltimoControlPrenatal: {},
          suministroCarbonatoCalcioUCPGenero: {},
          suministroCarbonatoCalcioGestacion: {},
          fechaDiagnosticoDesnutricionProteicoCaloricaObesidad: {},
          fechaDiagnosticoDesnutricionProteicoCaloricaPeso: {},
          fechaDiagnosticoDesnutricionProteicoCaloricaTalla: {},
          fechaConsultaMujerMenorVictimaMaltrato: {},
          fechaConsultaVictimaViolenciaSexual: {},
          edadFechaConsultaCrecimientoDesarrolloPrimeraVez: {},
          edadFechaSuministroSulfatoFerrosoUltimaConsultaMenor10Anios: {},
          suministroSulfatoFerrosoUltimaConsultaMenor10AniosEdad: {},
          edadFechaSuministroVitaminaAUltimaConsultaMenor10AniosDiferente0: {},
          suministroVitaminaAUltimaConsultaMenor10AniosEdad: {},
          consultaJovenPrimeraVezEdad: {},
          edadConsultaJovenPrimeraVez: {},
          consultaAdultoPrimeraVezEdad: {},
          edadConsultaAdultoPrimeraVez: {},
          consultaAdultoPrimeraVezRangosEdad: {},
          preservativosEntregadosPacConITS: {},
          asesoriaPreTestElisaVIHMenorPostAsesoriaMismoTest: {},
          asesoriaPreTestElisaVIHMenorFechaTomaTest: {},
          asesoriaPostTestMayorPreAsesoriaMismoTest: {},
          asesoriaPostTestElisaVIHMayorFechaTomaTest: {},
          diagnosticoPacienteEnfermedadMental: {},
          fechaAntigenoSuperficieHepatitisBGestantesEdad: {},
          fechaAntigenoSuperficieHepatitisBGestantesResultado: {},
          fechaAntigenoSuperficieHepatitisBGestantesGenero: {},
          fechaAntigenoSuperficieHepatitisBGestantesGestacion: {},
          resultadoAntigenoSuperficieHepatitisBGestantesEdad: {},
          resultadoAntigenoSuperficieHepatitisBGestantesFechaAntigeno: {},
          resultadoAntigenoSuperficieHepatitisBGestantesGenero: {},
          resultadoAntigenoSuperficieHepatitisBGestantesGestacion: {},
          fechaSerologiaSifilisResultadoSerologiaSifilis: {},
          resultadoSerologiaSifilisFechaSerologiaSifilis: {},
          fechaTomaElisaVIHResultadoElisaVIH: {},
          fechaTomaElisaVIHMayorFechaAsesoriaPreTest: {},
          fechaTomaElisaVIHMayorFechaAsesoriaPostTest: {},
          resultadoElisaVIHFechaTomaTest: {},
          fechaTSHNeonatalResultadoTest: {},
          resultadoTSHNeonatalFechaTomaTest: {},
          fechaTamizajeCancerCuelloUterinoDiferenteCero: {},
          tamizajeCancerCuelloUterinoGenero: {},
          edadFechaCitologiaCervicoUterina1845: {},
          citologiaCervicoUterinaGenero: {},
          fechaCalidadMuestraCitologiaCervicouterinaBethesdaDiferenteCero: {},
          resultadoCitologiaCervicoUterinaFechaCitologia: {},
          citologiaCervicoUterinaSegunBethesdaGenero: {},
          fechaCalidadMuestraCitologiaCervicouterinaDiferenteCero: {},
          calidadMuestraCitologiaCervicouterinaResultados: {},
          calidadMuestraCitologiaCervicoUterinaGenero: {},
          fechaCodigoHabilitacionIpsCitologiaCervicouterina: {},
          codigoIpsTomaCitologiaCervicoUterinaGenero: {},
          codigoIpsTomaCitologiaCervicoUterinaMuestraTest: {},
          edadFechaColposcopia: {},
          fechacolposcopiaGenero: {},
          fechacolposcopiaCodigoIpsTomaTest: {},
          codigoHabilitacionIPSColposcopiaDB: {},
          fechaCodigoHabilitacionIPSColposcopia: {},
          codigoIpsTomaColposcopiaGenero: {},
          codigoIpsTomaColposcopiaFechaColposcopia: {},
          edadFechaBiopsiaCervical: {},
          fechaBiopsiaCervicalGenero: {},
          fechaBiopsiaCervicalResultadoTest: {},
          fechaResultadoBiopsiaCervical: {},
          resultadoBiopsiaCervicalGenero: {},
          resultadoBiopsiaCervicalFechaTest: {},
          codigoHabilitacionIpsBiopsiaCervicalDB: {},
          fechaCodigoHabilitacionIpsBiopsiaCervical: {},
          codigoIpsTomaBiopsiaCervicalGenero: {},
          codigoIpsTomaBiopsiaCervicalResultadoTest: {},
          fechaMamografiaGenero: {},
          fechaMamografia: {},
          fechaMamografiaEdad: {},
          fechaResultadoMamografia: {},
          resultadoMamografiaGenero: {},
          resultadoMamografiaFechaEdad: {},
          resultadoMamografiaFechaTest: {},
          codigoHabilitacionIPSTomaMamografiaDB: {},
          fechaCodigoHabilitacionIPSTomaMamografia: {},
          codigoIpsTomaMamografiaGenero: {},
          codigoIpsTomaMamografiaEdad: {},
          codigoIpsTomaMamografiaResultadoTest: {},
          fechatomaBiopsiaSenoBACAFGenero: {},
          fechaResultadoBiopsiaSenoGenero: {},
          fechaResultadoBiopsiaSenoResultado: {},
          fechaResultadoBiopsiaSenoMayorFechaTomaTest: {},
          fResultadoBiopsiaSenoGenero: {},
          resultadoBiopsiaSenoResultadoTest: {},
          codigoIpsTomaBiopsiaSenoDB: {},
          codigoIpsTomaBiopsiaSenoGenero: {},
          codigoIpsTomaBiopsiaSenoResultadoTomaTest: {},
          hemoglobina: {},
          fechaCreatininaCreatinina: {},
          creatininaFechaCreatinina: {},
          creatinina: {},
          fechaHemoglobinaGlicosilada: {},
          hemoglobinaGlicosilada: {},
          hemoglobinaGlicosiladaFechaHemoglobinaGlicosilada: {},
          fechaTomaBaciloscopiaDiagnosticoResultadoTest: {},
          resultadoBaciloscopiaDiagnosticoFechaTest: {},
          tratamientoParaHipotiroidismoCongenitoHipotiroidismo: {},
          tratamientoSifilisGestacionalGenero: {},
          tratamientoSifilisGestacionalGestacion: {},
          tratamientoSifilisGestacionalCongenitaOGestacional: {},
          tratamientoSifilisGestacionalEdad: {},
          tratamientoSifilisCongenitaGestacionalCongenita: {},
          codigoIpsTomaCitologiaCervicoUterinaDB: {}
        }
      }
    },
    methods: {
      getReglasValidacion () {
        this.axios.get('rs_reglas_validacion_4505')
          .then(response => {
            console.log(response.data)
            this.reglas = response.data.reglas
            this.ayudas = response.data.ayudas
          })
          .catch(e => {
            console.log(e.response)
            this.$store.commit('SNACK_SHOW', {msg: e.response.message, color: 'error'})
          })
      },
      submit () {
        this.axios.post('rs_reglas_validacion_4505', this.reglas)
          .then(response => {
            if (response.data.state === 'save') {
              this.$store.commit('SNACK_SHOW', {msg: 'Reglas de validación configuradas correctamente', color: 'success'})
              this.reglas = response.data.reglas
            } else {
              this.$store.commit('SNACK_SHOW', {msg: 'No se pueden configurar las reglas de validación.', color: 'error'})
            }
          })
          .catch(e => {
            console.log(e.response)
            this.$store.commit('SNACK_SHOW', {msg: e.response.message, color: 'error'})
          })
      },
      mostrarAyuda (data) {
        this.dialogHelp = true
        this.help = data
      }
    },
    mounted () {
      this.getReglasValidacion()
    },
    computed: {
      color () {
        switch (this.bottomNav) {
          case 0: return 'blue-grey'
          case 1: return 'teal'
          case 2: return 'red'
        }
      }
    }
  }
</script>