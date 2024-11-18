<template>
    <div>
      <v-dialog v-model="diOpenAntesDeAsignar" persistent width="400">
        <v-card>
          <v-card-title>
            <span class="title" v-text="'¿Está seguro de asignar los auditores?'"></span>
          </v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn  color="grey" dark  @click="diOpenAntesDeAsignar = false" v-text="'No'"></v-btn>
            <v-btn  color="primary" @click="asignar" v-text="'Sí'"></v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="diOpen" persistent width="600px">
        <v-card>
          <toolbar-list title="Asignar Auditores"/>
          <v-form data-vv-scope="formAsignarAuditor">
            <v-container fluid grid-list-xl>
              <v-layout row wrap>
                <v-flex xs12>
                  <postulador-v2
                    no-data="Busqueda por identificación, nombre o código de auditor"
                    hint="usuario.identification"
                    item-text="usuario.name"
                    data-title="identificacion_completa"
                    data-subtitle="usuario.name"
                    label="Auditor"
                    entidad="ac_auditores"
                    preicon="person_outline"
                    route-params="estado=1&auditor_concurrente=1"
                    v-model="auditorTemporal"
                    name="auditor"
                    rules="noAuditor"
                    v-validate="'noAuditor'"
                    :error-messages="errors.collect('auditor')"
                    no-btn-create
                    :min-characters-search="3"
                    clearable
                  />
                </v-flex>
                <v-flex xs12 class="pa-1">
                  <template>
                    <v-slide-y-transition group>
                      <v-data-table
                        v-if="auditores !== []"
                        :items="auditores"
                        class="elevation-1"
                        :headers="headersAuditor"
                        hide-actions :key="'table'">
                        <template slot="items" slot-scope="props">
                          <tr :key="'tag-' + (!props.item.id ? (props.item.id = props.index) : props.index)">
                            <td class="text-xs-left">{{ props.item.usuario ?  props.item.identificacion_completa : null }}</td>
                            <td class="text-xs-left">{{ props.item.usuario ?  props.item.usuario.name : null }}</td>
                            <td class="text-xs-left">{{ props.item.usuario ?  props.item.tipo : null }}</td>
                            <td class="text-xs-center">
                              <v-tooltip top>
                                <v-btn icon slot="activator"
                                       class="mx-0"
                                       fab small @click="eliminar(props)">
                                  <v-icon color="accent">delete</v-icon>
                                </v-btn>
                                <span v-text="'Eliminar'"></span>
                              </v-tooltip>
                            </td>
                          </tr>
                        </template>
                        <template slot="no-data" class="error">
                          <div class="pa-1 pl-2 text--white text-xs-center">
                            Lo sentimos, no existen registros. <v-icon>sentiment_very_dissatisfied</v-icon>
                          </div>
                        </template>
                      </v-data-table>
                    </v-slide-y-transition>

                  </template>
                </v-flex>
              </v-layout>
            </v-container>
          </v-form>
          <v-card-actions class="grey lighten-3">
            <v-spacer></v-spacer>
            <v-btn flat color="grey darken-1"  @click="diOpen = false" v-text="'Cerrar'"></v-btn>
            <v-btn flat color="primary" @click.prevent="openDialogTwo" v-text="'Asignar'"></v-btn>
