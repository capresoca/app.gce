<template>
    <v-card>
      <toolbar-list :info="infoComponent" title="Concurrencias" subtitle="Gestión"/>
      <data-table
        v-model="dataTableConcurrenciasAsignadas"
        @resetOption="item => resetOptions(item)"
        @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro + (item.afiliado ? ' ' + item.afiliado.identificacion_completa : null), parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
      ></data-table>
    </v-card>
</template>

<script>
  export default {
    name: 'ConcurrenciasAsignadas',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable')
    },
    data: () => ({
      dataTableConcurrenciasAsignadas: {
        nameItemState: 'itemConcurrencias',
        route: 'cuentasmedicas/concurrenciasAsignadas',
        simplePaginate: false,
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
            sortable: true,
            value: 'consecutivo',
            component: {
              component: {
                template: `
                    <div>
                      <v-btn
                        color="primary"
                        dark
                        fab
                        small
                        v-text="value.consecutivo"
                      />
                    </div>
                  `,
                props: ['value']
              }
            }
          },
          {
            text: 'PACIENTE',
            align: 'left',
            sortable: false,
            value: 'as_afiliado_id',
            component: {
              component: {
                template: `
                    <div>
                      <mini-card-detail
                      :data="value.afiliado
                      ? value.afiliado.mini_afiliado
                      : null"></mini-card-detail>
                    </div>
                  `,
                props: ['value']
              }
            }
          },
          {
            text: 'Estancia',
            align: 'center',
            sortable: false,
            value: 'estancia',
            component: {
              component: {
                template: `
                    <div>
                       <v-chip color="red" text-color="white"><span v-text="value.tiempo_estancia + ' días'"></span></v-chip>
                    </div>
                  `,
                props: ['value']
              }
            },
            classData: 'text-xs-center'
          },
          {
            text: 'CONSECUTIVO IPS',
            align: 'center',
            sortable: false,
            value: 'consecutivo_ips',
            component: {
              component: {
                template: `
                    <div>
                       <v-chip color="teal" label text-color="white" medium>
                          <span v-text="value.consecutivo_ips"></span>
                      </v-chip>
                    </div>
                  `,
                props: ['value']
              }
            },
            classData: 'text-xs-center'
          },
          {
            text: 'ENTIDAD',
            align: 'left',
            sortable: false,
            value: 'cod_dx',
            component: {
              component: {
                template: `
                    <div>
                     <span v-text="value.entidad ? value.entidad.nombre : 'No Registra'"></span>
                    </div>
                  `,
                props: ['value']
              }
            }
          },
          {
            text: 'FECHA INGRESO',
            align: 'center',
            sortable: false,
            value: 'fecha_ingreso',
            component: {
              component: {
                template: `
                    <div>
                     <span v-text="moment(value.fecha).format('YYYY/MM/DD')"></span>
                    </div>
                  `,
                props: ['value']
              }
            },
            classData: 'text-xs-center'
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
            singlesActions: true,
            classData: 'text-xs-center'
          }
        ]
      }
    }),
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('concurrenciasAsignadas')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'detalle', icon: 'fas fa-tasks', tooltip: 'Ir a la concurrencia', color: 'teal', size: '20px'})
        return item
      }
    }
  }
</script>

<style scoped>

</style>
