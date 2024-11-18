<template>
  <data-table
    ref="tablaPrescripcionesAfiliado"
    v-model="dataTable"
    @resetOption="item => resetOptions(item)"
    @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detallePrescripcion', titulo: 'Detalle prescripción', parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
    @imprimir="item => imprimir(item)"
    @estudiar="item => estudiar(item)"
  />
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'DetalleGeneralPrescripciones',
    props: {
      afiliado: {
        type: Object,
        default: null
      },
      afiliadoId: {
        type: Number,
        default: 0
      }
    },
    components: {
      DataTable
    },
    data () {
      return {
        dataTable: {
          route: null,
          makeHeaders: [
            {
              text: 'Número',
              align: 'left',
              sortable: true,
              value: 'NoPrescripcion',
              classData: ''
            },
            {
              text: 'Fecha',
              align: 'left',
              sortable: true,
              value: 'FPrescripcion',
              classData: ''
            },
            {
              text: 'Código IPS',
              align: 'left',
              sortable: false,
              value: 'CodHabIPS',
              classData: ''
            },
            {
              text: 'Nombre IPS',
              align: 'left',
              sortable: false,
              value: 'ips',
              classData: ''
            },
            {
              text: 'Registros',
              align: 'center',
              sortable: false,
              value: 'id',
              classData: 'text-xs-center',
              width: '150px',
              component: {
                component: {
                  template:
                    `<div>
                        <v-tooltip top class="mr-2" v-if="value.medicamentos">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.medicamentos}}</span>
                            <v-avatar color="indigo" size="30">
                              <span class="white--text subheading">M</span>
                            </v-avatar>
                          </v-badge>
                          <span>Medicamentos</span>
                        </v-tooltip>
                        <v-tooltip top class="mr-2" v-if="value.procedimientos">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.procedimientos}}</span>
                            <v-avatar color="green" size="30">
                              <span class="white--text subheading">P</span>
                            </v-avatar>
                          </v-badge>
                          <span>Procedimientos</span>
                        </v-tooltip>
                        <v-tooltip top class="mr-2" v-if="value.dispositivos">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.dispositivos}}</span>
                            <v-avatar color="brown" size="30">
                              <span class="white--text subheading">D</span>
                            </v-avatar>
                          </v-badge>
                          <span>Dispositivos</span>
                        </v-tooltip>
                        <v-tooltip top class="mr-2" v-if="value.productosnutricionales">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.productosnutricionales}}</span>
                            <v-avatar color="purple" size="30">
                              <span class="white--text subheading">N</span>
                            </v-avatar>
                          </v-badge>
                          <span>Productos Nutricionales</span>
                        </v-tooltip>
                        <v-tooltip top class="mr-2" v-if="value.serviciosComplementarios">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.serviciosComplementarios}}</span>
                            <v-avatar color="teal" size="30">
                              <span class="white--text subheading">C</span>
                            </v-avatar>
                          </v-badge>
                          <span>Servicios Complementarios</span>
                        </v-tooltip>
                    </div>`,
                  props: ['value']
                }
              }
            },
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
              classData: 'text-xs-center',
              singlesActions: true
            }
          ]
        }
      }
    },
    created () {
      this.dataTable.route = `prescripciones?as_afiliado_id=${this.afiliadoId}`
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('prescripciones')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'detalle', icon: 'find_in_page', tooltip: 'Ver detalle', color: 'primary'})
        return item
      },
      getData () {
        this.$refs.tablaPrescripcionesAfiliado.reloadPage()
      }
    }
  }
</script>

<style scoped>

</style>
