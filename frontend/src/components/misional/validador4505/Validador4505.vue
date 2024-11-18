<template>
  <main>
    <v-card>
      <toolbar-list class="elevation-0 grey lighten-4" title="Validador 4505"/>
      <v-container fluid>
        <data-table
          ref="tablaValidador4505"
          v-model="dataTable"
          @resetOption="item => resetOptions(item)"
          @descargar="item => descargar(item)"
          @confirmar="item => confirmar(item)"
        >

        </data-table>
      </v-container>
    </v-card>
    <v-dialog
      v-model="dialogdescarga"
      hide-overlay
      persistent
      width="300"
    >
      <v-card
        color="primary"
        dark
      >
        <v-card-text>
          Por favor espere, estamos descargando el archivo 4505
          <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </main>
</template>

<script>
  export default {
    name: 'Validador405',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable')
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemValidador4505',
          route: 'rs_validador4505_radicados',
          makeHeaders: [
            {
              text: 'Cod. Radicación',
              align: 'left',
              sortable: true,
              value: 'cod_radicacion_ct'
            },
            {
              text: 'Fecha',
              align: 'left',
              sortable: false,
              classData: 'text-xs-left',
              component: {
                component: {
                  template:
                    `<span>{{moment(value.updated_at).format('YYYY-MM-DD')}}</span>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Periodo',
              align: 'left',
              sortable: false,
              component: {
                component: {
                  template:
                    `<span>{{value.periodo}}</span>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Entidad',
              align: 'left',
              sortable: false,
              component: {
                component: {
                  template:
                    `
                      <v-list-tile>
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.rs_entidad.nombre}}</v-list-tile-title>
                          <v-list-tile-title class="caption grey--text">Cod Habilitación: {{value.rs_entidad.cod_habilitacion}}</v-list-tile-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Estado',
              align: 'center',
              sortable: true,
              value: 'estado_radicacion',
              classData: 'text-xs-center'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'text-xs-center'
            }
          ]
        },
        datosValidador: [],
        dialogEliminar: false,
        tableLoading: false,
        ripAcciones: {},
        eliminarRip: {
          titulo: '',
          mensaje: ''
        },
        sePuedeEliminar: true,
        dialogdescarga: false
      }
    },
    methods: {
      descargar (data) {
        this.dialogdescarga = true
        this.axios({
          url: 'descargar-4505-validados/' + data.id,
          method: 'GET',
          responseType: 'blob' // important
        })
          .then((response) => {
            this.dialogdescarga = false
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', data.cod_radicacion_ct + '.TXT')
            document.body.appendChild(link)
            link.click()
          })
          .catch((error) => {
            this.dialogdescarga = false
            if (error.response.status === 513) {
              this.$store.commit('SNACK_SHOW', {msg: 'El directorio de archivos validador4505 fue eliminado del servidor. Contacte con el administrador', color: 'error'})
            } else {
              this.$store.commit('SNACK_SHOW', {msg: 'Algo salio mal al descargar el archivo ' + error.response.message, color: 'error'})
            }
          })
      },
      confirmar (data) {
        this.axios.post('rs_validador4505_radicados', data)
          .then(response => {
            this.$store.commit('SNACK_SHOW', {msg: 'Archivs Confirmados.', color: 'success'})
          })
          .catch(e => {
            this.loadingSubmit = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al realizar la confirmacion de los archivos. ', error: e})
          })
      },
      resetOptions (item) {
        item.options = []
        if (item.estado_radicacion === 'Validado') {
          item.options.push({event: 'descargar', icon: 'cloud', tooltip: 'Descargar', color: 'primary'})
        }
        item.options.push({event: 'confirmar', icon: 'check', tooltip: 'Confirmar', color: 'primary'})
        item.periodo = (this.moment(item.fecha_inicio_periodo).format('YYYYMM')) + ' - ' + (this.moment(item.fecha_fin_periodo).format('YYYYMM'))
        return item
      }
    }
  }
</script>

<style scoped>

</style>
