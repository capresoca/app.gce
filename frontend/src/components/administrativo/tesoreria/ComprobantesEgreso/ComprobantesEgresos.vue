<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Crear comprobante" title="Comprobantes de egresos" subtitle="Registro y gestión"/>
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: `Comprobante de egreso No. ${item.consecutivo}`, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
    ></data-table>
  </v-card>
</template>
<script>
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'ComprobantesEgresos',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemConceptoReciboCajaTesoreria',
          route: 'compegresos',
          makeHeaders: [
            {
              text: 'Conse.',
              tooltip: 'Consecutivo',
              align: 'center',
              sortable: false,
              value: 'consecutivo',
              classData: 'text-xs-center'
            },
            {
              text: 'Fecha',
              align: 'left',
              sortable: false,
              value: 'fecha',
              component: {
                component: {
                  template:
                    `
                        <span>{{value.fecha ? moment(value.fecha).format('YYYY-MM-DD') : ''}}</span>
                      `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Tercero',
              align: 'left',
              sortable: false,
              value: 'tercero',
              component: {
                component: {
                  template:
                    `
                        <div>
                          <v-list-tile class="content-v-list-tile-p0" v-if="value.tercero">
                            <v-list-tile-content>
                              <v-list-tile-title class="body-1">{{value.tercero.nombre_completo}}</v-list-tile-title>
                              <v-list-tile-sub-title class="caption">{{value.tercero.identificacion_completa}}</v-list-tile-sub-title>
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
              text: 'Centro costo',
              align: 'left',
              sortable: false,
              value: 'cencosto',
              component: {
                component: {
                  template:
                    `
                        <div>
                          <v-list-tile class="content-v-list-tile-p0" v-if="value.cencosto">
                            <v-list-tile-content>
                              <v-list-tile-title class="body-1">{{value.cencosto.codigo}} - {{value.cencosto.nombre}}</v-list-tile-title>
                              <v-list-tile-sub-title class="caption">Tipo: {{value.cencosto.tipo}}</v-list-tile-sub-title>
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
              text: 'Descripción',
              align: 'left',
              sortable: false,
              value: 'descripcion'
            },
            {
              text: 'Estado',
              align: 'center',
              sortable: false,
              value: 'estado',
              classData: 'text-xs-center'
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
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('comprobantesegresos')
      }
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
