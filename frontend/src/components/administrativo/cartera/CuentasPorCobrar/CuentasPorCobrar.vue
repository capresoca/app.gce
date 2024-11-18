<template>
  <div>
    <v-dialog v-model="dialog" persistent max-width="300">
      <v-card>
        <v-card-text class="subheading font-weight-light">¿Realmente desea cambiar el estado de esta cuenta?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click.native="dialog = false">No</v-btn>
          <v-btn color="primary" flat @click="cr_cuentaxcobrar.estado === 'Anulado' ? descAnulado(cr_cuentaxcobrar) : actualizarEstado(cr_cuentaxcobrar)">Si</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialog2" persistent max-width="400">
      <v-card>
        <v-card-title class="grey lighten-3">
          <v-card-text class="title">¿Por qué anula esta cuenta?</v-card-text>
        </v-card-title>
        <v-container fluid>
          <v-layout row wrap>
            <v-flex xs12>
              <v-textarea
                outline v-model="detalle_anulacion"
                color="primary" name="Detalle de anulación">
                <div slot="label">Detalle de anulación</div>
              </v-textarea>
            </v-flex>
          </v-layout>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" flat @click="actualizarEstado(cr_cuentaxcobrar, detalle_anulacion)">Enviar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card>
      <v-container fluid grid-list-xs>
        <v-layout row wrap>
          <v-flex xs12>
            <v-toolbar class="grey lighten-3 elevation-1 toolbar--dense">
              <v-toolbar-title>Cuentas Por Cobrar </v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn fab right
                     small color="accent"
                     @click="$store.commit('NAV_ADD_ITEM',{
                     ruta: infoComponent.ruta_registro,
                     titulo: infoComponent.titulo_registro,
                     parametros: { entidad: null, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}
                     })"
                     v-if="infoComponent ? infoComponent.permisos.agregar : false">
                <v-icon>add</v-icon>
              </v-btn>
            </v-toolbar>
            <v-card-title class="elevation-1">
              <v-layout row wrap>
                <v-spacer></v-spacer>
                <v-flex xs1 sm3 md2 class="text-xs-right">
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
                    <span>Actualizar registros</span>
                  </v-tooltip>
                </v-flex>
                <v-flex xs12 sm3 md3>
                  <v-select
                    label="Registros por página"
                    v-model="pagination.per_page"
                    :items="items_page"
                    item-text="text"
                    item-value="value">
                  </v-select>
                </v-flex>
                <v-flex xs12 sm3 md2 v-if="cxcs.length > 0">
                  <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Buscar"
                    hide-details
                    single-line
                    @input="buscar">
                  </v-text-field>
                </v-flex>
              </v-layout>
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="cxcs"
              :loading="tableLoading"
              :pagination.sync="pagination"
              :total-items="cxcs.length"
              hide-actions
              :search="search" class="elevation-1">
              <template slot="items" slot-scope="props">
                <tr class="white" :class="{'grey lighten-4': props.item.estado ? props.item.estado === 'Anulado' : ''}">
                  <td>{{ props.item.consecutivo }}</td>
                  <td>{{ props.item.nombre }}</td>
                  <td>{{ props.item.identificacion_completa }}</td>
                  <td class="text-xs-right">{{ props.item.numero_factura }}</td>
                  <td class="text-xs-center">{{ props.item.fecha_factura}}</td>
                  <td class="text-xl-center">{{ props.item.fecha_vencimiento}}</td>
                  <td class="text-xs-right">{{ props.item.estado}}</td>
                  <td class="text-xs-right">
                    <v-speed-dial
                      v-model="props.item.show"
                      :direction="direction"
                      :transition="transition"
                      open-on-hover>
                      <v-btn v-model="props.item.show" slot="activator"
                             color="blue darken-2" dark small fab>
                        <v-icon>chevron_left</v-icon>
                        <v-icon>close</v-icon>
                      </v-btn>
                      <v-tooltip bottom>
                        <v-btn small fab color="white" slot="activator"
                               @click.prevent="imprimir(props.item)">
                          <v-icon color="black">far fa-file-pdf</v-icon>
                        </v-btn>
                        <span>Imprimir</span>
                      </v-tooltip>
                      <v-tooltip bottom>
                        <v-btn small fab color="white" slot="activator"
                               v-if="props.item.estado === 'Registrado'"
                               @click="preguntarCambiarEstado(props.item, 'Anulado')">
                          <v-icon color="grey">report_off</v-icon>
                        </v-btn>
                        <span>Anular</span>
                      </v-tooltip>
                      <v-tooltip bottom>
                        <v-btn small fab slot="activator"
                               v-if="props.item.estado === 'Registrado'"
                               @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })">
                          <v-icon color="accent">mode_edit</v-icon>
                        </v-btn>
                        <span>Editar</span>
                      </v-tooltip>
                      <v-tooltip bottom>
                        <v-btn small fab color="white" slot="activator"
                               v-if="props.item.estado === 'Registrado'"
                               @click="preguntarCambiarEstado(props.item, 'Confirmado')">
                          <v-icon color="teal">check</v-icon>
                        </v-btn>
                        <span>Confirmar</span>
                      </v-tooltip>
                      <!--<v-tooltip bottom>-->
                        <!--<v-btn small fab-->
                               <!--color="white" slot="activator"-->
                               <!--v-if="props.item.estado === 'Confirmado'"-->
                               <!--@click="preguntarCambiarEstado(props.item, 'Registrado')">-->
                          <!--<v-icon color="red">remove_circle</v-icon>-->
                        <!--</v-btn>-->
                        <!--<span>Desconfirmar</span>-->
                      <!--</v-tooltip>-->
                    </v-speed-dial>
                  </td>
                </tr>
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
                <td colspan="100%">
                  <div class="text-xs-center">
                    <v-pagination v-model="pagination.current_page" :length="pagination.last_page" @input="reloadPage"></v-pagination>
                  </div>
                </td>
              </template>
            </v-data-table>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
  </div>
