<template>
  <v-card>
    <v-form data-vv-scope="formcuentaRadicada">
      <v-container fluid grid-list-xl>
        <v-layout row wrap class="refSpace">
          <v-flex xs12 sm12 md5 lg5 xl5 class="grey lighten-4">
            <v-toolbar class="grey lighten-4 elevation-0 toolbar--dense">
              <v-layout row wrap>
                <v-flex xs12 sm6 md12 class="pa-0 pt-2 head1">
                  <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
                </v-flex>
                <v-flex xs12 sm6 md12 class="pa-0 head2">
                  <h2 v-if="cmradicada.id !== null" class="d-inline-block subheading">Referencia</h2>
                  <div v-if="cmradicada.id !== null" class="d-inline-block">
                    <v-chip label color="white" text-color="red" dark absolute right top>
                      <span class="subheading">N°</span>&nbsp;
                      <span class="subheading" v-text="cmradicada.consecutivo == null ? '0000' : cmradicada.consecutivo_radicado"></span>
                    </v-chip>
                  </div>
                </v-flex>
              </v-layout>
              <!--&nbsp;-->
            </v-toolbar>
          </v-flex>
          <v-spacer></v-spacer>
          <v-divider vertical class="dNone pl-1"></v-divider>
          <v-flex xs12 sm6 md2>
            <input-date
              v-model="cmradicada.periodo_inicio"
              label="Periodo Radicación"
              placeholder="(Año-Mes-Día)"
              format="YYYY-MM-DD"
              name="periodo radicación"
              v-validate="'required|fechaValida'"
              :error-messages="errors.collect('periodo radicación')"
              :disabled="cmradicada.id ? true : (false)"
            ></input-date>
<!--            <v-datetime-picker-->
<!--              :label="'Periodo Radicación'"-->
<!--              :readonly="cmradicada.id ? true : (false)"-->
<!--              :datetime="cmradicada.periodo_inicio" @input="updateDatetime" :timeData="cmradicada.periodo_inicio"></v-datetime-picker>-->
          </v-flex>
          <v-flex xs12 sm6 md2>
            <input-date
              v-model="cmradicada.periodo_fin"
              label="Periodo Fin"
              format="YYYY-MM-DD"
              placeholder="(Año-Mes-Día)"
              name="periodo fin"
              v-validate="'required|fechaValida'"
              :error-messages="errors.collect('periodo fin')"
              :disabled="cmradicada.id ? true : (false)"
              :min="cmradicada.periodo_inicio"
            ></input-date>
<!--            <v-datetime-picker :label="'Periodo Fin'" :readonly="cmradicada.id ? true : (false)" :datetime="cmradicada.periodo_fin" @input="updateDatetimeTwo" :timeData="cmradicada.periodo_fin" :min="cmradicada.periodo_inicio"></v-datetime-picker>-->
          </v-flex>
          <v-flex xs12 sm6 md6>
            <v-text-field
              v-model="cmradicada.radicado_entidad"
              class="pl-0 pb-0"
              label="Radicado Entidad"
              v-validate="'required|not_in:' + codigosUsados + '|max:15'"
              name="radicado_entidad"
              key="radicado_entidad"
              :readonly="cmradicada.id ? true : (false)"
              :error-messages="errors.collect('radicado_entidad')">
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md6>
            <input-date
              v-model="cmradicada.fecha_radicado"
              label="Fecha Radicado (Año-Mes-Día)"
              format="YYYY-MM-DD"
              name="fecha radicado"
              v-validate="'required|fechaValida'"
              :error-messages="errors.collect('fecha radicado')"
              :disabled="cmradicada.id ? true : (false)"
            ></input-date>
