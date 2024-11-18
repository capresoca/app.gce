<template>
  <div>
    <v-dialog v-model="dialog" width="720px">
      <v-form data-vv-scope="formBitacoras">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12 sm4>
                <v-text-field v-model="numeroTutela"
                              label="No. Tutela" key="no. tutela" prepend-icon="description" disabled
                              name="no. tutela" v-validate="'required|max:150'"
                              :error-messages="errors.collect('no. tutela')"></v-text-field>
              </v-flex>

              <v-flex xs12 sm4>
                <v-select
                  :items="tipos"
                  v-model="bitacora.tipo"
                  name="tipo"
                  label="Tipo"
                  :error-messages="errors.collect('tipo')"
                  v-validate="'required'" required
                ></v-select>
              </v-flex>

              <v-flex xs12 sm4>
                <v-menu
                  ref="menuDate"
                  :close-on-content-click="false"
                  v-model="menuDate"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="computedDateFormatted"
                    label="Fecha"
                    prepend-icon="event"
                    readonly
                    name="fecha"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="bitacora.fecha"
                    @input="menuDate = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>
              </v-flex>

              <v-flex xs12 sm6>
                <v-layout align-center fill-height>
                  <v-flex d-flex xs10 sm8>
                    <input-file
                      ref="archivoAdjunto"
                      label="Adjuntar archivo"
                      v-model="bitacora.archivo"
                      :file-name="bitacora.archivo ? bitacora.archivo.nombre : null"
                      accept="application/pdf"
                      :hint="'Extenciones aceptadas: pdf'"
                      class="mb-3"
                      prepend-icon="attach_file"
                    />
                  </v-flex>
                  <v-flex d-flex xs2 sm4>
                    <v-tooltip bottom>
                      <v-btn
                        fab
                        color="success"
                        small
                        :href="bitacora.archivo ? bitacora.archivo.url_signed : ''"
                        target="_blank"
                        slot="activator"
                      >
                        <v-icon>remove_red_eye</v-icon>
                      </v-btn>
                      <span>Ver archivo</span>
                    </v-tooltip>
                  </v-flex>
                </v-layout>
              </v-flex>

              <v-flex xs12>
                <v-textarea v-model="bitacora.observacion"
                            label="Observación" key="observacion" prepend-icon="format_align_left"
                            name="observacion" v-validate="'required'"
                            :error-messages="errors.collect('observacion')"></v-textarea>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close" :loading="loadingSubmit">Cancelar</v-btn>
            <v-btn color="primary" @click="save" :loading="loadingSubmit" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" title="Bitacoras" subtitle="Registro y gestión" btnplus btnplustitle="Crear Bitacora" btnplustruncate @click="dialog = true"/>
      <v-container fluid>
        <loading v-model="loading" />
        <v-data-table
          :headers="headers"
          :items="bitacoras"
          :loading="tableLoading"
          rows-per-page-text="Registros por página"
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ numeroTutela }}</td>
            <td>{{ props.item.tipo }}</td>
            <td>{{ props.item.fecha }}</td>
            <td>{{ props.item.observacion }}</td>
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
                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="edit(props.item)"
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
              Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
            </v-alert>
            <v-alert v-else :value="true" color="info" icon="info">
              Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
            </v-alert>
          </template>
        </v-data-table>
      </v-container>
    </v-card>
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'

  export default {
    name: 'BitacorasDetalle',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading,
      InputFile: () => import('@/components/general/InputFile')
    },
    props: {
      tutelaId: {
        type: Number,
        default: 0
      }
    },
    data () {
      return {
        bitacoras: [],
        bitacora: {},
        tipos: ['Manual', 'Automatica'],
        payload: null,
        numeroTutela: null,
        menuDate: false,
        dialog: false,
        loading: false,
        tableLoading: false,
        loadingSubmit: false,

        headers: [
          {
            text: 'No. tutela',
            align: 'left',
            sortable: false,
            value: 'noTutela'
          },
          {
            text: 'Tipo',
            align: 'left',
            sortable: false,
            value: 'tipo'
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false,
            value: 'fecha'
          },
          {
            text: 'Observación',
            align: 'left',
            sortable: false,
            value: 'observacion'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    created () {
      this.getData()
      this.formReset()
    },
    computed: {
      modalTitulo () {
        return !this.bitacora.id ? 'Nueva Bitacora' : 'Edición'
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('bitacorasDetalle')
      },
      computedDateFormatted () {
        return this.formDate(this.bitacora.fecha)
      }
    },
    methods: {
      getData () {
        this.loading = true
        this.tableLoading = true
        this.axios.get(`oj_tutelas/${this.tutelaId}`)
          .then((response) => {
            if (response.data !== '') {
              this.bitacoras = response.data.data.bitacoras
              this.numeroTutela = response.data.data.no_tutela
              this.loading = false
              this.tableLoading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Error al traer los datos básicos de la bitacora. ' + e.response, color: 'danger'})
          })
      },
      formReset () {
        if (this.bitacora.archivo) this.$refs['archivoAdjunto'].reset()
        this.bitacora = {
          id: '',
          archivo: null,
          oj_tutela_id: this.tutelaId,
          tipo: null,
          fecha: null,
          observacion: null
        }
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      edit (item) {
        this.dialog = true
        this.bitacora = JSON.parse(JSON.stringify(item))
      },
      close () {
        this.dialog = false
        this.loadingSubmit = false
        this.formReset()
        this.$validator.reset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      addFormData () {
        let data = new FormData()
        data.append('id', this.bitacora.id)
        data.append('oj_tutela_id', this.bitacora.oj_tutela_id)
        data.append('fecha', this.bitacora.fecha)
        data.append('tipo', this.bitacora.tipo)
        data.append('observacion', this.bitacora.observacion)

        data.append('archivo', typeof this.bitacora.archivo === 'undefined' ? '' : this.bitacora.archivo)

        this.payload = data
      },
      async save () {
        let validFile = await this.$refs['archivoAdjunto'].validate('formBitacoras')
        if (await this.validador('formBitacoras') && validFile) {
          this.loadingSubmit = true
          this.addFormData()

          let send = !this.bitacora.id ? this.axios.post('oj_tutbitacoras', this.payload) : this.axios.post('oj_tutbitacoras/' + this.bitacora.id, this.payload)
          send.then(response => {
            if (this.bitacora.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'La bitacora se actualizó correctamente', color: 'success'})
              this.bitacoras.splice(this.bitacoras.findIndex(bitacora => bitacora.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'La bitacora se creó correctamente', color: 'success'})
              this.bitacoras.splice(0, 0, response.data.data)
            }
            this.close()
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
            this.close()
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }

    }
  }
</script>

<style scoped>

</style>
