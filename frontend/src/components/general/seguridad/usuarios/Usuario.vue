<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Usuario" title="Usuarios" subtitle="Registro y gestión"/>
    <data-table
      v-model="dataTableUsuario"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
    />
  </v-card>
</template>

<script>
  // import {mapState} from 'vuex'
  export default {
    name: 'Usuario',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable')
    },
    data () {
      return {
        dataTableUsuario: {
          nameItemState: 'itemUsuario',
          route: 'usuarios',
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
              value: 'identification'
            },
            {
              text: 'Nombre',
              align: 'left',
              sortable: false,
              value: 'name'
            },
            {
              text: 'Celular',
              align: 'left',
              sortable: false,
              value: 'phone'
            },
            {
              text: 'Email',
              align: 'left',
              sortable: false,
              value: 'email'
            },
            {
              text: 'Tipo',
              align: 'left',
              sortable: false,
              value: 'tipo'
            },
            {
              text: 'Estado',
              align: 'left',
              sortable: false,
              value: 'state'
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
        return this.$store.getters.getInfoComponent('usuarios')
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
