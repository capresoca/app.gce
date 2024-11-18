<template>
	<v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Crear Supervisor" title="Supervisores" subtitle="Gestion de Supervisores"/>
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar Supervisor', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
	</v-card>
</template>

<script>
  export default {
    name: 'Supervisores',
    components: {
      DataTable: () => import('@/components/general/DataTable'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemSupervisor',
          route: 'ce_supervisores',
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
              text: 'Tercero',
              align: 'left',
              sortable: false,
              value: 'tercero.razon_social'
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
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('supervisores')
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
