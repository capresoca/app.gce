<template>
  <v-card>
    <v-toolbar dense>
      <template v-if="infoComponent">
        <v-icon v-if="infoComponent">{{infoComponent ? infoComponent.icono : ''}}</v-icon>
        <v-toolbar-title>Autorizaciones</v-toolbar-title>
        <small class="mt-2 ml-1"> Procesos de autorización</small>
        <v-spacer></v-spacer>
        <dialog-informe-autorizaciones-usuario></dialog-informe-autorizaciones-usuario>
        <v-btn
          small
          color="accent"
          @click="$store.commit('NAV_ADD_ITEM',
                { ruta: infoComponent.ruta_registro,
                  titulo: infoComponent.titulo_registro,
                  parametros: {
                    entidad: null,
                    tabOrigin: $store.state.nav.currentItem.split('tab-')[1]
                  }
                })"
          v-if="infoComponent.permisos.agregar">
          <v-icon>add</v-icon>
          Crear solicitud de autorización
        </v-btn>
      </template>
    </v-toolbar>
    <v-tabs
      grow
      fixed-tabs
      color="cyan"
      dark
    >
      <v-tabs-slider color="accent"></v-tabs-slider>
      <v-tab href="#tab-1">
        <v-icon left>fas fa-user-tie</v-icon>
        Funcionarios
      </v-tab>
      <v-tab href="#tab-2">
        <v-icon left>fas fa-clinic-medical</v-icon>
        Prestadores
      </v-tab>
      <v-tab-item
        value="tab-1"
      >
        <v-card flat>
          <data-table-v2
            ref="tableAutorizacionesFuncionarios"
            v-model="dataTable"
            @resetOption="item => resetOptions(item)"
            @imprimir="item => imprimir(item)"
            @anularAutorizacion="item => registroAnular(item)"
            @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleAutorizacion', titulo: 'Detalle autorización', parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
            @autorizar="item => $store.commit('NAV_ADD_ITEM', { ruta: 'registroAutorizacion', titulo: 'Registro de autorización', parametros: {entidad: item, envia: 'funcionarios', tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
            @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
            @servicio="item => openDialogServicios(item)"
            @aprobarSolicitud="item => openDialogAprobarSolicitud(item)"
          >
            <template slot="actions" v-if="esJefeAutorizador">
              <v-subheader class="pa-0 ma-0">Solicitudes por Gestionar</v-subheader>
              <v-flex xs1 sm1 md1>
                <v-tooltip center label="" top>
                  <v-switch slot="activator" class="ma-0 pa-0 switch-solicitud" color="accent" v-model="espera_solicitudes" :true-value="1" :false-value="0"></v-switch>
                  <span v-text="espera_solicitudes ? `Activo` : `Inactivo`"></span>
                </v-tooltip>
              </v-flex>
            </template>
          </data-table-v2>
        </v-card>
      </v-tab-item>
      <v-tab-item
        value="tab-2"
      >
        <v-card flat>
          <data-table-v2
            ref="tableAutorizacionesPrestadores"
            v-model="dataTable2"
            @resetOption="item => resetOptions(item)"
            @imprimir="item => imprimir(item)"
            @anularAutorizacion="item => registroAnular(item)"
            @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleAutorizacion', titulo: 'Detalle autorización', parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
            @autorizar="item => $store.commit('NAV_ADD_ITEM', { ruta: 'registroAutorizacion', titulo: 'Registro de autorización', parametros: {entidad: item, envia: 'prestadores', tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
            @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
            @servicio="item => openDialogServicios(item)"
            @aprobarSolicitud="item => openDialogAprobarSolicitud(item)"
          >
            <template slot="actions" v-if="esJefeAutorizador">
              <v-subheader class="pa-0 ma-0">Solicitudes por Gestionar</v-subheader>
              <v-flex xs1 sm1 md1>
                <v-tooltip center label="" top>
                  <v-switch slot="activator" class="ma-0 pa-0 switch-solicitud" color="accent" v-model="espera_solicitudes_dos" :true-value="1" :false-value="0"></v-switch>
                  <span v-text="espera_solicitudes_dos ? `Activo` : `Inactivo`"></span>
                </v-tooltip>
              </v-flex>
