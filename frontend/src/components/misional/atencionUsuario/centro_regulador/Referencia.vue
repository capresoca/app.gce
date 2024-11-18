<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Crear Referencia" title="Centro Regulador" subtitle="Referencias"/>
    <data-table
      v-model="dataTableReferencia"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detallesReferencia', titulo: 'Detalle Referencia', parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
      @imprimir="item => imprimir(item)"
    />
  </v-card>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'Referencia',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    data () {
      return {
        dataTableReferencia: {
          nameItemState: 'itemReferencia',
          route: 'au_referencias',
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
              text: 'Consecutivo',
              align: 'left',
              sortable: false,
              value: 'consecutivo'
            },
            {
              text: 'Fecha Solicitud',
              align: 'left',
              sortable: false,
              value: 'fecha_solicitud'
            },
            {
              text: 'Afiliado',
              align: 'left',
              sortable: false,
              value: 'identificacion_afiliado',
              component: {
                component: {
                  template:
                    `<mini-card-detail :data="value.afiliado.mini_afiliado"/>`,
                  props: ['value']
                }
              }
            },
            // {
            //   text: 'Nombre Afiliado',
            //   align: 'left',
            //   sortable: false,
            //   value: 'carita_nombre_afiliado',
            //   component: {
            //     component: {
            //       template: `
            //         <div>
            //           <v-tooltip right>
            //             <a slot="activator" style="cursor: pointer !important; text-decoration: none !important; color: rgba(0,0,0,0.87) !important;" @click.prevent="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: {id: value.id_afiliado}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})" v-text="value.carita_nombre_afiliado"></a>
            //             <span v-text="'Ver más información'"></span>
            //           </v-tooltip>
            //         </div>
            //       `,
            //       props: ['value']
            //     }
            //   }
            // },
            {
              text: 'IPS Origen',
              align: 'left',
              sortable: false,
              value: 'origen'
            },
            {
              text: 'Usuario',
              align: 'left',
              sortable: false,
              value: 'usuario'
            },
            {
              text: 'Estado',
              align: 'left',
              sortable: false,
              value: 'estado'
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
        return this.$store.getters.getInfoComponent('referencias')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar && (item.estado !== 'Fallecida' && item.estado !== 'Finalizada' && item.estado !== 'Cancelada')) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'detalle', icon: 'find_in_page', tooltip: 'Ver Detalle'})
        if (this.infoComponent && this.infoComponent.permisos.ver && (item.bitacota === true)) item.options.push({event: 'imprimir', icon: 'far fa-file-pdf', tooltip: 'Imprimir Informe'})
        return item
      },
      imprimir (item) {
        // console.log(item)
        let id = item.id
        this.axios.get('firmar-ruta?nombre_ruta=reporte_bitacora&id=' + id)
          .then((res) => {
            let url = res.data
            let win = window.open(url, '_blank')
            win.focus()
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al abrir el archivo de impresión. ' + e.response, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
