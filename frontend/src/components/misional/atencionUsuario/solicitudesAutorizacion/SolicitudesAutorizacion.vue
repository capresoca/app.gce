<template>
  <v-card>
    <toolbar-list
      :info="infoComponent"
      title="Solicitudes de Autorización"
      subtitle="Gestión"
    />
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @imprimir="item => imprimir(item)"
      @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleSolicitudAutorizacion', titulo: 'Detalle Solicitud Autorización', parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
      @estudiar="item => $store.commit('NAV_ADD_ITEM', { ruta: 'estudioSolicitudAutorizacion', titulo: 'Estudio Solicitud Autotización', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
    />
  </v-card>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'SolicitudesAutorizacion',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemAuSolicitudAutorizacion',
          route: 'autsolicitudes',
          makeHeaders: [
            {
              text: 'Identificación afiliado',
              align: 'left',
              sortable: false,
              value: 'identificacion'
            },
            {
              text: 'Nombre afiliado',
              align: 'left',
              sortable: true,
              value: 'nombre_completo'
            },
            {
              text: 'Fecha solicitud',
              align: 'left',
              sortable: false,
              value: 'fecha',
              component: {
                component: {
                  template:
                    `<span>{{moment(value.fecha).format('DD/MM/YYYY  HH:mm')}}</span>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Estado',
              align: 'center',
              sortable: true,
              value: 'estado',
              classData: 'text-xs-center'
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
        return this.$store.getters.getInfoComponent('solicitudesAutorizacion')
      }
    },
    methods: {
      imprimir (item) {
        this.axios.get('firmar-ruta?nombre_ruta=autsolicitude&id=' + item.id)
          .then(response => {
            if (response.data !== '') {
              window.open(response.data, '_blank')
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al generar el documento. ', error: e})
          })
      },
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar && item.estado === 'Confirmada') item.options.push({event: 'estudiar', icon: 'ballot', tooltip: 'Estudiar'})
        if (this.infoComponent && this.infoComponent.permisos.imprimir && item.estado !== 'Registrada') item.options.push({event: 'imprimir', icon: 'far fa-file-pdf', color: 'primary', tooltip: 'Imprimir'})
        item.options.push({event: 'detalle', color: 'success', icon: 'find_in_page', tooltip: 'Detalle'})
        return item
      }
    }
  }
</script>

<style scoped>
</style>
