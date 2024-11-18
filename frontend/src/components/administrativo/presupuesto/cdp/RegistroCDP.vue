<template>
  <div>
    <v-dialog v-model="dialog" persistent width="500px">
      <v-form data-vv-scope="formCdpDialog" @submit.prevent="agregarDetalle(detalle)">
        <v-card ref="dialogModificacion">
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar rubro</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12>
                <v-autocomplete
                  :items="rubros"
                  v-model="detalle.rubro"
                  @change="() => obtenerPresupuestoRubro()"
                  :filter="filterRubros"
                  item-text="nombre"
                  item-value="id"
                  name="rubro"
                  label="Rubro"
                  :hint="detalle.rubro ? detalle.rubro.codigo : ''"
                  persistent-hint
                  no-data-text="No hay rubros disponibles"
                  :error-messages="errors.collect('rubro')"
                  v-validate="'required|rubroValido'" required
                  prepend-icon="subtitles"
                  return-object
                >
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
                  :text="detalle.valor_disponible | numberFormat(0,'$')"
                />
              </v-flex>

              <v-flex xs12>
                <input-number
                  v-model.number="detalle.valor"
                  label="Valor a ejecutar"
                  name="valor a ejecutar"
                  :precision="0"
                  prepend-icon="attach_money"
                  v-validate="'required|min_value:1|max_value:' + detalle.valor_disponible"
                  :error-messages="errors.collect('valor a ejecutar')"
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
          <v-card-text class="title">¿Por qué anula el CDP?</v-card-text>
        </v-card-title>
        <v-container fluid>
          <v-layout row wrap>
            <v-flex xs12>
              <v-textarea
                outline
                v-model="cdp.concepto_anulacionint"
                color="primary"
                name="Detalle de anulación"
              >
                <div slot="label">
                  Detalle de anulación
                </div>
              </v-textarea>
              <!--<textarea box name="Detalle de anulación" v-model="detalle_anulacion" placeholder="Detalle de anulación"></textarea>-->
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
        <v-toolbar-title> Registro CDP </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-chip label color="white" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="cdp.consecutivo == null ? '00' : cdp.consecutivo"></span>
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
                <v-layout v-if="cdp.estado === 'Anulada'">
                  <v-flex xs12>
                    <v-alert
                      :value="true"
                      type="error"
                    >
                      <strong>Concepto anulación:</strong> {{ cdp.concepto_anulacionint }}
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
                            <v-text-field v-model="presupuesto.entidad_resolucion.periodo"
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
                          <v-flex xs12 sm4 v-if="estado !== 'Confirmada' && estado !== 'Anulada'">
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
                                :error-messages="errors.collect('fecha activo')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="cdp.fecha"
                                @input="menuDate = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha activo')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                  this.saveLocalStorage()
                                }"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4 v-else>
                            <v-text-field v-model="computedDateFormatted" prepend-icon="event"
                                          label="Fecha" key="fecha"
                                          name="fecha"
                                          readonly
                                          :error-messages="errors.collect('fecha')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field
                              v-model="cdp.vigente"
                              prepend-icon="assignment"
                              label="Vigencia (Días)"
                              key="vigente"
                              name="vigencia"
                              v-validate="'required|numeric'"
                              required
                              :readonly="readOnly"
                              @keyup="sumarDiasVigencia(cdp.vigente)"
                              :error-messages="errors.collect('vigencia')"
                            >
                            </v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 v-if="cdp.vigente">
                            <v-text-field v-model="cdp.fecha_vence" prepend-icon="event"
                                          label="Fecha vence" key="fecha vence"
                                          name="fecha vence" v-validate="'required'" required
                                          readonly
                                          :error-messages="errors.collect('fecha vence')" ></v-text-field>
                          </v-flex>

                        </v-layout>

                        <v-layout row wrap>
                          <v-flex xs12 sm4>
                            <v-autocomplete
                              :items="complementos.dependencias"
                              v-model="dependencia"
                              @change="val => cdp.pr_dependencia_id = val ? val.id : null"
                              item-text="codigo"
                              item-value="id"
                              name="dependencias"
                              label="Dependencia"
                              no-data-text="No hay dependenciass disponibles"
                              :error-messages="errors.collect('dependencias')"
                              v-validate="'required'" required
                              prepend-icon="subtitles"
                              return-object
                              :readonly="readOnly"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.codigo_nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm8 v-if="dependencia">
                            <v-text-field v-model="dependencia.nombre"
                                          label="Nombre"
                                          key="nombre"
                                          name="nombre"
                                          readonly></v-text-field>
                          </v-flex>


                        </v-layout>

                        <v-layout>
                          <v-flex xs12>
                            <v-textarea v-model="cdp.objecto" prepend-icon="reorder"
                                        label="Objeto" key="objeto"
                                        name="objeto" v-validate="'required'" required
                                        :readonly="readOnly"
                                        :error-messages="errors.collect('objeto')" ></v-textarea>
                          </v-flex>
                        </v-layout>

                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4" v-if="presupuesto.id">
                    <template>
                      <loading v-model="localLoading" />
                      <v-card>
                        <v-toolbar dense class="grey lighten-4 elevation-0">
                          <v-toolbar-title class="subheading">Rubros</v-toolbar-title>
                          <v-spacer></v-spacer>
                          <v-btn v-if="!readOnly" small color="primary" @click.prevent="openDialog"><v-icon>add</v-icon> Agregar rubro</v-btn>
                        </v-toolbar>
                        <v-data-table :headers="headers"
                                      :items="cdp.detalles"
                                      :loading="tableLoading"
                                      hide-actions rows-per-page-text="Registros por página"
                                      :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                          <template slot="items" slot-scope="props">
                            <td>{{ props.item.rubro.codigo }}</td>
                            <td>{{ props.item.rubro.nombre }}</td>
                            <td>{{ props.item.tipo_gasto.nombre }}</td>
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
                              <strong>Valor CDP: {{ cdp.valor_cdp | numberFormat(0,'$') }}</strong>
                            </td>
                          </template>
                        </v-data-table>
                      </v-card>
                    </template>
                  </v-flex>

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
            <v-btn v-if="cdp.id" @click="dialogAnular = true" dark color="danger" :loading="loadingSubmit">Anular</v-btn>
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
  import {CDP_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import store from '@/store/complementos/index'
  import { Validator } from 'vee-validate'
  import InputNumber from '@/components/general/InputNumber'

  export default {
    name: 'registroCDP',
    props: ['parametros'],
    components: {
      Loading,
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      InputNumber
    },
    data () {
      return {
        cdp: null,
        presupuesto: null,
        presupuestos: [],
        rubro: {},
        rubros: [],
        rubrosCreados: '',
        presupuestoRubro: {},
        detalle: {},
        dependencia: null,
        localLoading: false,
        seEstaCreando: false,
        tableLoading: false,
        loadingSubmit: false,
        dialog: false,
        dialogAnular: false,
        menuDate: false,
        readOnly: false,
        estado: null,
        headers: [
          {
            text: 'Rubro',
            align: 'left',
            sortable: false,
            value: 'rubro'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Tipo gasto',
            align: 'left',
            sortable: false,
            value: 'tipoGasto'
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
      this.loadLocalStorage()
    },
    mounted () {
      this.parametros.entidad !== null ? this.getRegistro(this.parametros.entidad.id) : this.obtenerPresupuestos()
      this.resetDialog()

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
      complementos () {
        return store.getters.complementosPresupuesto
      },
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
        return this.formDate(this.cdp.fecha)
      }
    },
    watch: {
      'detalle.pr_rubro_id' (value) {
        if (value) {
          this.detalle.rubro = this.rubros.find(rubro => {
            return rubro.id === value
          })
          this.obtenerPresupuestoRubro()
        }
      },
      'cdp.fecha' () {
        this.sumarDiasVigencia(this.cdp.vigente)
      }
    },
    methods: {
      getRegistro (id) {
        let loader = this.$loading.show({
          container: this.$refs.formPresupuesto.$el
        })
        this.axios.get('pr_cdps/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.cdp = response.data.data
              this.estado = this.cdp.estado
              this.dependencia = this.cdp.dependencia
              if (response.data.data.estado === 'Registrada') this.obtenerPresupuesto(this.cdp.presupuesto_inicial_gasto.periodo)
              if (this.cdp.estado === 'Confirmada' || this.cdp.estado === 'Anulada') {
                this.readOnly = true
                this.cdp.periodo = response.data.data.periodo
                this.presupuesto = response.data.data.presupuesto_inicial_gasto
              }
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
        this.axios.get('cdps/' + periodo)
          .then((response) => {
            if (response.data !== '') {
              this.presupuesto = response.data.strgasto
              this.rubros = response.data.rubros
              this.cdp.pr_strgasto_id = this.presupuesto.id
              this.cdp.periodo = this.presupuesto.periodo
              this.cdp.pr_entidadresolucion_id = this.presupuesto.entidad_resolucion.id
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
        this.axios.get('cdp_periodos_presupuestos ')
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
      obtenerPresupuestoRubro () {
        let loader = this.$loading.show({
          container: this.$refs.dialogModificacion.$el
        })
        this.axios.get(`cdp_presupuesto_rubro?strgastoId=${this.presupuesto.id}&rubroId=${this.detalle.rubro.id}`)
          .then((response) => {
            if (response.data !== '') {
              this.presupuestoRubro = response.data.data
              this.detalle.tipo_gasto = this.presupuestoRubro.tipo_gasto
              this.detalle.valor_disponible = this.presupuestoRubro.valor_disponible
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      getRubrosUsados () {
        return this.cdp.detalles.map(cod => { if (!(this.detalle.rubro.id != null && this.detalle.rubro.id === cod.rubro.id)) return cod.rubro.id })
      },
      async agregarDetalle (detalle) {
        if (await this.validador('formCdpDialog')) {
          this.agregarValoresAlDetalle()
          if (typeof detalle.index !== 'number') {
            this.cdp.detalles.push(detalle)
          } else {
            this.cdp.detalles
              .splice(this.cdp.detalles
                .findIndex(x => this.cdp.detalles.indexOf(x) === detalle.index), 1, detalle)
          }
          this.calcularCdp()
          this.dialog = false
          this.resetDialog()
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
        }
      },
      saveLocalStorage () {
        if (this.cdp.fecha) localStorage.setItem('fechaCdp', JSON.stringify(this.cdp.fecha))
      },
      loadLocalStorage () {
        if (localStorage.getItem('fechaCdp')) { this.cdp.fecha = JSON.parse(localStorage.getItem('fechaCdp')) }
      },
      editarDetalle (index, detalle) {
        this.detalle = JSON.parse(JSON.stringify(detalle))
        this.detalle.index = index
        this.dialog = true
        this.rubrosCreados = this.getRubrosUsados()
      },
      eliminarDetalle (index) {
        this.cdp.detalles.splice(index, 1)
        this.calcularCdp()
        this.dialog = false
      },
      calcularCdp () {
        let valorCalculado = 0
        for (let detalle of this.cdp.detalles) {
          valorCalculado += detalle.valor
        }
        this.cdp.valor_cdp = valorCalculado
      },
      formReset () {
        this.cdp = {
          id: '',
          periodo: null,
          fecha: null,
          fecha_vence: null,
          vigente: null,
          documento: null,
          detalle_modificacion: null,
          estado: 'Registrada',
          concepto_anulacion: null,
          fecha_anulacion: null,
          valor_cdp: 0,
          detalles: []
        }
        this.rubrosCreados = this.getRubrosUsados()

        this.presupuesto = {
          entidad_resolucion: {periodo: ''},
          detalles: []
        }

        this.presupuestoRubro = {
          tipo_gasto: null,
          valor_inicial: null
        }
      },
      resetDialog () {
        this.dialog = false
        this.detalle = {
          pr_tipo_gasto_id: null,
          tipo_gasto: {nombre: null},
          valor: 0,
          valor_ejecutado: null,
          valor_disponible: null,
          rubro: {id: '', codigo: ''}
        }
        this.$validator.reset()
        this.rubrosCreados = this.getRubrosUsados()
      },
      agregarValoresAlDetalle () {
        this.detalle.pr_detstrgasto_id = this.presupuestoRubro.id
        this.detalle.pr_rubro_id = this.detalle.rubro.id
        this.detalle.pr_tipo_gasto_id = this.presupuestoRubro.tipo_gasto.id
      },
      sumarDiasVigencia (dias) {
        this.cdp.fecha_vence = this.moment(this.cdp.fecha).add(dias, 'days').format('YYYY/MM/DD')
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
      filterRubros (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.codigo + item.codigo.split('.').join(''))
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      confirmar () {
        this.cdp.estado = 'Confirmada'
        this.submit()
      },
      anular () {
        this.dialogAnular = false
        this.cdp.estado = 'Anulada'
        this.submit()
      },
      async submit () {
        if (await this.validador('formCdp')) {
          this.localLoading = true
          const typeRequest = this.seEstaCreando ? 'crear' : 'editar'
          let send = this.seEstaCreando ? this.axios.post('pr_cdps', this.cdp) : this.axios.put('pr_cdps/' + this.cdp.id, this.cdp)
          send.then(response => {
            if (this.seEstaCreando) {
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.$store.commit(CDP_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.$store.commit(CDP_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
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

