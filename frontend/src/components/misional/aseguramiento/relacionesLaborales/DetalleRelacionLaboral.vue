<template>
  <div>
    <v-dialog v-model="dialoInterno" persistent width="300px">
      <v-card>
        <v-card-title class="grey lighten-3">
          <span class="subheading" style="font-weight: 500 !important;" v-text="mensaje"></span>
        </v-card-title>
        <v-card-actions>
          <v-layout row wrap>
            <v-flex xs12 sm6 class="text-xs-center">
              <v-btn @click.prevent="decidirUsoPlanilla(1)" flat small color="red" class="white--text" dark v-text="'No'"></v-btn>
            </v-flex>
            <v-flex xs12 sm6 class="text-xs-center">
              <v-btn @click.prevent="decidirUsoPlanilla(2, existe)" flat small color="primary" dark v-text="'Si'"></v-btn>
            </v-flex>
          </v-layout>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="cardDialog" persistent style="box-shadow: none !important;" width="550px">
      <v-card>
        <v-card-title class="grey lighten-3 pt-1 pb-1">
          <span class="subheading" style="font-weight: 500 !important;" v-text="'Nuevo Aporte'"></span>
          <v-spacer></v-spacer>
          <v-tooltip left center>
            <v-checkbox
              slot="activator"
              v-model="aporte.novedad"
              :true-value="1"
              :false-value="0"
              :label="`Novedad`"
            ></v-checkbox>
            <span v-text="aporte.novedad === 0 ? 'Marcar' : 'Desmarcar'"></span>
          </v-tooltip>
        </v-card-title>
        <v-form data-vv-scope="formRegistroAporte">
          <v-container fluid grid-list-xl>
            <v-layout row wrap>
              <v-subheader v-text="'Planilla'"></v-subheader>
              <v-flex xs12 sm12 class="pt-0 pb-1">
                <v-layout align-center class="pt-2 px-2">
                  <v-text-field
                    v-model="numero"
                    label="Número de Planilla"
                    :hint="textoBusqueda"
                    :loading="custom"
                    v-validate="'required'"
                    :name="'número de planilla'"
                    :key="'número de planilla'"
                    prepend-icon="notes"
                    :error-messages="errors.collect('número de planilla')">
                  </v-text-field>
