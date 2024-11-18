<template>  
  <div>
    <v-dialog v-model="dialog2" width="500px">
      <v-form>
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Rechazo</span>
          </v-card-title>

          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeTable">Cancelar</v-btn>
            <v-btn color="primary" @click="saveCausal" :loading="loadingSubmitRechazo">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>


    <v-card ref="loaderRef">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ ordenEdit ? 'Edicion' : 'Editar soporte' }} </v-toolbar-title>
      </v-toolbar>
      <h1 style="align: center" v-if="parametros.afiliado.estado != 'Radicada'">No se puede modificar los soportes a una licencia {{parametros.afiliado.estado}}.</h1>
      <v-card-text class="no-padding" v-else>
      <!-- start -->
        <v-container fluid grid-list-xl>
          <v-layout>
            <v-flex>
              <div class="text-xs-center">
                <h4>Afiliado: {{parametros.afiliado.afiliado}}</h4>
                <h4>Prestación: {{parametros.afiliado.solicitud}}</h4>
              </div>
            </v-flex>
          </v-layout>
          <v-data-table
            :headers="headersTipoSoporte"
            :items="dataSoporte.detalle"
            :loading="tableLoading"
            :total-items="dataSoporte.length"
            hide-actions
            class="elevation-1">
            <template slot="items" slot-scope="props">
              <td>{{ props.item.descripcion }}</td>
              <td>{{ props.item.obligatorio }}</td>
              <td>{{ props.item.observacion }} </td>
              <td>
                <span v-for="url in props.item.urls" :key="url">
                  <span :id="'spanTexto' + props.index" v-if="props.item.descripcion == url.soporte">{{url.url | nombreArchivo}}</span>
                </span>
                <input-file
                  :id="props.index"
                  style="display: none;"
                  label="Adjuntar archivo"
                  v-model="incapacidad.archivo"
                  :file-name="incapacidad.archivo ? incapacidad.archivo.nombre : null"
                  accept=""
                  :hint="''"
                  class="mb-3"
                  prepend-icon="description"
                  @change.native="gestionarArchivos(incapacidad.archivo, props.index, props.item.descripcion, props.item.urls)"
                />
              </td>
              <td>
                <v-speed-dial
                      v-model="props.item.show"
                      direction="left"
                      open-on-hover
                      transition="slide-x-transition"
                    >
                      <v-btn
                        slot="activator"
                        v-model="props.item.show"
                        color="blue darken-2"
                        dark
                        fab
                        small
                      >
                        <v-icon>chevron_left</v-icon>
                        <v-icon>close</v-icon>
                      </v-btn>
                      <span v-for="url in props.item.urls" :key="url">
                        <v-tooltip top v-show="props.item.obligatorio == 'No' && props.item.descripcion == url.soporte">
                          <v-btn
                            fab
                            dark
                            small
                            color="white"
                            slot="activator"
                            @click="eliminar(props.item.descripcion)"
                          >
                            <v-icon color="accent">delete</v-icon>
                          </v-btn>
                          <span>Eliminar</span>
                        </v-tooltip>
                      <!-- </span>
                      <span v-for="url in props.item.urls" :key="url"> -->
                        <v-tooltip top v-if="props.item.descripcion == url.soporte">
                            <v-btn
                              fab
                              dark
                              small
                              color="white"
                              slot="activator"
                              title="Ver"
                              @click="descargar(url.url)"
                            >
                              <v-icon color="accent">image_search</v-icon>
                            </v-btn>
                            <span>Ver</span>
                        
                        </v-tooltip>
                      </span>
                      <v-tooltip top>
                        <v-btn
                          fab
                          dark
                          small
                          color="white"
                          slot="activator"
                          title="Agregar o Modificar"
                          @click="agregarArchivo(props.index)"
                        >
                          <v-icon color="accent">mode_edit</v-icon>
                        </v-btn>
                        <span>Editar</span>
                      </v-tooltip>
                </v-speed-dial>
              </td>
            </template>
            <template slot="no-data">
              <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
              </v-alert>
              <v-alert v-else :value="true" color="info" icon="info">
                Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
              </v-alert>
            </template>
          </v-data-table>
        </v-container>
      <!-- end --> 
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 class="text-xs-right">
            <v-btn @click="close()">Anterior</v-btn>
            <v-btn :disabled="isDisabled" v-show="parametros.afiliado.estado != 'Aprobado'" color="primary" @click.prevent="actualizar()" :loading="loadingSubmit" >Actualizar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
    <confirmar ref="confirmo" :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)" />
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  // import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import Confirmar from '@/components/general/Confirmar2'
  export default {
    name: 'GestionAuditPrestacionEconomica',
    components: {
      Confirmar,
      InputFile: () => import('@/components/general/InputFile')
    },
    props: ['parametros'],
    data () {
      return {
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        loadingSubmit: false,
        payload: null,
        incapacidad: {},
        mostrarInput: false,
        dialog2: false,
        tableLoading: false,
        arrayTutela: [],
        dataSoporte: { detalle: [] },
        causalesRechazo: { causal: [] },
        headersTipoSoporte: [
          {
            text: 'Tipo Soporte',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Obligatorio',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Observación',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Tipos de archivo',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Acciones',
            align: 'left',
            sortable: false,
            value: ''
          }
        ],
        arrayRechazo: {},
        dataEliminar: [],
        archivo: [],
        descripcionSoporte: [],
        soportes: [],
        mostrar: true,
        mostrarDocs: true
      }
    },
    mounted () {
      if (this.parametros.afiliado !== null) this.getRegistro(this.parametros.afiliado.id)
      console.log(this.parametros)
    },
    created () {
      this.reloadPage()
    },
    watch: {
    },
    computed: {
      complementos () {
        return store.getters.complementosTercerosFormComplementarios
      },
      isDisabled () {
        return !this.incapacidad.archivo
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.incapacidad.consecutivo_afiliado = this.parametros.afiliado.id
        this.axios.get('soportesLicencia?id=' + this.parametros.afiliado.solicitud)
          .then((response) => {
            this.dataSoporte.detalle = response.data.data
            this.tableLoading = false
            this.localLoading = false
            // console.log('la data del soporte en edicion soportes auditoria', this.dataSoporte)
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            console.log(e.response)
            return false
          })
      },
      closeTable () {
        this.dialog = false
        this.dialog2 = false
      },
      getRegistro (id) {
        //
      },
      actualizar () {
        this.loadingSubmit = true
        this.addFormData()
        this.axios.post('updatesoportes', this.payload)
          .then((response) => {
            // console.log('el response', response)
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Actualización realizada correctamente.', color: 'success'})
            this.reloadPage()
            this.formReset()
            // this.close()
          })
          .catch(e => {
            this.loadingSubmit = false
            console.log(e)
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
            return false
          })
      },
      close () {
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      formReset () {
        for (let i = 0; i < this.descripcionSoporte.length; i++) {
          if (this.descripcionSoporte[i]) {
            console.log(this.descripcionSoporte[i])
            var span = document.getElementById(i)
            span.setAttribute('style', 'display: none')
            var nombre = 'spanTexto' + i
            document.getElementById(nombre).removeAttribute('style')
          }
        }
        this.archivo = []
        this.descripcionSoporte = []
        this.soportes = []
        this.incapacidad = {}
      },
      descargar (soporte) {
        console.log(soporte)
        this.axios.get('descargasoporte?url=' + soporte)
          .then((response) => {
            let urls = soporte
            let ultimaPosicion = urls.lastIndexOf('/')
            let nombreArchivo = urls.substr(ultimaPosicion + 1, urls.length)
            // obtener extension
            let punto = urls.lastIndexOf('.')
            let extension = urls.substr(punto + 1, urls.length)
            console.log(extension)
            // let blob = new Blob([response.data], { type: response.headers['content-type'] })
            // console.log('contentype', response.headers['content-type'])
            console.log(response.data)
            // let link = document.createElement('a')
            // link.href = window.URL.createObjectURL(blob)
            // link.download = nombreArchivo
            // link.click()
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', nombreArchivo) // or any other extension
            document.body.appendChild(link)
            link.click()
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al descargar el archvio.', color: 'danger'})
            return false
          })
      },
      gestionarArchivos (file, index, descripcionSoporte, soporteNombre) {
        this.archivo[index] = file
        this.descripcionSoporte[index] = descripcionSoporte
        this.soportes[index] = soporteNombre
        console.log(index)
        console.log('array archivos', this.archivo)
      },
      agregarArchivo (id) {
        this.mostrarInput = true
        document.getElementById(id).removeAttribute('style')
        var nombre = 'spanTexto' + id
        var span = document.getElementById(nombre)
        span.setAttribute('style', 'display: none')
      },
      addFormData () {
        let data = new FormData()

        var f = new File([''], 'noArchivo.txt', {type: 'text/plain', lastModified: Date.now()})

        for (var i = 0; i < this.archivo.length; i++) {
          data.append('nombresArchivos[]', this.soportes[i])
        }

        for (var j = 0; j < this.archivo.length; j++) {
          if (!this.archivo[j]) {
            this.archivo[j] = f // si no hay archivo es esa posición, agrego un archivo dummy para empatar con el array de descripcion y facilitar actualizar BD
          }
          data.append('archivos[]', this.archivo[j])
        }

        for (var k = 0; k < this.archivo.length; k++) {
          data.append('descripcionSoporte[]', this.descripcionSoporte[k])
        }
        data.append('consecutivo_licencia', this.parametros.afiliado.solicitud)
        this.payload = data
        // Object.assign(this.payload, data)
      },
      eliminar (soporte) {
        this.dialogA.content = '¿Está seguro de eliminar?'
        this.dialogA.registroID = this.parametros.afiliado.solicitud
        this.dialogA.visible = true
        this.dialogA.soporte = soporte
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroID = null
      },
      confirmaAnulacion () {
        this.localLoading = true
        this.axios.delete('auditoria/eliminar/' + this.dialogA.registroID + '/' + this.dialogA.soporte)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'El soporte se eliminó correctamente', color: 'success'})
            this.reloadPage()
            this.cancelaAnulacion()
            this.localLoading = false
            this.$refs.confirmo.pararCarga()
          })
          .catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al eliminar el registro. ' + e.response, color: 'danger'})
          })
      }
      // eliminarCausal (index, url) {
      //   alert(index)
      //   this.dataEliminar[index] = url

      // }
    },
    filters: {
      nombreArchivo: function (val) {
        let ultimaPosicion = val.lastIndexOf('/')
        let nombreArchivo = val.substr(ultimaPosicion + 1, val.length)
        return nombreArchivo
      },
      estado: function (val) {
        if (val === 1) {
          return 'Radicada'
        } else if (val === 2) {
          return 'Aprobada'
        } else if (val === 3) {
          return 'Negado'
        } else if (val === 4) {
          return 'En progreso'
        } else if (val === 5) {
          return 'Anulada'
        } else if (val === 6) {
          return 'Rechazado'
        }
      }
    }
  }
</script>

<style scoped>
  .tipo {
    height: 10px;
    padding: 0;
  }
  .no-padding {
    padding: 0;
  }
  .trverde {
    background-color: #6ccd29;
  }
</style>
