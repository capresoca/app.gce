<template>
  <div>
    <v-dialog v-model="dialog" persistent width="500px">
      <v-form data-vv-scope="formObligacionesDialog" @submit.prevent="agregarDetalle(detalle)">
        <v-card ref="dialogModificacion">
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar rubro</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12>
                <v-autocomplete
                  label="RP"
                  v-model="detalle.rp"
                  @change="val => obtenerRubrosYCdp(val.id)"
                  :items="rps"
                  :hint="detalle.rp.id ? 'Consecutivo: ' + detalle.rp.consecutivo : ''"
                  persistent-hint
                  item-text="periodo"
                  item-value="id"
                  name="RP"
                  no-data-text="No existen datos"
                  prepend-icon="library_books"
                  required
                  v-validate="'required'" required
                  return-object
                  :error-messages="errors.collect('RP')">

                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="'Periodo: ' + data.item.periodo"/>
                        <v-list-tile-sub-title v-html="'Consecutivo: ' + data.item.consecutivo"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-flex xs12>
                <v-autocomplete
                  label="Rubro"
                  v-model="detalle.rubro"
                  @change="val => obtenerRubro()"
                  :items="rubrosRp"
                  :filter="filterRubros"
                  :hint="detalle.rubro ? detalle.rubro.codigo : ''"
                  persistent-hint
                  item-text="nombre"
                  item-value="id"
                  name="rubro"
                  no-data-text="No existen datos"
                  prepend-icon="subtitles"
                  required
                  v-validate="'required|rubroValido'" required
                  return-object
                  :error-messages="errors.collect('rubro')">

                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                        <v-list-tile-sub-title v-html="data.item.codigo"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>


              <v-flex xs12>
                <v-text-field v-model="detalle.tipo_gasto.nombre" readonly
                              label="Tipo de gasto" key="tipo de gasto" prepend-icon="compare_arrows"
                              name="tipo de gasto" v-validate="'required'" required></v-text-field>
              </v-flex>

              <v-flex xs12>
                <input-detail-flex
                  label="Valor disponible"
                  prependIcon="money"
                  :text="detalle.saldo_por_obligar | numberFormat(0,'$')"
                />
              </v-flex>

              <v-flex xs12>
                <input-number
                  v-model.number="detalle.valor"
                  label="Saldo"
                  name="saldo"
                  :precision="0"
                  prepend-icon="attach_money"
                  v-validate="'required|max_value:' + detalle.saldo_por_obligar"
                  :error-messages="errors.collect('saldo')"
                ></input-number>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="resetDialog">Cancelar</v-btn>
            <v-btn type="submit" color="primary">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-dialog v-model="dialogAnular" persistent max-width="400">
      <v-card>
        <v-card-title class="grey lighten-3">
          <v-card-text class="title">¿Por qué anula la obligación?</v-card-text>
        </v-card-title>
        <v-container fluid>
          <v-layout row wrap>
            <v-flex xs12>
              <v-textarea
                outline
                v-model="obligacion.concepto_anulacionint"
                color="primary"
                name="Detalle de anulación"
              >
                <div slot="label">
                  Detalle de anulación
                </div>
              </v-textarea>
            </v-flex>
          </v-layout>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click.native="dialogAnular = false">Cancelar</v-btn>
          <v-btn color="primary" flat @click="anular">Enviar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card ref="formPresupuesto">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title>Registro Obligación </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-chip label color="white" v-if="obligacion.consecutivo" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="obligacion.consecutivo === null ? '00' : obligacion.consecutivo"></span>
        </v-chip>
        <v-chip v-if="!seEstaCreando" :color="colorEstado" text-color="white">
          <v-avatar>
            <v-icon>{{ iconoEstado }}</v-icon>
          </v-avatar>
          {{ estado }}
        </v-chip>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12>
            <v-form data-vv-scope="formCdp">
              <v-container fluid grid-list-md>
                <v-layout v-if="obligacion.estado === 'Anulada'">
                  <v-flex xs12>
                    <v-alert
                      :value="true"
                      type="error"
                    >
                      <strong>Concepto anulación:</strong> {{ obligacion.concepto_anulacionint }}
                    </v-alert>

                  </v-flex>
                </v-layout>
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> Presupuesto</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4 v-if="parametros.entidad !== null">
                            <v-text-field v-model="obligacion.entidad_resolucion.periodo"
                                          prepend-icon="calendar_today"
                                          label="Periodo" key="periodo"
                                          readonly>

                            </v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 v-else>
                            <v-select
                              :items="presupuestos"
                              v-model="presupuesto.entidad_resolucion.periodo"
                              @change="val => obtenerPresupuesto(val)"
                              name="periodo"
                              label="Periodo"
                              no-data-text="No hay resoluciones disponibles"
                              :error-messages="errors.collect('periodo')"
                              v-validate="'required'" required
                              prepend-icon="calendar_today"
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="presupuesto.entidad_resolucion.codigo"
                                          prepend-icon="account_balance"
                                          label="Unidad ejecutora" key="unidad ejecturoa"
                                          readonly>

                            </v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="presupuesto.entidad_resolucion.nombre"
                                          prepend-icon="account_balance"
                                          label="Nombre" key="nombre"
                                          readonly>

                            </v-text-field>
                          </v-flex>

                        </v-layout>

                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>

                        <v-layout>
                          <v-flex xs12 sm3 v-if="estado !== 'Confirmada' && estado !== 'Anulada'">
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
                                label="Fecha"
                                prepend-icon="event"
                                readonly
                                name="fecha activo"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="obligacion.fecha"
                                @input="menuDate = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha activo')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                }"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>
                          <v-flex xs12 sm3 v-else>
                            <v-text-field
                              v-model="computedDateFormatted"
                              prepend-icon="event"
                              label="Fecha"
                              key="fecha"
                              name="fecha"
                              readonly
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm9>
                            <v-text-field
                              v-model="obligacion.documento"
                              prepend-icon="code"
                              label="Documento"
                              key="documento"
                              name="documento"
                              v-validate="'required'"
                              :readonly="readOnly"
                              :error-messages="errors.collect('documento')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout>
                          <v-flex xs12 sm3 v-if="estado !== 'Confirmada' && estado !== 'Anulada'">
                            <v-menu
                              ref="menuDateDocumento"
                              :close-on-content-click="false"
                              v-model="menuDateDocumento"
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
                                v-model="computedDateFormattedDocumento"
                                label="Fecha documento"
                                prepend-icon="event"
                                readonly
                                name="fecha documento"
                                required
                                data-vv-delay="1000"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha documento')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="obligacion.fecha_documento"
                                @input="menuDateDocumento = false"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>
                          <v-flex xs12 sm3 v-else>
                            <v-text-field
                              v-model="computedDateFormattedDocumento"
                              prepend-icon="event"
                              label="Fecha documento"
                              key="fecha documento"
                              name="fecha documento"
                              readonly
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm9 v-if="estado !== 'Confirmada' && estado !== 'Anulada'">
                            <v-autocomplete
                              label="Tercero"
                              v-model="obligacion.gn_tercero_id"
                              @change="val => obtenerRpsTercero(val)"
                              :items="terceros"
                              item-value="id"
                              item-text="nombre_completo"
                              name="tercero"
                              persistent-hint
                              prepend-icon="person"
                              no-data-text="No existen terceros"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('tercero')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre_completo"/>
                                    <v-list-tile-sub-title v-html="data.item.identificacion"></v-list-tile-sub-title>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm9 v-else>
                            <v-text-field v-model="obligacion.tercero.nombre_completo" prepend-icon="person"
                                          label="Tercero" key="tercero"
                                          name="tercero"
                                          readonly
                                          :error-messages="errors.collect('tercero')" ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout>
                          <v-flex xs12>
                            <v-textarea v-model="obligacion.observaciones"
                                        prepend-icon="reorder"
                                        label="Observaciones"
                                        key="observaciones"
                                        rows="1"
                                        auto-grow
                                        name="observaciones"
                                        v-validate="'required'"
                                        :readonly="readOnly"
                                        :error-messages="errors.collect('observaciones')"
                            ></v-textarea>
                          </v-flex>
                        </v-layout>


                      </v-card-text>
                    </v-card>
                  </v-flex>
                  <v-expand-transition>
                    <v-flex xs12 class="pb-4" v-if="obligacion.gn_tercero_id && rps.length">
                      <template>
                        <loading v-model="localLoading" />
                        <v-card>
                          <v-toolbar dense class="grey lighten-4 elevation-0">
                            <v-toolbar-title class="subheading">Detalles</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn v-if="!readOnly" small color="primary" @click.prevent="openDialog"><v-icon>add</v-icon> Agregar rubro</v-btn>
                          </v-toolbar>
                          <v-data-table :headers="headers"
                                        :items="obligacion.detalles"
                                        :loading="tableLoading"
                                        hide-actions rows-per-page-text="Registros por página"
                                        :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                            <template slot="items" slot-scope="props">
                              <td>{{ props.item.cdp.consecutivo }}</td>
                              <td>{{ props.item.rp.consecutivo }}</td>
                              <td>{{ props.item.rubro.codigo }}</td>
                              <td>{{ props.item.rubro.nombre }}</td>
                              <td>{{ props.item.tipo_gasto.nombre }}</td>
                              <td>{{ props.item.rp.fecha }}</td>
                              <td>{{ props.item.saldo_por_obligar }}</td>
                              <td>{{ props.item.valor | numberFormat(0,'$') }}</td>
                              <td>
                                <v-speed-dial v-if="!readOnly"
                                              v-model="props.item.show"
                                              direction="left"
                                              open-on-hover
                                              transition="slide-x-transition"
                                >
                                  <v-btn
                                    slot="activator"
                                    v-model="props.item.show"
                                    color="blue darken-2"
                                    dark
                                    fab
                                    small
                                  >
                                    <v-icon>chevron_left</v-icon>
                                    <v-icon>close</v-icon>
                                  </v-btn>
                                  <v-tooltip top>
                                    <v-btn
                                      fab
                                      dark
                                      small
                                      color="white"
                                      slot="activator"
                                      @click="editarDetalle(props.index,props.item)"
                                    >
                                      <v-icon color="accent">mode_edit</v-icon>
                                    </v-btn>
                                    <span>Editar</span>
                                  </v-tooltip>
                                  <v-tooltip top>
                                    <v-btn
                                      fab
                                      dark
                                      small
                                      color="white"
                                      slot="activator"
                                      @click="eliminarDetalle(props.index, props.item.id)"
                                    >
                                      <v-icon color="accent">delete</v-icon>
                                    </v-btn>
                                    <span>Eliminar</span>
                                  </v-tooltip>

                                </v-speed-dial>
                              </td>
                            </template>
                            <template slot="no-data">
                              <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                                Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                              </v-alert>
                              <v-alert v-else :value="true" color="info" icon="info">
                                Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                              </v-alert>
                            </template>
                            <template slot="pageText" slot-scope="props">
                              {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                            </template>
                            <template v-slot:footer>
                              <td :colspan="headers.length">
                                <v-spacer></v-spacer>
                                <strong>Valor compromiso: {{ obligacion.valor_obligacion | numberFormat(0,'$') }}</strong>
                              </td>
                            </template>
                          </v-data-table>
                        </v-card>
                      </template>
                    </v-flex>
                  </v-expand-transition>

                </v-layout>
              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions v-if="estado !== 'Confirmada' && estado !== 'Anulada'" class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn v-if="obligacion.id" @click="dialogAnular = true" dark color="danger" :loading="loadingSubmit">Anular</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn v-if="estado !== 'Confirmada' && estado !== 'Anulada'" color="primary" @click.prevent="submit" :loading="loadingSubmit" >Guardar</v-btn>

            <v-btn color="success" @click="confirmar" slot="activator"><span v-if="seEstaCreando">Guardar y Confirmar</span> <span v-else>Confirmar</span></v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {OBLIGACIONES_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import { Validator } from 'vee-validate'
  import InputNumber from '@/components/general/InputNumber'

  export default {
    name: 'RegistroObligaciones',
    props: ['parametros'],
    components: {
      Loading,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      InputNumber
    },
    data () {
      return {
        detalleTercero: () => import('@/components/administrativo/niif/terceros/DetalleTercero'),
        obligacion: null,
        presupuesto: null,
        presupuestos: [],
        rubro: {},
        rubros: [],
        rubrosCreados: '',
        terceros: [],
        rp: {},
        rps: [],
        detalleRp: {},
        rubrosRp: [],
        detalle: {},
        localLoading: false,
        seEstaCreando: false,
        tableLoading: false,
        loadingSubmit: false,
        dialog: false,
        dialogAnular: false,
        menuDate: false,
        menuDateDocumento: false,
        readOnly: false,
        estado: null,
        headers: [
          {
            text: 'No. CDP',
            align: 'left',
            sortable: false,
            value: 'noCdp'
          },
          {
            text: 'No. RP',
            align: 'left',
            sortable: false,
            value: 'noRp'
          },
          {
            text: 'Rubro',
            align: 'left',
            sortable: false,
            value: 'rubro'
          },
          {
            text: 'Nombre rubro',
            align: 'left',
            sortable: false,
            value: 'nombreRubro'
          },
          {
            text: 'Tipo gasto',
            align: 'left',
            sortable: false,
            value: 'tipoGasto'
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false,
            value: 'fecha'
          },
          {
            text: 'Saldo por obligar',
            align: 'left',
            sortable: false,
            value: 'saldoPorObligar'
          },
          {
            text: 'Valor',
            align: 'left',
            sortable: false,
            value: 'valor'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    beforeMount () {
      this.formReset()
      this.resetDialog()
      this.validadorPostulador()
    },
    mounted () {
      this.parametros.entidad !== null ? this.getRegistro(this.parametros.entidad.id) : this.obtenerPresupuestos()
      this.resetDialog()
      this.obtenerTerceros()

      const dict = {
        custom: {
          rubro: {
            not_in: 'Este rubro ya fue registrado.'
          }
        }
      }

      this.$validator.localize('es', dict)
    },
    computed: {
      iconoEstado () {
        if (this.estado === 'Registrada') return 'stars'
        if (this.estado === 'Confirmada') return 'check_circle'
        if (this.estado === 'Anulada') return 'remove_circle'
      },
      colorEstado () {
        if (this.estado === 'Registrada') return 'primary'
        if (this.estado === 'Confirmada') return 'teal'
        if (this.estado === 'Anulada') return 'red'
      },
      computedDateFormatted () {
        return this.formDate(this.obligacion.fecha)
      },
      computedDateFormattedDocumento () {
        return this.formDate(this.obligacion.fecha_documento)
      }
    },
    methods: {
      getRegistro (id) {
        let loader = this.$loading.show({
          container: this.$refs.formPresupuesto.$el
        })
        this.axios.get('pr_obligaciones/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.obligacion = response.data.data
              this.estado = this.obligacion.estado
              this.obtenerPresupuesto(this.obligacion.periodo)
              this.obtenerRpsTercero(this.obligacion.gn_tercero_id)
              if (this.obligacion.estado === 'Confirmada' || this.obligacion.estado === 'Anulada') this.readOnly = true
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      obtenerPresupuesto (periodo) {
        let loader = this.$loading.show({
          container: this.$refs.formPresupuesto.$el
        })
        this.axios.get('obligaciones/' + periodo)
          .then((response) => {
            if (response.data !== '') {
              this.presupuesto.entidad_resolucion = response.data.strgasto.entidad_resolucion
              this.rubrosRp = response.data.rubros
              this.obligacion.pr_strgasto_id = this.presupuesto.id
              this.obligacion.periodo = this.presupuesto.entidad_resolucion.periodo
              this.obligacion.pr_entidad_resolucion_id = this.presupuesto.entidad_resolucion.id
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      obtenerPresupuestos () {
        this.seEstaCreando = true
        let loader = this.$loading.show({
          container: this.$refs.formPresupuesto.$el
        })
        this.axios.get('obligacion_periodos ')
          .then((res) => {
            if (res.data !== '') {
              this.presupuestos = res.data
              this.obtenerPresupuesto(this.presupuestos[0])
            }
            loader.hide()
          })
          .catch((e) => {
            loader.hide()
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al obtener presupuesto. ', error: e})
          })
      },
      obtenerTerceros () {
        let loader = this.$loading.show({
          container: this.$refs.formPresupuesto.$el
        })
        this.axios.get('rps_tercero ')
          .then((res) => {
            if (res.data !== '') {
              this.terceros = res.data.data
            }
            loader.hide()
          })
          .catch((e) => {
            loader.hide()
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al obtener terceros. ', error: e})
          })
      },
      obtenerRpsTercero (idTercero) {
        if (idTercero) {
          let loader = this.$loading.show({container: this.$refs.formPresupuesto.$el})
          this.axios.get(`rps/tercero/${idTercero}`)
            .then((res) => {
              if (res.data !== '') { this.rps = res.data.rps }
              loader.hide()
            })
            .catch((e) => {
              loader.hide()
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al obtener terceros. ', error: e})
            })
        } else {
          this.rps = []
        }
      },
      obtenerRubrosYCdp (idRp) {
        this.obtenerRubrosRP(idRp)
        this.obtenerCdpDelRp(idRp)
      },
      obtenerRubrosRP (idRp) {
        let loader = this.$loading.show({
          container: this.$refs.dialogModificacion.$el
        })
        this.axios.get(`obligaciones/rubros/${idRp}`)
          .then((response) => {
            if (response.data !== '') {
              this.rubrosRp = response.data.rubros
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los rubros. ' + e, color: 'danger'})
          })
      },
      obtenerCdpDelRp (idRp) {
        let loader = this.$loading.show({
          container: this.$refs.dialogModificacion.$el
        })
        this.axios.get(`obligaciones/rp/cdp/${idRp}`)
          .then((response) => {
            if (response.data !== '') {
              this.detalle.cdp = response.data.cdp
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el cdp. ' + e, color: 'danger'})
          })
      },
      obtenerRubro () {
        let loader = this.$loading.show({
          container: this.$refs.dialogModificacion.$el
        })
        this.axios.get(`obligacion_rubro?rpId=${this.detalle.rp.id}&rubroId=${this.detalle.rubro.id}`)
          .then((response) => {
            if (response.data !== '') {
              this.detalleRp = response.data.data
              this.detalle.pr_detrp_id = this.detalleRp.id
              this.detalle.pr_tipo_gasto_id = this.detalleRp.tipo_gasto.id
              this.detalle.saldo_por_obligar = this.detalleRp.valor_disponible
              this.detalle.tipo_gasto = this.detalleRp.tipo_gasto
              // this.detalle.valor_disponible = this.detalleRp.valor_disponible
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el rubro. ' + e, color: 'danger'})
          })
      },
      getRubrosUsados () {
        return this.obligacion.detalles.map(cod => { if (!(this.detalle.rubro.id != null && this.detalle.rubro.id === cod.rubro.id)) return cod.rubro.id })
      },
      async agregarDetalle (detalle) {
        if (await this.validador('formObligacionesDialog')) {
          this.agregarValoresAlDetalle()
          if (typeof detalle.index !== 'number') {
            this.obligacion.detalles.push(detalle)
          } else {
            this.obligacion.detalles
              .splice(this.obligacion.detalles
                .findIndex(x => this.obligacion.detalles.indexOf(x) === detalle.index), 1, detalle)
          }
          this.calcularObligacion()
          this.dialog = false
          this.resetDialog()
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
        }
      },
      editarDetalle (index, detalle) {
        this.obtenerRubrosYCdp(detalle.rp.id)
        this.detalle = JSON.parse(JSON.stringify(detalle))
        this.detalle.index = index

        this.dialog = true
        this.rubrosCreados = this.getRubrosUsados()
      },
      eliminarDetalle (index) {
        this.obligacion.detalles.splice(index, 1)
        this.calcularObligacion()
        this.dialog = false
      },
      filterRubros (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.codigo + item.codigo.split('.').join(''))
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      validadorPostulador () {
        Validator.extend('rubroValido', {
          validate: (value, prop) => new Promise((resolve) => {
            let response = {}
            if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
              this.rubrosCreados.includes(value.id) ? response = {valido: false, mensaje: 'Rubro ya usado'} : response = {valido: true, mensaje: null}
              return resolve({
                valid: response.valido,
                data: {
                  message: response.mensaje
                }
              })
            }
          }),
          getMessage: (field, params, data) => data.message
        })
      },
      calcularObligacion () {
        let valorCalculado = 0
        for (let detalle of this.obligacion.detalles) {
          valorCalculado += detalle.valor
        }
        this.obligacion.valor_obligacion = valorCalculado
      },
      formReset () {
        this.obligacion = {
          id: null,
          entidad_resolucion: {periodo: null},
          consecutivo: null,
          fecha: null,
          documento: null,
          fecha_documento: null,
          gn_tercero_id: null,
          observaciones: null,
          estado: 'Registrada',
          periodo: null,
          pr_entidad_resolucion_id: null,
          valor_obligacion: null,
          tercero: {nombre_completo: ''},
          detalles: []
        }
        this.rubrosCreados = this.getRubrosUsados()

        this.presupuesto = {
          entidad_resolucion: {periodo: ''},
          detalles: []
        }

        this.detalleRp = {
          tipo_gasto: null,
          valor_inicial: null
        }
      },
      resetDialog () {
        this.dialog = false
        this.detalle = {
          id: null,
          pr_detrp_id: null,
          pr_tipo_gasto_id: null,
          tipo_gasto: {nombre: null},
          valor: 0,
          saldo_por_obligar: 0,
          // valor_disponible: 0,
          rubro: {id: ''},
          rp: {id: '', consecutivo: ''},
          cdp: {id: ''}
        }
        this.$validator.reset()
        this.rubrosCreados = this.getRubrosUsados()
      },
      agregarValoresAlDetalle () {
        this.detalle.fecha_rp = this.detalle.rp.fecha_vence
        this.detalle.pr_rubro_id = this.detalle.rubro.id
        this.detalle.pr_cdp_id = this.detalle.cdp.id
        this.detalle.pr_rp_id = this.detalle.rp.id
      },
      openDialog () {
        this.dialog = true
        this.rubrosCreados = this.getRubrosUsados()
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      confirmar () {
        this.obligacion.estado = 'Confirmada'
        this.submit()
      },
      anular () {
        this.dialogAnular = false
        this.obligacion.estado = 'Anulada'
        this.submit()
      },
      async submit () {
        if (await this.validador('formCdp')) {
          this.localLoading = true
          const typeRequest = this.seEstaCreando ? 'crear' : 'editar'
          let send = !this.obligacion.id ? this.axios.post('pr_obligaciones', this.obligacion) : this.axios.put('pr_obligaciones/' + this.obligacion.id, this.obligacion)
          send.then(response => {
            if (this.seEstaCreando) {
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.$store.commit(OBLIGACIONES_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.$store.commit(OBLIGACIONES_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            }
          }).catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro. ', error: e})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>


