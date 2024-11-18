<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Contratistas" subtitle="Registro y gestión" btnplus btnplustitle="Crear contratista" />
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
    />
  </v-card>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  import ToolbarList from '@/components/general/ToolbarList'
  export default {
    name: 'Contratistas',
    components: {
      ToolbarList,
      DataTable
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemContratista',
          route: 'ce_contratistas',
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
              text: 'Identificación',
              align: 'left',
              sortable: false,
              value: 'identificacion'
            },
            {
              text: 'Nombre',
              align: 'left',
              sortable: false,
              value: 'nombre_contratista'
            },
            {
              text: 'Naturaleza',
              align: 'left',
              sortable: false,
              value: 'nombre_natjuridica'
            },
            {
              text: 'Camara de comercio',
              align: 'left',
              sortable: true,
              value: 'ccomercio'
            },
            {
              text: 'Fecha expedición',
              align: 'left',
              sortable: true,
              value: 'fecha_ccomercio'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'justify-center layout px-0'
            }
          ],
          filters: []
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('contratistas')
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
