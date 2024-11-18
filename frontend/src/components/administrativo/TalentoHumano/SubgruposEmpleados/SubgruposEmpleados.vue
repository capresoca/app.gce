<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Agregar item" title="Subgrupos de empleados" subtitle="Registro y gestión"/>
    <data-table
      v-model="dataTableAlmacen"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar subgrupos de empleados', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
  </v-card>
</template>
<script>
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'SubgruposEmpleadosTalentoHumano',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    data () {
      return {
        dataTableAlmacen: {
          nameItemState: 'itemSubgruposEmpleados',
          route: 'th_subgrupos_empleados',
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
            {
              text: 'Codigo',
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
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'justify-center layout px-0'
            }
          ]
        }
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        return item
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('subgruposempleados')
      }
    }
  }
</script>