<!--              <v-tooltip top v-if="esJefeAutorizador">-->
<!--                <v-subheader class="pa-0 ma-0">Filtro de Solicitudes</v-subheader>-->
<!--                <v-switch class="ma-0 pa-0"  color="accent"-->
<!--                          v-model="espera_solicitudes_dos"  :true-value="1" :false-value="0"></v-switch>-->
<!--                <span v-text="'Filtrar solicitudes por aprobar'"></span>-->
<!--              </v-tooltip>-->
            </template>
          </data-table-v2>
        </v-card>
      </v-tab-item>
    </v-tabs>
      <confirmar :value="dialogA.visible" :loading="dialogA.loading" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)"></confirmar>
    <v-dialog
      v-model="dialogPrint.show"
      width="500"
    >
      <v-card>
        <loading v-model="dialogPrint.loading"></loading>
        <v-toolbar dense>
          <v-icon left>print</v-icon>
          <span>Impresión de la autorización No. <strong>{{dialogPrint.item ? dialogPrint.item.id : ''}}</strong>, por prestador</span>
        </v-toolbar>
        <v-container fluid grid-list-sm>
          <v-layout row wrap>
            <v-flex xs12>
              <v-select
                key="autocompletePrestadorPrint"
                label="Prestador"
                name="prestador"
                item-value="id"
                item-text="nombre"
                v-model="dialogPrint.entidadesSeleccionadas"
                :items="dialogPrint.entidadesDisponibles"
                multiple
                v-validate="'required'"
                :error-messages="errors.collect('prestador')"
                hide-selected
                chips
                deletable-chips
                small-chips
              ></v-select>
            </v-flex>
            <v-flex xs12>
<!--              <v-text-field-->
<!--                placeholder="Recibe"-->
<!--                v-model="dialogPrint.recibido"-->
<!--                name="recibe"-->
<!--                v-validate="'required'"-->
<!--                :error-messages="errors.collect('recibe')"-->
<!--              ></v-text-field>-->
              <v-text-field
                placeholder="Recibe"
                v-model="dialogPrint.recibido"
              ></v-text-field>
            </v-flex>
          </v-layout>
        </v-container>
        <v-divider></v-divider>
        <v-card-actions>
          <v-btn
            flat
            @click="dialogPrint.show = false"
          >
            Cancelar
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            flat
            @click="imprimirPrestadores"
          >
            Imprimir
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <detalle-servicios v-model="dialogDetalle" :data="item" @cancelar="dialogDetalle = false"></detalle-servicios>
    <dialog-aprobacion-servicios ref="aprobacion" @aprobar="datos => aprobarServicios(datos)"></dialog-aprobacion-servicios>
  </v-card>
</template>

