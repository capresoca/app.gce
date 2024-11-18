<template>
  <div>
    <v-dialog v-model="dialog" width="720px">
      <v-form data-vv-scope="formFallos">
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
                <v-text-field v-model="fallo.no_fallo"
                              label="No. Fallo" key="no. fallo"
                              name="no. fallo" v-validate="'required|max:150'"
                              :error-messages="errors.collect('no. fallo')"></v-text-field>
              </v-flex>

              <v-flex xs12 sm4>
                <v-select
                  :items="instancias"
                  v-model="fallo.instancia"
                  name="instancia"
                  label="Instancia"
                  :error-messages="errors.collect('instancia')"
                  v-validate="'required'" required
                ></v-select>
              </v-flex>

              <v-flex xs12 sm4>
                <v-autocomplete
                  label="Juzgado"
                  v-model="fallo.oj_juzgado_id"
                  :items="complementos.juzgados"
                  item-value="id"
                  item-text="nombre"
                  name="juzgado"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('juzgado')"
                  clearable>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
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
                    v-model="fallo.fecha"
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

              <v-flex xs12 sm4>
                <v-select
                  :items="fallosIntegrales"
                  v-model="fallo.fallo_integral"
                  label="Fallo integral"
                  name="fallo integral"
                  :error-messages="errors.collect('fallo integral')"
                  v-validate="'required'" required
                ></v-select>
              </v-flex>

              <v-flex xs12 sm4>
                <v-select
                  :items="tiposFallos"
                  v-model="fallo.tipo_fallo"
                  label="Tipo fallo"
                  name="tipo fallo"
                  :error-messages="errors.collect('tipo fallo')"
                  v-validate="'required'" required
                ></v-select>
              </v-flex>

              <v-flex xs12 sm6>
                <v-layout align-center fill-height>
                  <v-flex d-flex xs10 sm8>
                    <input-file
                      ref="archivoAdjunto"
                      label="Adjuntar archivo"
                      v-model="fallo.archivo"
                      :file-name="fallo.archivo ? fallo.archivo.nombre : null"
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
                        :href="fallo.archivo ? fallo.archivo.url_signed : ''"
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
                <v-textarea v-model="fallo.desc_fallo"
                              label="Descripción" key="descFallo" prepend-icon="format_align_left"
                              name="descripcion fallo" v-validate="'required'"
                              :error-messages="errors.collect('descripcion fallo')"></v-textarea>
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
      <toolbar-list :info="infoComponent" title="Fallos" subtitle="Registro y gestión" btnplus btnplustitle="Crear Fallo" btnplustruncate @click="dialog = true"/>
      <v-container fluid>
        <loading v-model="loading" />
        <v-data-table
          :headers="headers"
          :items="fallos"
          :loading="tableLoading"
          rows-per-page-text="Registros por página"
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ numeroTutela  }}</td>
            <td>{{ props.item.instancia}}</td>
            <td>{{ props.item.fecha}}</td>
            <td>{{ props.item.fallo_integral}}</td>
            <td>{{ props.item.tipo_fallo}}</td>
            <td>{{ props.item.desc_fallo}}</td>
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
  import store from '../../../../../store/complementos/index'
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'

  export default {
    name: 'FallosDetalle',
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
        fallos: [],
        fallo: {},
        instancias: ['Primera Instancia', 'Segunda Instancia'],
        fallosIntegrales: ['Si', 'No'],
        tiposFallos: ['A Favor', 'En Contra'],
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
            text: 'Instancia',
            align: 'left',
            sortable: false,
            value: 'instancia'
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false,
            value: 'fecha'
          },
          {
            text: 'Fallo integral',
            align: 'left',
            sortable: false,
            value: 'falloIntegral'
          },
          {
            text: 'Tipo fallo',
            align: 'left',
            sortable: false,
            value: 'tipo fallo'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
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
      complementos () {
        return store.getters.complementosTutelas
      },
      modalTitulo () {
        return !this.fallo.id ? 'Nuevo Fallo' : 'Edición'
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('fallosDetalle')
      },
      computedDateFormatted () {
        return this.formDate(this.fallo.fecha)
      }
    },
    methods: {
      getData () {
        this.loading = true
        this.tableLoading = true
        this.axios.get(`oj_tutelas/${this.tutelaId}`)
          .then((response) => {
            if (response.data !== '') {
              this.fallos = response.data.data.fallos
              this.numeroTutela = response.data.data.no_tutela
              this.loading = false
              this.tableLoading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Error al traer los datos básicos del fallo. ' + e.response, color: 'danger'})
          })
      },
      formReset () {
        if (this.fallo.archivo) this.$refs['archivoAdjunto'].reset()
        this.fallo = {
          id: '',
          archivo: null,
          oj_tutela_id: this.tutelaId,
          no_fallo: null,
          instancia: null,
          oj_juzgado_id: null,
          fecha: null,
          fallo_integral: null,
          tipo_fallo: null,
          desc_fallo: null
        }
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      edit (item) {
        this.dialog = true
        this.fallo = JSON.parse(JSON.stringify(item))
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
        data.append('id', this.fallo.id)
        data.append('oj_tutela_id', this.fallo.oj_tutela_id)
        data.append('no_fallo', this.fallo.no_fallo)
        data.append('instancia', this.fallo.instancia)
        data.append('oj_juzgado_id', this.fallo.oj_juzgado_id)
        data.append('fecha', this.fallo.fecha)
        data.append('fallo_integral', this.fallo.fallo_integral)
        data.append('tipo_fallo', this.fallo.tipo_fallo)
        data.append('desc_fallo', this.fallo.desc_fallo)

        data.append('archivo', typeof this.fallo.archivo === 'undefined' ? '' : this.fallo.archivo)

        this.payload = data
      },
      async save () {
        let validFile = await this.$refs['archivoAdjunto'].validate('formFallos')
        if (await this.validador('formFallos') && validFile) {
          this.loadingSubmit = true
          this.addFormData()

          let send = !this.fallo.id ? this.axios.post('oj_tutfallos', this.payload) : this.axios.post('oj_tutfallos/' + this.fallo.id, this.payload)
          send.then(response => {
            if (this.fallo.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'El fallo se actualizó correctamente', color: 'success'})
              this.fallos.splice(this.fallos.findIndex(fallo => fallo.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'El fallo se creó correctamente', color: 'success'})
              let respuesta = response.data.data
              respuesta.oj_juzgado_id = parseInt(respuesta.oj_juzgado_id)
              this.fallos.splice(0, 0, response.data.data)
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
