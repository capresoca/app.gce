<template>
  <div>
    <v-card ref="formCum">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12 sm12 md12 lg12>
            <v-form data-vv-scope="formCums">
              <v-container fluid grid-list-md>
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>
                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.expediente"
                                          label="Expediente" key="expediente"
                                          name="expediente" v-validate="'required'" required
                                          :error-messages="errors.collect('expediente')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.consecutivo"
                                          label="Consecutivo" key="consecutivo"
                                          name="consecutivo" v-validate="'required'" required
                                          :error-messages="errors.collect('consecutivo')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.producto"
                                          label="Producto" key="producto"
                                          name="producto" v-validate="'required'" required
                                          :error-messages="errors.collect('producto')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.titular"
                                          label="Titular" key="titular"
                                          name="titular" v-validate="'required'" required
                                          :error-messages="errors.collect('titular')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.descripcion_comercial"
                                          label="Descripción comercial" key="descripcion_comercial"
                                          name="descripcion comercial" v-validate="'required'" required
                                          :error-messages="errors.collect('descripcion comercial')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.unidad"
                                          label="Unidad" key="unidad"
                                          name="unidad" v-validate="'required'" required
                                          :error-messages="errors.collect('unidad')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-select
                              :items="muestraMedica"
                              v-model="cum.muestra_medica"
                              name="muestra medica"
                              label="Muestra médica"
                              :error-messages="errors.collect('muestra medica')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="tiposClasificacion"
                              v-model="cum.clasificacion"
                              name="clasificacion"
                              label="Clasificación"
                              :error-messages="errors.collect('clasificacion')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-subheader class="pa-0 ma-0">Estado CUM</v-subheader>
                            <v-switch class="ma-0 pa-0"  color="accent" :label="cum.estado_cum"
                                      v-model="cum.estado_cum"  true-value="Activo" false-value="Inactivo"></v-switch>
                          </v-flex>
                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>memory</v-icon> Laboratorio</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>
                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.atc"
                                          label="ATC" key="atc"
                                          name="atc" v-validate="'required'" required
                                          :error-messages="errors.collect('atc')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.descripcion_atc"
                                          label="Descripción ATC" key="descripcion_atc"
                                          name="descripcion atc" v-validate="'required'" required
                                          :error-messages="errors.collect('descripcion atc')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-select
                              :items="complementos_cums.concentraciones"
                              v-model="cum.concentracion"
                              label="Concentración"
                              key="concentracion"
                              name="via concentracion"
                              v-validate="'required'"
                              required
                              :error-messages="errors.collect('concentracion')"
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.principio_activo"
                                          label="Principio activo" key="principio_activo"
                                          name="principio activo" v-validate="'required'" required
                                          :error-messages="errors.collect('principio activo')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-combobox
                              v-model="cum.unidad_medida"
                              :items="complementos_cums.unidades_medida"
                              chips
                              height="32"
                              label="Unidad de medida"
                              key="unidad_medida"
                              name="unidad de medida"
                              v-validate="'required'"
                              required
                              :error-messages="errors.collect('unidad de medida')"
                            ></v-combobox>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.cantidad"
                                          label="Cantidad" key="cantidad"
                                          name="cantidad" v-validate="'required'" required
                                          :error-messages="errors.collect('cantidad')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-combobox
                              v-model="cum.unidad_referencia"
                              :items="complementos_cums.unidades_referencia"
                              chips
                              height="32"
                              label="Unidad de referencia"
                              key="unidad_referencia"
                              name="unidad referencia"
                              v-validate="'required'"
                              required
                              :error-messages="errors.collect('unidad referencia')"
                            ></v-combobox>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-combobox
                              v-model="cum.forma_farmaceutica"
                              :items="complementos_cums.formas_farmaceticas"
                              chips
                              height="32"
                              label="Forma farmacéutica"
                              key="forma_farmaceutica"
                              name="forma farmaceutica"
                              v-validate="'required'"
                              required
                              :error-messages="errors.collect('forma farmaceutica')"
                            ></v-combobox>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-menu
                              ref="menuDateExpedicion"
                              :close-on-content-click="false"
                              v-model="menuDateFechaActivo"
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
                                v-model="computedDateFormattedFechaActivo"
                                label="Fecha Activo"
                                prepend-icon="event"
                                readonly
                                name="fecha activo"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha activo')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="cum.fecha_activo"
                                @input="menuDateFechaActivo = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha activo')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                }"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-menu
                              ref="menuDateExpedicion"
                              :close-on-content-click="false"
                              v-model="menuDateFechaInactivo"
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
                                v-model="computedDateFormattedFechaInactivo"
                                label="Fecha Inactivo"
                                prepend-icon="event"
                                readonly
                                name="fecha inactivo"
                                required
                                data-vv-delay="1000"
                                v-validate="'date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha inactivo')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="cum.fecha_inactivo"
                                @input="menuDateFechaInactivo = false"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-combobox
                              v-model="cum.via_administracion"
                              :items="complementos_cums.vias_administracion"
                              chips
                              height="32"
                              label="Vía administración"
                              key="via_administracion"
                              name="via administracion"
                              v-validate="'required'"
                              required
                              :error-messages="errors.collect('via administracion')"
                            ></v-combobox>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.cantidad_cum"
                                          label="Cantidad CUM" key="cantidad_cum"
                                          name="cantidad cum" v-validate="'required'" required
                                          :error-messages="errors.collect('cantidad cum')" ></v-text-field>
                          </v-flex>
                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>search</v-icon> Vigilancia</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>
                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.registro_sanitario"
                                          label="Registro sanitario" key="registro_sanitario"
                                          name="registro_sanitario" v-validate="'required'" required
                                          :error-messages="errors.collect('registro_sanitario')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-select
                              :items="tiposEstadoRegistro"
                              v-model="cum.estado_registro"
                              name="estado registro"
                              label="Estado registro"
                              :error-messages="errors.collect('estado registro')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-menu
                              ref="menuDateExpedicion"
                              :close-on-content-click="false"
                              v-model="menuDateExpedicion"
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
                                v-model="computedDateFormattedFechaExpedicion"
                                label="Fecha Expedición"
                                prepend-icon="event"
                                readonly
                                name="fecha expedición"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha expedicion')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="cum.fecha_expedicion"
                                @input="menuDateExpedicion = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha expedición')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                }"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-menu
                              ref="menuDateExpedicion"
                              :close-on-content-click="false"
                              v-model="menuDateVencimiento"
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
                                v-model="computedDateFormattedFechaVencimiento"
                                label="Fecha Vencimiento"
                                prepend-icon="event"
                                readonly
                                name="fecha vencimiento"
                                required
                                data-vv-delay="1000"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha vencimiento')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="cum.fecha_vencimiento"
                                @input="menuDateVencimiento = false"
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
                      <v-toolbar  dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>person</v-icon> Rol</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>
                          <v-flex xs12 sm4 md4>
                            <v-text-field v-model="cum.nombre_rol"
                                          label="Nombre de rol" key="nombre_rol"
                                          name="nombre de rol" v-validate="'required'" required
                                          :error-messages="errors.collect('nombre de rol')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-select
                              :items="complementos_cums.tipos_rol"
                              v-model="cum.tipo_rol"
                              label="Tipo de rol"
                              key="tipo_rol"
                              name="tipo de rol"
                              v-validate="'required'"
                              required
                              :error-messages="errors.collect('tipo de rol')"
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4 md4>
                            <v-select
                              :items="complementos_cums.modalidades"
                              v-model="cum.modalidad"
                              label="Modalidad"
                              key="modalidad"
                              name="modalidad"
                              v-validate="'required'"
                              required
                              :error-messages="errors.collect('modalidad')"
                            ></v-select>
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
            <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CUMS_RELOAD_ITEM} from '../../../../store/modules/general/tables'

  export default {
    name: 'RegistroCums',
    props: ['parametros'],
    data () {
      return {
        complementos_cums: {},
        cum: {},
        tiposEstadoRegistro: ['Vigente', 'Vencido'],
        tiposClasificacion: ['Formula Magistral', 'Vital No Disponible'],
        muestraMedica: ['Si', 'No'],
        menuDateExpedicion: false,
        menuDateVencimiento: false,
        menuDateFechaActivo: false,
        menuDateFechaInactivo: false,
        loadingSubmit: false
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
    },
    beforeCreate () {
      this.axios.get('complementos_cums')
        .then((res) => {
          this.complementos_cums = res.data
        })
        .catch(e => {
          // console.log('Hay un error. ' + e)
          return false
        })
    },
    computed: {
      tapTitulo () {
        return !this.cum.id ? 'Nuevo Item' : 'Edición de : ' + this.cum.producto
      },
      computedDateFormattedFechaExpedicion () {
        return this.formDate(this.cum.fecha_expedicion)
      },
      computedDateFormattedFechaVencimiento () {
        return this.formDate(this.cum.fecha_vencimiento)
      },
      computedDateFormattedFechaActivo () {
        return this.formDate(this.cum.fecha_activo)
      },
      computedDateFormattedFechaInactivo () {
        return this.formDate(this.cum.fecha_inactivo)
      }
    },
    methods: {
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.formCum.$el
        })
        this.axios.get('rs_cums/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.cum = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la CUM. ' + e.response, color: 'danger'})
          })
      },
      formReset () {
        this.cum = {
          id: '',
          expediente: null,
          producto: null,
          titular: null,
          registro_sanitario: null,
          fecha_expedicion: null,
          fecha_vencimiento: null,
          estado_registro: null,
          expediente_cum: null,
          consecutivo: null,
          cantidad_cum: null,
          descripcion_comercial: null,
          estado_cum: 'Inactivo',
          fecha_activo: null,
          fecha_inactivo: null,
          muestra_medica: null,
          unidad: null,
          atc: null,
          descripcion_atc: null,
          via_administracion: null,
          concentracion: null,
          principio_activo: null,
          unidad_medida: null,
          cantidad: null,
          unidad_referencia: null,
          forma_farmaceutica: null,
          nombre_rol: null,
          tipo_rol: null,
          modalidad: null
        }
        this.$validator.reset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      async submit () {
        if (await this.validador('formCums')) {
          this.loadingSubmit = true
          const typeRequest = this.cum.id ? 'editar' : 'crear'
          let send = !this.cum.id ? this.axios.post('rs_cums', this.cum) : this.axios.put('rs_cums/' + this.cum.id, this.cum)
          send.then(response => {
            if (this.cum.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.$store.commit(CUMS_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.$store.commit(CUMS_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            }
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
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
