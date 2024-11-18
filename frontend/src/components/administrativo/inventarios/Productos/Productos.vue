<template>
	<v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Crear Producto" title="Productos" subtitle="Gestion de Productos"/>
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar Producto', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
	</v-card>
</template>

<script>
  export default {
    name: 'Productos',
    components: {
      DataTable: () => import('@/components/general/DataTable'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemProducto',
          route: 'in_productos',
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
              value: 'descripcion'
            },
            {
              text: 'Grupo',
              align: 'left',
              sortable: false,
              value: 'grupo.nombre'
            },
            {
              text: 'Subgrupo',
              align: 'left',
              sortable: false,
              value: 'subgrupo.nombre'
            },
            {
              text: 'fecha de creacion',
              align: 'left',
              sortable: false,
              value: 'fecha_creacion'
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
        return this.$store.getters.getInfoComponent('productos')
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
