<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Saldos iniciales" title="Saldos iniciales" subtitle="gestión de saldos iniciales"/>
     <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar saldos iniciales', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
  </v-card>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'CuentasPorCobrar',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemCarteraSalinicial',
          route: 'saliniciales',
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
            { text: 'Consecutivo', align: 'left', sortable: false, value: 'consecutivo' },
            { text: 'tipo_saldo', align: 'left', sortable: false, value: 'tipo_saldo' },
            { text: 'Numero de factura', align: 'left', sortable: false, value: 'numero_factura' },
            { text: 'Fecha de factura', align: 'left', sortable: false, value: 'fecha_factura' },
            { text: 'Fecha de vencimiento', align: 'left', sortable: false, value: 'fecha_vencimiento' },
            { text: 'Opciones', align: 'center', actions: true, sortable: false, classData: 'justify-center layout px-0' }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('saliniciales')
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

