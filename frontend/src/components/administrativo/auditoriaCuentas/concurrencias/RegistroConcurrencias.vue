<template>
  <div>
    <v-card ref="formConcurrencia">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12 sm12 md12 lg12>
            <v-form data-vv-scope="formConcurrencias">
              <v-container fluid grid-list-md>
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>person</v-icon> Afiliado</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <!-- <v-flex xs12 sm4>
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
                                v-validate="'date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="concurrencia.fecha"
                                @input="menuDate = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                }"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex> -->

                          <v-flex xs12 sm8>
                            <postulador-v2
                              no-data="Busqueda por nombre o número de documento."
                              hint="identificacion_completa"
                              item-text="nombre_completo"
                              data-title="identificacion_completa"
                              data-subtitle="nombre_completo"
                              label="Afiliado"
                              :detail="detalleAfiliado"
                              entidad="afiliados"
                              preicon="person"
                              v-model="concurrencia.afiliado"
                              @changeid="val => concurrencia.as_afiliado_id = val"
                              name="Afiliado"
                              :rules="'required'"
                              v-validate="'required'"
                              :error-messages="errors.collect('Afiliado')"
                              :slot-append='{
                                template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,
                                props: [`value`]
                               }'
                              no-btn-edit
                            />
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>
                          <v-flex xs12 sm6>
                            <postulador-v2
                              no-data="Busqueda por NIT, razon social u código de habilitación."
                              hint="tercero.identificacion_completa"
                              item-text="tercero.nombre_completo"
                              data-title="tercero.identificacion_completa"
                              data-subtitle="tercero.nombre_completo"
                              label="IPS"
                              entidad="entidades"
                              preicon="location_city"
                              @changeid="val => concurrencia.rs_entidad_id = val"
                              v-model="concurrencia.entidad"
                              name="IPS"
                              no-btn-create
                              rules="required"
                              v-validate="'required'"
                              :error-messages="errors.collect('IPS')"
                            />
                          </v-flex>

                          <v-flex xs12 sm6>
                            <v-text-field v-model="concurrencia.consecutivo_ips"
                                          label="Consecutivo IPS" key="consecutivo ips"
                                          name="consecutivo ips" v-validate="'required'" required
                                          prepend-icon="subtitles"
                                          :error-messages="errors.collect('consecutivo ips')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12>
                            <v-autocomplete
                              :items="complementos.rsServicios"
                              v-model="concurrencia.rs_servicio_id"
                              item-text="nombre"
                              item-value="id"
                              name="servicio"
                              label="Servicio"
                              no-data-text="No hay servicios disponibles"
                              :error-messages="errors.collect('servicio')"
                              v-validate="'required'" required
                              prepend-icon="contacts"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.codigo"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12>
                            <v-autocomplete
                              :items="complementos.rsServicios"
                              v-model="concurrencia.rs_especialidad_id"
                              item-text="nombre"
                              item-value="id"
                              name="especialidad"
                              label="Especialidad"
                              no-data-text="No hay especialidades disponibles"
                              :error-messages="errors.collect('especialidad')"
                              v-validate="'required'" required
                              prepend-icon="assignment_turned_in"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.codigo"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>


                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>label_important</v-icon> Ingreso</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

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
                                label="Fecha de ingreso"
                                prepend-icon="event"
                                readonly
                                name="fecha"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="fechaRecepcion"
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
                            <v-menu
                              ref="menuTime"
                              :close-on-content-click="false"
                              v-model="menuTime"
                              :nudge-right="40"
                              :return-value.sync="horaRecepcion"
                              lazy
                              transition="scale-transition"
                              offset-y
                              full-width
                              max-width="290px"
                              min-width="290px"
                            >
                              <v-text-field
                                slot="activator"
                                v-model="horaRecepcion"
                                label="Hora de ingreso"
                                prepend-icon="access_time"
                                readonly
                                required
                                name="hora ingreso"
                                v-validate="'required'"
                                :error-messages="errors.collect('hora ingreso')"
                              ></v-text-field>
                              <v-time-picker
                                v-if="menuTime"
                                v-model="horaRecepcion"
                                full-width
                                @change="$refs.menuTime.save(horaRecepcion)"
                              ></v-time-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="tiposViaIngreso"
                              v-model="concurrencia.via_ingreso"
                              label="Via de ingreso"
                              name="via de ingreso"
                              prepend-icon="input"
                              :error-messages="errors.collect('via de ingreso')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>


                          <v-flex xs12 v-if="concurrencia.via_ingreso === 'Remitido'">
                            <postulador-v2
                              no-data="Busqueda por NIT, razon social u código de habilitación."
                              hint="tercero.identificacion_completa"
                              item-text="tercero.nombre_completo"
                              data-title="tercero.identificacion_completa"
                              data-subtitle="tercero.nombre_completo"
                              label="IPS Origen"
                              entidad="entidades"
                              preicon="location_city"
                              @changeid="val => concurrencia.rs_entorigen_id = val"
                              v-model="concurrencia.entidad_origen"
                              name="IPS"
                              no-btn-create
                              rules="required"
                              v-validate="'required'"
                              :error-messages="errors.collect('IPS')"
                            />
                          </v-flex>

                          <v-flex xs12>
                            <postulador-v2
                              no-data="Busqueda por código, descripcion y genero"
                              hint="codigo"
                              item-text="descripcion"
                              data-title="codigo"
                              data-subtitle="descripcion"
                              label="Diagnóstico Principal"
                              entidad="rs_cie10s"
                              preicon="reorder"
                              @changeid="val => concurrencia.rs_cie10_ingreso = val"
                              v-model="concurrencia.diagnostico_ingreso"
                              name="diagnóstico"
                              rules="required"
                              v-validate="'required'"
                              :error-messages="errors.collect('diagnóstico')"
                              no-btn-create
                              :min-characters-search="3" clearable
                            />
                          </v-flex>

                          <v-flex xs12>
                            <postulador-v2
                              no-data="Busqueda por código, descripcion y genero"
                              hint="codigo"
                              item-text="descripcion"
                              data-title="codigo"
                              data-subtitle="descripcion"
                              label="Diagnóstico Relacionado (opcional)"
                              entidad="rs_cie10s"
                              preicon="reorder"
                              @changeid="val => concurrencia.rs_cie10_relacionado = val"
                              v-model="concurrencia.diagnostico_relacionado"
                              name="diagnóstico"
                              no-btn-create
                              :min-characters-search="3" clearable
                            />
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
          <v-flex xs12 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CONCURRENCIAS_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import store from '../../../../store/complementos/index'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'RegistroConcurrencias',
    props: ['parametros'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      Loading
    },
    data () {
      return {
        concurrencia: {},
        tiposViaIngreso: ['Consulta Externa', 'Urgencias', 'Remitido'],
        menuDate: false,
        menuTime: false,
        fechaRecepcion: null,
        horaRecepcion: null,
        loading: false,
        window: 0,
        loadingSubmit: false,
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado')
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
    },
    computed: {
      complementos () {
        return store.getters.complementosConcurrencias
      },
      tapTitulo () {
        return 'Nuevo Item'
      },
      computedDateFormatted () {
        return this.formDate(this.fechaRecepcion)
      }
    },
    methods: {
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.formConcurrencia.$el
        })
        this.axios.get('cuentasmedicas/concurrencia/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.concurrencia = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la concurrencia. ' + e.response, color: 'danger'})
          })
      },
      formReset () {
        this.concurrencia = {
          id: '',
          consecutivo: null,
          consecutivo_ips: null,
          fecha: null,
          estado: null,
          as_afiliado_id: null,
          rs_entidad_id: null,
          rs_servicio_id: null,
          rs_especialidad_id: null
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
        if (await this.validador('formConcurrencias')) {
          this.loadingSubmit = true
          this.concurrencia.fecha = `${this.fechaRecepcion} ${this.horaRecepcion}`
          const typeRequest = this.concurrencia.id ? 'editar' : 'crear'
          let send = !this.concurrencia.id ? this.axios.post('cuentasmedicas/concurrencia', this.concurrencia) : this.axios.put('cuentasmedicas/concurrencia/' + this.concurrencia.id, this.concurrencia)
          send.then(response => {
            if (this.concurrencia.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.$store.commit(CONCURRENCIAS_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.$store.commit(CONCURRENCIAS_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            }
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro. ', error: e})
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
