<template>
  <v-card>
    <toolbar-list class="elevation-1 grey lighten-4" :info="infoComponent" title="Procesos BDUA" btnplus btnplustitle="Crear proceso BDUA"/>
    <v-container fluid grid-list-md>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card>
            <v-card-title class="title py-2">
              <v-avatar size="38" color="primary" class="mr-2">
                <v-icon class="white--text">fas fa-file-download</v-icon>
              </v-avatar>
              Descarga de archivos
            </v-card-title>
            <v-divider></v-divider>
            <v-container fluid grid-list-md class="pt-2">
              <v-layout row wrap>
                <v-flex xs12 sm12 md6>
                  <v-card>
                    <v-subheader>Régimen Contributivo</v-subheader>
                    <v-card-actions>
                      <v-layout align-center justify-center row fill-height>
                        <template v-for="(btn, index) in tiposProceso.contributivos">
                          <v-btn :key="'btnC' + index" :loading="btn.loading" outline color="primary" @click.stop="download(btn)">{{btn.type}}</v-btn>
                        </template>
                      </v-layout>
                    </v-card-actions>
                  </v-card>
                </v-flex>
                <v-flex xs12 sm12 md6>
                  <v-card>
                    <v-subheader>Régimen  Subsidiado</v-subheader>
                    <v-card-actions>
                      <v-layout align-center justify-center row fill-height>
                        <template v-for="(btn, index) in tiposProceso.subsidiados">
                          <v-btn :key="'btnS' + index" :loading="btn.loading" outline color="primary" @click.stop="download(btn)">{{btn.type}}</v-btn>
                        </template>
                      </v-layout>
                    </v-card-actions>
                  </v-card>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
      </v-layout>
      <v-data-table
        :headers="headers"
        :items="procesosBDUA"
        :loading="tableLoading"
        :pagination.sync="pagination"
        :total-items="procesosBDUA.length"
        hide-actions
        :search="search" class="elevation-1">
        <template slot="items" slot-scope="props">
          <td>{{ props.item.fecha }}</td>
          <td>{{ props.item.tipo }}</td>
          <td class="pl-5">
            <v-menu :disabled="!props.item.archivos.length" v-model="props.item.sheet" :key="'Enviados' + props.item.id" :close-on-content-click="false" :nudge-width="250" offset-overflow open-on-hover>
              <v-icon v-model="props.item.sheet" :color="!props.item.archivos.length ? 'grey' : 'accent'" slot="activator">archive</v-icon>
              <!--<v-btn slot="activator" color="white" dark fab icon outline>-->
              <!--</v-btn>-->
              <v-card>
                <v-card-title class="elevation-0 grey lighten-2">
                  <span class="title">Archivos En Proceso</span>
                  <v-spacer></v-spacer>
                  <v-icon @click="props.item.sheet = false">close</v-icon>
                </v-card-title>
                <v-container fluid grid-list-md grid-list-xl>
                  <v-layout row wrap>
                    <v-flex xs6 sm6 md3 xl3 v-for="(item, index) in props.item.archivos" :key="index">
                      <v-badge overlap color="transparent" v-if="habilitarDescargaArchivos(item)">
                        <span slot="badge" class="btn__badge_unique" v-text="item.total_tramites"></span>
                        <!--<v-btn small class="pa-0 ma-0" v-text="item.tipo ? item.tipo.iniciales : ''" color="grey" outline label></v-btn>-->
                          <v-chip style="cursor: pointer !important;" @click="descargarArchivo(item.id, item.nombre, item.url_signed)" class="pa-1 ma-0" v-text="item.tipo ? item.tipo.iniciales : ''" color="grey" outline label></v-chip>
                      </v-badge>
                      <v-badge overlap color="transparent"  v-else>
                        <span slot="badge" class="btn__badge_unique" v-text="'Prc'"></span>
                        <!--<v-btn small class="pa-0 ma-0" v-text="item.tipo ? item.tipo.iniciales : ''" color="grey" outline label></v-btn>-->
                          <v-chip style="cursor: pointer !important;" @click="noDescargarArchivo(item)" class="pa-1 ma-0" v-text="item.tipo ? item.tipo.iniciales : ''" color="grey" outline label></v-chip>
                      </v-badge>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card>
            </v-menu>
          </td>
          <td class="pl-5">
            <v-tooltip left :disabled="infoComponent.permisos.agregar ? false : true">
              <v-btn small fab
                     slot="activator"
                     :disabled="infoComponent.permisos.agregar ? false : true"
                     @click="$store.commit('NAV_ADD_ITEM', { ruta: 'respuestasBDUA', titulo: 'Respuestas BDUA', parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })">
                <v-icon color="accent">unarchive</v-icon>
              </v-btn>
              <span>Ir a registrar respuestas BDUA</span>
            </v-tooltip>
          </td>
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
              <!--<v-pagination v-model="pagination.current_page" :length="pagination.last_page" @input="reloadPage"></v-pagination>-->
            </div>
          </td>
        </template>
      </v-data-table>
      <v-dialog v-model="dialogProgress" persistent width="300" content-class="grey lighten-3">
        <v-card color="primary" dark>
          <v-card-text>
            <span class="subheading" v-text="'Descargando... '"></span>
            <v-progress-linear
              indeterminate
              color="white"
              class="mb-0"
            ></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-container>
  </v-card>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {mapState} from 'vuex'
  import Echo from 'laravel-echo'
  window.io = require('socket.io-client')
  export default {
    name: 'ProcesosBDUA',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        tiposProceso: {
          contributivos: [
            {
              loading: false,
              type: 'MC'
            },
            {
              loading: false,
              type: 'NC'
            },
            {
              loading: false,
              type: 'R1'
            }
          ],
          subsidiados: [
            {
              loading: false,
              type: 'MS'
            },
            {
              loading: false,
              type: 'NS'
            },
            {
              loading: false,
              type: 'S1'
            }
          ]
        },
        procesosBDUA: [],
        procesosBDUAFilter: null,
        tableLoading: false,
        search: '',
        menu: false,
        sheet: false,
        sheet2: false,
        fecha: null,
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
          {
            text: 'Fecha',
            align: 'left',
            sortable: false,
            value: 'fecha'
          },
          {
            text: 'Tipo',
            align: 'left',
            sortable: false,
            value: 'tipo'
          },
          {
            text: 'Archivos Generados',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Carga de Recibidos',
            align: 'left',
            sortable: false,
            value: ''
          }
        ],
        dialogProgress: false
      }
    },
    created () {
      this.reloadPage()
    },
    watch: {
      'itemProcesoBDUA' (value) {
        value.procesoBDUA.sheet = false
        value.procesoBDUA.sheet2 = false
        if (value.estado === 'crear') {
          this.procesosBDUA.splice(0, 0, value.procesoBDUA)
          let echo = new Echo({
            broadcaster: 'socket.io',
            host: 'http://localhost:6001',
            auth: {headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }},
            key: '0330753d0e16efd42bf6b7d0b88488a5'
          })
          echo.private('bdua-proceso.' + value.procesoBDUA.id).listen('BduaProcesosEvent', (data) => {
            this.procesoType(data.type, data, value.procesoBDUA.id)
          })
        } else if (value.estado === 'editar') {
          let echo = new Echo({
            broadcaster: 'socket.io',
            host: 'http://localhost:6001',
            auth: {headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }},
            key: '0330753d0e16efd42bf6b7d0b88488a5'
          })
          echo.private('bdua-proceso.' + value.procesoBDUA.id).listen('BduaProcesosEvent', (data) => {
            this.procesoType(data.type, data, value.procesoBDUA.id)
          })
          this.procesosBDUA.splice(this.procesosBDUA.findIndex(x => x.id === value.procesoBDUA.id), 1, value.procesoBDUA)
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('procesosBDUA')
      },
      ...mapState({
        itemProcesoBDUA: state => state.tables.itemProcesoBDUA
      })
    },
    methods: {
      async download (btn) {
        btn.loading = true
        await this.filePrint(`bdua_file&tipo=${btn.type}`, false)
        this.$store.commit('SNACK_SHOW', {msg: 'Generando archivo.', color: 'info'})
        btn.loading = false
      },
      procesoType (item, data, proceso) {
        switch (item) {
          case 'generador':
            if (data.message.text === 'begin') {
              this.$store.commit(SNACK_SHOW, {msg: 'Iniciando proceso de creacion de archivos.', color: 'info'})
            }
            if (data.message.text === 'end') {
              this.reloadPage()
              this.$store.commit(SNACK_SHOW, {msg: 'Se proceso por completo un archivo del proceso, descarge los archivos deseados.', color: 'success'})
            }
            break
          default:
        }
      },
      async descargarArchivo (item, nombre, url) {
        console.log('andale')
        this.dialogProgress = true
        await window.fileDownload(url)
        this.dialogProgress = false
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      reloadPage () {
        this.tableLoading = true
        this.axios.get('bduaprocesos?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page)
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.procesosBDUA = response.data.data
            this.tableLoading = false
          }).catch(e => {
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })
      },
      habilitarDescargaArchivos (item) {
        if (item.numero_registros === null && item.total_tramites === null) {
          return false
        }
        if (item.numero_registros === null && item.total_tramites != null) {
          return true
        }
        if (item.numero_registros != null && item.total_tramites != null) {
          if (item.numero_registros === item.total_tramites) {
            return true
          }
          return false
        }
        return false
      },
      noDescargarArchivo (item) {
        this.axios.get('bduarchivo-por-id/' + item.id)
          .then((response) => {
            if (response.data.archivo) {
              this.$store.commit(SNACK_SHOW, {msg: 'El archivo se esta procesando, no se puede descargar. Registros procesados: ' + response.data.archivo.total_tramites + ' de ' + response.data.archivo.numero_registros, color: 'info'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda del archivo. ' + item.id, color: 'danger'})
            }
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>
  .btn__badge_unique {
    margin: -8px -8px 0 0;
    background: #FF5D5D;
    color: white;
    font-size: 11px;
    top: -10px;
    right: -10px;
    padding: 1.5px 6px 0.5px;;
    /*padding: 3px 6px 1px;*/
    border-radius: 15px;
  }
</style>
