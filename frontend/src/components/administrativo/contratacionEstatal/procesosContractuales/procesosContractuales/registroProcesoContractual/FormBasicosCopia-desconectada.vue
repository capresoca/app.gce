<template>
  <v-form data-vv-scope="formBasicoProcesoContractual">
    <v-layout row wrap>
      <v-flex xs12 sm6 md3>
        <v-autocomplete
          label="Unidad de apoyo"
          v-model="value.pg_uniapoyo_id"
          :items="complementos.uniapoyos"
          item-value="id"
          item-text="nombre"
          :filter="filterUniapoyo"
          name="Unidad de apoyo"
          v-validate="'required'"
          :error-messages="errors.collect('Unidad de apoyo')"
        >
          <template slot="item" slot-scope="data">
            <template>
              <v-list-tile-content>
                <v-list-tile-title v-html="data.item.nombre"/>
                <v-list-tile-sub-title v-html="'Código:' + data.item.codigo"/>
              </v-list-tile-content>
            </template>
          </template>
        </v-autocomplete>
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-switch
          label="Servicios de salud"
          v-model.number="value.servicios_salud"
          :false-value="0"
          :true-value="1"
        ></v-switch>
      </v-flex>
      <v-flex xs12>
        <v-textarea
          label="Objeto contractual"
          v-model="value.objeto"
          rows="1"
          auto-grow
          name="Objeto contractual"
          v-validate="'required'"
          :error-messages="errors.collect('Objeto contractual')"
        ></v-textarea>
      </v-flex>
    </v-layout>
    <v-layout row wrap v-if="value.pg_uniapoyo_id">
      <v-dialog
        v-model="dialog"
        width="900"
      >
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Seleccione el detalle del plan de adquisición</span>
          </v-card-title>
          <v-card-text>
            <v-expansion-panel key="panel2" v-model="activeRegistros" focusable expand>
              <v-expansion-panel-content
                v-for="(item, index) in planesAdquisicion"
                :key="'pa' + index"
              >
                <v-list-tile slot="header">
                  <v-list-tile-avatar color="primary" size="36">
                    <span class="white--text">{{index + 1}}</span>
                  </v-list-tile-avatar>
                  <v-list-tile-content>
                    <v-list-tile-title class="subheading">Año: {{item.anio}}</v-list-tile-title>
                    <v-list-tile-sub-title>{{item.descripcion}}</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
                <v-container
                  fluid
                  grid-list-xl
                >
                  <v-layout>
                    <v-flex xs12>
                      <v-divider></v-divider>
                      <v-card>
                        <v-card-title class="py-0">
                          <strong>Detalles</strong>
                          <v-spacer></v-spacer>
                          <v-text-field
                            v-model="search"
                            append-icon="search"
                            label="Buscar"
                            single-line
                            hide-details
                          ></v-text-field>
                        </v-card-title>
                        <v-data-table
                          :headers="headers"
                          :items="item.detalles"
                          :search="search"
                          hide-actions
                        >
                          <template slot="items" slot-scope="props">
                            <tr class="cursor-pointer" :active="props.item.id === (value.det_planadquisicione && value.det_planadquisicione.id)" @click="asignaDetalle(props.item)">
                              <td>{{ props.item.bien_servicio.nombre }}</td>
                              <td>{{ props.item.mod_contratacione.nombre }}</td>
                              <td>{{ props.item.uni_apoyo.nombre }}</td>
                              <td class="text-xs-center">{{ props.item.duracion }}</td>
                              <td class="text-xs-right">{{ props.item.valor | numberFormat(2)}}</td>
                            </tr>
                          </template>
                          <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                          </v-alert>
                        </v-data-table>
                      </v-card>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-card-text>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="dialog = false">Cancelar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-btn
        :disabled="!planesAdquisicion.length"
        :loading="loading"
        color="primary"
        @click="openDialogPlanAdquisicion"
      >
        <span v-if="planesAdquisicion.length === 0">
          No se encontraron planes de adquisición
        </span>
        <span v-else>
          {{(value && value.det_planadquisicione) ? 'Cambiar' : 'Seleccionar'}} el detalle del plan de adquisición
        </span>
        <span slot="loader">
          <v-progress-circular indeterminate :size="20" :width="2" class="mb-1"/>
          Cargando...
        </span>
      </v-btn>
      <v-flex xs12 class="subrayado-dot">
        <v-layout row wrap>
          <v-flex>
            <label class="v-label v-label--active theme--light caption" :class="errorDetalleAdquisicion ? 'error--text' : ''">Bien/Servicio</label>
            <v-list-tile-title v-if="value && value.det_planadquisicione">{{value.det_planadquisicione.bien_servicio.nombre}}</v-list-tile-title>
          </v-flex>
          <v-flex>
            <label class="v-label v-label--active theme--light caption" :class="errorDetalleAdquisicion ? 'error--text' : ''">Modo contratación</label>
            <v-list-tile-title v-if="value && value.det_planadquisicione">{{value.det_planadquisicione.mod_contratacione.nombre}}</v-list-tile-title>
          </v-flex>
          <v-flex>
            <label class="v-label v-label--active theme--light caption" :class="errorDetalleAdquisicion ? 'error--text' : ''">Duración</label>
            <v-list-tile-title v-if="value && value.det_planadquisicione">{{value.det_planadquisicione.duracion}} días</v-list-tile-title>
          </v-flex>
          <v-flex>
            <label class="v-label v-label--active theme--light caption" :class="errorDetalleAdquisicion ? 'error--text' : ''">Valor</label>
            <v-list-tile-title v-if="value && value.det_planadquisicione">$ {{value.det_planadquisicione.valor | numberFormat(2)}}</v-list-tile-title>
          </v-flex>
        </v-layout>
      </v-flex>
      <label class="v-label v-label--active theme--light caption ml-3 pt-1" :class="errorDetalleAdquisicion ? 'error--text' : ''">{{errorDetalleAdquisicion}}</label>
    </v-layout>
  </v-form>
