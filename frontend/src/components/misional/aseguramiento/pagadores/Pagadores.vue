<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Aportantes" subtitle="Registro y gestión" btnplus btnplustitle="Crear aportante" />
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
    />
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'Pagadores',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemPagadore',
          route: 'pagadores',
          optionsPerPage: [
            {
              text: 15,
              value: 15
            },
            {
              text: 50,
              value: 50
            },
            {
              text: 100,
              value: 100
            }
          ],
          makeHeaders: [
            { text: 'Tercero',
              align: 'center',
              sortable: true,
              value: 'gn_tercero_id',
              classData: 'text-xs-center',
              component: {
                component: {
                  template:
                  `<v-tooltip top>
                    <v-btn slot="activator" flat icon :color="value.gn_tercero_id ? 'success' : 'error'">
                      <v-icon>{{value.gn_tercero_id ? 'how_to_reg' : 'warning'}}</v-icon>
                    </v-btn>
                      <span>{{value.gn_tercero_id ? 'Tercero vinculado' : 'No tiene tercero vinculado'}}</span>
                  </v-tooltip>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Identificación',
              align: 'left',
              sortable: false,
              value: 'identificacion'
            },
            {
              text: 'Nombre',
              align: 'left',
              sortable: false,
              value: 'nombre_razon_social'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              singlesActions: true,
              classData: 'text-xs-center'
            }
          ],
          filters: [
            {
              label: 'Tipo aportante',
              type: 'v-autocomplete',
              items: () => this.complementos.tipaportantes,
              vModel: [],
              multiple: true,
              itemText: 'nombre',
              itemValue: 'id',
              value: 'tipo_aportante',
              clearable: true
            }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('pagadores')
      },
      complementos () {
        return store.getters.complementosTablaPagadores
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

<style scoped>

</style>
