<template>
  <v-card>
    <toolbar-list
      :info="infoComponent"
      title="Reportes"
      subtitle="Registro y gestión"
      btnplustitle="Crear reporte"
      btnplus
    />
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
    />
  </v-card>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'ConfiguracionesReportes',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemReporte',
          route: 'reportes',
          makeHeaders: [
            {
              text: 'Nombre',
              align: 'left',
              sortable: true,
              value: 'nombre'
            },
            {
              text: 'Descripción',
              align: 'left',
              sortable: false,
              value: 'descripcion'
            },
            {
              text: 'Módulo',
              align: 'left',
              sortable: false,
              value: 'modulo'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              singlesActions: true,
              width: '150px',
              classData: 'text-xs-center'
            }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('configuracionReportes')
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
