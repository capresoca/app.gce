<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Crear Periodo" title="Periodos validador 4505" subtitle="Configurar periodos validador 4505"/>
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar periodo', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
  </v-card>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'Periodos',
    data () {
      return {
        dataTable: {
          nameItemState: 'itemPeriodos4505',
          route: 'periodos4505',
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
              text: 'Año',
              align: 'left',
              sortable: false,
              value: 'year'
            },
            {
              text: 'Periodo',
              align: 'left',
              sortable: false,
              value: 'periodo'
            },
            {
              text: 'Fecha de inicio de validacion',
              align: 'left',
              sortable: false,
              value: 'fecha_inicio_validacion'
            },
            {
              text: 'Fecha Final de validacion',
              align: 'left',
              sortable: false,
              value: 'fecha_fin_validacion'
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
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('periodos4505')
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
