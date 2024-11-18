<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Certificado Disponibilidad Presupuestal" subtitle="Registro y gestión" btnplus btnplustitle="Crear Item"/>
    <data-table
      v-model="dataTableCdp"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      @imprimir="item => descargarPdf(item.id)"
    />
  </v-card>
</template>

<script>
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'
  export default {
    name: 'CDP',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        cdps: [],
        search: '',
        dataTableCdp: {
          nameItemState: 'itemCdp',
          route: 'pr_cdps',
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
              text: 'CONSECUTIVO',
              align: 'left',
              sortable: false,
              value: 'consecutivo'
            },
            {
              text: 'PERIODO',
              align: 'left',
              sortable: false,
              value: 'periodo'
            },
            {
              text: 'FECHA',
              align: 'left',
              sortable: false,
              value: 'fecha'
            },
            {
              text: 'VENCIMIENTO',
              align: 'left',
              sortable: false,
              value: 'fecha_vence'
            },
            {
              text: 'OBJECIÓN',
              align: 'left',
              sortable: false,
              value: 'objecto'
            },
            {
              text: 'VALOR CDP',
              align: 'center',
              sortable: false,
              value: 'valor_cdp',
              classData: 'text-xs-right',
              component: {
                component: {
                  template: `
                    <div><span v-text="currency(value.valor_cdp)"></span></div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'VALOR EJECUTADO',
              align: 'center',
              sortable: false,
              value: 'suma_valores_ejecutados',
              classData: 'text-xs-right',
              component: {
                component: {
                  template: `
                    <div><span v-text="currency(value.suma_valores_ejecutados)"></span></div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'ESTADO',
              align: 'left',
              sortable: false,
              value: 'estado'
            },
            {
              text: 'OPCIONES',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'justify-center layout px-0'
            }
          ]
        },
        tableLoading: false
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('CDP')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar && (item.estado !== 'Confirmada')) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        if (this.infoComponent && this.infoComponent.permisos.agregar && (item.estado === 'Confirmada')) item.options.push({event: 'editar', icon: 'remove_red_eye', tooltip: 'Ver CDP', color: 'teal'})
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'imprimir', icon: 'far fa-file-pdf', tooltip: 'Imprimir Reporte', color: 'black'})
        return item
      },
      descargarPdf (id) {
        this.axios.get(`firmar-ruta?nombre_ruta=reporte-pr-cdp&id=${id}`)
          .then(res => {
            window.open(res.data)
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {msg: 'Error al generar el archivo. ' + e, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