</template>

<script>
  import store from '@/store/complementos/index'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'FormBasicos',
    props: ['value'],
    data () {
      return {
        flagUA: 0,
        errorDetalleAdquisicion: null,
        search: '',
        headers: [
          {
            text: 'Bien/Servicio',
            align: 'left',
            sortable: false
          },
          {
            text: 'Modo contratación',
            align: 'left',
            sortable: false
          },
          {
            text: 'Unidad de apoyo',
            align: 'left',
            sortable: false
          },
          {
            text: 'Duración (días)',
            align: 'center',
            sortable: false
          },
          {
            text: 'Valor ($)',
            align: 'right',
            sortable: false
          }
        ],
        dialog: false,
        filterUniapoyo: (item, queryText) => this.getFilter(item, queryText, item.codigo + ' ' + item.nombre),
        activeRegistros: [],
        planesAdquisicion: [],
        loading: false
      }
    },
    components: {
    },
    watch: {
      'value.pg_uniapoyo_id' (val) {
        if (val) {
          if (this.flagUA >= 1) {
            this.value.det_planadquisicione = null
            this.value.ce_detplanadquisicione_id = null
          }
          this.flagUA++
          this.planesAdquisicion = []
          this.loading = true
          this.axios.get('ce_planadquisiciones/by_uniapoyo/' + val)
            .then((response) => {
              this.planesAdquisicion = response.data.data
              this.resetActives()
              this.loading = false
            })
            .catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al hacer la busqueda de planes de adquisición. ', error: e})
            })
        }
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosProcesosContractualesFormBasicos
      }
    },
    methods: {
      reset () {
        this.errorDetalleAdquisicion = null
        this.activeRegistros = []
        this.planesAdquisicion = []
        this.dialog = false
        this.$validator.reset()
      },
      async validate () {
        let errorForm = await this.$validator.validateAll('formBasicoProcesoContractual')
        this.errorDetalleAdquisicion = (this.value.ce_detplanadquisicione_id === null) ? 'Seleccione un detalle de un plan de adquisición.' : null
        return (errorForm && this.errorDetalleAdquisicion === null)
      },
      openDialogPlanAdquisicion () {
        this.resetActives()
        this.dialog = true
      },
      resetActives () {
        this.activeRegistros = []
        this.planesAdquisicion.forEach(item => {
          this.activeRegistros.push(this.value && this.value.det_planadquisicione && item.id === this.value.det_planadquisicione.ce_planadquisicione_id)
        })
      },
      asignaDetalle (detalle) {
        this.value.det_planadquisicione = detalle
        this.value.ce_detplanadquisicione_id = detalle.id
        this.errorDetalleAdquisicion = null
        this.dialog = false
      },
      getFilter (item, queryText, compareString) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(compareString)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      }
    }
  }
</script>

<style scoped>

</style>
