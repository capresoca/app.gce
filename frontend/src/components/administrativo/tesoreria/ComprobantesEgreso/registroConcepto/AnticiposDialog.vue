<template>
  <v-dialog
    persistent
    v-model="dialog"
    width="1200"
  >
    <template v-slot:activator="{ on }">
      <v-btn small flat v-on="on">
        Anticipos
      </v-btn>
    </template>
    <v-card>
      <v-toolbar dense>
        <v-list-tile class="content-v-list-tile-p0">
          <v-list-tile-content>
            <v-list-tile-title>Anticipos</v-list-tile-title>
            <v-list-tile-sub-title>{{comprobante.tercero.identificacion_completa}} - {{comprobante.tercero.nombre_completo}}</v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-spacer></v-spacer>
        <v-tooltip left>
          <v-btn flat icon slot="activator" @click="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <span>Cerrar</span>
        </v-tooltip>
      </v-toolbar>
      <v-expansion-panel dark v-if="anticipos.length">
        <v-expansion-panel-content>
          <template v-slot:header>
            <span>
              <v-badge>
              <template v-slot:badge>
                <span>{{anticipos.length}}</span>
              </template>
              Anticipos del tercero
            </v-badge>
            </span>
          </template>
          <v-data-table
            :items="anticipos"
            hide-actions
          >
            <template v-slot:headers="props">
              <tr style="height: 10px !important;">
                <th class="text-xs-center">No.</th>
                <th>Fecha</th>
                <th>Cuenta</th>
                <th>Origen</th>
                <th class="text-xs-right">Valor</th>
              </tr>
            </template>
            <template v-slot:items="props">
              <tr>
                <td class="text-xs-center">{{props.item.consecutivo}}</td>
                <td>{{props.item.fecha_documento}}</td>
                <td>
                  <v-list-tile class="content-v-list-tile-p0">
                    <v-list-tile-content>
                      <v-list-tile-title>{{props.item.cuenta.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title>Código: {{props.item.cuenta.codigo}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </td>
                <td>{{props.item.origen}}</td>
                <td class="text-xs-right">{{currency(props.item.valor)}}</td>
              </tr>
            </template>
          </v-data-table>
        </v-expansion-panel-content>
      </v-expansion-panel>
      <v-layout>
        <v-flex xs12>
          <v-card>
            <v-data-table
              :items="[1]"
              hide-actions
              hide-headers
              no-data-text=""
            >
              <template v-slot:items="props">
                <tr style="height: 10px !important;">
                  <th>
                    <v-switch
                      v-model="siDirecto"
                      label="Anticipo directo al tercero"
                      hide-details
                    ></v-switch>
                  </th>
                  <th class="text-xs-center">
                    <input-number
                      :disabled="!siDirecto"
                      key="inputDirecto"
                      class="ma-0 py-1"
                      name="anticipo directo"
                      prepend-icon="attach_money"
                      v-model.number="anticipoDirecto"
                      data-vv-as="Valor"
                      v-validate="`required|min_value:0`"
                      :error-messages="errors.collect('anticipo directo')"
                    ></input-number>
                  </th>
                </tr>
              </template>
            </v-data-table>
            <v-divider></v-divider>
            <v-card-title class="py-1">
              <span class="title">Anticipos a contratos</span>
              <v-spacer></v-spacer>
              <v-btn color="primary" @click.stop="crearRegistro">
                <v-icon left>add</v-icon>
                Crear anticipo
              </v-btn>
            </v-card-title>
            <v-data-table
              :items="registros"
              :headers="headers"
              hide-actions
              no-data-text="No registra anticipos a contratos."
            >
              <template v-slot:items="props">
                <tr>
                  <td>
                    <v-autocomplete
                      :key="'autocompleteContrato' + props.index"
                      placeholder="Seleccionar contrato"
                      v-model="props.item.contrato"
                      :items="contratos"
                      :name="'contrato' + props.index"
                      return-object
                      v-validate="'required'"
                      data-vv-as="contrato"
                      :error-messages="errors.collect('contrato' + props.index)"
                      @change="props.item.plan_cobertura = null"
                    >
                      <template slot="selection" slot-scope="data">
                        <div style="width: 100% !important;" class="content-v-list-tile-p0">
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title>No. {{data.item.numero_contrato}}</v-list-tile-title>
                              <v-list-tile-sub-title>{{data.item.entidad.nombre}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      </template>
                      <template slot="item" slot-scope="data">
                        <div style="width: 100% !important;">
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title>No. {{data.item.numero_contrato}}</v-list-tile-title>
                              <v-list-tile-sub-title>{{data.item.entidad.nombre}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      </template>
                    </v-autocomplete>
                  </td>
                  <td class="text-xs-center">
                    <v-select
                      :key="'selectPlanCobertura' + props.index"
                      :disabled="!props.item.contrato"
                      placeholder="Seleccionar plan de cobertura"
                      v-model="props.item.plan_cobertura"
                      :items="props.item.contrato ? contratos.find(x => x.id === props.item.contrato.id).planes_cobertura : []"
                      :name="'planCobertura' + props.index"
                      return-object
                      v-validate="'required'"
                      data-vv-as="plan de cobertura"
                      :error-messages="errors.collect('planCobertura' + props.index)"
                    >
                      <template slot="selection" slot-scope="data">
                        <div style="width: 100% !important;" class="content-v-list-tile-p0">
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                              <v-list-tile-sub-title>Estado: {{data.item.estado}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      </template>
                      <template slot="item" slot-scope="data">
                        <div style="width: 100% !important;">
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                              <v-list-tile-sub-title>Estado: {{data.item.estado}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      </template>
                    </v-select>
                  </td>
                  <td class="text-xs-center" style="min-width: 200px !important;">
                    <input-number
                      :key="'numberValor' + props.index"
                      :name="'valor' + props.index"
                      prepend-icon="attach_money"
                      v-model.number="props.item.valor"
                      data-vv-as="valor"
                      v-validate="`required|min_value:1`"
                      :error-messages="errors.collect('valor' + props.index)"
                    ></input-number>
                  </td>
                  <td class="text-xs-center">
                    <v-tooltip right>
                      <v-btn flat icon color="red" class="darken-3" slot="activator" @click.stop="registros.splice(props.index, 1)">
                        <v-icon>delete</v-icon>
                      </v-btn>
                      <span>Borrar</span>
                    </v-tooltip>
                  </td>
                </tr>
              </template>
            </v-data-table>
          </v-card>
        </v-flex>
      </v-layout>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn
          flat
          @click="dialog = false"
        >
          Cerrar
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          flat
          @click="asignar"
        >
          Guardar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'AnticiposDialog',
    props: {
      comprobante: {
        type: Object,
        default: null
      },
      concepto: {
        type: Object,
        default: null
      }
    },
    components: {
      InputNumber: () => import('@/components/general/InputNumber')
    },
    data: () => ({
      siDirecto: false,
      anticipoDirecto: 0,
      registros: [],
      dialog: false,
      loading: false,
      contratos: [],
      anticipos: [],
      headers: [
        {
          text: 'Contrato',
          align: 'center',
          sortable: false
        },
        {
          text: 'Plan cobertura',
          align: 'center',
          sortable: false
        },
        {
          text: 'Valor',
          align: 'right',
          sortable: false
        },
        {
          text: '',
          align: 'center',
          sortable: false
        }
      ]
    }),
    watch: {
      'siDirecto' (val) {
        !val && (this.anticipoDirecto = 0)
      },
      'dialog' (val) {
        val && this.getDataTerceroConcepto()
      }
    },
    methods: {
      crearRegistro () {
        this.registros.push({
          contrato: null,
          plan_cobertura: null,
          valor: 0
        })
      },
      asignar () {
        this.$validator.validateAll().then(result => {
          if (result) {
            if (this.siDirecto) {
              this.registros.push({
                contrato: null,
                plan_cobertura: null,
                valor: this.anticipoDirecto
              })
            }
            this.$emit('returnItems', this.registros)
            this.dialog = false
          }
        })
      },
      getDataTerceroConcepto () {
        this.loading = true
        this.axios.get(`anti_compegresos/${this.comprobante.tercero.id}/${this.concepto.id}`)
          .then(response => {
            this.anticipos = response.data.anticipos
            this.contratos = response.data.contratos
            this.siDirecto = false
            this.anticipoDirecto = 0
            this.registros = []
            this.loading = false
          }).catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer las cxps. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
