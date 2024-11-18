<template>
  <v-card>
    <v-dialog v-model="diOpen" persistent width="600px">
      <v-card>
        <toolbar-list :title="estado === 'Radicado' ? 'Asignar Auditor' : 'Auditores'"/>
        <v-form data-vv-scope="formAsignarAuditor">
          <v-container fluid grid-list-xl>
            <v-layout row wrap>
              <v-flex xs12 v-if="estado === 'Radicado' && radicado.auditores_asignados.length !== 2">
                <postulador-v2
                  no-data="Busqueda por identificación, nombre o código de auditor"
                  hint="usuario.identification"
                  item-text="usuario.name"
                  data-title="identificacion_completa"
                  data-subtitle="usuario.name"
                  :label="radicado.auditores_asignados.filter(x => x.auditor.tecnico === 0).length === 1 ? 'Técnico' : 'Auditor'"
                  entidad="ac_auditores"
                  preicon="person_outline"
                  :route-params="`estado=1&auditor_concurrente=0${radicado.auditores_asignados.filter(x => x.auditor.tecnico === 0).length === 1 ? '&tecnico=1' : '&tecnico=0'}`"
                  v-model="auditorTemporal"
                  :name="radicado.auditores_asignados.length ? 'técnico' : 'auditor'"
                  rules="auditorExiste"
                  v-validate="'auditorExiste'"
                  :error-messages="errors.collect(radicado.auditores_asignados.filter(x => x.auditor.tecnico === 0).length === 1 ? 'técnico' : 'auditor')"
                  no-btn-create
                  :min-characters-search="3"
                  clearable
                />
<!--                <v-layout row wrap>-->
<!--                  <v-flex xs10 sm10>-->
<!--                    -->
<!--                  </v-flex>-->
<!--                  <v-flex xs2 sm2>-->
<!--                    <v-tooltip top>-->
<!--                      <v-btn small-->
<!--                             fab-->
<!--                             slot="activator"-->
<!--                             dark color="pink"-->
<!--                             @click="addAuditor(radicado_asignado)">-->
<!--                        <v-icon dark>add</v-icon>-->
<!--                      </v-btn>-->
<!--                      <span v-text="'Agregar Auditor'"></span>-->
<!--                    </v-tooltip>-->
<!--                  </v-flex>-->
<!--                </v-layout>-->
              </v-flex>
              <v-flex xs12 class="pa-1">
                <template>
                  <v-slide-y-transition group>
                    <v-data-table
                      v-if="radicado.auditores_asignados.length"
                      :items="radicado.auditores_asignados"
                      class="elevation-1"
                      :headers="headersAuditor"
                      hide-actions :key="'table'">
                      <template slot="items" slot-scope="props">
                        <tr :key="'tag-' + (!props.item.id ? (props.item.id = props.index) : props.index)">
                          <td class="text-xs-left">{{ props.item.auditor ?  props.item.auditor.identificacion_completa : null }}</td>
                          <td class="text-xs-left">{{ props.item.auditor ?  props.item.auditor.usuario.name : null }}</td>
                          <td class="text-xs-left">{{ props.item.auditor ?  props.item.auditor.tipo + (props.item.auditor.tecnico === 1 ? ' Técnico' : '') : null }}</td>
                          <td class="text-xs-center">
                            <v-tooltip top>
                              <v-btn icon slot="activator"
                                     class="mx-0" :disabled="estado !== 'Radicado' ? true : (false)"
                                     fab small @click="eliminar(props.index)">
                                <v-icon color="accent">delete</v-icon>
                              </v-btn>
                              <span v-text="estado === 'En Proceso' ? 'En proceso de auditoría' : 'Eliminar'"></span>
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
          <v-btn flat v-if="estado === 'Radicado'" color="primary" @click="cambioEstado()" v-text="'Primera Asiganción'"></v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Cuenta Médica" title="Cuentas Médicas" subtitle="Radicadas"/>
    <data-table
      v-model="dataTableCuentaMedica"
      @resetOption="item => resetOptions(item)"
      @anular="item => anular(item)"
      @imprimir="item => imprimir(item, 1)"
      @impInforme="item => imprimir(item, 2)"
      @imprimirGlosa="item => imprimir(item, 3)"
      @ver="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: `Cuenta Médica N° ${item.consecutivo_radicado}`, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
    />
  </v-card>