<!--            <v-btn flat color="primary" @click="asignar" v-text="'Asignar'"></v-btn>-->
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-card>
        <toolbar-list v-if="dialogOpenOfMenu" :info="infoComponent" title="Pacientes Hospitalarios" subtitle="Gestión"/>
        <template v-else>
          <v-card key="cardtop" class="elevation-1">
            <v-container
              class="pa-2"
              fluid
              grid-list-sm
            >
              <v-layout align-center justify-space-between row wrap fill-height>
                <v-flex md4 sm6 xs12 order-xs2 order-sm2 order-md1>
                  <v-toolbar class="toolbar-pa0 elevation-0" style="background: transparent !important;">
                    <v-tooltip top>
                      <v-btn
                        slot="activator"
                        icon
                        large
                      >
                        <v-icon
                          color="teal"
                          large
                          v-text="'fas fa-clipboard-list'"
                        >
                        </v-icon>
                      </v-btn>
                      <span>Listado de Pacientes</span>
                    </v-tooltip>
                    <v-divider vertical></v-divider>
                    <span class="pt-1 pl-2 text-xs-left">
                      <span class="body-2 text-capitalize" v-if="censo !== null">{{censo.ips ? censo.ips.nombre : null}}</span>
                      <p class="mb-0 caption">{{'Sin Asignar'}}</p>
                    </span>
                  </v-toolbar>
                </v-flex>
                <v-flex md4 sm12 xs12 order-xs1 order-sm1 order-md2 class="text-xs-center">
                  <span v-text="'Pacientes Hospitalarios'"></span><br/>
                  <span class="font-weight-bold" v-text="'Listado de pacientes censados'"></span><br/>
                  <span v-if="censo !== null" class="font-weight-bold">Fecha: {{censo.fecha}}</span>
                </v-flex>
                <v-flex md4 sm6 xs12 order-xs3 order-sm3 order-md3 class="text-xs-right">
                  <v-toolbar class="toolbar-pa0 elevation-0" style="background: transparent !important;">
                    <v-toolbar-title v-if="censo !== null" class="body-1 text-xs-right pr-2" style="width: 100% !important;">
                      <span>Total pacientes: {{censo.cantidad_pacientes}}</span><br/>
                      <span class="font-weight-bold">{{ 'Usuario: ' + (censo.usuario ? censo.usuario.name : '')}}</span>
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                  </v-toolbar>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
        </template>
        <data-table
          ref="dataCenso"
          select-all="accent"
          @selecteds="items => itemsSeleccionados = items"
          v-if="dataTablePacientes.route"
          v-model="dataTablePacientes"
          @resetOption="item => resetOptions(item)"
          @asignar="item => openDialogFormAsignacion(item)"
          @linked="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleConcurrencia', titulo: 'Detalle concurrencia', parametros: {entidad: { id: item.cm_concurrencia_id, afiliado: item.afiliado }, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
        >
          <template slot="actions" slot-scope="props">
            <v-tooltip top v-if="itemsSeleccionados.length">
              <v-btn
                slot="activator"
                flat
                dark
                icon
                large
                color="accent darken-2"
                @click="diOpen = !diOpen"
              >
                <v-icon>fab fa-slideshare</v-icon>
              </v-btn>
              <span v-text="'Asignación masiva'"></span>
            </v-tooltip>
            <v-layout row wrap>
                <v-flex xs12 sm6 md6 class="pt-1">
                  <v-autocomplete
                    v-if="censo === null"
                    :items="instituciones"
                    :loading="loadingIps"
                    label="Filtrar por IPS"
                    v-model="ips"
                    prepend-icon="far fa-hospital"
                    item-value="id"
                    item-text="nombre_razon_social"
                    flat
                    return-object
                    clearable
                  >
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.nombre_razon_social"/>
                          <v-list-tile-sub-title v-html="data.item.cod_habilitacion"/>
                        </v-list-tile-content>
                      </template>
                    </template>
                    <template slot="no-data">
                      <v-alert  v-if="!loadingIps" class="pt-1 pb-1 mb-0 mt-0" :value="true" color="error" icon="warning">
                        Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                      </v-alert>
                      <v-alert v-else :value="true" color="info" icon="info">
                        Se estan cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                      </v-alert>
                    </template>
                  </v-autocomplete>
                </v-flex>
            </v-layout>
          </template>
        </data-table>
      </v-card>
    </div>
</template>