</template>
<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {mapState} from 'vuex'
  export default {
    name: 'CuentasPorCobrar',
    components: {},
    data () {
      return {
        cxcs: [],
        tableLoading: false,
        transition: 'slide-x-transition',
        direction: 'left',
        fab: false,
        search: '',
        show: false,
        dialog: false,
        dialog2: false,
        detalle_anulacion: null,
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
          { text: 'Consecutivo', align: 'left', sortable: false, value: 'consecutivo' },
          { text: 'Cliente', align: 'left', sortable: false, value: 'cliente.nombre' },
          { text: 'Identificación', align: 'left', sortable: false, value: 'cliente.tercero.identificacion_completa' },
          { text: 'Número de factura', align: 'left', sortable: false, value: 'numero_factura' },
          { text: 'Fecha de factura', align: 'left', sortable: false, value: 'fecha_factura' },
          { text: 'Fecha de vencimiento', align: 'left', sortable: false, value: 'fecha_vencimiento' },
          { text: 'Estado', align: 'left', sortable: false, value: 'estado' },
          { text: 'Opciones', align: 'center', actions: true, sortable: false, classData: 'justify-center layout px-0' }
        ]
      }
    },
    created () {
      this.reloadPage()
    },
    watch: {
      'pagination.per_page' (value) {
        this.pagination.current_page = 1
        this.reloadPage()
      },
      'itemCxc' (value) {
        console.log('MIEN', value)
        value.cr_cuentaxcobrar.show = false
        if (value.estado === 'crear') {
          this.cxcs.splice(0, 0, value.cr_cuentaxcobrar)
        } else if (value.estado === 'editar') {
          this.cxcs.splice(this.cxcs.findIndex(x => x.id === value.cr_cuentaxcobrar.id), 1, value.cr_cuentaxcobrar)
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('cxcs')
      },
      ...mapState({
        itemCxc: state => state.tables.itemCxc
      })
    },
    methods: {
      imprimir (item) {
        console.log('Abriendo DOM')
        let id = item.id
        this.axios.get('reportecxps?id=' + id)
          .then((res) => {
            let url = res.data
            let win = window.open(url, '_blank')
            win.focus()
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al Imprimir. ' + e.response, color: 'danger'})
          })
      },
      preguntarCambiarEstado (crCuentaxcobrar, nuevoEstado) {
        this.dialog = true
        console.log(nuevoEstado)
        console.log(crCuentaxcobrar)
        this.cr_cuentaxcobrar = JSON.parse(JSON.stringify(crCuentaxcobrar))
        this.cr_cuentaxcobrar.estado = nuevoEstado
      },
      descAnulado (crCuentaxcobrar) {
        this.dialog2 = true
      },
      actualizarEstado (crCuentaxcobrar, detalleAnulacion) {
        this.dialog = false
        this.dialog2 = false
        delete crCuentaxcobrar.show
        crCuentaxcobrar.detalle_anulacion = detalleAnulacion
        this.axios.put('cr_cuentasxcobrars/' + crCuentaxcobrar.id, crCuentaxcobrar)
          .then((response) => {
            console.log('Estado Actualizado')
            this.$store.commit(SNACK_SHOW, {msg: 'Se realizó el cambio de estado a ' + crCuentaxcobrar.estado, color: 'success'})
            this.cxcs.splice(this.cxcs.findIndex(crCuentaxcobrar => crCuentaxcobrar.id === this.cr_cuentaxcobrar.id), 1, this.cr_cuentaxcobrar)
            console.log(response)
          }).catch(e => {
            this.dialog = false
            this.$store.commit(SNACK_SHOW, {msg: 'Existe un error al actualizar el estado. ' + e.response, color: 'danger'})
          })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      reloadPage () {
        this.tableLoading = true
        this.axios.get('cr_cuentasxcobrars?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.cxcs = response.data.data
            this.tableLoading = false
          }).catch(e => {
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.message, color: 'danger'})
          })
      }
      // resetOptions (item) {
      //   item.options = []
      //   if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
      //   return item
      // }
    }
  }
</script>
<style scoped>

</style>
