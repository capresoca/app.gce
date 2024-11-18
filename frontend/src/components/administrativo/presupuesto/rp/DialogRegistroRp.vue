<template>
  <div>
    <v-card v-if="(mensajeNoRubro === null && rps.length)">
<!--      <toolbar-list title="Asignación de Rubros"/>-->
      <v-toolbar class="grey lighten-3 pt-0 pb-0 dense elevation-0">
        <span class="subheading font-weight-medium" v-text="`Asignación de Rubros`"></span>
        <v-spacer></v-spacer>
        <v-btn icon v-if="estadoCxp === null || estadoCxp === 'Registrado'" @click="cardDialog = !cardDialog">
          <v-icon color="dark" v-text="`fas fa-${cardDialog ? 'chevron-up' : 'chevron-down'}`" size="15px"></v-icon>
        </v-btn>
      </v-toolbar>
      <v-expand-transition>
        <template v-if="cardDialog">
          <v-form data-vv-scope="formRubroObligacion">
            <v-container fluid grid-list-xl>
              <v-layout row wrap>
                <v-flex xs12 sm2 md2 xl2>
                  <v-autocomplete
                    :label="`RP ${value.rp ? 'consecutivo' : ''}`"
                    v-model="value.rp"
                    @change="val => obtenerRubrosYCdp(val.id)"
                    :items="rps"
                    :hint="value.rp ? (`Fecha: ${moment(value.rp.fecha).format('YYYY/MM/DD')}`) : ''"
                    persistent-hint
                    item-text="consecutivo"
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
                          <v-list-tile-title v-html="`Fecha: ${moment(data.item.fecha).format('YYYY/MM/DD')} - Periodo: ${data.item.periodo}`"/>
                          <v-list-tile-sub-title v-html="'Consecutivo: ' + str_pad(data.item.consecutivo,6)"/>
                        </v-list-tile-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 sm4 md4 xl4>
                  <v-autocomplete
                    :label="`Rubro ${value.rubro ? ('No. ' + value.rubro.codigo) : ''}`"
                    v-model="value.rubro"
                    @change="val => obtenerRubro()"
                    :items="rubrosRp"
                    :filter="filterRubros"
                    :hint="value.rubro ? ((value.tipo_gasto ? 'Tipo Gasto: '+ value.tipo_gasto.nombre : '')) : ''"
                    persistent-hint
                    item-text="nombre"
                    item-value="id"
                    name="rubro"
                    no-data-text="No existen datos"
                    prepend-icon="subtitles"
                    :disabled="!value.rp"
                    v-validate="'required'"
                    return-object
                    :error-messages="errors.collect('rubro')"
                    clearable
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
                <v-flex xs12 sm3 class="pt-0">
                  <input-detail-flex
                    label="Valor disponible"
                    prependIcon="money"
                    :text="value.saldo_por_obligar | numberFormat(0,'$')"
                  />
                </v-flex>
                <v-flex xs12 sm3>
                  <input-number
                    v-model.number="value.valor"
                    label="Saldo"
                    name="saldo"
                    :precision="0"
                    prepend-icon="attach_money"
                    v-validate="'required|max_value:' + value.saldo_por_obligar"
                    :error-messages="errors.collect('saldo')"
                  ></input-number>
                </v-flex>
                <v-flex xs12 class="text-xs-right grey lighten-3 pt-0 pb-0">
                  <v-btn  flat small color="primary" class="mr-0 mt-0 mb-0" dark @click.prevent="agregar">
                    <v-icon  v-text="'fas fa-plus'" size="14px" color="cyan"></v-icon>
                    &nbsp;
                    <span v-text="value.index ? 'Actualizar' : 'Agregar'"></span>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-form>
        </template>
      </v-expand-transition>
      <v-data-table v-if="openTable"
                    :headers="headers"
                    :items="dataTableDetails"
                    hide-actions rows-per-page-text="Registros por página"
                    :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
        <template slot="items" slot-scope="props">
          <td>{{ props.item.cdp.consecutivo }}</td>
          <td>{{ props.item.rp.consecutivo }}</td>
          <td> <span v-html="`<b>${ props.item.rubro.codigo}</b> - ${props.item.rubro.nombre}`"></span></td>
          <td>{{ props.item.tipo_gasto.nombre }}</td>
          <td>{{ props.item.rp.fecha }}</td>
          <td class="text-xs-right">{{ currency(props.item.saldo_por_obligar) }}</td>
          <td class="text-xs-right">{{ props.item.valor | numberFormat(0,'$') }}</td>
          <td>
            <v-speed-dial
              v-if="estadoCxp === null || estadoCxp === 'Registrado'"
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
<!--              <v-tooltip top>-->
<!--                <v-btn-->
<!--                  fab-->
<!--                  dark-->
<!--                  small-->
<!--                  color="white"-->
<!--                  slot="activator"-->
<!--                  @click="eliminarDetalle(props.index, props.item.id)"-->
<!--                >-->
<!--                  <v-icon color="accent">delete</v-icon>-->
<!--                </v-btn>-->
<!--                <span>Eliminar</span>-->
<!--              </v-tooltip>-->
            </v-speed-dial>
            <div v-else>
              <v-tooltip top>
                <v-btn slot="activator" icon small fab @click.prevent="$store.commit('NAV_ADD_ITEM', { ruta: 'registroObligaciones', titulo: 'Visor de Obligación', parametros: {entidad: {id: props.item.pr_obligacion_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})">
                  <v-icon color="teal" v-text="'fas fa-paperclip'"></v-icon>
                </v-btn>
                <span v-text="'Ir a la Obligación'"></span>
              </v-tooltip>
            </div>
          </td>
        </template>