<!--                  @keyup="findByNumero"-->
                  <v-tooltip top v-if="numero">
                    <v-btn
                      :disabled="verified || !numero"
                      slot="activator"
                      flat
                      icon
                      color="accent"
                      @click="findByNumero"
                    >
                      <v-icon>{{verified ? 'done_all' : 'done'}}</v-icon>
                    </v-btn>
                    <span>{{!verified ? 'Verificar identificación' : 'Identificación verificada'}}</span>
                  </v-tooltip>
                </v-layout>
              </v-flex>
              <v-subheader v-text="'Aporte'"></v-subheader>
              <v-flex sm12 class="pa-0"></v-flex>
              <v-flex xs12 sm6 class="pt-0">
                <input-number
                  label="IBC"
                  key="ibc"
                  name="ibc"
                  prepend-icon="attach_money"
                  :disabled="!verified ? true : (false)"
                  :precision="0"
                  v-model.number="aporte.ibc"
                  v-validate="(aporte.novedad === 1 ? '' : 'required|') + 'min_value:0'"
                  :error-messages="errors.collect('ibc')"
                />
              </v-flex>
              <v-flex xs12 sm6 class="pt-0">
                <v-menu
                  v-model="menu2"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  min-width="290px"
                >
                    <v-text-field
                      slot="activator"
                      v-model="aporte.fecha_pago"
                      label="Fecha Pago"
                      prepend-icon="event"
                      readonly
                      :disabled="!verified"
                      required
                      v-validate="'required|date_format:YYYY-MM-DD'"
                      name="fecha pago"
                      key="fecha pago"
                      :error-messages="errors.collect('fecha pago')"
                    ></v-text-field>
                  <v-date-picker
                    locale="es-co"
                    v-model="aporte.fecha_pago"
                    @input="menu2 = false"
                    @change="() => {
                        let index = $validator.errors.items.findIndex(x => x.field === 'fecha pago')
                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                        menu2 = false
                        }">
                  </v-date-picker>
                </v-menu>
              </v-flex>
              <v-flex xs12 sm6>
                <input-number
                  label="Días Pagados"
                  key="días pagados"
                  name="días pagados"
                  prepend-icon="bookmark_border"
                  :disabled="!verified ? true : (false)"
                  :precision="0"
                  v-model.number="aporte.dias"
                  v-validate="(aporte.novedad === 1 ? '' : 'required|') + 'min_value:0|max:3'"
                  :error-messages="errors.collect('días pagados')"
                />
              </v-flex>
              <v-flex xs12 sm6>
                <input-number
                  label="Valor Aporte"
                  key="valor aporte"
                  name="valor aporte"
                  prepend-icon="attach_money"
                  :disabled="!verified ? true : (false)"
                  :precision="0"
                  v-model.number="aporte.cotizacion"
                  v-validate="(aporte.novedad === 1 ? '' : 'required|') + 'min_value:0'"
                  :error-messages="errors.collect('valor aporte')"
                />
              </v-flex>
              <v-flex xs12 class="pt-1 pb-0">
                <v-textarea
                  v-model="aporte.observacion"
                  auto-grow
                  label="Observación"
                  rows="1"
                  :disabled="!verified ? true : (false)"
                  key="observacion"
                  name="observacion"
                  class="pb-0"
                />
              </v-flex>
              <v-flex xs12 sm4 md4  class="pt-1 pb-0" :class="{'sm6 md6': aporte.retiro === 0}">
                <v-switch
                  color="accent"
                  label="Retiro"
                  :disabled="!verified ? true : (false)"
                  v-model="aporte.retiro"
                  :true-value="1"
                  :false-value="0">
                </v-switch>
              </v-flex>
              <v-flex xs12 sm4 md4  class="pt-1 pb-0" :class="{'sm6 md6': aporte.retiro === 0}">
                <v-switch
                  color="accent"
                  label="Ingreso"
                  :disabled="!verified ? true : (false)"
                  v-model="aporte.ingreso"
                  :true-value="1"
                  :false-value="0"
                ></v-switch>
              </v-flex>
              <v-flex xs12 sm4 md4  class="pt-1 pb-0" v-if="aporte.retiro === 1">
                <v-switch
                  color="accent"
                  label="Forzar Retiro"
                  :disabled="!verified ? true : (false)"
                  v-model="aporte.forzar_retiro"
                  :true-value="1"
                  :false-value="0"
                ></v-switch>
              </v-flex>
              <v-flex xs12 v-show="aporte.forzar_retiro === 1" class="pt-0 pb-0">
                <v-alert
                  :value="true"
                  type="warning"
                  color="orange"
                  icon="fas fa-radiation"
                  class="pt-1 pb-1"
                >
                  <span v-text="'Al marcar esta opción se eliminaran las liquidaciones generadas a partir de este periodo,\n'+'de lo contrario solo eliminara las liquidaciones generadas después de la fecha de pago'"></span>
                </v-alert>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
        <v-card-actions class="grey lighten-4">
          <v-spacer></v-spacer>
          <v-btn @click.prevent="close()" flat small color="red" class="white--text" dark>Cancelar</v-btn>
          <v-btn  flat small @click.prevent="send()" color="primary" dark v-text="'Agregar'"></v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card>
      <loading v-model="loading" />
      <v-toolbar dense>
        <v-icon>assignment</v-icon>
        <v-toolbar-title v-html="'Detalle de relación laboral'" />
        <v-spacer/>
      </v-toolbar>
      <v-slide-y-transition>
        <v-container fluid grid-list-xl v-if="item">
          <v-layout row wrap>
            <v-flex xs12 sm12 md6>
              <v-label>Afiliado</v-label>
              <v-expansion-panel focusable>
                <v-expansion-panel-content>
                  <v-list-tile
                    @click=""
                    slot="header"
                  >
                    <v-list-tile-action>
                      <div>
                        <span style="font-size: 30px" v-text="item.afiliado.emoticon"></span>
                      </div>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>{{item.afiliado.nombre_completo}}</v-list-tile-title>
                      <v-list-tile-sub-title>{{item.afiliado.identificacion_completa}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <detalle-afiliado :item="item.afiliado" class="elevation-1" />
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-flex>
            <v-flex xs12 sm12 md6>
              <v-label>Aportante</v-label>
              <v-expansion-panel focusable>
                <v-expansion-panel-content>
                  <v-list-tile
                    @click=""
                    slot="header"
                  >
                    <v-list-tile-action>
                      <div>
                        <span v-if="item.pagador.emoticon" style="font-size: 30px" v-text="item.pagador.emoticon"></span>
                        <v-icon v-else large color="accent" v-text="'fas fa-id-card'"></v-icon>
                      </div>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>{{item.pagador.razon_social}}</v-list-tile-title>
                      <v-list-tile-sub-title>{{item.pagador.identificacion}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <detalle-pagador :item="item.pagador" class="elevation-1" />
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-flex>
            <v-flex xs12>
              <v-card>
                <v-toolbar flat color="white">
                  <v-toolbar-title>Liquidaciones</v-toolbar-title>
                  <v-divider
                    class="mx-2"
                    inset
                    vertical
                  />
                  <v-spacer/>
                  <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Buscar"
                    single-line
                    hide-details
                  ></v-text-field>
                </v-toolbar>
                <v-data-table
                  :headers="headers"
                  :items="item.liquidaciones"
                  :search="search"
                  :loading="cargaLiquidacione"
                  rows-per-page-text="Registros por página"
                  :rows-per-page-items="[10,20,50,{'text': 'TODOS','value':-1}]"
                  item-key="periodo"
                  disable-initial-sort
                >
                  <template slot="items" slot-scope="props">
                    <tr @click="props.expanded = !props.expanded" class="cursor-pointer" :style="props.item.dias_pagados && props.item.dias_pagados > 0 ? '' : 'background-color: pink !important;'">
                      <td>{{ props.item.periodo }}</td>
                      <td class="text-xs-right">${{props.item.ibc | numberFormat(2)}}</td>
                      <td class="text-xs-center">{{ props.item.dias_pagados }}</td>
                      <td class="text-xs-center"><v-icon :color="props.item.ingreso ? 'accent' : ''">{{props.item.ingreso ? 'check_box' : 'check_box_outline_blank'}}</v-icon></td>
                      <td class="text-xs-center"><v-icon :color="props.item.retiro ? 'accent' : ''">{{props.item.retiro ? 'check_box' : 'check_box_outline_blank'}}</v-icon></td>
                      <td class="text-xs-center">{{ props.item.aportes.length }}</td>
                    </tr>
                  </template>
                  <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    Lo sentimos, el criterio de busqueda "{{ search }}", no tiene registros coincidentes.
                  </v-alert>
                  <template slot="expand" slot-scope="props">
                    <v-card flat>
                      <v-card-text class="pt-1">
                        <v-card>
                          <v-card-title class="grey lighten-3">
                            <span class="subheading" style="font-weight: 500" v-html="`Aportes periodo: <strong>${props.item.periodo}</strong>`"></span>
                            <v-spacer></v-spacer>
                            <v-btn v-if="item" @click="openCardDialog(props.item)" class="mr-0" small color="primary" dark><span v-text="'Agregar Aporte'"></span></v-btn>
                          </v-card-title>
                          <v-data-table
                            :headers="headers2"
                            :items="props.item.aportes"
                            hide-actions
                            class="elevation-1 table-aportes"
                          >
                            <template slot="items" slot-scope="props">
                              <td>{{ props.item.planilla.numero }}</td>
                              <td class="text-xs-right">${{props.item.ibc | numberFormat(2)}}</td>
                              <td class="text-xs-center">{{ props.item.fecha_pago }}</td>
                              <td class="text-xs-center">{{ props.item.dias }}</td>
                              <td class="text-xs-right">${{props.item.cotizacion | numberFormat(2)}}</td>
                              <td class="text-xs-center"><v-icon :color="props.item.retiro ? 'accent' : ''">{{props.item.retiro ? 'check_box' : 'check_box_outline_blank'}}</v-icon></td>
                              <td class="text-xs-center"><v-icon :color="props.item.ingreso ? 'accent' : ''">{{props.item.ingreso ? 'check_box' : 'check_box_outline_blank'}}</v-icon></td>
                              <td class="text-xs-center">
                                <v-menu
                                  v-model="props.item.visible"
                                  :close-on-content-click="false"
                                  :nudge-width="200"
                                  offset-y
                                  v-if="props && props.item.gs_usuario_id !== null"
                                >
                                  <template v-slot:activator="{ on }" >
                                      <v-btn flat fab small icon v-on="on">
                                        <v-icon :color="'orange darken-1'"
                                                v-text="'pan_tool'"></v-icon>
                                      </v-btn>
                                  </template>

                                  <v-card>
                                    <v-list>
                                      <v-list-tile avatar>
                                        <v-list-tile-avatar>
                                          <img src="./../../../../assets/user.png" alt="user">
                                        </v-list-tile-avatar>

                                        <v-list-tile-content>
                                          <v-list-tile-title v-html="`<b>${props.item.usuario ? props.item.usuario.name : ''}</b>`"></v-list-tile-title>
                                          <v-list-tile-sub-title v-html="`<b>${props.item.usuario ? 'C.C. ' + props.item.usuario.identification : ''}</b>`"></v-list-tile-sub-title>
                                        </v-list-tile-content>
                                      </v-list-tile>
                                    </v-list>
                                    <v-divider></v-divider>
                                    <v-container class="pt-1 pb-1 pl-4 ml-0">
                                      <v-list>
                                        <v-list-tile-content>
                                          <v-list-tile-title v-html="`<b>Fecha Creación:</b> ${props.item ? moment(props.item.created_at).format('YYYY-MM-DD') : ''}`"></v-list-tile-title>
                                        </v-list-tile-content>
                                      </v-list>
                                    </v-container>
                                  </v-card>

                                </v-menu>

                              </td>
                            </template>
                            <template slot="no-data">
                              <span>No hay aportes registrados</span>
                            </template>
                          </v-data-table>
                        </v-card>
                        <!--                      <v-subheader>-->
                        <!--                        Aportes periodo: <strong> {{props.item.periodo}}</strong>-->
                        <!--                      </v-subheader>-->
                      </v-card-text>
                    </v-card>
                  </template>
                </v-data-table>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-slide-y-transition>
    </v-card>

  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST, SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'DetalleRelacionLaboral',
    props: ['parametros'],
    components: {
      Loading,
      InputNumber: () => import('@/components/general/InputNumber'),
      DetalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
      DetallePagador: () => import('@/components/misional/aseguramiento/pagadores/DetallePagador'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        cardDialog: false,
        menu2: false,
        dialogDetalle: false,
        detalleAporteManual: null,
        cargaLiquidacione: false,
        dialoInterno: false,
        loading: true,
        menu: false,
        mensaje: null,
        item: null,
        search: '',
        headers: [
          {
            text: 'Periodo',
            align: 'left',
            sortable: true,
            value: 'periodo'
          },
          {
            text: 'IBC',
            align: 'right',
            sortable: true,
            value: 'ibc'
          },
          {
            text: 'Días pagados',
            align: 'center',
            sortable: false
          },
          {
            text: 'Ingreso',
            align: 'center',
            sortable: false
          },
          {
            text: 'Retiro',
            align: 'center',
            sortable: false
          },
          {
            text: 'Aportes',
            align: 'center',
            sortable: false
          }
        ],
        headers2: [
          {
            text: 'Planilla',
            align: 'left',
            sortable: false
          },
          {
            text: 'IBC',
            align: 'right',
            sortable: false
          },
          {
            text: 'Fecha pago',
            align: 'center',
            sortable: false
          },
          {
            text: 'Días pagados',
            align: 'center',
            sortable: false
          },
          {
            text: 'Valor aporte',
            align: 'right',
            sortable: false
          },
          {
            text: 'Retiro',
            align: 'center',
            sortable: false
          },
          {
            text: 'Ingreso',
            align: 'center',
            sortable: false
          },
          {
            text: 'Tipo',
            align: 'center',
            sortable: false
          }
        ],
        aporte: null,
        numero: null,
        verified: false,
        custom: false,
        existe: null,
        textoBusqueda: null,
        buscarPlanilla: null,
        copiaLiquidacion: null
      }
    },
    created () {
      this.formReset()
      this.getRegistro(this.parametros.entidad.id)
    },
    watch: {
      'item' (value) {
        // console.log('ite,>', value)
      },
      'numero' (val) {
        if (!val) {
          this.verified = false
          this.existe = null
        }
      },
      'dialogDetalle' (val) {
        if (!val) {
          this.detalleAporteManual = false
        }
      }
    },
    computed: {
    },
    methods: {
      openDetalle (item) {
        // console.log('item', item)
        this.detalleAporteManual = JSON.parse(JSON.stringify(item))
        this.dialogDetalle = true
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.aporte.dias = 0
        this.aporte.ibc = 0
        this.aporte.cotizacion = 0
        this.cardDialog = false
      },
      openCardDialog (value) {
        // console.log('vbalals', value)
        this.cardDialog = !this.cardDialog
        this.aporte.liquidacion_id = value.id
        this.aporte.planilla.periodo = this.moment(value.periodo).format('YYYYMM')
        this.aporte.planilla.aportante_id = this.item ? this.item.pagador.id : null
        this.copiaLiquidacion = JSON.parse(JSON.stringify(value))
      },
      decidirUsoPlanilla (val, existe) {
        if (val === 1) {
          this.$validator.reset()
          this.verified = false
          this.numero = null
        } else if (val === 2 && existe === false) {
          this.verified = true
          this.aporte.planilla.numero = this.numero
        } else if (val === 2 && existe === true) {
          this.verified = true
          this.aporte.planilla = this.buscarPlanilla
          this.aporte.planilla_id = this.aporte.planilla.id
        }
        this.dialoInterno = false
      },
      formReset () {
        this.aporte = {
          id: null,
          planilla_id: null,
          liquidacion_id: null,
          dias: 0,
          ibc: 0,
          cotizacion: 0,
          fecha_pago: null,
          retiro: 0,
          ingreso: 0,
          observacion: null,
          novedad: 0,
          forzar_retiro: 0,
          planilla: {
            id: null,
            numero: null,
            aportante_id: null,
            periodo: null
          }
        }
        this.numero = null
        this.existe = null
        this.buscarPlanilla = null
        this.copiaLiquidacion = null
      },
      findByNumero () {
        this.textoBusqueda = 'Digite el número completo para realizar la búsqueda.'
        if (this.numero && this.numero.length >= 3) {
          this.custom = true
          this.textoBusqueda = 'Estamos bucando el documento en los registros.'
          this.axios.get('buscar_planillas/' + this.numero)
            .then(response => {
              this.custom = false
              this.existe = response.data.exists
              this.verified = true
              if (response.data.exists) {
                this.buscarPlanilla = response.data.data
                this.textoBusqueda = 'La planilla esta registrada.'
              } else {
                // this.mensaje = response.data.msg
                this.buscarPlanilla = null
                this.textoBusqueda = 'Nuevo número de planilla.'
                // console.log('aqui')
              }
              this.mensaje = response.data.msg
              this.dialoInterno = true
              // console.log('response', response)
            }).catch(e => {
              this.custom = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al verificar el número de planilla. ' + e.response, color: 'danger'})
            })
        } else {
          // console.log('null')
        }
      },
      async send () {
      // && this.aporte.ibc > 0 && this.aporte.cotizacion > 0 && this.aporte.dias > 0
        if (await this.validador('formRegistroAporte')) {
          let loader = this.$loading.show({
            container: this.$refs.cargar
          })
          this.cargaLiquidacione = true
          this.axios.post('cp_aportes', this.aporte)
            .then(response => {
              // console.log('response', response)
              this.$store.commit(SNACK_SHOW, {msg: 'Se agregó el aporte a la liquidación.', color: 'success'})
              this.item.liquidaciones.find(x => {
                if (x.id === this.copiaLiquidacion.id) {
                  // let diasPagos = x.dias_pagados
                  // let retiro = x.retiro
                  // let ingreso = x.ingreso
                  // x.push({
                  //   id: response.data.data.id,
                  //   dias_pagados: diasPagos + response.data.data.dias,
                  //   retiro: response.data.data.retiro === 1 ? response.data.data.retiro : retiro,
                  //   ingreso: response.data.data.ingreso === 1 ? response.data.data.ingreso : ingreso
                  // })
                  x.aportes.push({
                    id: response.data.data.id,
                    ibc: response.data.data.ibc,
                    cotizacion: response.data.data.cotizacion,
                    dias: response.data.data.dias,
                    fecha_pago: response.data.data.fecha_pago,
                    ingreso: response.data.data.ingreso,
                    retiro: response.data.data.retiro,
                    planilla: response.data.data.planilla,
                    planilla_id: response.data.data.planilla_id,
                    liquidacion_id: response.data.data.liquidacion_id,
                    created_at: response.data.created_at
                  })
                }
              })
              this.getRegistro(this.parametros.entidad.id)
              this.close()
              loader.hide()
              this.cargaLiquidacione = false
            }).catch(e => {
              loader.hide()
              this.cargaLiquidacione = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar el aporte. ', error: e})
            })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Existe uno o varios campos sin completar.', color: 'danger'})
          // if (this.aporte.ibc === 0) {
          //   this.$store.commit(SNACK_SHOW, {msg: 'Debe agregar un valor válido para el IBC.', color: 'danger'})
          // } else if (this.aporte.dias === 0) {
          //   this.$store.commit(SNACK_SHOW, {msg: 'Falta agregar el número de días.', color: 'danger'})
          // } else if (this.aporte.cotizacion === 0) {
          //   this.$store.commit(SNACK_SHOW, {msg: 'Debe agregar un valor válido para el valor del aporte.', color: 'danger'})
          // } else {
          //   this.$store.commit(SNACK_SHOW, {msg: 'Existe uno o varios campos sin completar.', color: 'danger'})
          // }
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      // findByNumero: window.lodash.debounce(function () {
      //   // console.log('valor', this.numero)
      //   this.textoBusqueda = 'Digite el número completo para realizar la búsqueda.'
      //   if (this.numero && this.numero.length >= 3) {
      //     this.custom = true
      //     this.textoBusqueda = 'Estamos bucando el documento en los registros.'
      //     this.axios.get('buscar_planillas/' + this.numero)
      //       .then(response => {
      //         this.custom = false
      //         this.existe = response.data.exists
      //         if (response.data.exists) {
      //           this.buscarPlanilla = response.data.data
      //           this.textoBusqueda = 'La planilla esta registrada.'
      //         } else {
      //           this.mensaje = response.data.msg
      //           this.buscarPlanilla = null
      //           this.textoBusqueda = 'Nuevo número de planilla.'
      //           console.log('aqui')
      //         }
      //         this.mensaje = response.data.msg
      //         this.dialoInterno = true
      //         console.log('response', response)
      //       }).catch(e => {
      //         this.custom = false
      //         this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al verificar el número de planilla. ' + e.response, color: 'danger'})
      //       })
      //   } else {
      //     console.log('null')
      //   }
      // }, 1000),
      getRegistro (id) {
        this.axios.get('afiliadoaportantes/' + id)
          .then(response => {
            // console.log('response relacion', response)
            if (response.data !== '') {
              this.item = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer la relación laboral. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>
  .table-aportes table.v-table tbody td {
    height: 0 !important;
  }
</style>
