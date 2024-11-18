<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Compensaciones" subtitle="Carga de archivos" btnplus btnplustruncate @click="showForm = true" btnplustitle="Nueva carga de archivos" />
    <v-slide-y-transition>
      <registro-archivos-compensaciones
        v-show="showForm"
        v-model="showForm"
        @cancel="showForm = false"
      ></registro-archivos-compensaciones>
    </v-slide-y-transition>
    <data-table
      ref="tablaCompensaciones"
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      :filters="false"
    ></data-table>
  </v-card>
</template>

<script>
  export default {
    name: 'Compensaciones',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable'),
      RegistroArchivosCompensaciones: () => import('@/components/misional/aseguramiento/relacionesLaborales/compensaciones/RegistroArchivosCompensaciones'),
      InputFile: () => import('@/components/general/InputFile')
    },
    data () {
      return {
        showForm: false,
        dataTable: {
          nameItemState: 'itemArchivoCompensacion',
          visibleLoading: 'true',
          route: 'resultado_compensacion',
          makeHeaders: [
            {
              text: 'Archivo ABX',
              align: 'left',
              sortable: false,
              value: 'abx.nombre',
              classData: '',
              component: {
                component: {
                  template:
                    `<span>{{value.abx ? value.abx.nombre : ''}}</span>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Archivo ACX',
              align: 'left',
              sortable: false,
              value: 'acx.nombre',
              classData: '',
              component: {
                component: {
                  template:
                    `<span>{{value.acx ? value.acx.nombre : ''}}</span>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Fecha resultado',
              align: 'left',
              sortable: true,
              value: 'fecha_resultado',
              classData: ''
            },
            {
              text: 'Fecha cargue',
              align: 'left',
              sortable: true,
              value: 'updated_at',
              classData: ''
            },
            {
              text: 'Estado',
              align: 'center',
              sortable: false,
              value: 'estado',
              classData: 'text-xs-center',
              component: {
                component: {
                  template:
                    `<div>
                      <v-tooltip top v-if="value.estado === 'Procesando'">
                        <span slot="activator">
                                <v-progress-circular
                                  indeterminate
                                  color="primary"
                                /> Procesando...
                        </span>
                        <span>{{value.estado}}</span>
                      </v-tooltip>
                      <v-chip v-else label color="success" text-color="white">
                        <v-icon left>check_box</v-icon> {{value.estado}}
                      </v-chip>
                    </div>`,
                  props: ['value']
                }
              }
            }
          ]
        }
      }
    },
    watch: {
      'item' (val) {
        if (val && val.item) {
          setInterval(() => {
            this.dataTable.visibleLoading = false
            this.$refs.tablaCompensaciones.reloadPage()
            setTimeout(() => {
              this.dataTable.visibleLoading = true
            }, 2000)
          }, 30000)
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('compensaciones')
      },
      item () {
        return JSON.parse(JSON.stringify(this.$store.state.tables.itemArchivoCompensacion))
      }
    },
    mounted () {

    },
    methods: {
      resetOptions (item) {
        item.options = []
        return item
      }
    }
  }
</script>

<style scoped>

</style>