<!--  @asignar="item => openModal(item)"-->
  <!--$store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })-->
</template>

<script>
  // import ToolbarList from '@/components/general/ToolbarList'
  import { Validator } from 'vee-validate'
  import {CUENTA_RADICADA_RELOAD_ITEM} from '../../../store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {mapState} from 'vuex'
  export default {
    name: 'CmRadicada',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      DataTable: () => import('@/components/general/DataTable')
    },
    data () {
      return {
        dataTableCuentaMedica: {
          nameItemState: 'itemCuentaradicada',
          route: 'cm_radicadas',
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
              text: 'Consecutivo',
              align: 'left',
              sortable: false,
              value: 'consecutivo_radicado'
            },
            {
              text: 'Número Radicado',
              align: 'left',
              sortable: false,
              value: 'radicado_entidad'
            },
            {
              text: 'IPS',
              align: 'left',
              sortable: false,
              value: 'entidad.nombre'
            },
            {
              text: 'Fecha de Radicado',
              align: 'left',
              sortable: false,
              value: 'fecha_radicado'
            },
            {
              text: 'Usuario',
              align: 'left',
              sortable: false,
              value: 'gs_usuario_id',
              component: {
                component: {
                  template:
                          `
                      <div>
                        <v-list-tile class="content-v-list-tile-p0">
                          <v-list-tile-content>
                            <v-list-tile-title class="body-1">{{value.usuario ? value.usuario.name : ''}}</v-list-tile-title>
                              <v-list-tile-sub-title class="caption">{{value.usuario ? value.usuario.email : ''}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      </div>
                    `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Estado',
              align: 'left',
              sortable: true,
              value: 'estado'
            },
            {
              text: 'Avance Auditoria',
              align: 'center',
              sortable: false,
              value: 'avance_proceso',
              classData: 'text-xs-center',
              width: '100px',
              component: {
                component: {
                  template:
                    `<div>
                        <v-tooltip v-if="value.estado === 'En Auditoria'" top>
                          <div slot="activator" class="elevation-2">
                            <v-progress-linear class="cursor-pointer" :value="value.avance_proceso"></v-progress-linear>
                          </div>
                          <span v-text="value.avance_proceso + ' %'"></span>
                        </v-tooltip>
                        <div v-else>
                          <v-chip color="red" text-color="white">Por asignar</v-chip>
                        </div>
                    </div>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'justify-center layout px-0'
            }
          ]
        },
        diOpen: false,
        radicado: null,
        idRadicado: null,
        estado: null,
        auditorTemporal: null,
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
    created () {
      this.formRadicado()
    },
    beforeMount () {
      Validator.extend('auditorExiste', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = (this.radicado.auditores_asignados.find(x => x.ac_auditor_id === value.id))
              ? {valido: false, mensaje: 'El auditor seleccionado ya se encuentra en el listado de auditores.'}
              : {valido: true, mensaje: null}
            return resolve({valid: response.valido, data: {message: response.mensaje}})
          }
        }),
        getMessage: (field, params, data) => data.message
      })
    },
    watch: {
      async 'auditorTemporal' (val) {
        if (val) {
          if (this.radicado.auditores_asignados.length && this.radicado.auditores_asignados.find(x => x.ac_auditor_id === val.id)) return
          let temp = JSON.parse(JSON.stringify(this.radicado.auditores_asignados))
          this.radicado.auditores_asignados.push({
            id: null,
            ac_auditor_id: val.id,
            tipo: 'Primera vez',
            auditor: val
          })
          this.$nextTick(() => {
            this.auditorTemporal = null
            this.$validator.reset()
          })
          if (!(await this.asignar())) this.radicado.auditores_asignados = temp
        }
      },
      'diOpen' (val) {
        if (!val) {
          this.close()
        }
      },
      'idRadicado' (val) {
        if (val !== null) this.radicado.id = val
      }
    },
    computed: {
      ...mapState({
        currentUser: state => state.user.profile
      }),
      infoComponent () {
        return this.$store.getters.getInfoComponent('cmradicadas')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar && item.estado !== 'Anulado' && item.estado !== 'En Proceso') item.options.push({event: 'anular', icon: 'not_interested', tooltip: 'Anular', color: 'red'})
        // if (this.infoComponent && this.infoComponent.permisos.agregar && item.estado !== 'Anulado') item.options.push({event: 'asignar', icon: 'person_add', tooltip: 'Asignar Auditores'})
        if (this.infoComponent && this.infoComponent.permisos.imprimir) item.options.push({event: 'imprimirGlosa', icon: 'far fa-file-excel', tooltip: 'Informe de Glosas', color: 'teal'})
        if (this.infoComponent && this.infoComponent.permisos.imprimir) item.options.push({event: 'impInforme', icon: 'far fa-file-pdf', tooltip: 'Informe Auditoría Facturas Radicadas', color: 'red'})
        if (this.infoComponent && this.infoComponent.permisos.agregar && item.estado === '') item.options.push({event: 'ver', icon: 'remove_red_eye', tooltip: 'Ver cuenta médica'})
        if (this.infoComponent && this.infoComponent.permisos.imprimir) item.options.push({event: 'imprimir', icon: 'far fa-file-pdf', tooltip: 'Radicado de Cuenta Médica', color: 'black'})
        return item
      },
      imprimir (item, numer) {
        let id = item.id
        this.axios.get(`firmar-ruta?nombre_ruta=${numer === 1 ? 'documeto_radicado' : (numer === 2 ? `informe_sosalud&user=${this.currentUser.id}` : 'reporte_glosas')}&id=` + id)
          .then((res) => {
            let url = res.data
            if (numer === 1 || numer === 2) {
              window.open(url)
            } else {
              window.fileDownload(url)
            }
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' Error al Imprimir.', error: e})
          })
      },
      anular (item) {
        let cmradicada = JSON.parse(JSON.stringify(item))
        cmradicada.estado = 'Anulado'
        delete cmradicada.options
        delete cmradicada.facturas
        delete cmradicada.show
        this.axios.put('cm_radicadas/' + cmradicada.id, cmradicada)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'Se anuló la radicación de la cuenta.', color: 'success'})
            // this.$store.commit(CUENTA_RADICADA_RELOAD_ITEM, {item: res.data.data, estado: 'editar', key: this.parametros.key})
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al tratar de anular la radicación de la cuenta médica.', color: 'danger'})
          })
      },
      openModal (item) {
        this.idRadicado = item.id
        this.estado = item.estado
        this.radicado.auditores_asignados = item.auditores_asignados.length ? item.auditores_asignados : []
        this.diOpen = true
      },
      close () {
        this.formRadicado()
        this.$validator.reset()
        // this.$store.commit(CUENTA_RADICADA_RELOAD_ITEM, {item: this.radicado, estado: 'reload', key: null})
        this.idRadicado = null
      },
      async eliminar (index) {
        let temp = JSON.parse(JSON.stringify(this.radicado.auditores_asignados))
        this.radicado.auditores_asignados.splice(index, 1)
        if (!(await this.asignar())) this.radicado.auditores_asignados = temp
      },
      formRadicado () {
        this.radicado = {
          id: null,
          auditores_asignados: []
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      asignar () {
        if (this.radicado.auditores_asignados !== []) {
          return new Promise(resolve => {
            this.axios.post(`asignacion_auditores/${this.radicado.id}`, this.radicado)
              .then(() => {
                // this.$store.commit(SNACK_SHOW, {msg: response.data.msg, color: 'success'})
                // this.$store.commit(CUENTA_RADICADA_RELOAD_ITEM, {item: response.data.data, estado: 'reload', key: null})
                // this.diOpen = false
                resolve(true)
              })
              .catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Error al registrar los cambios.', color: 'danger'})
                resolve(false)
              })
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Debe asignar un auditor.', color: 'danger'})
        }
      },
      cambioEstado () {
        if (this.radicado.auditores_asignados.length) {
          this.axios.post('primeras_asignaciones/' + this.idRadicado, {estado: 'En Proceso'})
            .then(response => {
              this.$store.commit(CUENTA_RADICADA_RELOAD_ITEM, {item: response.data.data, estado: 'reload', key: null})
              this.diOpen = false
            }).catch(() => {
              this.$store.commit(SNACK_SHOW, {msg: 'Error al registrar los cambios.', color: 'danger'})
            })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'No se han asignado auditores a la cuenta.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>
