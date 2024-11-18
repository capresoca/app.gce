<template>
    <div>
      <v-dialog
        v-model="dialog" hide-overlay persistent width="300">
        <v-card
          color="grey lighten-3">
          <v-card-text>
            <span class="body-1 font-weight-regular text--black" v-text="'Enviando respuestas'"></span>
            <v-progress-linear indeterminate color="accent" class="mb-0"></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-card>
        <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
          <v-toolbar-title>   Respuestas BDUA </v-toolbar-title> &nbsp;
          <v-spacer></v-spacer>
          <h2 class="title" v-text="'Fecha Proceso: '"></h2>
          <div>
            <v-chip label color="transparent" text-color="red" dark absolute right top>
              <span class="title" v-text="parametros.entidad.fecha"></span>
            </v-chip>
          </div>
        </v-toolbar>
        <v-container fluid>
          <v-layout row wrap>
            <v-flex xs12 sm12 md6 xl6 class="pb-3">
              <file-input-archive
                :title="'Cargar Respuestas'"
                v-model="respuestaBDUA.files"
                mime-types="text/plain,application/x-zip-compressed"
                :multiple="true" :container_px="containerPx"></file-input-archive>
            </v-flex>
            <v-flex xs12 sm12 md5 offset-md1 xl5>
              <v-card class="elevation-1" height="322.49px">
                <toolbar-list :title="'Monitor del Proceso'" class="elevation-0"></toolbar-list>
                <v-container
                  id="scroll-target-1"
                  style="max-height: 308px !important;"
                  class="scroll-y back_console pt-2 pl-2 pb-3">
                  <v-content v-scroll:#scroll-target-1="onScroll"
                            column align-center justify-center style="height: 250px !important;" class="pt-0">
                    <p class="captionMsg mb-1" v-text="'> Monitor 1.4'"></p>
                    <p  v-for="msg in socketMessages" :class="msg.class" class="msg captionMsg mb-1" v-text="'> ' + msg.text"></p>
                  </v-content>
                </v-container>
              </v-card>
            </v-flex>
            <v-flex xs12 v-if="respuestaBDUA.files.length" class="text-xs-right pt-3" transition="fade-transition" slot="activator">
              <v-btn class="mr-0" color="primary" @click.prevent="save">Generar</v-btn>
            </v-flex>
            <v-flex xs12 sm12 md12 xl12 class="mt-3">
              <v-card>
                <toolbar-list :title="'Detalle del Proceso'" class="elevation-0"></toolbar-list>
                <v-container fluid>
                    <v-layout row wrap>
                      <template>
                        <v-flex xs12 v-for="(detalle, i) in detallesProceso" class="pb-3 mb-1" v-if="detalle.total_tramites" :key="'detallesProceso' + i">
                          <v-layout row wrap>
                            <v-flex xs12 class="text-xs-left mb-3" align-center fill-height>
                              <v-avatar :tile="false" :size="'38px'" class="accent white--text" >
                                <v-tooltip top>
                                  <v-btn slot="activator" fab small color="transparent" dark class="elevation-0 ma-0">
                                    <span class="body-1 font-weight-bold" v-text="detalle.tipo.iniciales"></span>
                                  </v-btn>
                                  <span v-text="detalle.nombre"></span>
                                </v-tooltip>
                              </v-avatar>
                              &nbsp;
                              <span class="body-1 font-weight-medium" v-text="detalle.total_tramites + ' Registros'"></span>
                              <!--<span v-text="detalle.nombre"></span>-->
                            </v-flex>
                            <v-flex xs12 class="grey lighten-4">
                              <v-container fluid grid-list-xl>
                                <v-layout row wrap align-center fill-height>
                                  <v-flex xs12 sm10 offset-sm1 offset-md0 md6 @click="irADetalleRespuesta(respuesta)"  v-for="(respuesta, index) in detalle.respuestas" :key="`2${index}`">
                                    <v-card class="white" height="80" style="cursor: pointer;">
                                      <v-container fluid class="pa-3">
                                        <v-layout row wrap fill-height>
                                          <v-flex xs12>
                                            <v-subheader>
                                              <v-list-tile-content>
                                                <v-subheader class="newSub">
                                                  <span class="body-1 font-weight-medium dark-gray" v-text="respuesta.nombre_archivo"></span>
                                                </v-subheader>
                                                <v-list-tile-title>
                                                  <v-progress-linear width="6" class="grey lighten-3" background-opacity="0.5"  :value="(respuesta.aplicadas * 100) / respuesta.total_registros" :buffer-value="(respuesta.avance * 100) / respuesta.total_registros" buffer></v-progress-linear>
                                                </v-list-tile-title>
                                                <v-list-tile-sub-title class="caption text-xs-right" v-text="'Procesadas: ' + respuesta.avance + ', Aplicadas: ' + respuesta.aplicadas + ', Registros: ' + respuesta.total_registros + '.'"></v-list-tile-sub-title>
                                              </v-list-tile-content>
                                            </v-subheader>
                                          </v-flex>
                                        </v-layout>
                                      </v-container>
                                    </v-card>
                                  </v-flex>
                                </v-layout>
                              </v-container>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </template>
                    </v-layout>
                </v-container>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>
    </div>
