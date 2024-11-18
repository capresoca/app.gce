<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Entidades" subtitle="Registro y gestión" btnplus btnplustitle="Crear entidad" />
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
    />
  </v-card>
</template>

<script>
  export default {
    name: 'Entidades',
    components: {
      DataTable: () => import('@/components/general/DataTable'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemEntidad',
          route: 'entidades',
          makeHeaders: [
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
              text: 'Tipo',
              align: 'left',
              sortable: false,
              value: 'tipo'
            },
            {
              text: 'Código habilitación',
              align: 'left',
              sortable: true,
              value: 'cod_habilitacion'
            },
            {
              text: 'Municipio',
              align: 'left',
              sortable: true,
              value: 'municipio'
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
        return this.$store.getters.getInfoComponent('entidades')
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