<!--        <template slot="no-data">-->
<!--          <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">-->
<!--            Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>-->
<!--          </v-alert>-->
<!--          <v-alert v-else :value="true" color="info" icon="info">-->
<!--            Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>-->
<!--          </v-alert>-->
<!--        </template>-->
<!--        <template slot="pageText" slot-scope="props">-->
<!--          {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}-->
<!--        </template>-->
<!--        <template v-slot:footer>-->
<!--          <td :colspan="headers.length">-->
<!--            <v-spacer></v-spacer>-->
<!--            <strong>Valor compromiso: {{ obligacion.valor_obligacion | numberFormat(0,'$') }}</strong>-->
<!--          </td>-->
      </v-data-table>
    </v-card>
    <v-flex class="pa-0" v-else-if="mensajeNoRubro !== null">
      <v-expand-transition>
        <template>
          <v-alert
            :value="true"
            color="orange"
            icon="priority_high"
            outline
          >
            <span v-html="`${mensajeNoRubro}`"></span>
          </v-alert>
        </template>
      </v-expand-transition>
    </v-flex>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import InputNumber from '@/components/general/InputNumber'
  // import { Validator } from 'vee-validate'
  export default {
    name: 'DialogRegistroRp',
    components: {
      InputNumber,
      ToolbarList: () => import('@/components/general/ToolbarList'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex')
    },
    props: {
      value: {
        type: Object,
        default: null
      },
      tercero: {
        type: Object,
        default: null
      },
      estadoCxp: {
        type: String,
        default: null
      },
      dataTableDetails: {
        type: Array,
        default: function () { return [] }
      }
    },
    data () {
      return {
        mensajeNoRubro: null,
        rps: [],
        cardDialog: false,
        openTable: false,
        rubrosRp: [],
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
    watch: {
      'tercero' (val) {
        // console.log('val', val)
        this.rpsTercero(val)
      },
      'dataTableDetails' (vals) {
        // console.log('val', vals)
        let nuth = false
        this.openTable = (vals.length) ? true : nuth
        if (vals.length) {
          let suma = 0
          vals.forEach(x => {
            suma += x.valor
          })
          this.$emit('total_detalles', suma)
        }
      }
    },
    // beforeMount () {
    //   this.validadorPostulador()
    // },
    methods: {
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async agregar () {
        let item = this.value
        if (await this.validador('formRubroObligacion')) {
          this.$emit('agregar', item)
          this.$emit('cancelar')
        }
      },
      filterRubros (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.codigo)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      // validadorPostulador () {
      //   Validator.extend('rubroValido', {
      //     validate: (value, prop) => new Promise((resolve) => {
      //       let response = {}
      //       if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
      //         this.rubrosCreados.includes(value.id) ? response = {valido: false, mensaje: 'Rubro ya usado'} : response = {valido: true, mensaje: null}
      //         return resolve({
      //           valid: response.valido,
      //           data: {
      //             message: response.mensaje
      //           }
      //         })
      //       }
      //     }),
      //     getMessage: (field, params, data) => data.message
      //   })
      // },
      rpsTercero (val) {
        if (val) {
          this.axios.get(`rps/tercero/${val.id}`)
            .then((response) => {
              // console.log('response', response)
              if (response.data.exists === false) {
                this.mensajeNoRubro = response.data.msg
                this.rps = []
              } else {
                this.mensajeNoRubro = null
                this.rps = response.data.rps
              }
            })
        } else {
          this.mensajeNoRubro = null
          this.rps = []
        }
      },
      editarDetalle (index, detalle) {
        this.obtenerRubrosYCdp(detalle.rp.id)
        this.$emit('editar', {index, detalle})
        this.cardDialog = true
        // this.detalle = JSON.parse(JSON.stringify(detalle))
        // this.detalle.index = index
        // this.rubrosCreados = this.getRubrosUsados()
      },
      obtenerRubrosYCdp (idRp) {
        this.obtenerRubrosRP(idRp)
        this.obtenerCdpDelRp(idRp)
      },
      obtenerRubrosRP (idRp) {
        this.axios.get(`obligaciones/rubros/${idRp}`)
          .then((response) => {
            if (response.data !== '') {
              this.rubrosRp = response.data.rubros
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los rubros. ' + e, color: 'danger'})
          })
      },
      obtenerCdpDelRp (idRp) {
        this.axios.get(`obligaciones/rp/cdp/${idRp}`)
          .then((response) => {
            if (response.data !== '') {
              this.value.cdp = response.data.cdp
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el cdp. ' + e, color: 'danger'})
          })
      },
      obtenerRubro () {
        this.axios.get(`obligacion_rubro?rpId=${this.value.rp.id}&rubroId=${this.value.rubro.id}`)
          .then((response) => {
            if (response.data !== '') {
            //   this.detalleRp = response.data.data
              this.value.pr_detrp_id = response.data.data.id
              this.value.pr_tipo_gasto_id = response.data.data.tipo_gasto.id
              this.value.saldo_por_obligar = response.data.data.valor_disponible
              this.value.tipo_gasto = response.data.data.tipo_gasto
              // this.value.valor_disponible = response.data.data.valor_disponible
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el rubro. ' + e, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