</template>

<script>
  import FileInputArchive from '../../../../general/FileInput'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Echo from 'laravel-echo'
  window.io = require('socket.io-client')
  export default {
    props: ['parametros'],
    name: 'RespuestasBDUA',
    components: {
      FileInputArchive,
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        errorArchivo: '',
        loadingArchivo: false,
        respuestaBDUA: {},
        procesoRespuesta: {
          as_bduaproceso_id: null,
          files: []
        },
        dialog: false,
        offsetTop: 0,
        socketMessages: [],
        messages: null,
        containerPx: '180px',
        detallesProceso: [],
        buffer: 10,
        bufferValue: 100
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('respuestasBDUA')
      }
    },
    watch: {
      'respuestaBDUA.as_bduaproceso_id' (val) {
        if (val) {
          let echo = new Echo({
            broadcaster: 'socket.io',
            host: 'http://' + window.location.hostname + ':6001',
            auth: {headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }},
            key: '0330753d0e16efd42bf6b7d0b88488a5'
          })
          echo.private('bdua-proceso.' + val).listen('BduaProcesosEvent', (data) => {
            this.procesoType(data.type, data)
            this.scrollToBottom()
          })
        }
      }
    },
    created () {
      if (this.parametros.entidad !== null) this.getProceso(this.parametros.entidad.id)
    },
    beforeMount () {
      this.reset()
    },
    mounted () {
      this.messages = document.getElementById('scroll-target-1')
      // this.scrollToBottom()
      // setInterval(this.getMessages, 1000)
    },
    methods: {
      procesoType (item, data) {
        switch (item) {
          case 'monitor':
            this.socketMessages.push(data.message)
            break
          case 'avance':
            let fileAvance = this.detallesProceso.find(val => val.id === data.respuesta.as_bduaarchivo_id)
            if (fileAvance.respuestas.length) {
              let indexRespuesta = fileAvance.respuestas.findIndex(val => val.id === data.respuesta.id)
              fileAvance.respuestas.splice(indexRespuesta > -1 ? indexRespuesta : 0, indexRespuesta > -1 ? 1 : 0, data.respuesta)
            } else {
              fileAvance.respuestas.push(data.respuesta)
            }
            break
          default:
            this.$store.commit(SNACK_SHOW, {msg: 'No existe: ' + item + '.', color: 'danger'})
        }
      },
      scrollToBottom () {
        this.messages.scrollTop = this.messages.scrollHeight
      },
      onScroll (e) {
        this.offsetTop = e.target.scrollTop
      },
      reset () {
        this.$validator.reset()
        this.respuestaBDUA = JSON.parse(JSON.stringify(this.procesoRespuesta))
      },
      save () {
        this.dialog = true
        let formData = new FormData()
        for (let file of this.respuestaBDUA.files) {
          formData.append('files[]', file)
        }
        formData.append('as_bduaproceso_id', this.respuestaBDUA.as_bduaproceso_id)
        this.axios.post('bduarespuestas', formData)
          .then(res => {
            // Por arreglar
            this.$store.commit(SNACK_SHOW, {msg: this.respuestaBDUA.files.length > 1 ? 'Los archivos se han enviado.' : 'El archivo se ha enviado.', color: 'success'})
            this.reset()
            this.dialog = false
          }).catch(e => {
            this.dialog = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al enviar.' + e.response, color: 'danger'})
          })
      },
      getProceso (id) {
        let loader = this.$loading.show({
          container: this.$refs.cargar
        })
        this.axios.get('bduaprocesos/' + id)
          .then(res => {
            if (res.data.data) {
              this.respuestaBDUA.as_bduaproceso_id = res.data.data.id
              this.detallesProceso = res.data.data.archivos
            }
            loader.hide()
          }).catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: `Hay un error al traer el proceso BDUA ${e.response}`, color: 'danger'})
          })
      },
      irADetalleRespuesta (respuesta) {
        this.$store.commit('NAV_ADD_ITEM', { ruta: 'detalleRespuestas', titulo: 'Detalle Respuestas', parametros: {entidad: respuesta, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })
      }
    }
  }
</script>

<style scoped>
  @import url('https://fonts.googleapis.com/css?family=Inconsolata');
  .captionMsg {
    font-family: 'Inconsolata', monospace;
    font-size: 11.5px !important;
    font-weight: lighter;
    color: rgb(58, 249, 51);
    /*#87f9b0*/
  }
  .back_console {
    /*background-color: #081918 !important;*/
    background: linear-gradient(#171717, #242424) !important;
    -webkit-background-clip: text;
    color: transparent;
  }
  .console-success {
    color: rgb(58, 249, 51) !important;
  }
  .newSub{
    height: 14px;
    padding: 0 !important;
    align-items: unset;
    color: rgba(0,0,0,0.87);
  }
  @media (min-width: 960px){
    .pa-xl-bottom {
      padding-bottom: 2.555% !important;
    }
    .pa-xl-top {
      padding-top: 2.5555% !important;
    }
  }
</style>
