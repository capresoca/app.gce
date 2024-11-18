<template>
  <v-card>
    <toolbar-list
      :info="infoComponent"
      title="Pre-Nómina"
      subtitle="Mantenimiento"
      btnplus
      btnplustitle="Crear Nómina"
    ></toolbar-list>
    <data-table-v2
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
    >
    </data-table-v2>
  </v-card>
</template>

<script>
  export default {
    name: 'ScLiquidacionNomina',
    data: () => ({
      dataTable: {
        nameItemState: 'tableScNominalLiquidacion',
        route: 'talentohumano/nominaliquidaciones',
        makeHeaders: [
          {
            text: 'Año',
            align: 'left',
            sortable: false,
            value: 'ano',
            classData: 'text-xs-center'
          },
          {
            text: 'Mes',
            align: 'left',
            sortable: false,
            value: 'mes_nombre'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
          },
          {
            text: 'Centro de Costo',
            align: 'left',
            sortable: false,
            value: 'descripcion',
            component: {
              template: `
                <span v-text="value.cencosto ? value.cencosto.descripcion : 'N/A'"></span>
              `,
              props: ['value']
            }
          },
          {
            text: 'Área',
            align: 'left',
            sortable: false,
            value: 'descripcion',
            component: {
              template: `
                <span v-text="value.tbarea ? value.tbarea.descripcion : 'N/A'"></span>
              `,
              props: ['value']
            }
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado_nombre'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            actions: true,
            singlesActions: true,
            classData: 'text-xs-center'
          }
        ]
      }
    }),
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('preScnomina')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        item.options.push({event: 'editar', color: 'accent', icon: 'fas fa-edit', tooltip: 'Edición del registro'})
        return item
      }
    }
  }
</script>

<style scoped>

</style>
