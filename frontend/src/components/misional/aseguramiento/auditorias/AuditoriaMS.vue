<template>
  <div>
    <v-dialog v-model="dialogProceso" persistent width="350px">
      <v-card>
        <v-card-title class="grey lighten-3"><span class="title" v-text="'Respuesta Proceso'"></span></v-card-title>
        <v-container fluid grid-list-xl>
          <v-layout row wrap>
            <v-flex xs12 v-for="(propiedad, attribute, index) in logProceso" :key="`Log-${index}`">
              <v-text-field
                :value="propiedad"
                :key="`TwoLog-${index}`"
                :label="attribute"
                placeholder="Placeholder"
                readonly
                box
              ></v-text-field>
            </v-flex>
          </v-layout>
        </v-container>
        <v-card-actions class="grey lighten-3">
          <v-spacer></v-spacer>
          <v-btn color="black lighten-3" flat medium @click.prevent="dialogProceso = false" v-text="'Cerrar'"></v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="dialogLoader"
      hide-overlay
      persistent
      width="300"
    >
      <v-card
        color="primary"
        dark
      >
        <v-card-text>
          <span v-text="message"></span>
          <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialog" persistent max-width="500px">
      <v-card>
        <toolbar-list title="Cargar de Archivo MS"/>
          <v-form data-vv-scope="formArchivoMS">
            <v-container fluid grid-list-xl>
              <v-flex xs12>
                <v-select
                  :items="tipos"
                  prepend-icon="label_important"
                  v-model="msAuditoria.tipo"
                  label="Tipo"
                  name="tipo"
                  key="tipo"
                  :error-messages="errors.collect('tipo')"
                  v-validate="'required'"
                >
                </v-select>
              </v-flex>
              <v-flex xs12>
                <input-file
                  ref="archivoAdjunto"
                  label="Adjuntar Archivo"
                  v-model="msAuditoria.archivo"
                  :file-name="msAuditoria.archivo ? msAuditoria.archivo.nombre : null"
                  accept="application/x-zip-compressed"
                  :hint="'Extenciones aceptadas: zip'"
                  class="mb-3"
                  prepend-icon="attach_file"
                />
              </v-flex>
            </v-container>
          </v-form>
        <v-card-actions class="grey lighten-3">
          <v-spacer></v-spacer>
          <v-btn flat @click="close">Cancelar</v-btn>
          <v-btn color="blue darken-1" @click.prevent="validar()" flat>Cargar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent"  btnplustruncate @click="dialog = true" btnplus btnplustitle="Cargar Archivo" title="Auditoría MS" subtitle="Gestión"/>
      <data-table
        v-model="dataTableMsauditoria"
        @resetOption="item => resetOptions(item)"
        @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
    </v-card>
  </div>
</template>

<script>
  import {MS_AUDITORIA_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import InputFile from '../../../general/InputFile'
  export default {
    name: 'AuditoriaMS',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable'),
      InputFile
    },
    data () {
      return {
        dataTableMsauditoria: {
          nameItemState: 'itemMsAuditoria',
          route: 'maestroafiliados',
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
              text: 'Fecha Archivo',
              align: 'center',
              sortable: false,
              value: 'fecha_archivo',
              classData: 'text-xs-center',
              component: {
                component: {
                  template: `
                    <div>
                      <span class="text-xs-center">
                        {{ moment(value.fecha_archivo, 'DDMMYYYY').format('DD/MM/YYYY') }}
                      </span>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Nombre Archivo',
              align: 'left',
              sortable: false,
              value: 'nombre_archivo'
            },
            {
              text: 'Número de Filas',
              align: 'center',
              sortable: false,
              value: 'numero_filas',
              classData: 'text-xs-center'
            },
            {
              text: 'Usuario',
              align: 'left',
              sortable: false,
              value: 'usuario.name',
              classData: 'text-xs-left'
            },
            {
              text: 'Tipo',
              align: 'left',
              sortable: false,
              value: 'tipo',
              classData: 'text-xs-left'
            }
          ]
        },
        dialog: false,
        dialogLoader: false,
        dialogProceso: false,
        msAuditoria: {},
        nombre_archivo: null,
        message: null,
        logProceso: null
      }
    },
    watch: {
    //   'dialog' (value) {
    //     if (value === false) this.formReset()
    //   },,
    // {
    //   text: 'Opciones',
    //     align: 'center',
    //   sortable: false,
    //   actions: true,
    //   classData: 'justify-center layout px-0'
    // }
      'dialogProceso' (value) {
        if (value === false) this.logProceso = null
      },
      'dialog' (value) {
        if (value === false) this.$validator.reset()
      },
      'dialogLoader' (value) {
        if (value === false) this.message = null
      },
      'msAuditoria.archivo' (value) {
        if (value) {
          this.nombre_archivo = this.$refs.archivoAdjunto.namesArchivos
        }
        // console.log('val', value)
        // let file = new File()
        // console.log('jajs', this.$refs.archivoAdjunto.namesArchivos)
      }
    },
    mounted () {
      this.formReset()
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('auditoriaMS')
      },
      tipos () {
        return ['Subsidiado', 'Contributivo']
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        return item
      },
      formReset () {
        this.msAuditoria = {
          tipo: null,
          archivo: null
        }
        this.nombre_archivo = null
      },
      close () {
        this.$validator.reset()
        this.formReset()
        this.dialog = false
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async validar () {
        let validateFile = await this.$refs.archivoAdjunto.validate()
        if (await this.validador('formArchivoMS') && validateFile && (this.msAuditoria.archivo !== null)) {
          this.dialogLoader = true
          this.message = 'Validando archivo...'
          this.axios.get('maestroafiliados/' + this.nombre_archivo)
            .then(res => {
              console.log('respuesta', res)
              if (res.data.exists === false) {
                this.dialog = false
                this.message = res.data.msg
                this.save()
              } else {
                this.close()
                this.dialogLoader = false
                this.message = res.data.msg
                this.$store.commit(SNACK_SHOW, {msg: res.data.msg, color: 'danger'})
              }
              // console.log('respuesta 2Q1W2Q', res)
            }).catch(e => {
              this.close()
              this.dialogLoader = false
              this.$store.commit(SNACK_SHOW, {msg: e.response.msg, color: 'danger'})
            })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Se debe cargar un archivo para iniciar el proceso.', color: 'danger'})
        }
      },
      save () {
        let formData = new FormData()
        formData.append('tipo', this.msAuditoria.tipo)
        if (this.msAuditoria.archivo) formData.append('archivo', typeof this.msAuditoria.archivo === 'undefined' ? '' : this.msAuditoria.archivo)
        this.axios.post('maestroafiliados/store', formData)
          .then(response => {
            this.$store.commit(SNACK_SHOW, {msg: 'El proceso se realizo correctamente.', color: 'success'})
            this.$store.commit(MS_AUDITORIA_RELOAD_ITEM, {
              item: response.data.proceso,
              estado: 'reload',
              key: null
            })
            this.logProceso = response.data.log
            this.dialogProceso = true
            this.dialogLoader = false
            this.close()
            // console.log('response-save', response)
          }).catch(e => {
            this.dialogLoader = false
            this.$store.commit(SNACK_SHOW, {msg: e.response.msg, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