<script>
  import {mapState} from 'vuex'
  import DialogAprobacionServicios from './DialogAprobacionServicios'
  export default {
    name: 'Autorizaciones',
    components: {
      DialogAprobacionServicios,
      DetalleServicios: () => import('./DetalleServicios'),
      Confirmar: () => import('@/components/general/Confirmar'),
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DialogInformeAutorizacionesUsuario: () => import('@/components/misional/atencionUsuario/autorizaciones/DialogInformeAutorizacionesUsuario'),
      DataTable: () => import('@/components/general/DataTable')
    },
    data () {
      return {
        dialogDetalle: false,
        item: null,
        espera_solicitudes: null,
        espera_solicitudes_dos: null,
        rutaBaseUno: '',
        rutaBaseDos: '',
        dialogPrint: {
          show: false,
          loading: false,
          item: null,
          entidadesDisponibles: [],
          entidadesSeleccionadas: [],
          recibido: null
        },
        dialogA: {
          loading: false,
          visible: false,
          content: null,
          registro: null
        },
        dataTable: {
          nameItemState: 'tableAutorizacionesFuncionarios',
          route: 'autorizaciones_all',
          makeHeaders: [
            {
              text: '# Solicitud',
              align: 'center',
              sortable: false,
              value: 'id',
              classData: 'text-xs-center'
            },
            {
              text: 'Fecha radicado',
              align: 'left',
              sortable: false,
              value: 'fecha',
              component: {
                template:
                  `<span>{{value.fecha ? moment((value.fecha + ' ' + value.hora), 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD HH:mm') : ''}}</span>`,
                props: ['value']
              }
            },
            {
              text: 'Afiliado',
              align: 'left',
              sortable: false,
              value: 'identificacion',
              component: {
                template:
                  `<mini-card-detail :data="value.afiliado.mini_afiliado"></mini-card-detail>`,
                props: ['value']
              }
            },
            {
              text: 'I.P.S. Origen',
              align: 'left',
              sortable: false,
              value: 'entidad_ips',
              width: '250px',
              component: {
                template:
                  `<v-tooltip right>
                      <v-list-tile class="content-v-list-tile-p0 cursor-pointer" v-if="value.entidad_ips" slot="activator">
                            <v-list-tile-content class="truncate-content">
                              <v-list-tile-title slot="activator" class="body-2">{{value.entidad_ips.nombre}}</v-list-tile-title>
                              <v-list-tile-title class="caption grey--text">Código: {{value.entidad_ips.cod_habilitacion}}</v-list-tile-title>
                            </v-list-tile-content>
                      </v-list-tile>
                      <span>{{value.entidad_ips.nombre}}</span>
                      </v-tooltip>
                    `,
                props: ['value']
              }
            },
            {
              text: 'Usuario',
              align: 'left',
              sortable: false,
              value: 'usuarioProceso',
              width: '250px',
              component: {
                template:
                  `<v-tooltip right>
                      <v-list-tile class="content-v-list-tile-p0 cursor-pointer" v-if="value.usuarioProceso" slot="activator">
                            <v-list-tile-content class="truncate-content">
                                <v-list-tile-title class="body-2">{{value.usuarioProceso.name}}</v-list-tile-title>
                                <v-list-tile-title class="caption grey--text">{{value.usuarioProceso.email}}</v-list-tile-title>
                            </v-list-tile-content>
                      </v-list-tile>
                      <span>{{value.usuarioProceso.name}}</span>
                      </v-tooltip>
                    `,
                props: ['value']
              }
            },
            {
              text: 'Estado',
              align: 'center',
              sortable: true,
              value: 'ind',
              classData: 'text-xs-center',
              component: {
                template:
                  `<span>{{(value.sw_espera !== 0) ? 'En espera por aprobación de la solicitud' : (value.ind === '1' ? 'Registrada' : value.ind === '2' ? 'Anulada' : 'Enviada')}}</span>`,
                props: ['value']
              }
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              singlesActions: true,
              classData: 'text-xs-center'
            }
          ]
        },
        dataTable2: {
          nameItemState: 'tableAutorizacionesPrestadores',
          route: 'aut_all_prestadores',
          makeHeaders: [
            {
              text: '# Solicitud',
              align: 'center',
              sortable: false,
              value: 'id',
              classData: 'text-xs-center'
            },
            {
              text: 'Fecha radicado',
              align: 'left',
              sortable: false,
              value: 'fecha',
              component: {
                template:
                  `<span>{{value.fecha ? moment((value.fecha + ' ' + value.hora), 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD HH:mm') : ''}}</span>`,
                props: ['value']
              }
            },
            {
              text: 'Afiliado',
              align: 'left',
              sortable: false,
              value: 'identificacion',
              component: {
                template:
                  `<mini-card-detail :data="value.afiliado.mini_afiliado"></mini-card-detail>`,
                props: ['value']
              }
            },
            {
              text: 'I.P.S. Origen',
              align: 'left',
              sortable: false,
              value: 'entidad_ips',
              width: '250px',
              component: {
                template:
                  `<v-tooltip right>
                      <v-list-tile class="content-v-list-tile-p0 cursor-pointer" v-if="value.entidad_ips" slot="activator">
                            <v-list-tile-content class="truncate-content">
                              <v-list-tile-title slot="activator" class="body-2">{{value.entidad_ips.nombre}}</v-list-tile-title>
                              <v-list-tile-title class="caption grey--text">Código: {{value.entidad_ips.cod_habilitacion}}</v-list-tile-title>
                            </v-list-tile-content>
                      </v-list-tile>
                      <span>{{value.entidad_ips.nombre}}</span>
                      </v-tooltip>
                    `,
                props: ['value']
              }
            },
            {
              text: 'Usuario',
              align: 'left',
              sortable: false,
              value: 'usuarioProceso',
              width: '250px',
              component: {
                template:
                  `<v-tooltip right>
                      <v-list-tile class="content-v-list-tile-p0 cursor-pointer" v-if="value.usuarioProceso" slot="activator">
                            <v-list-tile-content class="truncate-content">
                                <v-list-tile-title class="body-2">{{value.usuarioProceso.name}}</v-list-tile-title>
                                <v-list-tile-title class="caption grey--text">{{value.usuarioProceso.email}}</v-list-tile-title>
                            </v-list-tile-content>
                      </v-list-tile>
                      <span>{{value.usuarioProceso.name}}</span>
                      </v-tooltip>
                    `,
                props: ['value']
              }
            },
            {
              text: 'Estado',
              align: 'center',
              sortable: true,
              value: 'ind',
              classData: 'text-xs-center',
              component: {
                template:
                  `<span>{{(value.sw_espera !== 0)  ? 'En espera por aprobación de la solicitud' : (value.ind === '1' ? 'Registrada' : value.ind === '2' ? 'Anulada' : 'Enviada')}}</span>`,
                props: ['value']
              }
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              singlesActions: true,
              classData: 'text-xs-center'
            }
          ]
        }
      }
    },
    created () {
      // this.espera_solicitudes = 0
      // this.espera_solicitudes_dos = 0
      this.rutaBaseUno = JSON.parse(JSON.stringify(this.dataTable.route))
      this.rutaBaseDos = JSON.parse(JSON.stringify(this.dataTable2.route))
    },
    watch: {
      'dialogPrint.show' (val) {
        if (!val) {
          this.dialogPrint.entidadesSeleccionadas = []
          this.dialogPrint.entidadesDisponibles = []
          this.dialogPrint.loading = false
          this.dialogPrint.show = false
          this.dialogPrint.item = null
        }
      },
      'dialogDetalle' (val) {
        if (!val) {
          this.item = null
        }
      },
      'espera_solicitudes' (val) {
        let rutaTempUno = this.rutaBaseUno
        if (val) {
          rutaTempUno = 'autorizaciones/sin-aprobar'
          // rutaTempUno = rutaTempUno + (rutaTempUno.indexOf('?') > -1 ? '&' : '?') + 'espera=1'
        }
        this.dataTable.route = rutaTempUno
      },
      'espera_solicitudes_dos' (val) {
        let rutaTempDos = this.rutaBaseDos
        if (val) {
          rutaTempDos = 'autorizaciones/sin-aprobar-prestadores'
          // rutaTempDos = rutaTempDos + (rutaTempDos.indexOf('?') > -1 ? '&' : '?') + 'espera=1'
        }
        this.dataTable2.route = rutaTempDos
      }
    },
    computed: {
      ...mapState({
        currentUser: state => state.user.profile
      }),
      esJefeAutorizador () {
        return this.currentUser && this.currentUser.roles && this.currentUser.roles.find(x => x.id === 1 || x.id === 51)
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('autorizaciones')
      }
    },
    methods: {
      aprobarServicios (items) {
        // console.log('afjhbijhfbhjrg', items)
        let id = items.anexo_id
        this.axios.post(`autorizaciones/${id}/aprobar`, items)
          .then(() => {
            if (this.$refs.tableAutorizacionesFuncionarios && this.$refs.tableAutorizacionesFuncionarios.value.items.find(x => x.id === id)) {
              this.$store.commit('reloadTable', 'tableAutorizacionesFuncionarios')
            } else if (this.$refs.tableAutorizacionesPrestadores && this.$refs.tableAutorizacionesPrestadores.value.items.find(x => x.id === id)) {
              this.$store.commit('reloadTable', 'tableAutorizacionesPrestadores')
            }
            this.$store.commit('SNACK_SHOW', {msg: 'La solicitud se aprobó correctamente.', color: 'success'})
            id = null
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al intentar aprobar la solicitud. ', error: e})
          })
      },
      openDialogAprobarSolicitud (item) {
        this.$refs.aprobacion.assign(JSON.parse(JSON.stringify(item)))
      },
      openDialogServicios (item) {
        this.item = JSON.parse(JSON.stringify(item))
        this.dialogDetalle = !this.dialogDetalle
      },
      imprimirPrestadores () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.dialogPrint.loading = true
            this.dialogPrint.entidadesSeleccionadas.forEach(async x => {
              await this.filePrint(`autorizacione&id=${this.dialogPrint.item.id}&entidad_id=${x}&recibido=${this.dialogPrint.recibido}`)
              this.dialogPrint.loading = false
              this.dialogPrint.show = false
            })
          }
        })
      },
      imprimir (item) {
        this.dialogPrint.entidadesSeleccionadas = []
        this.dialogPrint.entidadesDisponibles = []
        this.dialogPrint.recibido = null
        this.dialogPrint.item = item
        this.dialogPrint.entidadesDisponibles = item.mini_entidades
        this.dialogPrint.show = true
        if (this.dialogPrint.entidadesDisponibles.length === 1) {
          this.dialogPrint.entidadesSeleccionadas.push(this.dialogPrint.entidadesDisponibles[0].id)
        }
      },
      resetOptions (item) {
        item.options = []
        if (this.infoComponent) item.options.push({event: 'servicio', color: 'teal', icon: 'fas fa-stream', tooltip: 'Servicios Registrados'})
        if (this.infoComponent && this.infoComponent.permisos.anular && item.ind !== '2') item.options.push({event: 'anularAutorizacion', color: 'error', icon: 'delete', tooltip: 'Anular'})
        if (this.infoComponent && this.infoComponent.permisos.imprimir && item.ind === '3') item.options.push({event: item.mini_entidades.length ? 'imprimir' : '', icon: 'far fa-file-pdf', color: item.mini_entidades.length ? 'red' : 'grey', tooltip: item.mini_entidades.length ? 'Ver PDF' : 'No está disponible'})
        if (this.infoComponent && (item.sw_espera !== 1)) item.options.push({event: 'autorizar', color: item.ind === '3' ? 'success' : item.ind === '1' ? 'info' : 'grey', icon: item.ind === '3' ? 'fas fa-clipboard-check' : item.ind === '1' ? 'fas fa-tasks' : 'fas fa-clipboard-list', tooltip: item.ind === '3' ? 'Ver autorización' : item.ind === '1' ? 'Autorizar' : 'Ver proceso'})
        if (this.infoComponent && (item.sw_espera !== 0) && this.esJefeAutorizador) item.options.push({event: 'aprobarSolicitud', color: 'teal lighten-3', icon: 'fas fa-user-clock', tooltip: 'Aprobación de Solicitud'})
        return item
      },
      registroAnular (item) {
        this.dialogA.registro = JSON.parse(JSON.stringify(item))
        this.dialogA.content = '¿Está seguro de anular el registro de autorización de <strong>' + this.dialogA.registro.afiliado.nombre_completo + '</strong>?'
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        setTimeout(() => {
          this.dialogA.loading = false
          this.dialogA.content = null
          this.dialogA.registro = null
        }, 500)
      },
      confirmaAnulacion (motivo) {
        this.dialogA.loading = true
        this.axios.put('autorizaciones/anular/' + this.dialogA.registro.id, {ind: '2', observacion: motivo})
          .then(() => {
            if (this.$refs.tableAutorizacionesFuncionarios && this.$refs.tableAutorizacionesFuncionarios.value.items.find(x => x.id === this.dialogA.registro.id)) {
              this.$store.commit('reloadTable', 'tableAutorizacionesFuncionarios')
            } else if (this.$refs.tableAutorizacionesPrestadores && this.$refs.tableAutorizacionesPrestadores.value.items.find(x => x.id === this.dialogA.registro.id)) {
              this.$store.commit('reloadTable', 'tableAutorizacionesPrestadores')
            }
            this.$store.commit('SNACK_SHOW', {msg: 'La autorización se anuló correctamente.', color: 'success'})
            this.cancelaAnulacion()
          }).catch(e => {
            this.dialogA.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al intentar anular la autorización. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>
  .switch-solicitud {
    height: 25px !important;
  }
</style>