<script>
  import { Validator } from 'vee-validate'
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {PACIENTES_CENSO_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'AuditoriaPacientes',
    props: ['parametros'],
    components: {
      Loading,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable')
    },
    data () {
      return {
        diOpen: false,
        pacientes: null,
        censo: null,
        estado: null,
        asignacion: null,
        loadingIps: false,
        auditorTemporal: null,
        ips: null,
        instituciones: [],
        auditores: [],
        itemsSeleccionados: [],
        dialogOpenOfMenu: false,
        diOpenAntesDeAsignar: false,
        ruta: null,
        tempIdPacienteHospitalario: null,
        dataTablePacientes: {
          nameItemState: 'itemPacientesCenso',
          route: null,
          simplePaginate: true,
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
              selectable: true
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
                      : {identificacion_completa: value.identificacion, nombre_completo: value.nombre_paciente, id: null, emoticon: '⚠️'}"></mini-card-detail>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'ESTANCIA',
              align: 'center',
              sortable: false,
              value: 'tiempo_estancia',
              component: {
                component: {
                  template: `
                    <div>
                       <v-chip :color="value.color_semaforo" text-color="white" small>
                          <v-avatar :class="value.color_semaforo + 'darken-4'" medium>{{value.tiempo_estancia}}</v-avatar>
                          <span v-text="'Día' + (value.tiempo_estancia !== 1 ? 's' : '')"></span>
                      </v-chip>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'FECHA INGRESO',
              align: 'left',
              sortable: false,
              value: 'fecha_ingreso',
              component: {
                component: {
                  template: `
                    <div>
                     <span v-text="moment(value.fecha_ingreso).format('YYYY/MM/DD')"></span>
                    </div>
                  `,
                  props: ['value']
                }
              },
              classData: 'text-xs-center'
            },
            {
              text: 'CAMA/SERVICIO',
              align: 'left',
              sortable: false,
              value: 'cama_servicio'
            },
            {
              text: 'DIAGNOSTICO',
              align: 'left',
              sortable: false,
              value: 'cod_dx',
              component: {
                component: {
                  template: `
                    <div>
                     <span v-text="value.cod_dx !== null ? (value.cod_dx + ' - ' + value.nombre_diagnostico) : 'No Registra'"></span>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'CÓDIGO INGRESO',
              align: 'left',
              sortable: false,
              value: 'consecutivo_entidad'
            },
            {
              text: 'ESTADO',
              align: 'left',
              sortable: true,
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
          ],
          filters: [
            {
              label: 'Estado',
              type: 'v-autocomplete',
              items: () => [
                {text: 'Sin Asignar', value: 'Sin Asignar'},
                {text: 'Asignado', value: 'Asignado'},
                {text: 'Cerrado', value: 'Cerrado'}
              ],
              vModel: [],
              multiple: true,
              itemText: 'text',
              itemValue: 'value',
              value: 'estado',
              clearable: true
            }
          ]
        },
        headersAuditor: [
          {
            text: 'Identificación',
            align: 'left',
            sortable: false,
            value: 'auditor.usuario.identification'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'auditor.usuario.name'
          },
          {
            text: 'Tipo Auditor',
            align: 'left',
            sortable: false,
            value: 'auditor'
          },
          {
            text: 'Eliminar',
            align: 'center',
            sortable: false,
            value: ''
          }
        ]
      }
    },
    watch: {
      'auditorTemporal' (val) {
        if (val) {
          this.$validator.validateAll('formAsignarAuditor').then(resolve => {
            if (resolve) {
              if (this.auditores.length && this.auditores.find(x => x.id === val.id)) return
              let temp = JSON.parse(JSON.stringify(val))
              this.asignacion.auditores.push(val.id)
              this.auditores.push(temp)
              this.$nextTick(() => {
                this.auditorTemporal = null
                this.$validator.reset()
              })
            }
          })
          // if (!(await this.asignar())) this.radicado.auditores_asignados = temp
        }
      },
      'itemsSeleccionados' (vals) {
        if (vals) {
          vals.forEach(x => {
            if (x.as_afiliado_id) {
              this.asignacion.pacientes.push(x.id)
            }
            if (x.concurrencia !== null) {
              this.auditores = JSON.parse(JSON.stringify(x.concurrencia.auditores))
            }
          })
        }
      },
      'diOpen' (val) {
        if (!val) {
          this.close()
        }
      },
      'ips' (val) {
        if (val) {
          this.ruta = `cm_pacientes_hospitalarios?censo:ips_id=${val.id}`
        } else {
          this.ruta = `cm_pacientes_hospitalarios`
        }
        // this.ruta = `cm_pacientes_hospitalarios${val ? '?censo:ips_id=' + val.id : ''}`
      },
      'ruta' (val) {
        if (val) {
          this.dataTablePacientes.route = val
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('auPacientes')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar && (item.as_afiliado_id !== null)) item.options.push({event: 'asignar', icon: 'fab fa-slideshare', tooltip: 'Asignar Auditores', color: 'red', size: '20px'})
        if (this.infoComponent && this.infoComponent.permisos.agregar && (item.as_afiliado_id !== null) && (item.estado === 'Asignado')) item.options.push({event: 'linked', icon: 'fas fa-link', tooltip: 'Ir a la concurrencia', color: 'teal', size: '20px'})
        return item
      },
      openDialogFormAsignacion (item) {
        if (item.concurrencia !== null) {
          this.auditores = item.concurrencia.auditores
        }
        if (item.as_afiliado_id) {
          this.asignacion.pacientes.push(item.id)
        }
        this.tempIdPacienteHospitalario = item.id
        this.diOpen = !this.diOpen
      },
      close () {
        this.auditorTemporal = null
        this.$refs.dataCenso.deselectAll()
        this.formData()
        this.$validator.reset()
        this.auditores = []
        this.itemsSeleccionados = []
        this.tempIdPacienteHospitalario = null
      },
      formData () {
        this.asignacion = {
          pacientes: [],
          auditores: []
        }
      },
      openDialogTwo () {
        if (this.asignacion.auditores.length) {
          this.diOpenAntesDeAsignar = !this.diOpenAntesDeAsignar
        } else {
          this.$store.commit(SNACK_SHOW, {msg: `Debe asignar almenos un auditor ${this.auditores !== [] ? (this.auditores.length > 1 ? 'diferentes los que se encuentran' : 'diferente al que esta') : ''}.`, color: 'danger'})
        }
      },
      asignar () {
        this.axios.post('asignarConcurrencia', this.asignacion)
          .then(response => {
            this.$store.commit(SNACK_SHOW, {msg: `Se ${this.asignacion.pacientes.length > 1 ? 'asignaron' : 'asignó'} correctamente ${this.asignacion.pacientes.length > 1 ? 'los pacientes' : 'el paciente'} al auditor.`, color: 'success'})
            this.$store.commit(PACIENTES_CENSO_RELOAD_ITEM, {item: response.data.data, estado: 'reload', key: null})
            this.diOpenAntesDeAsignar = false
            this.diOpen = false
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al asignar audiroes. ', error: e})
            this.$store.commit(SNACK_SHOW, {msg: e.response.data.message, color: 'danger'})
          })
      },
      async eliminar (props) {
        if (typeof props.item.pivot === 'undefined' || props.item.pivot === null) {
          this.auditores.splice(props.index, 1)
          this.$store.commit(SNACK_SHOW, {msg: 'El auditor se ha eliminado correctamente.', color: 'success'})
        } else {
          this.axios.delete(`eliminar_concurrencia_asignada/${props.item.id}/${props.item.pivot.concurrencia_id}/${this.tempIdPacienteHospitalario}`)
            .then(response => {
              this.$store.commit(SNACK_SHOW, {msg: 'El auditor se ha eliminado correctamente.', color: 'success'})
              this.auditores.splice(props.index, 1)
              this.$store.commit(PACIENTES_CENSO_RELOAD_ITEM, {item: response.data.data, estado: 'reload', key: null})
            }).catch(e => {
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' ' + e.response.data.msg, error: e})
            })
        }
      }
    },
    created () {
      this.formData()
      if (this.parametros) {
        // this.dataTablePacientes.route = `cm_censos/${this.parametros.entidad !== null ? this.parametros.entidad.id : null}/pacientes`
        this.ruta = `cm_censos/${this.parametros.entidad !== null ? this.parametros.entidad.id : null}/pacientes`
        this.censo = JSON.parse(JSON.stringify(this.parametros.entidad !== null ? this.parametros.entidad : null))
        this.dialogOpenOfMenu = false
      } else {
        this.ruta = 'cm_pacientes_hospitalarios'
        this.dialogOpenOfMenu = true
        this.axios.get('institucionesConPacientes').then(res => {
          this.instituciones = JSON.parse(JSON.stringify(res.data.data))
        }).catch(() => {
          this.$store.commit(SNACK_SHOW, {msg: 'Error al cargar las instituciones.', color: 'danger'})
        })
      }
    },
    mounted () {
      Validator.extend('noAuditor', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = (value.auditor_concurrente === 0)
              ? {valido: false, mensaje: 'El auditor seleccionado no esta marcado como auditor concurrente.'}
              : (this.auditores.find(x => x.id === value.id))
                ? {valido: false, mensaje: 'El auditor seleccionado ya se encuentra asigando auditores.'}
                : {valido: true, mensaje: false}
            return resolve({valid: response.valido, data: {message: response.mensaje}})
          }
        }),
        getMessage: (field, params, data) => data.message
      })
    }
  }
</script>

<style scoped>

</style>