<!--            <v-datetime-picker :label="'Fecha Radicado'" :readonly="cmradicada.id ? true : (false)" :datetime="cmradicada.fecha_radicado" @input="updateDatetimeThree" :timeData="cmradicada.fecha_radicado"></v-datetime-picker>-->
          </v-flex>
          <v-flex xs12 sm12 md6>
            <v-autocomplete
              :loading="loadingIPSs"
              prepend-icon="fas fa-clinic-medical"
              label="Entidad"
              v-model="cmradicada.rs_entidad_id"
              :items="IPSs"
              item-value="id"
              item-text="nombre"
              name="entidad"
              v-validate="'required'"
              :error-messages="errors.collect('entidad')"
              :filter="filterIPSs"
              persistent-hint
              :hint="cmradicada.rs_entidad_id && IPSs ? IPSs.find(x => x.id === cmradicada.rs_entidad_id).cod_habilitacion : ''"
            >
              <template slot="item" slot-scope="data">
                <template>
                  <v-list-tile-content>
                    <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                    <v-list-tile-sub-title>Código habilitación: {{data.item.cod_habilitacion}}</v-list-tile-sub-title>
                  </v-list-tile-content>
                </template>
              </template>
            </v-autocomplete>
          </v-flex>
          <v-flex xs12 sm12 md6>
            <v-autocomplete
              :label="cmradicada.rs_contrato_id ? 'Plan de cobertura' : 'Seleccionar contrato'"
              v-model="cmradicada.contrato"
              @change="val => cmradicada.rs_contrato_id = val ? val.id : null"
              :items="contratosIps"
              prepend-icon="list_alt"
              item-value="id"
              item-text="nombre"
              name="contrato"
              return-object
              :readonly="cmradicada.id ? true : (false)"
              :hint="cmradicada.contrato ? `Contrato: ${cmradicada.contrato.contrato.numero_contrato}` : ''"
              persistent-hint
            >
              <template slot="item" slot-scope="data">
                <v-list-tile-content class="truncate-content" style="width: 100% !important;">
                  <v-list-tile-title>Plan cobertura: {{data.item.nombre}}</v-list-tile-title>
                  <v-list-tile-sub-title>Contrato: {{data.item.contrato.numero_contrato}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </template>
              <template slot="no-data">
                <div class="pa-2">
                  <span class="font-weight-thin body-1">
                    <v-icon v-text="'send'"></v-icon>
                    Seleccione la IPS para cargar los contratos.
                  </span>
                </div>
              </template>
            </v-autocomplete>
          </v-flex>
          <v-slide-y-transition>
            <v-flex xs12 v-if="ripsRadicados.length">
              <v-card class="pa-0 elevation-0 dense">
                <toolbar-list title="Listado de RIPS" subtitle="Radicados"/>
                <v-data-table
                  v-model="selected"
                  :headers="headers"
                  :items="ripsRadicados"
                  :loading="tableLoading"
                  :expand="expanded"
                  hide-actions
                  class="elevation-0 dense">
                  <template slot="items" slot-scope="props">
                    <tr :active="props.item.selected"
                        @click.prevent="(props.item.selected)
                        ? (props.expanded = !props.expanded)
                        : null">
                      <td>{{ props.item.cod_radicacion }}</td>
                      <td>{{ props.item.estado_radicacion }}</td>
                      <td>
                        <v-tooltip center left class="pa-0" :value="props.item.tableLoadingTwo" >
                          <v-checkbox
                            :key="'rip' + props.index"
                            slot="activator"
                            v-model="props.item.selected"
                            on-icon="receipt"
                            off-icon="receipt"
                            @change="getFacturas(props)" class="pt-4 pb-0 pl-2">
                          </v-checkbox>
                            <span v-if="props.item.tableLoadingTwo">
                              Cargando... <v-progress-circular :indeterminate="props.item.tableLoadingTwo" :value="0" size="24" class="ml-2"></v-progress-circular>
                            </span>
                          <span v-else v-text="'Obtener Facturas'"></span>
                        </v-tooltip>
                      </td>
                    </tr>
                  </template>
                  <template slot="expand" slot-scope="props">
                    <v-card flat>
                      <v-data-table
                        :headers="headerTwos"
                        :items="props.item.facturas"
                        :loading="props.item.tableLoadingTwo"
                        hide-actions
                        class="elevation-0 dense">
                        <template slot="items" slot-scope="props">
                          <td>{{ props.item.consecutivo }}</td>
                          <td>{{ props.item.fecha }}</td>
                          <td class="text-xs-center">{{ props.item.no_contrato ? props.item.no_contrato : 'No registra'  }}</td>
                          <td>{{ props.item.plan_beneficio }}</td>
                          <td class="text-xs-right">{{ props.item.valor_compartido | numberFormat(2,'$') }}</td>
                          <td class="text-xs-right">{{ props.item.valor_neto | numberFormat(2,'$') }}</td>
                        </template>
                        <template slot="no-data">
                          <v-alert  v-if="!props.item.tableLoadingTwo" :value="true" color="error" icon="warning">
                            Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                          </v-alert>
                          <v-alert v-else :value="true" color="info" icon="info">
                            Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                          </v-alert>
                        </template>
                        <template slot="footer">
                          <tr class="green accent-1" :class="{'red lighten-2': props.item.totalDetalles.totalCompartido !== 0}">
                            <td><strong>Cantidad facturas: {{props.item.facturas.length}}</strong></td>
                            <td colspan="3" class="text-xs-right"><strong>TOTAL</strong></td>
                            <td class="text-xs-right extfont-weight-regular">
                              {{ currency(props.item.totalDetalles.totalCompartido) }}
                            </td>
                            <td class="text-xs-right font-weight-regular" :class="props.item.totalDetalles.clase">
                              {{ currency(props.item.totalDetalles.totalValorneto) }}
                            </td>
                          </tr>
                        </template>
                      </v-data-table>
                    </v-card>
                  </template>
                </v-data-table>
              </v-card>
            </v-flex>
          </v-slide-y-transition>
          <v-flex xs12 sm12 md12 xl12 class="pt-1">
            <v-textarea
              v-model="cmradicada.observaciones"
              color="primary"
              name="observaciones"
              key="observaciones"
              :readonly="cmradicada.id ? true : (false)"
              v-validate="'required'"
              :error-messages="errors.collect('observaciones')">
              <div slot="label">
                Observaciones
              </div>
            </v-textarea>
          </v-flex>
        </v-layout>
      </v-container>
    </v-form>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn flat @click="close">Cancelar</v-btn>
      <v-btn color="blue darken-1" @click.prevent="save()" flat>Radicar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import VDatetimePicker from '../../general/VDatetimePicker'
  // import {CUENTA_RADICADA_RELOAD_ITEM} from '../../../store/modules/general/tables'
  export default {
    name: 'RegistroCuentaradicar',
    components: {
      VDatetimePicker,
      InputDate: () => import('@/components/general/InputDate'),
      ToolbarList: () => import('@/components/general/ToolbarList'),
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    props: ['parametros'],
    data () {
      return {
        filterIPSs (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.cod_habilitacion + ' ' + item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        cmradicada: {},
        ripsRadicados: [],
        contratosIps: [],
        expanded: false,
        selected: [],
        tableLoading: false,
        tableLoadingTwo: false,
        datetime: this.moment().format('YYYY-MM-DD HH:mm:ss'),
        headerTwos: [
          {
            text: '# Factura',
            align: 'left',
            sortable: false,
            value: 'consecutivo'
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false,
            value: 'fecha'
          },
          {
            text: 'Número Contrato',
            align: 'center',
            sortable: false,
            value: 'no_contrato'
          },
          {
            text: 'Plan Beneficio',
            align: 'left',
            sortable: false,
            value: 'plan_beneficio'
          },
          {
            text: 'Valor Compartido',
            align: 'right',
            sortable: false,
            value: 'valor_compartido'
          },
          {
            text: 'Valor Neto',
            align: 'right',
            sortable: false,
            value: 'valor_neto'
          }
        ],
        headers: [
          {
            text: 'Código de Radicación',
            align: 'left',
            sortable: false,
            value: 'cod_radicacion'
          },
          { text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado_radicacion'
          },
          { text: 'Selección',
            align: 'left',
            sortable: false,
            value: 'estado_radicacion'
          }
        ],
        totalDetalles: {
          totalCompartido: 0,
          totalComision: 0,
          totalDescuentos: 0,
          totalValorneto: 0,
          clase: null
        },
        radicados: [],
        codigosUsados: '',
        IPSs: [],
        loadingIPSs: false
      }
    },
    watch: {
      'cmradicada.rs_entidad_id' (val) {
        if (val) {
          this.getRipsradicados(val)
          this.getContratos(val)
        } else {
          this.cmradicada.rs_contrato_id = null
          this.cmradicada.contrato = null
        }
      }
    },
    beforeCreate () {
      this.axios.get('cm_radicadas')
        .then((response) => {
          this.radicados = response.data.data
          this.codigosUsados = this.getCodigosUsados()
          // console.log(this.radicados)
        })
        .catch(e => {
          this.$store.commit(SNACK_SHOW, {msg: 'Se ha presentado un error al guardar.', color: 'error'})
          return false
        })
    },
    created () {
      this.formReset()
      this.getIPSs()
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad)
      const dict = {
        custom: {
          radicado_entidad: {
            not_in: 'El consecutivo ya se encuentra registrado.'
          }
        }
      }

      this.$validator.localize('es', dict)
    },
    methods: {
      closex (x) {
        console.log('se cerro', x)
      },
      getRegistro (entidad) {
        // this.cmradicada = entidad
        this.axios.get('cm_radicadas/' + entidad.id)
          .then(response => {
            console.log('ggg', response.data)
            // this.cmradicada = response.data.data
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al cargar el registro.', color: 'error'})
          })
      },
      getIPSs () {
        this.loadingIPSs = true
        this.axios.get('entidades_rips')
          .then(response => {
            this.IPSs = response.data.data
            this.loadingIPSs = false
          }).catch(e => {
            this.loadingIPSs = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer el listado de entidades. ', error: e})
          })
      },
      // getFecha (val) {
      //   let datetime = this.moment(val).format('YYYY-MM-DD h:mm:ss a')
      //   let date1 = datetime.split(' ')[0]
      //   let dateFormat = this.moment(date1).format('YYYY-MM-DD')
      //   return `${dateFormat}`
      // },
      getCodigosUsados () {
        return this.radicados.map(cod => { if (!(this.cmradicada.radicado_entidad != null && this.cmradicada.radicado_entidad === cod.radicado_entidad)) return cod.radicado_entidad })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        let loader = this.$loading.show({
          container: this.$refs.cargar
        })
        if (await this.validador('formcuentaRadicada') && this.cmradicada.rips_id !== null) {
          // const typeRequest = this.cmradicada.id ? 'editar' : 'crear'
          this.axios.post('cm_radicadas', this.cmradicada)
            .then(res => {
              this.$store.commit(SNACK_SHOW, {msg: 'Se está realizando el proceso.', color: 'success'})
              this.$store.commit('reloadTable', 'tableCuentasMedicas')
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
              loader.hide()
              // this.$store.commit(CUENTA_RADICADA_RELOAD_ITEM, {item: res.data.data, estado: typeRequest, key: this.parametros.key})
              // this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
            }).catch(e => {
              loader.hide()
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' Hay un error al traer los registros.', error: e})
            // this.$store.commit(SNACK_ERROR_LIST, {expression: 'Hay un error al traer los registros.', error: e})
            // this.$store.commit(SNACK_SHOW, {msg: e.response.data.msg, color: 'error'})
            })
        } else {
          loader.hide()
          if (this.cmradicada.rips_id === null) {
            this.$store.commit(SNACK_SHOW, {msg: 'Debe seleccionar un rip para procesar.', color: 'error'})
          } else {
            this.$store.commit(SNACK_SHOW, {msg: 'Se ha presentado un error al guardar.', color: 'error'})
          }
        }
      },
      sumatoria (item) {
        let valorCompartido = 0
        let valorComision = 0
        let valorDescuentos = 0
        let valorNeto = 0
        if (item.facturas.length) {
          item.facturas.forEach((factura) => {
            valorCompartido += parseInt(factura.valor_compartido)
            valorComision += parseInt(factura.valor_comision)
            valorDescuentos += parseInt(factura.valor_descuentos)
            valorNeto += parseInt(factura.valor_neto)
          })
          item.totalDetalles.totalCompartido = valorCompartido
          item.totalDetalles.totalComision = valorComision
          item.totalDetalles.totalDescuentos = valorDescuentos
          item.totalDetalles.totalValorneto = valorNeto
          this.cmradicada.valorNetoTotal = valorNeto
          if (item.totalDetalles.totalCompartido !== 0) {
            item.totalDetalles.clase = 'red--text'
          }
        }
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      getFacturas (props) {
        if (props.item.selected) {
          this.cmradicada.rips_id = props.item.id
          this.cmradicada.rs_rip_radicado_id = props.item.id
          this.$nextTick(() => {
            this.ripsRadicados.forEach(x => {
              if (x.id !== props.item.id) x.selected = false
            })
          })
          if (!props.item.facturas.length) {
            props.item.tableLoadingTwo = true
            this.axios.get('facturas_rips/' + props.item.id)
              .then(res => {
                props.item.facturas = res.data.data
                this.sumatoria(props.item)
                this.$store.commit(SNACK_SHOW, {msg: 'Facturas Cargadas.', color: 'success'})
                props.item.tableLoadingTwo = false
                props.expanded = true
              }).catch((e) => {
                props.item.tableLoadingTwo = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: ' Hay un error al traer los registros', error: e})
              // this.$store.commit(SNACK_SHOW, {expression: 'Hay un error al traer los registros.', color: 'error'})
              })
          } else {
            this.$nextTick(() => {
              props.expanded = true
              this.sumatoria(props.item)
            })
          }
        } else {
          props.expanded = false
          this.cmradicada.rips_id = null
          this.cmradicada.rs_rip_radicado_id = null
        }
      },
      getRipsradicados (val) {
        this.tableLoading = true
        this.axios.get('radicadas/rips_radicados/' + val)
          .then(res => {
            res.data.data.forEach(x => {
              x.selected = false
              x.facturas = []
              x.totalDetalles = {totalCompartido: 0, totalComision: 0, totalDescuentos: 0, totalValorneto: 0, clase: null}
              x.tableLoadingTwo = false
            })
            this.ripsRadicados = res.data.data
            this.tableLoading = false
          }).catch(() => {
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {expression: 'Hay un erro al traer los registros.'})
          })
      },
      getContratos (val) {
        this.axios.get('radicadas/contratos_ips/' + val)
          .then(res => {
            if (res.data !== '') {
              this.contratosIps = res.data.data
              // console.log('ffsssff', this.contratosIps)
              if (this.contratosIps.length === 1 && this.contratosIps[0]) {
                this.cmradicada.contrato = JSON.parse(JSON.stringify(this.contratosIps[0]))
                this.cmradicada.rs_contrato_id = this.contratosIps[0].id
              }
            }
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {expression: 'Hay un error al traer los registros.' + e.response, color: 'danger'})
            this.$store.commit(SNACK_ERROR_LIST, {expression: 'Hay un error al traer los registros.', error: e.response.error})
          })
      },
      updateDatetime (datetime) {
        this.datetime = datetime
        this.cmradicada.periodo_inicio = datetime
      },
      updateDatetimeTwo (datetimeTwo) {
        this.cmradicada.periodo_fin = datetimeTwo
      },
      updateDatetimeThree (datetimeThree) {
        this.cmradicada.fecha_radicado = datetimeThree
      },
      formReset () {
        // this.moment().format('YYYY-MM-DD HH:mm:ss')
        this.cmradicada = {
          id: null,
          estado: 'Radicado',
          fecha: null,
          fecha_radicado: null,
          observaciones: null,
          periodo_fin: null,
          periodo_inicio: null,
          radicado_entidad: null,
          rs_contrato_id: null,
          rs_entidad_id: null,
          rips_id: null,
          rs_rip_radicado_id: null,
          valorNetoTotal: 0,
          // objects
          contrato: null,
          entidad: null,
          facturas: []
        }
      }
    },
    computed: {
      tapTitulo () {
        return 'Registro de Cuenta Médica'
      }
    }
  }
</script>

<style scoped>
  .dividerTwo {
    padding: 0.1mm;
    background-color: #f5f5f5 !important;
    border-color: #f5f5f5 !important;
  }
  .refSpace .spacer {
    background-color: #f5f5f5 !important;
    border-color: #f5f5f5 !important;
  }
  @media (max-width: 601px) {
    .head2 {
      text-align: right !important;
    }
  }
  @media (min-width: 602px) and (max-width: 959px){
    .head1 {
      padding-top: 4px !important;
    }
    .head2 {
      text-align: right;
    }
    .head2 h2 {
      font-size: 20px !important;
      font-weight: 500;
      line-height: 1 !important;
      letter-spacing: 0.02em !important;
      font-family: 'Roboto', sans-serif !important;
    }
  }

  @media (max-width: 959px) {
    .dNone {
      display: none !important;
    }
    .refSpace .spacer {
      display: none !important;
    }
  }
</style>
