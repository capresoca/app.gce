<template>
    <v-card>
      <toolbar-list :info="infoComponent" title="Auditores Asignados" subtitle="Gestión"/>
      <data-table
        v-model="dataTable"
        @resetOption="item => resetOptions(item)"
        @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: 'facturasradicadas', titulo: `Facturas Radicado: ${item.consecutivo_radicado}`, required: true, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
      />
    </v-card>
</template>

<script>
  export default {
    name: 'RadicadoAsignado',
    components: {
      DataTable: () => import('@/components/general/DataTable'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemAuditores',
          route: 'ac_radicados_asignados',
          makeHeaders: [
            {
              text: 'Código Radicado',
              align: 'left',
              sortable: false,
              value: 'consecutivo_radicado'
            },
            {
              text: 'Nombre Auditor',
              align: 'left',
              sortable: false,
              value: 'carita_nombre_afiliado',
              component: {
                component: {
                  template: `
                    <div>
                      <v-tooltip v-for="(item, i) in value.auditores_asignados" :key="i" left>
                        <a slot="activator" :key="i"
                        style="cursor: pointer !important; font-weight: bold; text-decoration: none !important; color: rgba(0,0,0,0.87) !important; display: block; padding: 2px !important;" v-text="item.auditor ? item.auditor.codigo + ' - ' + item.auditor.usuario.name : null"></a>
                        <span :key="i" v-text="'Identificación: ' + item.auditor.usuario.identification"></span>
                      </v-tooltip>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Código Entidad',
              align: 'left',
              sortable: false,
              value: 'codigo_habilitacion'
            },
            {
              text: 'Nombre Entidad',
              align: 'left',
              sortable: false,
              value: 'nombre_entidad'
            },
            {
              text: 'Facturas',
              align: 'center',
              sortable: false,
              value: 'id',
              classData: 'text-xs-center',
              width: '150px',
              component: {
                component: {
                  template:
                    `<div>
                        <v-tooltip top class="mr-2" v-if="value.facturas">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.facturas}}</span>
                            <v-avatar color="indigo" size="30">
                              <span class="white--text subheading">F</span>
                            </v-avatar>
                          </v-badge>
                          <span>Facturas</span>
                        </v-tooltip>
                    </div>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              singlesActions: true,
              classData: 'justify-center layout px-0'
            }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('cmradicadas')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar && (item.auditores_asignados.length !== 0)) item.options.push({event: 'editar', icon: 'local_library', tooltip: 'Revisar'})
        return item
      }
    }
  }
</script>

<style scoped>

</style>
