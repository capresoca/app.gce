<template>
    <div>
      <v-card>
        <toolbar-list :info="infoComponent"  btnplus btnplustitle="Crear Traslado" title="Traslados Presupuestales" subtitle="Ingresos y gastos"/>
        <data-table
          v-model="dataTableTraslado"
          @resetOption="item => resetOptions(item)"
          @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
        />
      </v-card>
    </div>
</template>

<script>
  export default {
    name: 'TrasladoPresupuestal',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable')
    },
    data () {
      return {
        dataTableTraslado: {
          nameItemState: 'itemTraladosPresupuestales',
          route: 'pr_traslados',
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
              value: 'consecutivo_presupuestal'
            },
            {
              text: 'N° resolución',
              align: 'left',
              sortable: false,
              value: 'resolucion',
              component: {
                component: {
                  template: `
                        <div>
                          <span class="text-xs-right">
                            {{ (value.tipo === 'Gasto') ? value.presupuesto_inicial_gasto.entidad_resolucion.resolucion : value.presupuesto_inicial_ingreso.entidad_resolucion.resolucion }}
                          </span>
                        </div>
                      `,
                  props: ['value']
                }
              }
            },
            // {
            //   text: 'Fecha',
            //   align: 'center',
            //   sortable: false,
            //   value: 'fecha',
            //   classData: 'text-xs-center'
            // },
            {
              text: 'Tipo',
              align: 'left',
              sortable: false,
              value: 'tipo'
            },
            // {
            //   text: 'Valor',
            //   align: 'left',
            //   sortable: false,
            //   value: 'valor',
            //   classData: 'text-xs-right',
            //   component: {
            //     component: {
            //       template: `
            //             <div>
            //               <span class="text-xs-right">
            //                 {{ value.valor | numberFormat(2,'$') }}
            //               </span>
            //             </div>
            //           `,
            //       props: ['value']
            //     }
            //   }
            // },
            {
              text: 'Estado',
              align: 'center',
              sortable: false,
              value: 'estado',
              classData: 'text-xs-center'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'justify-center layout px-0',
              value: 'id'
            }
          ],
          filters: [
            {
              label: 'Tipos',
              type: 'v-autocomplete',
              items: () => this.tipos,
              vModel: [],
              multiple: true,
              itemText: 'text',
              itemValue: 'value',
              value: 'tipo',
              clearable: true
            }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('trasladoPresupuestal')
      },
      tipos () {
        return [
          {
            text: 'Ingresos',
            value: 'Ingreso'
          },
          {
            text: 'Gastos',
            value: 'Gasto'
          }
        ]
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push(item.estado !== 'Registrado' ? {event: 'editar', icon: 'remove_red_eye', tooltip: 'Visualizar'} : {event: 'editar', icon: 'edit', tooltip: 'Editar'})
        return item
      }
    }
  }
</script>

<style scoped>

</style>
