<template>
	<v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Crear auditor" title="Auditores" subtitle="Gestion de auditores"/>
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar auditor', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
	</v-card>
</template>

<script>
  export default {
    name: 'Auditores',
    components: {
      DataTable: () => import('@/components/general/DataTable'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemAuditores',
          route: 'ac_auditores',
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
              value: 'usuario.name'
            },
            {
              text: 'Tipo',
              align: 'left',
              sortable: false,
              value: 'tipo'
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
        return this.$store.getters.getInfoComponent('auditores')
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
