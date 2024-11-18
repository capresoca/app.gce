<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Registro de Dependencia" title="Dependencia" subtitle="Gestion Dependencia"/>
    <data-table
      v-model="dataTableAlmacen"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar Dependencia', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
  </v-card>
</template>
<script>
  import ToolbarList from '@/components/general/ToolbarList'
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'Dependencias',
    components: {
      ToolbarList,
      DataTable
    },
    data () {
      return {
        dataTableAlmacen: {
          nameItemState: 'itemDependenciaPr',
          route: 'pr_dependencias',
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
            // {
            //   text: 'Identificacón Representante',
            //   align: 'left',
            //   sortable: false,
            //   value: 'tercero.identificacion_completa'
            // },
            // {
            //   text: 'Representante',
            //   align: 'left',
            //   sortable: false,
            //   value: 'tercero.nombre_completo'
            // },
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
        return this.$store.getters.getInfoComponent('dependencia')
      }
    }
  }
</script>
