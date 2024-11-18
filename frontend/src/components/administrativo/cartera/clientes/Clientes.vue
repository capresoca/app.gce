<template>
	<v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Crear cliente" title="Clientes" subtitle="Gestion de clientes"/>
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar cliente', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
	</v-card>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'Clientes',
    data () {
      return {
        dataTable: {
          nameItemState: 'itemCliente',
          route: 'clientes',
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
              text: 'Tipo identificacion',
              align: 'left',
              sortable: false,
              value: 'tercero.tipo_id.abreviatura'
            },
            {
              text: 'Identificacion',
              align: 'left',
              sortable: false,
              value: 'tercero.identificacion'
            },
            {
              text: 'Nombre del cliente',
              align: 'left',
              sortable: false,
              value: 'nombre'
            },
            {
              text: 'Codigo del vendedor',
              align: 'left',
              sortable: false,
              value: 'vendedor.codigo'
            },
            {
              text: 'Nombre del vendedor',
              align: 'left',
              sortable: false,
              value: 'vendedor.nombre'
            },
            {
              text: 'Estado',
              align: 'left',
              sortable: false,
              value: 'estado'
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
        return this.$store.getters.getInfoComponent('cliente')
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
