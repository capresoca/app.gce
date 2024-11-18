<template>
  <v-container grid-list-md style="max-width: inherit" ref="formConcurrencia">

        <v-form data-vv-scope="formConcurrencias">
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12>
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
                  :disabled="concurrenciaObj.estado === 'Cerrada'"
                  :slot-append='{
                        template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,
                        props: [`value`]
                       }'
                  no-btn-edit
                />
              </v-flex>

              <v-flex xs12>
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
                  :disabled="concurrenciaObj.estado === 'Cerrada'"
                />
              </v-flex>

              <v-flex xs12>
                <v-text-field v-model="concurrencia.consecutivo_ips" :disabled="concurrenciaObj.estado === 'Cerrada'"
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
                  :disabled="concurrenciaObj.estado === 'Cerrada'"
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
                  :items="complementos.especialidades"
                  v-model="concurrencia.rs_especialidad_id"
                  item-text="nombre"
                  item-value="id"
                  name="especialidad"
                  label="Especialidad"
                  no-data-text="No hay especialidades disponibles"
                  :error-messages="errors.collect('especialidad')"
                  v-validate="'required'" required
                  prepend-icon="assignment_turned_in"
                  :disabled="concurrenciaObj.estado === 'Cerrada'"
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
          </v-container>
          <v-divider/>
          <v-list-tile style="margin-top: 15px; margin-bottom: 15px">
            <v-list-tile-avatar color="red">
              <v-icon dark>label_important</v-icon>
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title>
                Ingreso
              </v-list-tile-title>
              <v-list-tile-sub-title>Ingreso</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-divider/>

          <v-container fluid grid-list-md>
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
                    name="fecha de ingreso"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha de ingreso')"
                    :disabled="concurrenciaObj.estado === 'Cerrada'"
                  ></v-text-field>
                  <v-date-picker
                    v-model="fechaRecepcion"
                    :readonly="concurrenciaObj.estado === 'Cerrada'"
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
                    :disabled="concurrenciaObj.estado === 'Cerrada'"
                  ></v-text-field>
                  <v-time-picker
                    v-if="menuTime"
                    v-model="horaRecepcion"
                    :readonly="concurrenciaObj.estado === 'Cerrada'"
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
                  :disabled="concurrenciaObj.estado === 'Cerrada'"
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
                  :disabled="concurrenciaObj.estado === 'Cerrada'"
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
                  :disabled="concurrenciaObj.estado === 'Cerrada'"
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
                  :disabled="concurrenciaObj.estado === 'Cerrada'"
                />
              </v-flex>

            </v-layout>
          </v-container>

        </v-form>
    <v-card-actions>

      <v-spacer/>
      <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit">Guardar</v-btn>
    </v-card-actions>
  </v-container>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import store from '../../../../../store/complementos/index'

  export default {
    name: 'InformacionBasica',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    props: {
      concurrenciaId: {
        type: Number,
        default: 0
      },
      concurrenciaObj: {
        type: Object,
        default: {}
      }
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
        loadingSubmit: false,
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado')
      }
    },
    mounted () {
      this.getRegisto()
    },
    computed: {
      complementos () {
        return store.getters.complementosConcurrencias
      },
      computedDateFormatted () {
        return this.formDate(this.fechaRecepcion)
      }
    },
    methods: {
      getRegisto () {
        let loader = this.$loading.show({
          container: this.$refs.formConcurrencia.$el
        })
        this.axios.get('cuentasmedicas/concurrencia/' + this.concurrenciaId)
          .then((response) => {
            if (response.data !== '') {
              this.concurrencia = response.data.data
              this.splitFechaRecepcionYHora()
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la concurrencia. ' + e.response, color: 'danger'})
          })
      },
      splitFechaRecepcionYHora () {
        let fechaHoraDivididos = this.concurrencia.fecha.split(' ')
        this.fechaRecepcion = fechaHoraDivididos[0]
        this.horaRecepcion = fechaHoraDivididos[1]
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      async submit () {
        if (await this.validador('formConcurrencias')) {
          this.loadingSubmit = true
          this.concurrencia.fecha = `${this.fechaRecepcion} ${this.horaRecepcion}`
          this.axios.put('cuentasmedicas/concurrencia/' + this.concurrencia.id, this.concurrencia)
            .then(response => {
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.loadingSubmit = false
            })
            .catch(e => {
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
