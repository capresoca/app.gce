<template>
  <div>
    <v-card ref="formPortabilidad">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12 sm12 md12 lg12>
            <v-form data-vv-scope="formPortabilidades">
              <v-container fluid grid-list-md>
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Portabilidad</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4>
                            <v-menu
                              ref="menuDateSolicitud"
                              :close-on-content-click="false"
                              v-model="menuDateSolicitud"
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
                                v-model="computedDateFormattedSolicitud"
                                label="Fecha solicitud"
                                prepend-icon="event"
                                readonly
                                name="fecha solicitud"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha solicitud')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="portabilidad.fecha_solicitud"
                                @input="menuDateSolicitud = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha solicitud')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                }"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-menu
                              ref="menuDateInicio"
                              :close-on-content-click="false"
                              v-model="menuDateInicio"
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
                                v-model="computedDateFormattedInicio"
                                label="Fecha inicio portabilidad"
                                prepend-icon="event"
                                readonly
                                name="inicio portabilidad"
                                required
                                data-vv-delay="1000"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('inicio portabilidad')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="portabilidad.fecha_inicio"
                                @input="menuDateInicio = false"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-menu
                              ref="menuDateFinalizacion"
                              :close-on-content-click="false"
                              v-model="menuDateFinalizacion"
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
                                v-model="computedDateFormattedFinalizacion"
                                label="Fecha finalización"
                                prepend-icon="event"
                                readonly
                                name="fecha finalización"
                                required
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha finalización')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="portabilidad.fecha_fin"
                                @input="menuDateFinalizacion = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha finalización')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                }"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>person</v-icon> Información Básica</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>
                          <v-flex xs12 sm8>
                            <postulador-v2
                              no-data="Busqueda por nombre o número de documento."
                              hint="identificacion_completa"
                              item-text="nombre_completo"
                              data-title="identificacion_completa"
                              data-subtitle="nombre_completo"
                              label="Afiliado"
                              entidad="afiliados"
                              preicon="person"
                              v-model="portabilidad.afiliado"
                              @changeid="val => portabilidad.as_afiliado_id = val"
                              name="Afiliado"
                              :rules="'required|afiliadoPortable'"
                              v-validate="'required|afiliadoPortable'"
                              :error-messages="errors.collect('Afiliado')"
                              :slot-append='{
                                template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,
                                props: [`value`]
                               }'
                              :slot-prepend='portabilidad.afiliado ? {
                                template:`<mini-card-detail :data="value.mini_afiliado" alone-icon/>`,
                                props: [`value`]
                               } : null'
                              no-btn-edit
                              clearable
                            />
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-autocomplete
                              label="Municipio receptor"
                              v-model="portabilidad.munreceptor_id"
                              :items="complementosMunicipios.municipios"
                              item-value="id"
                              item-text="nombre"
                              name="municipio"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('municipio')"
                              :filter="filterMunicipios">

                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.nombre_departamento"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm3>
                            <v-text-field v-model="portabilidad.direccion"
                                          label="Nueva Dirección" key="nueva dirección" required
                                          name="nueva dirección" v-validate="'required'" required
                                          :error-messages="errors.collect('nueva dirección')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm3>
                            <v-text-field v-model="portabilidad.telefono"
                                          label="Nuevo Teléfono" key="teléfono"
                                          name="nuevo teléfono" v-validate="'required'" required
                                          :error-messages="errors.collect('nuevo teléfono')" ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-text-field v-model="portabilidad.email"
                                          label="Correo Electrónico" key="correo electrónico"
                                          name="correo electrónico" v-validate="'email'"
                                          :error-messages="errors.collect('correo electrónico')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm3>
                            <v-layout align-center fill-height>
                              <v-flex d-flex xs10 sm8>
                                <input-file
                                  ref="archivoAdjunto"
                                  label="Carta solicitud"
                                  v-model="portabilidad.archivo"
                                  :file-name="portabilidad.archivo ? portabilidad.archivo.nombre : null"
                                  accept="application/pdf"
                                  :hint="'Extenciones aceptadas: pdf'"
                                  class="mb-3"
                                  prepend-icon="attach_file"
                                />
                              </v-flex>
                              <v-flex d-flex xs2 sm4 v-if="portabilidad.archivo && portabilidad.archivo.url_signed">
                                <v-tooltip bottom>
                                  <v-btn
                                    fab
                                    color="success"
                                    small
                                    :href="portabilidad.archivo ? portabilidad.archivo.url_signed : ''"
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
                          <v-flex xs12 sm12 md12 class="pt-1">
                            <v-textarea
                              label="Motivo"
                              v-model="portabilidad.motivo"
                              name="motivo"
                              key="motivo"
                              v-validate="'required'"
                              :error-messages="errors.collect('motivo')"
                              rows="1"
                              auto-grow
                            >
                            </v-textarea>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                </v-layout>
              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="formReset" :loading="loadingSubmit">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit" :disabled="errors.any()">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import store from '../../../../store/complementos/index'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {PORTABILIDAD_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import { Validator } from 'vee-validate'
  export default {
    name: 'RegistroPortabilidad',
    props: ['parametros'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputFile: () => import('@/components/general/InputFile')
    },
    data () {
      return {
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
        portabilidad: {},
        portabilidades: [],
        menuDateSolicitud: false,
        menuDateInicio: false,
        menuDateFinalizacion: false,
        loadingSubmit: false,
        payload: null
      }
    },
    beforeMount () {
      Validator.extend('afiliadoPortable', {
        validate: (value) => new Promise((resolve) => {
          function getRange (fechaActual, val) {
            return (fechaActual >= val.fecha_inicio) && (fechaActual <= val.fecha_fin)
          }

          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response
            let fechaActual = this.moment().format('YYYY-MM-DD')
            let itemsAceptados = this.portabilidades.filter(val => (val.id_afiliado === value.id) && (getRange(fechaActual, val)) && (val.estado === 'Aceptado'))
            let itemsRegistrados = this.portabilidades.filter(val => (val.id_afiliado === value.id) && (getRange(fechaActual, val)) && (val.estado === 'Registrado'))
            response = (itemsRegistrados.length)
              ? {valido: false, mensaje: 'El afiliado cuenta con una solicitud de portabilidad en la fecha actual.'}
              : (itemsAceptados.length)
                ? {valido: false, mensaje: 'El afiliado cuenta con una portabilidad aceptada en la fecha actual.'}
                : {valido: true, mensaje: null}
            return resolve({valid: response.valido, data: {message: response.mensaje}})
          }
        }),
        getMessage: (field, params, data) => data.message
      })
      this.formReset()
    },
    mounted () {
      if (this.parametros.entidad !== null) {
        this.getRegisto(this.parametros.entidad.id)
      }
      this.getAllPortabilidades()
    },
    computed: {
      tapTitulo () {
        return !this.portabilidad.id ? 'Nuevo Item' : 'Edición de : ' + this.portabilidad.id
      },
      computedDateFormattedSolicitud () {
        return this.formDate(this.portabilidad.fecha_solicitud)
      },
      computedDateFormattedInicio () {
        return this.formDate(this.portabilidad.fecha_inicio)
      },
      computedDateFormattedFinalizacion () {
        return this.formDate(this.portabilidad.fecha_fin)
      },
      complementosMunicipios () {
        let beforeComplement = JSON.parse(JSON.stringify(store.getters.complementosTercerosFormBasicos))

        return beforeComplement
      }
    },
    methods: {
      getAllPortabilidades () {
        this.axios.get('portabilidades')
          .then((response) => {
            console.log('response', response)
            this.portabilidades = response.data.data
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay error al traer las portabilidades. ', color: 'danger'})
          })
      },
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.formPortabilidad.$el
        })
        this.axios.get('portabilidades/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.portabilidad = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la portabilidad. ' + e.response, color: 'danger'})
          })
      },
      formReset () {
        this.portabilidad = {
          id: null,
          as_afiliado_id: null,
          fecha_solicitud: null,
          fecha_inicio: null,
          fecha_fin: null,
          munreceptor_id: null,
          direccion: null,
          telefono: null,
          archivo: null,
          motivo: null,
          email: null
        }
        this.$validator.reset()
      },
      addFormData () {
        let data = new FormData()
        data.append('id', this.portabilidad.id)
        data.append('as_afiliado_id', this.portabilidad.as_afiliado_id)
        data.append('fecha_solicitud', this.portabilidad.fecha_solicitud)
        data.append('fecha_inicio', this.portabilidad.fecha_inicio)
        data.append('fecha_fin', this.portabilidad.fecha_fin)
        data.append('munreceptor_id', this.portabilidad.munreceptor_id)
        data.append('direccion', this.portabilidad.direccion)
        data.append('telefono', this.portabilidad.telefono)
        data.append('email', this.portabilidad.email)
        data.append('motivo', this.portabilidad.motivo)
        data.append('archivo', typeof this.portabilidad.archivo === 'undefined' ? '' : this.portabilidad.archivo)

        this.payload = data
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      filterMunicipios (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      async submit () {
        if (await this.validador('formPortabilidades')) {
          this.loadingSubmit = true
          this.addFormData()

          const typeRequest = this.portabilidad.id ? 'editar' : 'crear'
          let send = !this.portabilidad.id ? this.axios.post('portabilidades', (this.portabilidad.archivo !== null) ? this.payload : this.portabilidad)
            : this.axios.post('portabilidades/' + this.portabilidad.id, (this.portabilidad.archivo !== null) ? this.payload : this.portabilidad)
          send.then(response => {
            this.$store.commit(SNACK_SHOW, {
              msg: `Se ${this.portabilidad.id
                ? 'actualizaron los datos'
                : 'realizó el registro correctamente'}`,
              color: 'success'
            })
            this.$store.commit(PORTABILIDAD_RELOAD_ITEM, {item: response.data.portabilidad_o, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e, color: 'danger'})
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
