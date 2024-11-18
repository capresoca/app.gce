<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Terceros" subtitle="Registro y gestión" btnplus btnplustitle="Crear tercero"></toolbar-list>
    <data-table-v2
      ref="tableTerceros"
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
    >
    </data-table-v2>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'Terceros',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      this.me = null
      return {
        dataTable: {
          nameItemState: 'tableTerceros',
          route: 'terceros',
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
              value: 'nombre_completo'
            },
            {
              text: 'Tipo contribuyente',
              align: 'left',
              sortable: false,
              value: 'tipo_contribuyente'
            },
            {
              text: 'Celular',
              align: 'left',
              sortable: true,
              value: 'celular'
            },
            {
              text: 'Email',
              align: 'left',
              sortable: true,
              value: 'correo_electronico'
            },
            {
              text: 'Estado',
              align: 'center',
              sortable: false,
              value: 'estado',
              component: {
                template:
                  `<cambiar-estado-tercero :tercero="value"></cambiar-estado-tercero>`,
                props: ['value']
              }
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
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('terceros')
      },
      complementos () {
        return store.getters.complementosTablaTerceros
      }
    },
    created () {
      this.me = this
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
