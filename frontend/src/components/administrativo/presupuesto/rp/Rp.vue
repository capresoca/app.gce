<template>
  <div>
    <v-card>
      <toolbar-list :info="infoComponent" title="Registro Presupuestal" subtitle="Registro y gestión" btnplus btnplustitle="Crear Item"/>
      <data-table
        v-model="dataTableRP"
        @resetOption="item => resetOptions(item)"
        @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
        @imprimir="item => descargarPdf(item.id)"
      />
    </v-card>
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  export default {
    name: 'RP',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable'),
      Loading
    },
    data () {
      return {
        rps: [],
        dataTableRP: {
          nameItemState: 'itemRp',
          route: 'pr_rps',
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
              text: 'Periodo',
              align: 'left',
              sortable: false,
              value: 'periodo'
            },
            {
              text: 'Consecutivo',
              align: 'left',
              sortable: false,
              value: 'consecutivo',
              classData: 'text-xs-left',
              component: {
                component: {
                  template: `
                  <div>
                    <span>{{str_pad(value.consecutivo,8)}}</span>
                  </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Tercero',
              align: 'left',
              sortable: false,
              value: 'tercero.nombre_completo'
            },
            {
              text: 'Fecha',
              align: 'center',
              sortable: false,
              value: 'fecha',
              width: '120px',
              classData: 'text-xs-center'
            },
            {
              text: 'Vencimiento',
              align: 'center',
              sortable: false,
              value: 'fecha_vence',
              classData: 'text-xs-center'
            },
            {
              text: 'Tipo',
              align: 'left',
              sortable: false,
              value: 'tipo',
              width: '120px'
            },
            {
              text: 'Estado',
              align: 'left',
              sortable: true,
              value: 'estado'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true
            }
          ],
          filters: [
            {
              label: 'Estado',
              type: 'v-autocomplete',
              items: () => [{text: 'Registrado', value: 'Registrado'}, {text: 'Confirmado', value: 'Confirmado'}, {text: 'Anulado', value: 'Anulado'}],
              vModel: [],
              multiple: true,
              itemText: 'text',
              itemValue: 'value',
              value: 'estado',
              clearable: true
            }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('rp')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push((item.estado === 'Registrado') ? {event: 'editar', icon: 'edit', tooltip: 'Editar'} : {event: 'editar', icon: 'fas fa-eye', tooltip: 'Visualizar', size: '20px'})
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'imprimir', icon: 'far fa-file-pdf', tooltip: 'Imprimir'})
        return item
      },
      descargarPdf (id) {
        this.filePrint(`reporte-pr-rp&id=${id}`)
      }
    }
  }
</script>

<style scoped>

</style>
