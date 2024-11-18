<template>
  <v-dialog
    persistent
    v-model="dialog"
    width="1100"
  >
    <template v-slot:activator="{ on }">
      <v-btn flat color="primary" v-on="on">
        <v-icon class="mr-1">add</v-icon>
        Agregar concepto
      </v-btn>
    </template>
    <v-card>
      <v-toolbar dense>
        <v-toolbar-title>Concepto de comprobante de egreso</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-tooltip left>
          <v-btn flat icon slot="activator" @click="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <span>Cerrar</span>
        </v-tooltip>
      </v-toolbar>
      <v-container fluid grid-list-sm>
        <v-layout row wrap>
          <v-flex xs12>
            <v-autocomplete
              label="Concepto"
              v-model="item.concepto"
              :items="conceptos"
              item-value="id"
              item-text="id"
              :filter="filterConceptos"
              placehoder="Busqueda por código o nombre."
              no-data-text="No hay resultados para mostrar"
              name="concepto"
              v-validate="'required'"
              :error-messages="errors.collect('concepto')"
              persistent-hint
              :loading="loadingConceptos"
              return-object
              :hint="item.concepto ? ('Naturaleza: ' + item.concepto.naturaleza) : ''"
            >
              <template slot="selection" slot-scope="data">
                <div style="width: 100% !important;">
                  <v-list-tile class="content-v-list-tile-p0">
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.codigo}} - {{data.item.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title class=caption>Cuenta: {{data.item.cuenta_banco ? (data.item.cuenta_banco.numero_cuenta + ' ' + data.item.cuenta_banco.banco.nombre) : data.item.niif ? (data.item.niif.codigo + ' ' + data.item.niif.nombre) : ''}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </div>
              </template>
              <template slot="item" slot-scope="data">
                <div style="width: 100% !important;">
                  <v-list-tile class="content-v-list-tile-p0">
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.codigo}} - {{data.item.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title class=caption>Cuenta: {{data.item.cuenta_banco ? (data.item.cuenta_banco.numero_cuenta + ' ' + data.item.cuenta_banco.banco.nombre) : data.item.niif ? (data.item.niif.codigo + ' ' + data.item.niif.nombre) : ''}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </div>
              </template>
            </v-autocomplete>
          </v-flex>
          <v-flex xs12 class="mt-2" v-if="item.concepto">
            <v-card>
              <v-toolbar dense>
                <v-toolbar-title>Detalles del concepto</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items class="hidden-sm-and-down">
                  <cxps-list v-if="btncxps" :comprobante="comprobante" @returnItems="cxps => asignarDetallesCxPs(cxps)"></cxps-list>
                  <anticipos-dialog v-if="btnAnticipos" :concepto="item.concepto" :comprobante="comprobante" @returnItems="anticipos => asignarDetallesAnticipos(anticipos)"></anticipos-dialog>
                </v-toolbar-items>
              </v-toolbar>
              <template v-if="item.detalles.length">
                <template v-if="item.detalles.filter(x => x.cxp).length">
                  <v-system-bar status color="primary" dark>
                    <v-spacer></v-spacer>
                    <span>Registros de cuentas por pagar</span>
                  </v-system-bar>
                  <v-data-table
                    :items="item.detalles.filter(x => x.cxp)"
                    hide-actions
                    no-data-text="No hay detalles registrados."
                  >
                    <template v-slot:headers="props">
                      <tr style="height: 10px !important;">
                        <th class="text-xs-center">CxP</th>
                        <th class="text-xs-left">Causación</th>
                        <th class="text-xs-left">Cuenta</th>
                        <th class="text-xs-center">Módulo</th>
                        <th class="text-xs-center">Factura</th>
                        <th class="text-xs-right">Valor</th>
                        <th class="text-xs-right">Saldo</th>
                        <th class="text-xs-right">Valor a pagar</th>
                        <th class="text-xs-center"></th>
                      </tr>
                    </template>
                    <template v-slot:items="props">
                      <td class="text-xs-center">{{ props.item.cxp.consecutivo }}</td>
                      <td class="text-xs-left">{{props.item.cxp.fecha_causacion}}</td>
                      <td class="text-xs-left">{{props.item.cxp.niif.codigo}} - {{props.item.cxp.niif.nombre}}</td>
                      <td class="text-xs-center">{{ props.item.cxp.modulo }}</td>
                      <td class="text-xs-center">{{ props.item.cxp.no_factura }}</td>
                      <td class="text-xs-right">{{ currency(props.item.cxp.valor_sin_formato) }}</td>
                      <td class="text-xs-right">{{ currency(props.item.cxp.saldo) }}</td>
                      <td class="text-xs-right">{{ currency(props.item.cxp.valor) }}</td>
                      <td class="text-xs-center">
                        <v-tooltip right>
                          <v-btn flat icon color="red" class="darken-3" slot="activator" @click.stop="item.detalles.filter(x => x.cxp).splice(props.index, 1)">
                            <v-icon>delete</v-icon>
                          </v-btn>
                          <span>Borrar</span>
                        </v-tooltip>
                      </td>
                    </template>
                  </v-data-table>
                </template>
                <template v-if="item.detalles.filter(x => !x.cxp).length">
                  <v-system-bar status color="primary" dark>
                    <v-spacer></v-spacer>
                    <span>Registros de anticipos</span>
                  </v-system-bar>
                  <v-data-table
                    :items="item.detalles.filter(x => !x.cxp)"
                    hide-actions
                    no-data-text="No hay detalles registrados."
                  >
                    <template v-slot:headers="props">
                      <tr style="height: 10px !important;">
                        <th class="text-xs-left">Contrato</th>
                        <th class="text-xs-left">Plan cobertura</th>
                        <th class="text-xs-right">Valor</th>
                        <th class="text-xs-center"></th>
                      </tr>
                    </template>
                    <template v-slot:items="props">
                      <td class="text-xs-left">
                        <v-list-tile class="content-v-list-tile-p0" v-if="props.item.contrato">
                          <v-list-tile-content>
                            <v-list-tile-title>No. {{props.item.contrato.numero_contrato}}</v-list-tile-title>
                            <v-list-tile-sub-title>{{props.item.contrato.entidad.nombre}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                        <span v-else>No aplica</span>
                      </td>
                      <td class="text-xs-left">
                        <v-list-tile class="content-v-list-tile-p0" v-if="props.item.plan_cobertura">
                          <v-list-tile-content>
                            <v-list-tile-title>{{props.item.plan_cobertura.nombre}}</v-list-tile-title>
                            <v-list-tile-sub-title>Estado: {{props.item.plan_cobertura.estado}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                        <span v-else>No aplica</span>
                      </td>
                      <td class="text-xs-right">{{ currency(props.item.valor) }}</td>
                      <td class="text-xs-center">
                        <v-tooltip right>
                          <v-btn flat icon color="red" class="darken-3" slot="activator" @click.stop="item.detalles.filter(x => !x.cxp).splice(props.index, 1)">
                            <v-icon>delete</v-icon>
                          </v-btn>
                          <span>Borrar</span>
                        </v-tooltip>
                      </td>
                    </template>
                  </v-data-table>
                </template>
              </template>
              <div v-else class="title grey--text text-xs-center font-weight-light pa-4">
                Seleccione una opción de detalle
              </div>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn
          flat
          @click="dialog = false"
        >
          Cancelar
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
          v-if="item.detalles.length"
          color="primary"
          flat
          @click="registrarConcepto"
        >
          Registrar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'RegistroConcepto',
    props: {
      comprobante: {
        type: Object,
        default: null
      }
    },
    components: {
      InputNumber: () => import('@/components/general/InputNumber'),
      CxpsList: () => import('@/components/administrativo/tesoreria/ComprobantesEgreso/registroConcepto/CxpsList'),
      AnticiposDialog: () => import('@/components/administrativo/tesoreria/ComprobantesEgreso/registroConcepto/AnticiposDialog')
    },
    data: () => ({
      dialog: false,
      loadingConceptos: false,
      conceptos: [],
      item: {
        concepto: null,
        detalles: [],
        id: null,
        ts_compegreso_id: null,
        ts_concepto_egreso_id: null
      },
      filterConceptos (item, queryText) {
        const hasValue = val => val != null ? val : ''
        return hasValue(item.codigo + ' ' + item.nombre).toString().toLowerCase().indexOf(hasValue(queryText).toString().toLowerCase()) > -1
      }
    }),
    computed: {
      btncxps () {
        return this.item.concepto && this.item.concepto.facturacion
      },
      btnAnticipos () {
        return this.item.concepto && this.item.concepto.anticipos
      }
    },
    watch: {
      'dialog' (val) {
        val && this.formReset()
      },
      'item.concepto' (val, prev) {
        if (typeof prev !== 'undefined') {
          if (typeof val === 'undefined' || val === null) {
            this.item.concepto = null
            this.item.ts_concepto_egreso_id = null
          } else {
            this.item.ts_concepto_egreso_id = val.id
          }
        }
      }
    },
    created () {
      this.getConceptos()
    },
    methods: {
      formReset () {
        this.item = {
          concepto: null,
          detalles: [],
          id: null,
          ts_compegreso_id: null,
          ts_concepto_egreso_id: null
        }
        this.$validator.reset()
      },
      registrarConcepto () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.item.ts_compegreso_id = this.comprobante.id
            this.item.ts_concepto_egreso_id = this.item.concepto.id
            this.$emit('returnConcepto', this.item)
            this.dialog = false
          }
        })
      },
      asignarDetallesCxPs (cxps) {
        cxps.forEach(x => {
          this.item.detalles.push({
            cxp: x,
            valor: x.valor,
            pg_cxp_id: x.id,
            rs_planescobertura_id: null,
            id: null
          })
        })
      },
      asignarDetallesAnticipos (anticipos) {
        anticipos.forEach(x => {
          this.item.detalles.push({
            contrato: x.contrato,
            plan_cobertura: x.plan_cobertura,
            pg_cxp_id: null,
            rs_planescobertura_id: x.plan_cobertura ? x.plan_cobertura.id : null,
            valor: x.valor,
            id: null
          })
        })
      },
      getConceptos () {
        this.loadingConceptos = true
        this.axios.get('ts_concepto_egresos')
          .then(response => {
            if (response.data.data) this.conceptos = response.data.data
            this.loadingConceptos = false
          }).catch(e => {
            this.loadingConceptos = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los conceptos de egreso. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
