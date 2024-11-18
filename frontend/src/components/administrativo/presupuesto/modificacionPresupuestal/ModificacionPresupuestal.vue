<template>
  <div>
    <v-card>
      <toolbar-list :info="infoComponent" title="Modificación Presupuestal" subtitle="Ingresos y gastos" btnplus btnplustitle="Crear Modificación"/>
      <data-table
        v-model="dataTableModificaciones"
        @resetOption="item => resetOptions(item)"
        @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
      <!--v-container fluid>
        <v-layout row wrap>
          <v-flex xs1 sm3 md6 class="text-xs-right">
            <v-tooltip top>
              <v-btn
                slot="activator"
                flat
                icon
                color="accent"
                @click="reloadPage"
              >
                <v-icon>cached</v-icon>
              </v-btn>
              <span>Actualizar registros </span>
            </v-tooltip>
          </v-flex>
          <v-flex xs12 sm3 md2>
            <v-select
              label="Registros por página"
              v-model="pagination.per_page"
              :items="items_page"
              item-text="text"
              item-value="value"
            />
          </v-flex>
          <v-flex xs12 sm6 md4>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              @input="buscar"
            />
          </v-flex>
        </v-layout>

        <v-data-table
          :headers="headers"
          :items="modificaciones"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="modificaciones.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.periodo }}</td>
            <td>{{ props.item.consecutivo_presupuestal}}</td>
            <td>{{ props.item.presupuesto_inicial_gasto.entidad_resolucion.resolucion}}</td>
            <td>{{ props.item.documento }}</td>
            <td>{{ props.item.estado }}</td>
            <td>
              <v-speed-dial
                v-model="props.item.show"
                direction="left"
                open-on-hover
                transition="slide-x-transition"
              >
                <v-btn
                  slot="activator"
                  v-model="props.item.show"
                  color="blue darken-2"
                  dark
                  fab
                  small
                >
                  <v-icon>chevron_left</v-icon>
                  <v-icon>close</v-icon>
                </v-btn>
                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
                  >
                    <v-icon color="accent">mode_edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
              </v-speed-dial>
            </td>
          </template>
          <template slot="no-data">
            <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
              Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
            </v-alert>
            <v-alert v-else :value="true" color="info" icon="info">
              Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
            </v-alert>
          </template>

          <template slot="footer">
            <td colspan="100%" class="text-xs-center">
              <v-pagination v-model="pagination.current_page" :total-visible="7" :length="pagination.last_page" @input="reloadPage"></v-pagination>
            </td>
          </template>
        </v-data-table>
      </v-container-->
    </v-card>
  </div>
</template>

<script>
  // import {mapState} from 'vuex'
  export default {
    name: 'ModificacionPresupuestal',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable')
    },
    data () {
      return {
        dataTableModificaciones: {
          nameItemState: 'itemModPresupuestales',
          route: 'pr_mod_presupuestales',
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
        },
        modificaciones: [],
        search: '',
        tableLoading: false,
        items_page: [
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
        pagination: {
          per_page: 15,
          current_page: 1
        },
        headers: [
          {
            text: 'Periodo',
            align: 'left',
            sortable: false,
            value: 'valorPresupuesto'
          },
          {
            text: 'Consecutivo',
            align: 'left',
            sortable: false,
            value: 'vigencia'
          },
          {
            text: 'N° resolución',
            align: 'left',
            sortable: false,
            value: 'resolucion'
          },
          {
            text: 'Documento',
            align: 'left',
            sortable: false,
            value: 'valor'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    // created () {
    //   this.reloadPage()
    // },
    // watch: {
    //   'itemModificacionesPresupuestoGastos' (value) {
    //     if (value.estado === 'crear') {
    //       this.modificaciones.splice(0, 0, value.item)
    //     } else if (value.estado === 'editar') {
    //       this.modificaciones.splice(this.modificaciones.findIndex(x => x.id === value.item.id), 1, value.item)
    //     }
    //   },
    //   'pagination.per_page' () {
    //     this.pagination.current_page = 1
    //     this.reloadPage()
    //   }
    // },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('modificacionPresupuestal')
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
      },
      estados () {
        return [
          {
            text: 'Registrada',
            value: 'Registrada'
          },
          {
            text: 'Anulada',
            value: 'Anulada'
          },
          {
            text: 'Confirmada',
            value: 'Confirmada'
          }
        ]
      }
      // ...mapState({
      //   itemModificacionesPresupuestoGastos: state => state.tables.itemModificacionesPresupuestoGastos
      // }),
      // pages () {
      //   if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
      //   return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      // }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: (item.estado !== 'Registrada' ? 'remove_red_eye' : 'edit'), tooltip: item.estado !== 'Registrada' ? 'Ver' : 'Editar'})
        return item
      }
      // reloadPage () {
      //   this.tableLoading = true
      //   this.axios.get('pr_mod_presupuestales?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
      //     .then((response) => {
      //       response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
      //       this.pagination = response.data.meta
      //       this.modificaciones = response.data.data
      //       this.tableLoading = false
      //     })
      //     .catch(e => {
      //       this.tableLoading = false
      //       return false
      //     })
      // },
      // buscar: window.lodash.debounce(function () {
      //   this.pagination.current_page = 1
      //   this.reloadPage()
      // }, 500)

    }
  }
</script>

<style scoped>

</style>
