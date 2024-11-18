<template>
  <div>
    <v-card ref="formTutela">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12 sm12 md12 lg12>
            <v-form data-vv-scope="formTutelas">
              <v-container fluid grid-list-md>
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>person</v-icon> Accionante</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4>
                            <v-autocomplete
                              label="Tipo Documento"
                              v-model="tutela.gn_tipdocentidad_id"
                              :items="complementos.tipdocidentidades ? complementos.tipdocidentidades : []"
                              item-value="id"
                              item-text="nombre"
                              name="tipo de documento accionante"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('tipo de documento accionante')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="tutela.identificacion"
                                          label="No. Identificación" key="identificación accionante"
                                          name="identificación accionante" :maxlength="maxLength2"
                                          v-validate="'required|max:'+ maxLength2"
                                          :error-messages="errors.collect('identificación accionante')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="tutela.nombre"
                                          label="Nombre" key="nombre accionante" v-upper="'tutela.nombre'"
                                          name="nombre accionante" v-validate="'required'" required
                                          :error-messages="errors.collect('nombre accionante')" ></v-text-field>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>person</v-icon> Afiliados</v-toolbar-title>
                      </v-toolbar>
                      <v-form data-vv-scope="formTutelasAfiliado" v-if="tutela.afiliados_tutelas.length < 3">
                        <v-card-text>
                            <v-layout row wrap>
                              <v-flex xs12 sm4>
                                <v-autocomplete
                                  label="Tipo Documento"
                                  v-model="afiliado_tutela.tipo_id"
                                  @change="val => afiliado_tutela.gn_tipdocidentidad_id = val ? val.id : null"
                                  :items="complementos.tipdocidentidades ? complementos.tipdocidentidades : []"
                                  item-value="id"
                                  item-text="nombre"
                                  name="tipo de documento afiliado"
                                  return-object
                                  clearable>
                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.nombre"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                              </v-flex>

                              <v-flex xs12 sm4>
                                <v-text-field
                                  v-model="afiliado_tutela.identificacion"
                                  label="No. Identificación"
                                  :disabled="!afiliado_tutela.tipo_id ? true : false"
                                  :hint="textoBusqueda"
                                  @keyup="findByIdentificacion"
                                  :maxlength="maxLength1"
                                  :loading="custom"
                                >
                                </v-text-field>
                              </v-flex>
                              <v-flex xs12 sm4>
                                <v-text-field v-model="afiliado_tutela.nombre_completo"
                                              :disabled="!afiliado_tutela.tipo_id || nombreAfiliado"
                                              label="Nombre Completo" v-upper="'afiliado_tutela.nombre_completo'">
                                </v-text-field>
                              </v-flex>
                              <template v-if="no_afiliado && afiliado_tutela.as_afiliado_id === null">
                                <v-flex xs12 sm3>
                                  <v-text-field v-model="afiliado_tutela.nombre1"
                                                label="Primer Nombre" v-upper="'afiliado_tutela.nombre1'"
                                                name="nombre"
                                                v-validate="'required'" required
                                                :error-messages="errors.collect('nombre')">
                                  </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3>
                                  <v-text-field v-model="afiliado_tutela.nombre2"
                                                label="Segundo Nombre" v-upper="'afiliado_tutela.nombre2'">
                                  </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3>
                                  <v-text-field v-model="afiliado_tutela.apellido1"
                                                label="Primer Apellido" v-upper="'afiliado_tutela.apellido1'">
                                  </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3>
                                  <v-text-field v-model="afiliado_tutela.apellido2"
                                                label="Segundo Apellido" v-upper="'afiliado_tutela.apellido2'">
                                  </v-text-field>
                                </v-flex>
                              </template>
                              <v-flex xs12 sm12 class="text-xs-right">
                                <v-tooltip top>
                                  <v-btn small
                                         slot="activator"
                                         dark color="pink"
                                         @click="addAfiliadosTutelas(afiliado_tutela)">
                                    <span v-text="'Agregar'"></span>
                                  </v-btn>
                                  <span v-text="'Agregar Afiliado'"></span>
                                </v-tooltip>
                              </v-flex>
                        </v-layout>
                        </v-card-text>
                      </v-form>
                      <v-slide-y-transition group>
                        <v-data-table
                          v-if="tutela.afiliados_tutelas.length"
                          :items="tutela.afiliados_tutelas"
                          class="elevation-1"
                          :headers="headersAfiliados"
                          :expanded="expanded"
                          hide-actions :key="'table'">
                          <template slot="items" slot-scope="props">
                            <tr @click.prevent="(props.item.as_afiliado_id !== null) ? (props.expanded = !props.expanded) : ''"
                                :key="'tag-' + (!props.item.id ? (props.item.id = props.index) : props.index)">
                              <td class="text-xs-center">
                                <v-tooltip top v-if="props.item.as_afiliado_id">
                                  <v-icon slot="activator" v-text="'verified_user'" color="success"></v-icon>
                                  <span v-text="'Afiliado -  ' + (props.item.afiliado ? props.item.afiliado.estado_afiliado.nombre : '')"></span>
                                </v-tooltip>
                                <v-tooltip top v-else>
                                  <v-icon slot="activator" v-text="'report_problem'" color="error"></v-icon>
                                  <span v-text="'La persona no se encuentra afiliada'"></span>
                                </v-tooltip>
                              </td>
                              <td class="text-xs-center">{{ props.item.tipo_id ? props.item.tipo_id.nombre : null }}</td>
                              <td class="text-xs-center">{{ props.item.identificacion }}</td>
                              <td class="text-xs-center">{{ props.item.nombre_completo }}</td>
                              <td class="text-xs-center">
                                <v-btn icon class="mx-0" fab small @click="eliminarAfiliadoTutela(props.index)"><v-icon color="accent">delete</v-icon></v-btn>
                              </td>
                            </tr>
                          </template>
                          <template slot="expand" slot-scope="props">
                            <div :key="'expander' + props.index" class="pa-2">
                              <detalle-afiliado :item="props.item.afiliado"></detalle-afiliado>
                            </div>
                          </template>
                          <template slot="no-data" class="error">
                            <div class="pa-1 pl-2 text--white text-xs-center">
                              Lo sentimos, no existen registros. <v-icon>sentiment_very_dissatisfied</v-icon>
                            </div>
                          </template>
                        </v-data-table>
                      </v-slide-y-transition>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Tutela</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4 md4>
                            <v-autocomplete
                              label="Juzgado"
                              v-model="tutela.oj_juzgado_id"
                              :items="complementos.juzgados"
                              item-value="id"
                              item-text="nombre"
                              name="juzgado"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('juzgado')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="tutela.no_tutela"
                                          ref="noTutela"
                                          label="No. Tutela" key="no. tutela"
                                          name="no. tutela" v-validate="'required'" required
                                          :error-messages="errors.collect('no. tutela')" ></v-text-field>
                          </v-flex>


                          <v-flex xs12 sm4 md4>
                            <v-menu
                              ref="menuDate"
                              :close-on-content-click="false"
                              v-model="menuDate"
                              :nudge-right="40"
                              lazy
                              transition="scale-transition"
                              offset-y
                              full-width
                              max-width="290px"
                              min-width="290px"
                            >
                              <v-text-field
                                slot="activator"
                                v-model="computedDateFormatted"
                                label="Fecha Radicación"
                                prepend-icon="event"
                                readonly
                                name="fecha radicación"
                                required
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha radicación')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="tutela.fecha"
                                @input="menuDate = false"
                                locale='es'
                                no-title
                                @change="() => {
                                              let index = $validator.errors.items.findIndex(x => x.field === 'fecha radicación')
                                              $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                              }"
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-menu
                              ref="menuDateNotificacion"
                              :close-on-content-click="false"
                              v-model="menuDateNotificacion"
                              :nudge-right="40"
                              lazy
                              transition="scale-transition"
                              offset-y
                              full-width
                              max-width="290px"
                              min-width="290px"
                            >
                              <v-text-field
                                slot="activator"
                                v-model="computedDateFormattedNotificacion"
                                label="Fecha Notificación"
                                prepend-icon="event"
                                readonly
                                name="fecha notificación"
                                required
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha notificación')"
                              ></v-text-field>
                              <!--data-vv-delay="1000"-->
                              <v-date-picker
                                v-model="tutela.fecha_notificacion"
                                @input="menuDateNotificacion = false"
                                locale='es'
                                no-title
                                @change="() => {
                                              let index = $validator.errors.items.findIndex(x => x.field === 'fecha notificación')
                                              $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                              }"
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-autocomplete
                              label="Departamento"
                              v-model="tutela.gn_departamento_id"
                              :items="complementos.departamentos"
                              item-value="id"
                              item-text="nombre"
                              name="departamento"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('departamento')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-combobox
                              v-model="entidadesAccionadas"
                              :items="entidadesAccionadas"
                              hide-selected
                              label="Entidades accionadas"
                              multiple
                              small-chips
                            >
                              <template slot="no-data">
                                <v-list-tile>
                                  <v-list-tile-content>
                                    <v-list-tile-title>
                                      Escribe y presiona <kbd>enter</kbd> para agregar item
                                    </v-list-tile-title>
                                  </v-list-tile-content>
                                </v-list-tile>
                              </template>
                            </v-combobox>

                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-select
                              :items="complementos.tiposTutela"
                              v-model="tutela.tipo_tutela"
                              name="tipo tutela"
                              label="Tipo tutela"
                              :error-messages="errors.collect('tipo tutela')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-select
                              :items="incidentesDesacatos"
                              v-model="tutela.incidente_desacato"
                              name="incidente desacato"
                              label="Incidente de desacato"
                              :error-messages="errors.collect('incidente desacato')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-autocomplete
                              label="Pretensión"
                              v-model="tutela.oj_pretencion_id"
                              :items="complementos.pretenciones"
                              item-value="id"
                              item-text="pretencion"
                              name="pretencion"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('pretencion')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.pretencion"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>


                          <v-flex xs12 sm4>
                            <v-layout align-center fill-height>
                              <v-flex d-flex xs10 sm8>
                                <input-file
                                  ref="archivoAdjunto"
                                  label="Adjuntar archivo"
                                  v-model="tutela.archivo"
                                  :file-name="tutela.archivo ? tutela.archivo.nombre : null"
                                  accept="application/pdf"
                                  :hint="'Extenciones aceptadas: pdf'"
                                  class="mb-3"
                                  prepend-icon="description"
                                />
                              </v-flex>
                              <v-flex d-flex xs2 sm4>
                                <v-tooltip bottom>
                                  <v-btn
                                    fab
                                    color="success"
                                    small
                                    :href="tutela.archivo ? tutela.archivo.url_signed : ''"
                                    target="_blank"
                                    slot="activator"
                                  >
                                    <v-icon>remove_red_eye</v-icon>
                                  </v-btn>
                                  <span>Ver archivo</span>
                                </v-tooltip>
                              </v-flex>
                            </v-layout>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-subheader class="pa-0 ma-0">Medida provisional</v-subheader>
                            <v-switch class="ma-0 pa-0"  color="accent" :label="tutela.med_provisional"
                                      v-model="tutela.med_provisional"  true-value="Si" false-value="No"></v-switch>
                          </v-flex>

                          <v-flex xs12>
                            <v-textarea v-model="tutela.desc_pretencion"
                                          label="Descripción de la pretensión" key="descripcion pretension" prepend-icon="format_align_left"
                                          name="descripcion pretension" v-validate="'required'" required
                                          :error-messages="errors.collect('descripcion pretension')" ></v-textarea>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                </v-layout>
              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="formReset" :loading="loadingSubmit">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="comprobarDuplicadosTutelas()" :loading="loadingSubmit" :disabled="errors.any()">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import store from '../../../../store/complementos/index'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {TUTELAS_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import DetalleAfiliado from '../../../misional/aseguramiento/afiliados/DetalleAfiliado'
  export default {
    name: 'RegistroTutelas',
    props: ['parametros'],
    components: {
      DetalleAfiliado,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputFile: () => import('@/components/general/InputFile')
    },
    data () {
      return {
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
        complementos_cums: {},
        tutela: {},
        afiliado_tutela: {},
        no_afiliado: false,
        menuDate: false,
        menuDateNotificacion: false,
        expanded: false,
        custom: false,
        incidentesDesacatos: ['Si', 'No'],
        loadingSubmit: false,
        textoBusqueda: null,
        nombreAfiliado: false,
        headersAfiliados: [
          {
            text: '',
            align: 'center',
            sortable: false,
            value: ''
          },
          {
            text: 'Tipo de Documento',
            align: 'center',
            sortable: false,
            value: 'gn_tipdocentidad_id'
          },
          {
            text: 'Identificación',
            align: 'center',
            sortable: false,
            value: 'identificacion'
          },
          {
            text: 'Nombre Completo',
            align: 'center',
            sortable: false,
            value: 'nombre_completo'
          },
          {
            text: 'Eliminar',
            align: 'center',
            sortable: false,
            value: ''
          }
        ],
        entidadesAccionadas: [],
        payload: null,
        maxLength1: null,
        maxLength2: null
      }
    },
    watch: {
      'afiliado_tutela.gn_tipdocentidad_id' (val) {
        if (typeof val === 'undefined') val = null
        if (val) {
          this.maxLength1 = this.complementos.tipdocidentidades.find(tipo => tipo.id === val).longitud
        }
      },
      'tutela.gn_tipdocentidad_id' (val) {
        if (typeof val === 'undefined') val = null
        if (val) {
          this.maxLength2 = this.complementos.tipdocidentidades.find(tipo => tipo.id === val).longitud
        }
      }
    },
    beforeMount () {
      this.formReset()
      this.registroAfiliado()
    },
    mounted () {
      if (this.parametros.entidad !== null) {
        this.getRegisto(this.parametros.entidad.id)
      }
    },
    computed: {
      tapTitulo () {
        return !this.tutela.id ? 'Nuevo Item' : 'Edición de : ' + this.tutela.no_tutela
      },
      computedDateFormatted () {
        return this.formDate(this.tutela.fecha)
      },
      computedDateFormattedNotificacion () {
        return this.formDate(this.tutela.fecha_notificacion)
      },
      complementos () {
        return store.getters.complementosTutelas
      },
      progress () {
        return Math.min(100, this.value.length * 10)
      },
      color () {
        return ['error', 'warning', 'success'][Math.floor(this.progress / 40)]
      }
    },
    methods: {
      async addAfiliadosTutelas (afiliadoTutela) {
        if (await this.validador('formTutelasAfiliado')) {
          if (this.no_afiliado) {
            afiliadoTutela.nombre_completo = (this.afiliado_tutela.nombre1 ? this.afiliado_tutela.nombre1 : '') +
              ' ' + (this.afiliado_tutela.nombre2 ? this.afiliado_tutela.nombre2 : '') +
              ' ' + (this.afiliado_tutela.apellido1 ? this.afiliado_tutela.apellido1 : ' ') +
              ' ' + (this.afiliado_tutela.apellido2 ? this.afiliado_tutela.apellido2 : ' ')
          }
          let item = JSON.parse(JSON.stringify(afiliadoTutela))
          if (this.afiliado_tutela.gn_tipdocidentidad_id && this.afiliado_tutela.identificacion && this.afiliado_tutela.nombre_completo) {
            if (item.id) {
              this.tutela.afiliados_tutelas.splice(this.tutela.afiliados_tutelas.findIndex(x => x.id === item.id), 1)
              this.tutela.afiliados_tutelas.push(item)
            } else {
              this.tutela.afiliados_tutelas.push(item)
              this.no_afiliado = false
            }
            this.registroAfiliado()
          } else {
            this.$store.commit(SNACK_SHOW, {msg: 'Existen campos vacios para registro de afiliado. ', color: 'danger'})
          }
        }
      },
      eliminarAfiliadoTutela (item) {
        this.tutela.afiliados_tutelas.splice(item, 1)
        // this.afiliado_tutela = JSON.parse(JSON.stringify(item))
      },
      findByIdentificacion: window.lodash.debounce(function () {
        this.textoBusqueda = 'Digite el número completo para realizar la búsqueda.'
        if (this.afiliado_tutela.identificacion && this.afiliado_tutela.identificacion.length >= 5) {
          this.custom = true
          this.textoBusqueda = 'Estamos bucando el documento en los registros.'
          this.axios.get('oj_tutelas/afiliado/' + (this.afiliado_tutela.identificacion))
            .then(res => {
              if (res.data.exists) {
                this.no_afiliado = false
                this.nombreAfiliado = true
                this.textoBusqueda = res.data.message
                this.custom = false
                this.afiliado_tutela.nombre_completo = res.data.data.razon_social ? res.data.data.razon_social : `${res.data.data.nombre1} ${res.data.data.nombre2} ${res.data.data.apellido1} ${res.data.data.apellido2}`
                this.afiliado_tutela.as_afiliado_id = res.data.data.id
                this.afiliado_tutela.afiliado = res.data.data
              } else {
                this.nombreAfiliado = true
                this.no_afiliado = true
                this.textoBusqueda = res.data.message
                this.custom = false
                this.afiliado_tutela.nombre_completo = null
                this.afiliado_tutela.as_afiliado_id = null
                this.afiliado_tutela.afiliado = null
              }
            }).catch(e => {
              this.custom = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al verificar el número de identificación. ' + e.response, color: 'danger'})
            })
        } else {
          this.no_afiliado = false
          this.afiliado_tutela.nombre_completo = null
          this.afiliado_tutela.as_afiliado_id = null
          this.afiliado_tutela.afiliado = null
        }
      }, 1000),
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.formTutela.$el
        })
        this.axios.get('oj_tutelas/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.tutela = response.data.data
              this.entidadesAccionadasToArray()
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la Tutela. ' + e.response, color: 'danger'})
          })
      },
      registroAfiliado () {
        this.afiliado_tutela = {
          id: null,
          as_afiliado_id: null,
          gn_tipdocidentidad_id: null,
          identificacion: null,
          nombre_completo: null,
          nombre1: null,
          nombre2: null,
          apellido1: null,
          apellido2: null,
          // Object
          afiliado: null,
          tipo_id: null
        }
      },
      formReset () {
        this.tutela = {
          id: null,
          gn_tipdocentidad_id: null,
          identificacion: null,
          nombre: null,
          as_afiliado_id: null,
          oj_juzgado_id: null,
          no_tutela: null,
          incidente_desacato: null,
          fecha_notificacion: null,
          fecha: null,
          oj_pretencion_id: null,
          desc_pretencion: null,
          tipo_tutela: null,
          ent_accionadas: null,
          gn_departamento_id: null,
          med_provisional: 'No',
          estado: 'Recibida',
          archivo: null,
          afiliados_tutelas: []
        }
        this.$validator.reset()
      },
      entidadesAccionadasToArray () {
        this.entidadesAccionadas = this.tutela.ent_accionadas.split(',')
      },
      entidadesAccionadasToString () {
        this.tutela.ent_accionadas = this.entidadesAccionadas.join()
      },
      addFormData () {
        let data = new FormData()
        data.append('id', this.tutela.id)
        data.append('gn_tipdocentidad_id', this.tutela.gn_tipdocentidad_id)
        data.append('identificacion', this.tutela.identificacion)
        data.append('nombre', this.tutela.nombre)
        if (this.tutela.as_afiliado_id) data.append('as_afiliado_id', this.tutela.as_afiliado_id)
        data.append('oj_juzgado_id', this.tutela.oj_juzgado_id)
        data.append('no_tutela', this.tutela.no_tutela)
        data.append('fecha', this.tutela.fecha)
        data.append('oj_pretencion_id', this.tutela.oj_pretencion_id)
        data.append('desc_pretencion', this.tutela.desc_pretencion)
        data.append('tipo_tutela', this.tutela.tipo_tutela)
        data.append('ent_accionadas', this.tutela.ent_accionadas)
        data.append('gn_departamento_id', this.tutela.gn_departamento_id)
        data.append('med_provisional', this.tutela.med_provisional)
        data.append('estado', this.tutela.estado)
        data.append('incidente_desacato', this.tutela.incidente_desacato)
        data.append('fecha_notificacion', this.tutela.fecha_notificacion)
        data.append('archivo', typeof this.tutela.archivo === 'undefined' ? '' : this.tutela.archivo)
        let afiliados = JSON.stringify(this.tutela.afiliados_tutelas)
        let afiliadosTutelas = JSON.parse(JSON.stringify(afiliados))
        data.append('afiliados_tutelas', afiliadosTutelas)

        this.payload = data
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      async submit () {
        let validFile = await this.$refs['archivoAdjunto'].validate('formTutelas')
        if (await this.validador('formTutelas') && validFile) {
          this.loadingSubmit = true
          this.entidadesAccionadasToString()
          this.addFormData()

          const typeRequest = this.tutela.id ? 'editar' : 'crear'
          let send = !this.tutela.id ? this.axios.post('oj_tutelas', this.payload) : this.axios.post('tutelas/' + this.tutela.id, this.payload)
          send.then(response => {
            this.$store.commit(SNACK_SHOW, {
              msg: `Se ${this.tutela.id
                ? 'actualizaron los datos'
                : 'realizó el registro'} correctamente del No. Tutela ${response.data.data.no_tutela}.`,
              color: 'success'
            })
            this.$store.commit(TUTELAS_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      // mis metodos RJPT
      comprobarDuplicadosTutelas () {
        this.axios.get(`comprobarDuplicado?notut=${this.tutela.no_tutela}`)
          .then((response) => {
            console.log(response.data)
            if ((response.data.no_tutela === this.tutela.no_tutela &&
                 response.data.oj_juzgado_id === this.tutela.oj_juzgado_id &&
                 response.data.oj_pretencion_id === this.tutela.oj_pretencion_id &&
                 response.data.tipo_tutela === this.tutela.tipo_tutela) && !this.tutela.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'Ya existe una tutela con el número ' + response.data.no_tutela, color: 'danger'})
              this.$refs.noTutela.focus()
            } else {
              this.submit()
            }
          })
          .catch(e => {
          })
      }
    }
  }
</script>

<style scoped>

</style>
