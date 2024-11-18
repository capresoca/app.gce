<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Reportes de entrega" subtitle="">
      <template slot="actions">
        <v-btn
          color="primary"
          dark
          @click.stop="descargas.visible = true"
        >
          <v-icon class="mr-1">cloud_download</v-icon> descargar reportes
        </v-btn>
      </template>
    </toolbar-list>
    <data-table
      ref="tablaReportesEntrega"
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
    ></data-table>
    <descargas v-model="descargas.visible" :tipo="descargas.tipo" @success="downloadSuccess"></descargas>
  </v-card>
</template>
<script>
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'ReportesEntrega',
    components: {
      DataTable,
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Descargas: () => import('@/components/mipres/descargas/Descargas')
    },
    data () {
      return {
        descargas: {
          visible: false,
          tipo: 4
        },
        registro: {
          item: null,
          show: false
        },
        dataTable: {
          nameItemState: 'itemMipresReportesEntrega',
          route: 'reporteentregas',
          optionsPerPage: [
            {
              text: 50,
              value: 50
            },
            {
              text: 100,
              value: 100
            },
            {
              text: 200,
              value: 200
            }
          ],
          makeHeaders: [
            {
              text: 'Prescripción',
              align: 'left',
              sortable: true,
              value: 'NoPrescripcion',
              classData: '',
              component: {
                component: {
                  template:
                    `
                        <div>
                          <v-tooltip v-if="value.tutela" top>
                            <v-chip small color="grey lighten-2" text-color="black" slot="activator" class="ma-0">
                              <v-avatar color="danger" size="12" class="darken-2" style="color: white !important;">T</v-avatar>
                              {{value.NoPrescripcion}}
                            </v-chip>
                            <span>Ordenado por tutela</span>
                          </v-tooltip>
                          <span v-else>{{value.NoPrescripcion}}</span>
                        </div>
                      `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Direccionamiento',
              align: 'left',
              sortable: true,
              value: 'IdDireccionamiento',
              classData: '',
              component: {
                component: {
                  template:
                    `
                        <div>
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title class="body-1">{{value.direccionamiento ? value.direccionamiento.IdDireccionamiento : ''}}</v-list-tile-title>
<!--                              <v-list-tile-sub-title class="caption">Cantidad: {{value.CantTotEntregada}}</v-list-tile-sub-title>-->
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Fechas',
              align: 'left',
              sortable: false,
              value: 'FecMaxEnt',
              classData: '',
              component: {
                component: {
                  template:
                    `
                        <div>
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title class="body-1">Reporte: {{value.FecRepEntrega ?  moment(value.FecRepEntrega).format('YYYY-MM-DD') : ''}}</v-list-tile-title>
                              <v-list-tile-sub-title class="caption">Entrega: {{value.FecEntrega ?  moment(value.FecEntrega).format('YYYY-MM-DD') : ''}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Paciente',
              align: 'left',
              sortable: false,
              value: 'NoIDPaciente',
              classData: '',
              component: {
                component: {
                  template:
                      `
                        <div>
                          <v-list-tile class="content-v-list-tile-p0" v-if="value.direccionamiento && value.direccionamiento.afiliado">
                            <v-list-tile-content>
                              <v-list-tile-title class="body-1">{{value.direccionamiento.afiliado.nombre_completo}}</v-list-tile-title>
                              <v-list-tile-sub-title class="caption">{{value.direccionamiento.afiliado.identificacion_completa}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                          <span v-else>{{value.TipoIDPaciente}}{{value.NoIDPaciente}}</span>
                        </div>
                      `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Proveedor',
              align: 'left',
              sortable: false,
              value: 'NoIDProv',
              classData: '',
              component: {
                component: {
                  template:
                    `
                        <div>
                          <v-list-tile class="content-v-list-tile-p0" v-if="value.direccionamiento && value.direccionamiento.entidad">
                            <v-list-tile-content>
                              <v-list-tile-title class="body-1">{{value.direccionamiento.entidad.nombre}}</v-list-tile-title>
                              <v-list-tile-sub-title class="caption">{{value.direccionamiento.entidad.cod_habilitacion}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                          <span v-else></span>
                        </div>
                      `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Tecnología',
              tooltip: 'Tecnología entregada',
              align: 'left',
              sortable: false,
              value: 'CodSerTecAEntregar',
              component: {
                component: {
                  template:
                    `
                        <div>
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title class="body-1">{{value.TipoTec}} {{value.CodTecEntregado}}</v-list-tile-title>
                              <v-list-tile-sub-title class="caption">Cantidad: {{value.CantTotEntregada}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Valor',
              tooltip: 'Valor entregado',
              align: 'right',
              sortable: false,
              value: 'ValorEntregado',
              classData: 'text-xs-right',
              component: {
                component: {
                  template:
                    `
                        <span>{{currency(value.ValorEntregado)}}</span>
                      `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'text-xs-center',
              singlesActions: true,
              width: '120px'
            }
          ],
          filters: [
            {
              label: 'Fecha Prescripción',
              type: 'v-range',
              items: {
                menuDate: false,
                type: 'date',
                range: true,
                itemMin: {
                  label: 'Inicial',
                  vModel: null,
                  clearable: true,
                  value: 'FPrescripcion'
                },
                itemMax: {
                  menuDate: false,
                  label: 'Final',
                  vModel: null,
                  clearable: true,
                  value: 'FPrescripcion'
                }
              }
            }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('reportesentrega')
      }
    },
    methods: {
      downloadSuccess () {
        this.$refs.tablaReportesEntrega.reloadPage()
      },
      verNotificaciones (item) {
        this.registro.item = item
        this.registro.show = true
      },
      resetOptions (item) {
        item.options = []
        // if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'notificaciones', icon: 'phone_in_talk', tooltip: 'Notificaciones', color: 'success'})
        return item
      }
    }
  }
</script>

<style>
</style>
