<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Registro de concepto de presupuesto" title="concepto de presupuesto" subtitle="Gestion concepto de presupuesto"/>
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar concepto de presupuesto', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
  </v-card>
</template>
<script>
  import ToolbarList from '@/components/general/ToolbarList'
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'ConceptoPresupuesto',
    components: {
      ToolbarList,
      DataTable
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemConceptoPresupuestoPr',
          route: 'pr_concepto_presupuestos',
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
        return this.$store.getters.getInfoComponent('conceptopresupuesto')
      }
    }
  }
</script>