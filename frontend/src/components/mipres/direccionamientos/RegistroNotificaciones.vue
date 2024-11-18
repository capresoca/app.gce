<template>
  <v-container
    fluid
    grid-list-sm
    class="pa-0 ma-0"
  >
    <v-dialog v-model="value" persistent max-width="1000px">
      <loading v-model="dialog.loading" />
      <v-card v-if="item">
        <v-toolbar dense>
          <v-toolbar-title>
            <v-list-tile>
              <v-icon left class="mr-2" style="margin-left: -14px !important;">phone_in_talk</v-icon>
              <v-list-tile-content>
                <v-list-tile-title v-if="item.prescripcion || item.tutela">{{item.prescripcion ? item.prescripcion.afiliado.nombre_completo : item.tutela.afiliado.nombre_completo}}</v-list-tile-title>
                <v-list-tile-title v-else>Paciente: {{item.TipoIDPaciente}}{{item.NoIDPaciente}}</v-list-tile-title>
                <v-list-tile-sub-title class="caption">Direccionamiento: {{item.IdDireccionamiento}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-toolbar-title>
          <v-spacer/>
          <v-btn flat icon :disabled="dialog.loading" @click="$emit('input', false)">
            <v-icon>close</v-icon>
          </v-btn>
        </v-toolbar>
        <v-container grid-list-md>
          <v-layout wrap>
            <v-flex xs12 v-if="datosBasicos">
              <v-expansion-panel class="v-expansion-panel-pi">
                <v-expansion-panel-content class="v-expansion-header-dark">
                  <div slot="header">
                    <v-list-tile>
                      <v-icon color="primary" class="mr-2">info</v-icon> Datos básicos del direccionamiento
                    </v-list-tile>
                  </div>
                  <v-container grid-list-sm class="pa-2">
                    <v-layout row wrap>
                      <input-detail-flex
                        v-for="(prop, i) in datosBasicos"
                        :key="`ib_${i}`"
                        v-if="prop.text !== null"
                        :flex-class="prop.flexClass"
                        :label="prop.label"
                        :text="prop.text === 0 ? 'NO' : prop.text === 1 ? 'SI' : prop.text"
                        :hint="prop.hint"
                        :prepend-icon="prop.icon"
                      />
                    </v-layout>
                  </v-container>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-flex>
            <v-flex xs12>
              <v-slide-y-transition>
                <v-card v-if="!newNotificacion" class="elevation-0">
                  <v-card-title class="pa-0">
                    <span class="title">Notificaciones registradas</span>
                    <v-spacer/>
                    <v-tooltip left v-if="infoComponent && infoComponent.permisos && infoComponent.permisos.agregar">
                      <v-btn
                        slot="activator"
                        fab
                        right
                        small
                        color="accent"
                        @click="newNotificacion = true"
                        :href="(item.prescripcion && item.prescripcion.afiliado.celular)  || (item.tutela && item.tutela.afiliado.celular) ? 'tel:' + (item.prescripcion ? item.prescripcion.afiliado.celular : item.tutela.afiliado.celular) : ''"
                      >
                        <v-icon>phone</v-icon>
                      </v-btn>
                      <span v-text="'Crear notificación'"></span>
                    </v-tooltip>
                  </v-card-title>
                  <v-divider/>
                  <v-list two-line v-if="item.notificaciones">
                    <template v-for="(notificacion, ni) in item.notificaciones">
                      <v-list-tile @click="">
                        <v-list-tile-content>
                          <v-list-tile-title class="subheading font-weight-bold">{{notificacion.tipo}}</v-list-tile-title>
                          <p class="text-xs-justify ma-0 mt-1 caption">{{notificacion.observaciones}}</p>
                        </v-list-tile-content>
                        <v-list-tile-action>
                          <v-tooltip left>
                            <v-btn flat small icon :color="notificacion.aceptada ? 'success' : 'danger'" slot="activator">
                              <v-icon>{{notificacion.aceptada ? 'check' : 'close'}}</v-icon>
                            </v-btn>
                            <span>Aceptada</span>
                          </v-tooltip>
                          <v-tooltip left>
                            <v-btn flat small icon :color="notificacion.notificacion_exitosa ? 'success' : 'danger'" slot="activator">
                              <v-icon>{{notificacion.notificacion_exitosa ? 'check' : 'close'}}</v-icon>
                            </v-btn>
                            <span>Exitosa</span>
                          </v-tooltip>
                        </v-list-tile-action>
                      </v-list-tile>
                      <v-divider/>
                    </template>
                  </v-list>
                  <div v-else class="text-xs-center grey--text lighten-2 mt-2">No hay notificaciones registradas</div>
                </v-card>
              </v-slide-y-transition>
              <v-slide-y-transition mide="out-in">
                <v-card v-show="newNotificacion" class="elevation-0 mt-3">
                  <v-card-title class="pa-0">
                    <span class="title">Nueva notificación</span>
                  </v-card-title>
                  <v-layout row wrap>
                    <v-flex xs12 sm6 md6>
                      <v-select
                        label="Tipo notificación"
                        v-model="data.tipo"
                        :items="complementos.tipos"
                        item-value="text"
                        item-text="value"
                        name="tipo"
                        v-validate="'required'"
                        :error-messages="errors.collect('tipo')"
                      />
                    </v-flex>
                    <v-flex dflex>
                      <v-switch  color="accent" label="Aceptada"
                                 v-model="data.aceptada" :true-value="1" :false-value="0"></v-switch>
                    </v-flex>
                    <v-flex dflex>
                      <v-switch  color="accent" label="Exitosa"
                                 v-model="data.notificacion_exitosa" :true-value="1" :false-value="0"></v-switch>
                    </v-flex>
                    <v-flex xs12>
                      <v-textarea
                        label="Observaciones"
                        v-model="data.observaciones"
                        rows="1"
                        auto-grow
                      />
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-slide-y-transition>
            </v-flex>
          </v-layout>
        </v-container>
        <v-divider/>
        <v-card-actions v-if="!newNotificacion">
          <v-spacer/>
          <v-btn :disabled="dialog.loading" @click="$emit('input', false)">Cerrar</v-btn>
        </v-card-actions>
        <v-card-actions v-else>
          <v-btn :disabled="dialog.loading" @click="newNotificacion = false">Cancelar</v-btn>
          <v-spacer/>
          <v-btn
            :loading="dialog.loading"
            :disabled="dialog.loading"
            color="blue darken-1"
            class="white--text"
            @click="submitNotificacion"
          >
            registrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <confirmar
      :value="dialogA.visible"
      :loading="dialogA.loading"
      :content="dialogA.content"
      @cancelar="cancelaAnulacion"
      :requiere-motivo="false"
      @aceptar="aceptaAnulacion"
    />
  </v-container>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import store from '@/store/complementos/index'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroNotificaciones',
    props: {
      item: {
        type: Object,
        default: null
      },
      value: {
        type: Boolean,
        default: false
      }
    },
    components: {
      Loading,
      Confirmar: () => import('@/components/general/Confirmar'),
      InputDate: () => import('@/components/general/InputDate'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex')
    },
    data: () => ({
      dialogA: {
        loading: false,
        visible: false,
        content: null,
        item: null
      },
      newNotificacion: false,
      datosBasicos: [],
      dialog: null,
      data: null,
      makeDialog: {
        show: false,
        loading: false
      },
      makeData: {
        id: null,
        mp_direccionamiento_id: null,
        tipo: null,
        notificacion_exitosa: 0,
        aceptada: 0,
        observaciones: null
      }
    }),
    watch: {
      'value' (val) {
        if (!val) {
          setTimeout(() => this.resetAll(), 500)
        }
      },
      'newNotificacion' (val) {
        if (!val) {
          setTimeout(() => this.resetData(), 500)
        }
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosRegistroNotificaionDireccionamiento
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('direccionamientos')
      }
    },
    created () {
      this.resetAll()
    },
    methods: {
      resetAll () {
        this.newNotificacion = false
        this.dialog = JSON.parse(JSON.stringify(this.makeDialog))
        this.resetData()
      },
      resetData () {
        this.data = JSON.parse(JSON.stringify(this.makeData))
        this.$validator.reset()
      },
      anularDireccionamiento (item) {
        this.dialogA.item = JSON.parse(JSON.stringify(item))
        this.dialogA.content = `El direccionamiento No. ${this.dialogA.item.item.IdDireccionamiento} será eliminado y el registro no se podrá ver ni cambiar de estado. </br><strong>¿Está seguro de continuar?</strong>`
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.loading = false
        this.dialogA.content = null
        this.dialogA.item = null
      },
      async aceptaAnulacion () {
        this.dialogA.loading = true
        this.axios.delete(`direccionamientos/${this.dialogA.item.item.id}`)
          .then(() => {
            this.$store.commit(SNACK_SHOW, {msg: `El direccionamiento se anuló correctamente.`, color: 'success'})
            this.item.direccionamientos.splice(this.dialogA.item.index, 1)
            this.cancelaAnulacion()
          })
          .catch(e => {
            let serverError = ''
            if (e.response.data.message && (e.response.data.message.ModelState || e.response.data.message.Message)) {
              e.response.data.errors = e.response.data.message.ModelState
              serverError = ' en el servidor MIPRES'
            }
            this.dialogA.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: `${serverError} al realizar la anulación del direccionamiento. `, error: e})
          })
      },
      submitNotificacion () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.dialog.loading = true
            this.data.mp_direccionamiento_id = this.item.id
            this.axios.post('notificaciones-mipres', this.data)
              .then(response => {
                console.log('response de notificacion', response)
                this.item.notificaciones.push(response.data.data)
                this.$store.commit(SNACK_SHOW, {msg: `La notificación se registró satisfactoriamente.`, color: 'success'})
                this.dialog.loading = false
                this.resetAll()
              })
              .catch(e => {
                let serverError = ''
                if (e.response.data.message && (e.response.data.message.ModelState || e.response.data.message.Message)) {
                  e.response.data.errors = (e.response.data.message.Errors && e.response.data.message.Errors.length) ? Object.assign({}, e.response.data.message.Errors) : e.response.data.message.ModelState
                  serverError = ' en el servidor MIPRES'
                }
                this.dialog.loading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: `${serverError} al realizar el registro de la notificaión. `, error: e})
              })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
