<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Direccionamientos" subtitle=""/>
    <data-table
      ref="tablaDireccionamientos"
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @notificaciones="item => verNotificaciones(item)"
    />
    <registro-notificaciones
      :item="registro.item"
      v-model="registro.show"
    ></registro-notificaciones>
  </v-card>
</template>
<script>
  // import store from '@/store/complementos/index'
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'Direccionamientos',
    components: {
      DataTable,
      RegistroNotificaciones: () => import('@/components/mipres/direccionamientos/RegistroNotificaciones'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        registro: {
          item: null,
          show: false
        },
        dataTable: {
          nameItemState: 'itemMipresPrescripciones',
          route: 'direccionamientos',
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
              classData: ''
            },
            {
              text: 'Fecha Max. Entrega',
              align: 'left',
              sortable: false,
              value: 'FecMaxEnt',
              classData: ''
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
                      `<div>
                          <mini-card-detail
                            :data="(value.prescripcion && value.prescripcion.afiliado) || (value.tutela && value.tutela.afiliado)
                            ? (!(value.tutela && value.tutela.afiliado) ? value.prescripcion.afiliado.mini_afiliado : value.tutela.afiliado.mini_afiliado)
                            : {identificacion_completa: (value.TipoIDPaciente + ' ' + value.NoIDPaciente), nombre_completo: 'Paciente no registrado', id: null, emoticon: '⚠️'}"/>
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
                          <v-list-tile class="content-v-list-tile-p0" v-if="value.entidad">
                            <v-list-tile-content>
                              <v-list-tile-title class="body-1">{{value.entidad.nombre}}</v-list-tile-title>
                              <v-list-tile-sub-title class="caption">{{value.entidad.cod_habilitacion}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                          <span v-else>{{value.TipoIDProv}}{{value.NoIDProv}}</span>
                        </div>
                      `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Tecnología',
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
                              <v-list-tile-title class="body-1">{{value.TipoTec}} {{value.CodSerTecAEntregar}}</v-list-tile-title>
                              <v-list-tile-sub-title class="caption">Cantidad: {{value.CantTotAEntregar}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
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
      complementos () {
        // return store.getters.complementostablaDireccionamientos
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('direccionamientos')
      }
    },
    methods: {
      verNotificaciones (item) {
        this.registro.item = item
        this.registro.show = true
      },
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'notificaciones', icon: 'phone_in_talk', tooltip: 'Notificaciones', color: 'success'})
        return item
      }
    }
  }
</script>

<style>
</style>
