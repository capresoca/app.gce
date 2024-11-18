<template>  
  <div>
    <v-card ref="loaderRef" v-show="etapa1">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ ordenEdit ? 'Edicion' : 'Gestión de incapacidad' }} </v-toolbar-title>
      </v-toolbar>
      <v-card-text class="no-padding">
      <!-- start -->
        <v-container fluid grid-list-xl >
          <v-card>
            <v-layout>
              <v-flex>
                <div class="text-xs-center">
                  <h1 >{{parametros.afiliado.emoticon_nombre_completo}}</h1>
                  <h4>{{parametros.afiliado.identificacion}}</h4>
                </div>
              </v-flex>
            </v-layout>
            <v-card>
              <v-card-title class="grey lighten-3 elevation-0">
                <span class="title">Solicitud</span>
              </v-card-title>
              <!-- formulario de creacion-->
              <v-form data-vv-scope="formTipo">
                <v-container fluid grid-list-md>
                  <v-layout row wrap>
                    <v-flex xs12>
                      <v-select
                        item-text="nombre"
                        item-value="value"
                        label="Tipo incapacidad"
                        :items="tipoIncapacidad"
                        name="tipo"
                        v-validate="'required'"
                        :error-messages="errors.collect('tipo')"
                        v-model="incapacidad.tipo"
                      ></v-select>
                    </v-flex>

                    <v-flex xs12>
                      <v-autocomplete
                        label="Tutela"
                        v-model="incapacidad.tutela"
                        :items="complementosTutelas"
                        item-value="id"
                        item-text="no_tutela"
                        :filter="filterTutelas"
                      >
                        <template slot="item" slot-scope="data">
                          <template>
                            <v-list-tile-content>
                              <v-list-tile-title v-html="data.item.no_tutela"/>
                            </v-list-tile-content>
                          </template>
                        </template>
                      </v-autocomplete>
                    </v-flex>

                    <v-flex xs12>
                      <v-menu
                        ref="menuDateFecha"
                        :close-on-content-click="false"
                        v-model="menuDateFecha"
                        :nudge-right="40"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        max-width="290px"
                        min-width="290px"
                      >
                        <v-text-field
                          ref="prueba"
                          slot="activator"
                          v-model="computedDateFormattedFecha"
                          label="Fecha inicio"
                          prepend-icon="event"
                          readonly
                          name="fecha"
                          v-validate="'required|date_format:YYYY/MM/DD'"
                          :error-messages="errors.collect('fecha')"
                        ></v-text-field>
                        <v-date-picker
                          v-model="incapacidad.fecha"
                          @input="menuDateFecha = false"
                          @change="() => {
                            let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                            $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                          }"
                          locale='es'
                          no-title
                        ></v-date-picker>
                      </v-menu>             
                    </v-flex>

                    <v-flex xs12 v-show="false">
                      <v-menu
                        ref="menuDateFechaParto"
                        :close-on-content-click="false"
                        v-model="menuDateFechaParto"
                        :nudge-right="40"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        max-width="290px"
                        min-width="290px"
                      >
                        <v-text-field
                          ref="prueba"
                          slot="activator"
                          v-model="computedDateFormattedFechaParto"
                          label="Fecha parto"
                          prepend-icon="event"
                          readonly
                          name="fecha"
                          v-validate="'date_format:YYYY/MM/DD'"
                          :error-messages="errors.collect('fecha')"
                        ></v-text-field>
                        <v-date-picker
                          v-model="incapacidad.fecha_parto"
                          @input="menuDateFechaParto = false"
                          @change="() => {
                            let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                            $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                          }"
                          locale='es'
                          no-title
                        ></v-date-picker>
                      </v-menu>             
                    </v-flex>

                  </v-layout>
                </v-container>
              </v-form>
            </v-card>
          </v-card>
        </v-container>

        <!-- <v-card flat v-show="!mostrarTabla"> -->
        <v-card flat >
          <v-card >
            <v-card-text>
              <v-toolbar dense short>
                Relaciones Laborales
              </v-toolbar>
              <v-data-table
                :headers="headers"
                :items="dataDetalles.detalle"
                :loading="tableLoading"
                :total-items="dataDetalles.length"
                hide-actions
                class="elevation-1">
                <template slot="items" slot-scope="props">
                  <td>
                    <v-flex>
                      <v-layout column justify-space-between>
                        <v-checkbox
                          :id="`${props.item.id}`"
                          @click.native="agregarFechasInicio(props.item)"
                          style="margin: 20% 0 0 20%;"
                        ></v-checkbox>
                      </v-layout>
                    </v-flex>
                  </td>
                  <td>{{ props.item.idaportante}}</td>
                  <td>{{ props.item.aportante}}</td>
                  <td>{{ props.item.fecha_vinculacion}}</td>
                  <td>
                    <span v-if="props.item.fecha_fin_vinculacion">{{ props.item.fecha_fin_vinculacion}}</span>
                    <span v-else> N/A </span>
                  </td>
                </template>
                <template slot="no-data">
                  <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                    No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                  </v-alert>
                  <v-alert v-else :value="true" color="info" icon="info">
                    Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                  </v-alert>
                </template>
              </v-data-table>
              <span v-for="ale in alerta" :key="ale.id">
                <v-alert :value="true" color="error" icon="warning" v-show="veralerta">
                  <span>{{ale}}</span> <v-icon @click="veralerta = false">highlight_off</v-icon>
                </v-alert>
              </span>
            </v-card-text>
          </v-card>
        </v-card>
      <!-- end --> 
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="validar(incapacidad, dataDetalles.detalle, fechasInicio, idAportantes, identificacionAportantes)" :loading="loadingSubmit" >Validar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>

    <v-card ref="loaderRef" v-show="etapa2">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ ordenEdit ? 'Edicion' : 'Gestión de incapacidad' }} </v-toolbar-title>
      </v-toolbar>
      <v-container fluid grid-list-xl >
        <v-layout>
          <v-flex>
            <div class="text-xs-center">
              <h1 >{{parametros.afiliado.emoticon_nombre_completo}}</h1>
              <h4>{{parametros.afiliado.identificacion}}</h4>
            </div>
          </v-flex>
        </v-layout>

        <v-container flat>
          <v-toolbar dense short>
              Soportes {{incapacidad.tipo | tipoincapacidad(tipoIncapacidad)}}
          </v-toolbar>
          <v-data-table
            :headers="headersTablaSoporte"
            :items="dataSoporte.soporte"
            :loading="tableLoading"
            :total-items="dataSoporte.soporte.length"
            hide-actions
            class="elevation-1">
            <template slot="items" slot-scope="props">
              <td>{{ props.item.descripcion }}</td>
              <td>{{ props.item.sw_obligatorio | dicotomia }}</td>
              <td>{{ props.item.observacion }}</td>
              <td id="tderr" v-show="props.item.sw_obligatorio == 1">
                <input-file
                  label="Adjuntar archivo"
                  v-model="incapacidad.archivo"
                  :file-name="incapacidad.archivo ? incapacidad.archivo.nombre : null"
                  accept=""
                  :hint="''"
                  class="mb-3"
                  prepend-icon="description"
                  @change.native="gestionarArchivos(incapacidad.archivo, props.index, props.item.descripcion)"
                />
              </td>
              <td v-show="props.item.sw_obligatorio == 2">
                <input-file
                  ref="archivoAdjunto"
                  label="Adjuntar archivo 4ddsfs"
                  v-model="incapacidad.archivo"
                  :file-name="incapacidad.archivo ? incapacidad.archivo.nombre : null"
                  accept=""
                  :hint="''"
                  class="mb-3"
                  prepend-icon="description"
                  @change.native="gestionarArchivos(incapacidad.archivo, props.index, props.item.descripcion)"
                />
              </td>
            </template>
            <template slot="no-data">
              <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
              </v-alert>
              <v-alert v-else :value="true" color="info" icon="info">
                Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
              </v-alert>
            </template>
          </v-data-table>
          <v-alert :value="true" color="error" icon="warning" v-show="veralertaarchivos" ref="alertarchivos">
            Por favor cargue los archivos que sean obligatorios <v-icon @click="veralertaarchivos = false">highlight_off</v-icon>
          </v-alert>
        </v-container>

        <!-- <div style="width: 50%;"> -->
        <div v-show="mostrarFormularios">

          <v-container>
            <v-toolbar dense short>
              Relaciones Laborales
            </v-toolbar>
            <v-data-table
              :headers="headers2"
              :items="dataDetalles.detalle"
              :loading="tableLoading"
              :total-items="dataDetalles.length"
              hide-actions
              class="elevation-1">
              <template slot="items" slot-scope="props">
                <!-- <tr v-show="props.item.id == idAportantes[props.index]"> -->
                  <td>{{ props.item.idaportante}}</td>
                  <td>{{ props.item.aportante}}</td>
                  <!-- <td>{{ idAportantes[props.index]}} {{props.item.id}} </td> -->
                <!-- </tr> -->
              </template>
              <template slot="no-data">
                <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                  No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                </v-alert>
                <v-alert v-else :value="true" color="info" icon="info">
                  Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                </v-alert>
              </template>
            </v-data-table>
          </v-container>

        </div>

        <v-layout row wrap v-show="mostrarFormularios">
          <v-flex xs12 sm6 align="center"> 
            <v-form data-vv-scope="formRadicar">
            <v-toolbar dense short>
              Consulta de Salud
            </v-toolbar>
            <v-card>
              <v-card-text>
                <v-layout row wrap>
                    <v-flex xs12>
                      <v-autocomplete
                        label="Prestador atiende"
                        v-model="incapacidad.prestador"
                        :items="complementosIncapacidades.entidades"
                        item-value="id"
                        item-text="nombre"
                        name="prestador atiende"
                        required
                        v-validate="'required'"
                        :error-messages="errors.collect('prestador atiende')"
                        :filter="filterPrestador"> 

                        <template slot="item" slot-scope="data">
                          <template>
                            <v-list-tile-content>
                              <v-list-tile-title v-html="data.item.nombre"/>
                            </v-list-tile-content>
                          </template>
                        </template>
                      </v-autocomplete>

                      <v-autocomplete
                        label="Registro Profesional"
                        v-model="incapacidad.registro_profesional"
                        :items="complementosIncapacidades.medicosolicitante"
                        item-value="id"
                        item-text="codigo"
                        name="registro profesional"
                        required
                        v-validate="'required'"
                        :error-messages="errors.collect('registro profesional')"
                        :filter="filterRegistro"> 

                        <template slot="item" slot-scope="data">
                          <template>
                            <v-list-tile-content>
                              <v-list-tile-title v-html="data.item.codigo"/>
                            </v-list-tile-content>
                          </template>
                        </template>
                      </v-autocomplete>
                      <v-text-field v-model="incapacidad.nombre_profesional"
                                    label="Nombre Profesional" key="nombre profesional"
                                    name="nombre profesional"
                                    v-validate="'max:50'"
                                    :error-messages="errors.collect('nombre profesional')" readonly></v-text-field>
                      <v-text-field v-model="incapacidad.cargo" v-show="false"
                                    label="Cargo o Actividad" key="cargo"
                                    name="cargo"
                                    v-validate="'max:50'"
                                    :error-messages="errors.collect('cargo')" ></v-text-field>
                      <v-autocomplete
                        label="Diagnóstico Principal"
                        v-model="incapacidad.diagnostico_principal"
                        :items="complementosIncapacidades.cie10"
                        item-value="id"
                        item-text="descripcion"
                        name="diagnóstico principal"
                        required
                        v-validate="'required'"
                        :error-messages="errors.collect('diagnóstico principal')"
                        :filter="filterDiagnostico"> 

                        <template slot="item" slot-scope="data">
                          <template>
                            <v-list-tile-content>
                              <v-list-tile-title v-html="data.item.descripcion"/>
                            </v-list-tile-content>
                          </template>
                        </template>
                      </v-autocomplete>
                    </v-flex>
                </v-layout>
              </v-card-text>
            </v-card>
            </v-form>
          </v-flex>
          <v-flex xs12 sm6> 
            <v-form data-vv-scope="formRadicar">
            <v-toolbar dense short>
              Trasncripción Incapacidad
            </v-toolbar>
            <v-card>
              <v-card-text>
                <v-layout row wrap>
                    <v-flex xs12>
                      <v-text-field v-model="incapacidad.tipoS"
                                    label="Tipo Incapacidad" readonly></v-text-field>
                      <v-text-field v-model="incapacidad.fecha_incio_transcripcion"
                                    label="Fecha Inicio" readonly></v-text-field>
                      <v-menu
                        ref="menuDateFechaFinTranscripcion"
                        :close-on-content-click="false"
                        v-model="menuDateFechaFinTranscripcion"
                        :nudge-right="40"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        max-width="290px"
                        min-width="290px"
                      >
                        <v-text-field
                          ref="prueba"
                          slot="activator"
                          v-model="computedDateFormattedFechaFinTranscrip"
                          label="Fecha Fin"
                          prepend-icon="event"
                          readonly
                          name="fecha"
                          v-validate="'required|date_format:YYYY/MM/DD'"
                          :error-messages="errors.collect('fecha')"
                        ></v-text-field>
                        <v-date-picker
                          v-model="incapacidad.fecha_fin"
                          @input="menuDateFechaFinTranscripcion = false"
                          @change="() => {
                            let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                            $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                          }"
                          locale='es'
                          no-title
                        ></v-date-picker>
                      </v-menu>
                      <v-menu
                        ref="menuDateFechaExpedicionTranscripcion"
                        :close-on-content-click="false"
                        v-model="menuDateFechaExpedicionTranscripcion"
                        :nudge-right="40"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        max-width="290px"
                        min-width="290px"
                      >
                        <v-text-field
                          ref="prueba"
                          slot="activator"
                          v-model="computedDateFormattedFechaExpedicion"
                          label="Fecha Expedición"
                          prepend-icon="event"
                          readonly
                          name="fecha"
                          v-validate="'required|date_format:YYYY/MM/DD'"
                          :error-messages="errors.collect('fecha')"
                        ></v-text-field>
                        <v-date-picker
                          v-model="incapacidad.fecha_expedicion"
                          @input="menuDateFechaExpedicionTranscripcion = false"
                          @change="() => {
                            let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                            $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                          }"
                          locale='es'
                          no-title
                        ></v-date-picker>
                      </v-menu> 
                      <v-text-field v-model="incapacidad.dias"
                                    label="Número días incapacidad" readonly></v-text-field>
                      <v-text-field v-model="incapacidad.fecha_inicio_liquida"
                                    label="Fecha Inicio Liquida" readonly></v-text-field>
                      <v-text-field v-model="incapacidad.fecha_fin_liquida"
                                    label="Fecha Fin Liquida" readonly></v-text-field>
                      <v-text-field label="Días Liquida" readonly></v-text-field>

                    </v-flex>
                </v-layout>
              </v-card-text>
            </v-card>
            </v-form>
          </v-flex>
        </v-layout>
      </v-container>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 class="text-xs-right">
            <v-btn @click="anterior()"><v-icon left>reply</v-icon>Anterior</v-btn>
            <v-btn color="primary" @click.prevent="radicar ()" :loading="loadingSubmit" >Radicar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>

  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  // import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import moment from 'moment'
  export default {
    name: 'RegistroIncapacidad',
    components: {
      InputFile: () => import('@/components/general/InputFile')
    },
    props: ['parametros'],
    data () {
      return {
        payload: null,
        alerta: {},
        checkbox: false,
        veralerta: false,
        veralertaarchivos: false,
        fechasInicio: [],
        idAportantes: [],
        identificacionAportantes: [],
        dataSoporte: { soporte: [] },
        etapa1: true,
        etapa2: false,
        mostrarFormularios: false,
        dialog: false,
        tableLoading: false,
        arrayTutela: [],
        dataDetalles: { detalle: [] },
        // arrayTutela: {no_tutela: '', id: ''},
        headers: [
          {
            text: '',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Identificación Aportante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Aportante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Fecha Inicio',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Fecha Fin',
            align: 'left',
            sortable: false,
            value: ''
          }
          // {
          //   text: 'Opciones',
          //   align: 'left',
          //   sortable: false,
          //   value: 'id'
          // }
        ],
        headers2: [
          {
            text: 'Identificación Aportante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Aportante',
            align: 'left',
            sortable: false,
            value: ''
          }
        ],
        headersTablaSoporte: [
          {
            text: 'Tipo soporte',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Obligatorio',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Observación',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Tipos de archivos',
            align: 'left',
            sortable: false,
            value: ''
          }
          // {
          //   text: 'Opciones',
          //   align: 'left',
          //   sortable: false,
          //   value: ''
          // }
        ],
        tipoIncapacidad: [
          {
            value: '2',
            nombre: 'Incapacidad enfermedad general'
          },
          {
            value: '4',
            nombre: 'Incapacidad por accidente de trabajo'
          },
          {
            value: '5',
            nombre: 'Incapacidad por accidente de tránsito'
          },
          {
            value: '7',
            nombre: 'Licencia de maternidad'
          },
          {
            value: '8',
            nombre: 'Licencia de paternidad'
          },
          {
            value: '17',
            nombre: 'Licencia fallecimiento madre'
          }
        ],
        tabs: null,
        menuDateFecha: false,
        menuDateFechaParto: false,
        menuDateFechaFinTranscripcion: false,
        menuDateFechaExpedicionTranscripcion: false,
        incapacidad: {},
        archivo: [],
        descripcionSoporte: [],
        mostrar: true,
        mostrarDocs: true,
        filterTutelas (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.no_tutela)
          const query = hasValue(queryText)
          return text.toString().indexOf(query) > -1
        },
        buscandoCodigo: false,
        stepActual: 1,
        fechaActual: '2018-01-01',
        tabActive: null,
        ordenEdit: false,
        loadingSubmit: false,
        motivosRetiro: [],
        cargos: [],
        gradosProfesional: [],
        fondosDeSalud: [],
        fondosDeCesantia: [],
        filterPrestador (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterRegistro (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codigo)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterDiagnostico (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.descripcion)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        }
      }
    },
    mounted () {
      if (this.parametros.afiliado !== null) this.getRegistro(this.parametros.afiliado.id)
      // console.log(this.parametros)
    },
    created () {
      this.reloadPage()
    },
    watch: {
      'incapacidad.fecha_fin' () {
        var inicio = moment(this.incapacidad.fecha)
        var fin = moment(this.incapacidad.fecha_fin)
        // console.log('inicio', this.incapacidad.fecha)
        // console.log('fin', this.incapacidad.fecha_fin)
        this.incapacidad.dias = fin.diff(inicio, 'days') + 1
      },
      'incapacidad.registro_profesional' () {
        // console.log(this.incapacidad.registro_profesional)
        for (const key in store.getters.complementosIncapacidades.medicosolicitante) {
          if (store.getters.complementosIncapacidades.medicosolicitante.hasOwnProperty(key)) {
            const element = store.getters.complementosIncapacidades.medicosolicitante[key]
            // console.log(element.codigo + ' ' + element.id + ' ' + element.descripcion)
            if (element.id === this.incapacidad.registro_profesional) {
              this.incapacidad.nombre_profesional = element.descripcion
            }
          }
        }
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosTercerosFormComplementarios
      },
      complementosIncapacidades () {
        // console.log('el complemento', JSON.parse(JSON.stringify(store.getters.complementosIncapacidades)))
        return JSON.parse(JSON.stringify(store.getters.complementosIncapacidades))
      },
      computedDateFormattedFecha () {
        return this.formDate(this.incapacidad.fecha)
      },
      computedDateFormattedFechaParto () {
        return this.formDate(this.incapacidad.fecha_parto)
      },
      computedDateFormattedFechaFinTranscrip () {
        return this.formDate(this.incapacidad.fecha_fin)
      },
      computedDateFormattedFechaExpedicion () {
        return this.formDate(this.incapacidad.fecha_expedicion)
      },
      complementosTutelas () {
        var regex = /(\d+)/g
        var documento = this.parametros.afiliado.identificacion.match(regex)
        for (let i = 0; i < store.getters.complementosTutelasIncapacidad.tutelas.length; i++) {
          const element = store.getters.complementosTutelasIncapacidad.tutelas[i]
          if (element.identificacion === documento.toString()) {
            this.arrayTutela.push({'id': element.id, 'no_tutela': element.no_tutela})
          }
        }
        return this.arrayTutela
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.incapacidad.consecutivo_afiliado = this.parametros.afiliado.id
        this.axios.get('incapacidadafiliadopagador?id=' + this.parametros.afiliado.id)
          .then((response) => {
            this.dataDetalles.detalle = response.data
            this.tableLoading = false
            this.localLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            return false
          })
      },
      addFormData () {
        let data = new FormData()
        data.append('consecutivo_afiliado', this.incapacidad.consecutivo_afiliado)
        data.append('tipo', this.incapacidad.tipo)
        data.append('fecha', this.incapacidad.fecha)
        data.append('fechas_aportantes', this.incapacidad.fechas_aportantes)

        for (var i = 0; i < this.incapacidad.ids_aportantes.length; i++) {
          data.append('ids_aportantes[]', this.incapacidad.ids_aportantes[i])
        }

        for (var j = 0; j < this.archivo.length; j++) {
          data.append('archivos[]', this.archivo[j])
        }

        for (var k = 0; k < this.archivo.length; k++) {
          data.append('descripcionSoporte[]', this.descripcionSoporte[k])
        }

        // data.append('ids_aportantes', this.incapacidad.ids_aportantes)
        data.append('identificaciones_aportantes', this.incapacidad.identificaciones_aportantes)
        data.append('nombre_profesional', this.incapacidad.nombre_profesional)
        data.append('tipoS', this.incapacidad.tipoS)
        data.append('fecha_incio_transcripcion', this.incapacidad.fecha_incio_transcripcion)
        data.append('prestador', this.incapacidad.prestador)
        data.append('registro_profesional', this.incapacidad.registro_profesional)
        data.append('diagnostico_principal', this.incapacidad.diagnostico_principal)
        data.append('fecha_fin', this.incapacidad.fecha_fin)
        data.append('dias', this.incapacidad.dias)
        data.append('fecha_expedicion', this.incapacidad.fecha_expedicion)
        data.append('tutela', this.incapacidad.tutela)
        this.payload = data
        // Object.assign(this.payload, data)
      },
      closeTable () {
        this.dialog = false
      },
      getRegistro (id) {
        //
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      editarDetalle (item, indice, incapacidad) {
        this.validar(incapacidad, item)
      },
      agregarFechasInicio (item) {
        let elt = document.getElementById(item.id)
        if (elt.checked) {
          this.fechasInicio.push(item.fecha_vinculacion)
          this.idAportantes.push(item.id)
          this.identificacionAportantes.push(item.idaportante)
        } else {
          const indexFecha = this.fechasInicio.indexOf(item.fecha_vinculacion)
          const indexId = this.idAportantes.indexOf(item.id)
          const indexIdentificacion = this.identificacionAportantes.indexOf(item.idaportante)
          if (indexFecha > -1 && indexId > -1 && indexIdentificacion > -1) {
            this.fechasInicio.splice(indexFecha, 1)
            this.idAportantes.splice(indexId, 1)
            this.identificacionAportantes.splice(indexIdentificacion, 1)
          }
        }
      },
      async validar (incapacidad, itemcomparar, arrayFechaAportante, arrayIdAportante, arrayIdentificaciones) {
        this.veralerta = false
        this.loadingSubmit = true
        this.incapacidad.fechas_aportantes = arrayFechaAportante
        this.incapacidad.ids_aportantes = arrayIdAportante
        this.incapacidad.identificaciones_aportantes = arrayIdentificaciones
        if (await this.validador('formTipo')) {
          if (!incapacidad.fecha) {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Debe registrar información de la fecha de incio', color: 'danger'})
          } else if (arrayFechaAportante.length === 0) {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Debe seleccionar por lo menos una relación laboral', color: 'danger'})
          } else {
            var f1 = new Date(incapacidad.fecha)
            for (let i = 0; i < arrayFechaAportante.length; i++) {
              var f2 = new Date(arrayFechaAportante[i])
              if (f1 < f2) {
                this.loadingSubmit = false
                this.$store.commit(SNACK_SHOW, {msg: 'Fecha Inicio debe ser mayor o igual a las fecha inicio de relación laboral', color: 'danger'})
                return
              }
            }
            this.axios.post('getcounter', this.incapacidad)
              .then((response) => {
                // console.log(response)
                this.loadingSubmit = false
                if (response.data.alertas.length >= 1) {
                  this.$store.commit(SNACK_SHOW, {msg: 'Ya existe una solicitud para esta incapacidad con las mismas fechas.', color: 'danger'})
                  this.veralerta = true
                  // this.alerta = response.data.alertas
                  for (let i = 0; i < response.data.alertas.length; i++) {
                    const element = response.data.alertas[i].alerta
                    this.alerta[i] = element
                  }
                } else {
                  this.etapa1 = false
                  this.etapa2 = true
                  this.getSoportes()
                  this.tableLoading = true
                  this.localLoading = true
                }
              })
              .catch(e => {
                return false
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos requeridos no pueden estar vacios.', color: 'danger'})
        }
      },
      async radicar () {
        this.veralertaarchivos = false
        var banderaarchivos = 0
        for (let index = 0; index < this.dataSoporte.soporte.length; index++) {
          const element = this.dataSoporte.soporte[index]
          // console.log('for', element.sw_obligatorio)
          if (element.sw_obligatorio === 1) {
            banderaarchivos = 1
          }
        }
        if (banderaarchivos === 1 && this.archivo.length === 0) {
          this.veralertaarchivos = true
          document.getElementById('tderr').classList.add('tderror')
          // this.$refs.archivoAdjunto0.focus()
          this.$store.commit(SNACK_SHOW, {msg: 'Por favor verifique los archivos de los tipos de soporte.', color: 'danger'})
          return
        } else {
          document.getElementById('tderr').classList.remove('tderror')
        }
        this.loadingSubmit = true
        let validFile = await this.$refs['archivoAdjunto'].validate('formRadicar')
        // console.log(this.archivo)
        if (!this.incapacidad.tutela) this.incapacidad.tutela = ''
        this.addFormData()
        if (await this.validador('formRadicar') && validFile) {
          this.axios.post('radicar', this.payload)
            .then((response) => {
              // console.log('el response', response)
              this.loadingSubmit = false
              this.formReset()
              this.$store.commit(SNACK_SHOW, {msg: 'Incapacidad radicada correctamente.', color: 'success'})
              this.close()
            })
            .catch(e => {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              return false
            })
        } else {
          this.loadingSubmit = false
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos requeridos no pueden estar vacios.', color: 'danger'})
        }
      },
      getSoportes () {
        this.axios.get('getsoportes?idtipo=' + this.incapacidad.tipo)
          .then((response) => {
            // response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            // this.pagination = response.data.meta
            // this.profesiones = response.data.data
            for (let i = 0; i < this.tipoIncapacidad.length; i++) {
              const element = this.tipoIncapacidad[i]
              if (this.incapacidad.tipo === element.value) {
                this.incapacidad.tipoS = element.nombre
              }
            }
            this.incapacidad.fecha_incio_transcripcion = moment(this.incapacidad.fecha).format('MM/DD/YYYY')
            this.dataSoporte.soporte = response.data
            console.log(this.dataSoporte)
            this.tableLoading = false
            this.localLoading = false
            this.mostrarFormularios = true
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            return false
          })
      },
      formReset () {
        this.mostrarTablaEducacion = false
        this.empleadoID = null
        this.incapacidad.tutela = null
        this.incapacidad.tipo = null
        this.incapacidad.tutela = null
        this.incapacidad.fecha = null
        this.incapacidad.fecha_parto = null
        // this.incapacidad.archivo = null
        this.incapacidad.prestador = null
        this.incapacidad.registro_profesional = null
        this.incapacidad.nombre_profesional = null
        this.incapacidad.cargo = null
        this.incapacidad.diagnostico_principal = null
        this.incapacidad.tipoS = null
        this.incapacidad.fecha_incio_transcripcion = null
        this.incapacidad.fecha_fin = null
        this.incapacidad.fecha_expedicion = null
        this.incapacidad.dias = null
        this.incapacidad.fecha_inicio_liquida = null
        this.incapacidad.fecha_fin_liquida = null
        this.payload = null
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      anterior () {
        this.mostrarFormularios = false
        this.dataSoporte = { soporte: [] }
        this.archivo = []
        this.etapa1 = true
        this.etapa2 = false
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      gestionarArchivos (file, index, descripcionSoporte) {
        // this.archivo.push(file)
        this.archivo[index] = file
        this.descripcionSoporte[index] = descripcionSoporte
        console.log(index)
        console.log('array archivos', this.archivo)
      }
    },
    filters: {
      dicotomia: function (val) {
        if (val === 1) {
          return 'Si'
        } else {
          return 'No'
        }
      },
      tipoincapacidad: function (val, tipoIncapacidad) {
        for (let i = 0; i < tipoIncapacidad.length; i++) {
          const element = tipoIncapacidad[i]
          if (val === element.value) {
            return element.nombre
          }
        }
      }
    }
  }
</script>

<style scoped>
  .tipo {
    height: 10px;
    padding: 0;
  }
  .no-padding {
    padding: 0;
  }
  .tderror {
    background-color: #f88d76;
  }
</style>
