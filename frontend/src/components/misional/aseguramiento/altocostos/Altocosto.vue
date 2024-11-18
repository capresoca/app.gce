<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Alto costos" subtitle="Registro y gestión" btnplus btnplustruncate />
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
    >
    </data-table>
  </v-card>
</template>


<script>
    export default {
      name: 'Altocosto',
      components: {
        DataTable: () => import('@/components/general/DataTable'),
        ToolbarList: () => import('@/components/general/ToolbarList')
      },
      data: function () {
        return {
          dataTable: {
            nameItemState: 'itemAsAfiliadoAltocostos',
            route: 'afiliadoaltocostos',
            makeHeaders: [
              {
                text: 'Afiliado',
                align: 'left',
                sortable: false,
                value: 'afiliado.identificacion',
                component: {
                  component: {
                    template: `<mini-card-detail :data="value.afiliado.mini_afiliado"/>`,
                    props: ['value']
                  }
                }
              },
              {
                text: 'Tipo',
                align: 'left',
                sortable: false,
                value: 'tipo.nombre'
              },
              {
                text: 'Diagnóstico',
                align: 'left',
                sortable: false,
                value: 'diagnostico.descripcion'
              }
            ]
          }
        }
      },
      computed: {
        infoComponent () {
          return this.$store.getters.getInfoComponent('altocostos')
        }
      },
      methods: {
        resetOptions (item) {
          item.options = []
          if (this.infoComponent && this.infoComponent.permisos.agregar && (item.length !== 0)) item.options.push({event: 'editar', icon: 'local_library', tooltip: 'Revisar'})
          return item
        }
      }
    }
</script>
