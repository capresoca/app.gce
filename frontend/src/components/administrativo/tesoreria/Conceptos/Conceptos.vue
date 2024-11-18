<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Crear concepto de tesorería" title="Conceptos de tesorería" subtitle="Registro y gestión"/>
    <v-container fluid grid-list-sm class="py-1">
      <v-layout row wrap>
        <v-flex xs12 ms6 md3>
          <v-select
            label="Filtro por tipo de concepto"
            :items="tiposConcepto"
            v-model="tipoConcepto"
            hide-details
          >
          </v-select>
        </v-flex>
      </v-layout>
    </v-container>
    <data-table
      v-model="dataTableReciboCaja"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar concepto de recibo de caja', parametros: {tipo: 'caja', entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      v-show="tipoConcepto === 1"
    ></data-table>
    <data-table
      v-model="dataTableEgresos"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar concepto de egreso', parametros: {tipo: 'egreso', entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      v-show="tipoConcepto === 2"
      />
    <data-table
      v-model="dataTableNotas"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar concepto de notas', parametros: {tipo: 'notas', entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      v-show="tipoConcepto === 3"
      />
  </v-card>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'ConceptosTesoreria',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    data () {
      return {
        makeHeaders: [
          {
            text: 'Código',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Cuenta',
            align: 'left',
            sortable: false,
            value: 'niif',
            component: {
              component: {
                template:
                  `
                        <div>
                          <v-list-tile class="content-v-list-tile-p0" v-if="value.niif || value.cuenta_banco">
                            <v-list-tile-content>
                              <v-list-tile-title class="body-1">{{value.niif ? value.niif.nombre : value.cuenta_banco.numero_cuenta}}</v-list-tile-title>
                              <v-list-tile-sub-title class="caption">{{value.niif ? value.niif.codigo : (value.cuenta_banco.tipo_cuenta + ' - ' + value.cuenta_banco.banco.nombre)}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                          <span v-else>No registra</span>
                        </div>
                      `,
                props: ['value']
              }
            }
          },
          {
            text: 'Naturaleza',
            align: 'left',
            sortable: false,
            value: 'naturaleza'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            actions: true,
            classData: 'text-xs-center',
            singlesActions: true,
            width: '120px'
          }
        ],
        tipoConcepto: 1,
        tiposConcepto: [{value: 1, text: 'Recibo de caja'}, {value: 2, text: 'Comprobante de egreso'}, {value: 3, text: 'Nota bancaria'}],
        dataTableReciboCaja: {
          nameItemState: 'itemConceptoReciboCajaTesoreria',
          route: 'ts_concepto_recibos',
          makeHeaders: []
        },
        dataTableEgresos: {
          nameItemState: 'itemConceptoEgresoTesoreria',
          route: 'ts_concepto_egresos',
          makeHeaders: []
        },
        dataTableNotas: {
          nameItemState: 'itemConceptoNotasTesoreria',
          route: 'ts_concepto_notas',
          makeHeaders: []
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('conceptosTesoreria')
      }
    },
    created () {
      [this.dataTableReciboCaja.makeHeaders, this.dataTableEgresos.makeHeaders, this.dataTableNotas.makeHeaders] = [this.makeHeaders, this.makeHeaders, this.makeHeaders]
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        return item
      }
    }
  }
</script>
