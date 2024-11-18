<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Aportes" subtitle="Carga de archivos" btnplus btnplustruncate @click="showForm = true" btnplustitle="Nueva carga de archivos" />
    <v-slide-y-transition>
      <registro-archivos-aportes
        v-show="showForm"
        v-model="showForm"
        @cancel="showForm = false"
      />
    </v-slide-y-transition>
    <data-table
      ref="tablaAportes"
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      :filters="false"
    />
  </v-card>
</template>

<script>
  export default {
    name: 'Aportes',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable'),
      RegistroArchivosAportes: () => import('@/components/misional/aseguramiento/relacionesLaborales/aportes/RegistroArchivosAportes'),
      InputFile: () => import('@/components/general/InputFile')
    },
    data () {
      return {
        showForm: false,
        dataTable: {
          nameItemState: 'itemArchivoAporte',
          visibleLoading: 'true',
          route: 'archivosaportes',
          makeHeaders: [
            {
              text: 'Financiero ADRES',
              align: 'left',
              sortable: false,
              value: 'archivo_financiero.nombre',
              classData: ''
            },
            {
              text: 'Pila ADRES',
              align: 'left',
              sortable: false,
              value: 'archivo_pila.nombre',
              classData: ''
            },
            {
              text: 'Fecha',
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
            this.$refs.tablaAportes.reloadPage()
            setTimeout(() => {
              this.dataTable.visibleLoading = true
            }, 2000)
          }, 30000)
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('aportes')
      },
      item () {
        return JSON.parse(JSON.stringify(this.$store.state.tables.itemArchivoAporte))
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
