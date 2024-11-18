<template>
  <v-container grid-list-md style="max-width: inherit" ref="formEgreso">

    <v-form data-vv-scope="formEgresos">
      <v-container fluid grid-list-md>
        <v-layout row wrap>

          <v-flex xs12 sm4>
            <v-select
              :items="tiposEstadoSalida"
              v-model="egreso.estado_salida"
              label="Estado de salida"
              name="estado de salida"
              prepend-icon="format_textdirection_r_to_l"
              :error-messages="errors.collect('estado de salida')"
              v-validate="'required'" required
              :disabled="concurrenciaObj.estado === 'Cerrada'"
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
                name="fecha de salida"
                v-validate="'required|date_format:YYYY/MM/DD'"
                :error-messages="errors.collect('fecha de salida')"
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
                label="Hora de Egreso"
                prepend-icon="access_time"
                readonly
                required
                name="hora egreso"
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

          <v-flex xs12 v-if="egreso.estado_salida === 'Remitido'">
            <v-text-field
              v-model="egreso.entidadRemitido.nombre"
              label="Entidad destino (Centro Regulador"
              :hint="'Teléfono: ' + egreso.entidadRemitido.telefono_sede"
              persistent-hint
              readOnly
              prepend-icon="contacts"
              :disabled="concurrenciaObj.estado === 'Cerrada'"
            ></v-text-field>
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
              @changeid="val => egreso.dx_egreso = val"
              v-model="egreso.diagnostico_egreso"
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
              @changeid="val => egreso.dx_relacionado = val"
              v-model="egreso.diagnostico_relacionado"
              name="diagnóstico"
              no-btn-create
              :min-characters-search="3" clearable
              :disabled="concurrenciaObj.estado === 'Cerrada'"
            />
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider/>

    </v-form>
    <v-card-actions>

      <v-spacer/>
      <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit">Guardar</v-btn>
    </v-card-actions>
  </v-container>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'

  export default {
    name: 'ConEgresos',
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
        defatul: {}
      }
    },
    data () {
      return {
        egreso: {
          cm_concurrencia_id: this.concurrenciaId,
          diagnostico_egreso: null
        },
        tiposEstadoSalida: ['Vivo', 'Muerto', 'Remitido'],
        au_referencias: [],
        menuDate: false,
        menuTime: false,
        fechaRecepcion: null,
        horaRecepcion: null,
        loadingSubmit: false
      }
    },
    mounted () {
      this.getRegisto()
    },
    watch: {
      'egreso.estado_salida' (value) {
        if (value === 'Remitido') this.getEntidadRemitido()
      }
    },
    computed: {
      computedDateFormatted () {
        return this.formDate(this.fechaRecepcion)
      }
    },
    methods: {
      getRegisto () {
        let loader = this.$loading.show({
          container: this.$refs.formEgreso.$el
        })
        this.axios.get('cuentasmedicas/concurrencia/egreso/' + this.concurrenciaId)
          .then((response) => {
            if (response.data !== '') {
              this.egreso = response.data
              this.splitFechaRecepcionYHora()
              if (this.egreso.estado_salida === 'Remitido') {
                this.egreso.au_referencia_id = this.concurrenciaObj.au_referencia_id
                this.getReferencias()
              }
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el egreso. ' + e.response, color: 'danger'})
          })
      },
      getReferencias () {
        let loader = this.$loading.show({
          container: this.$refs.formEgreso.$el
        })
        this.axios.get('referencias/by-afiliado/' + this.concurrenciaObj.as_afiliado_id)
          .then((response) => {
            if (response.data !== '') {
              console.log(response)
              this.au_referencias = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer las referencias. ' + e.response, color: 'danger'})
          })
      },
      getEntidadRemitido () {
        let loader = this.$loading.show({
          container: this.$refs.formEgreso.$el
        })
        this.axios.get('referencias/traslado/' + this.concurrenciaObj.afiliado.id)
          .then((response) => {
            if (response.data !== '') {
              this.egreso.entidadRemitido = response.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer las entidades. ' + e.response, color: 'danger'})
          })
      },
      splitFechaRecepcionYHora () {
        let fechaHoraDivididos = this.egreso.fecha_egreso.split(' ')
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
        if (await this.validador('formEgresos')) {
          this.loadingSubmit = true
          this.egreso.fecha_egreso = `${this.fechaRecepcion} ${this.horaRecepcion}`
          if (this.egreso.estado_salida === 'Remitido') this.egreso.rs_entidad_destino_id = this.egreso.entidadRemitido.id
          console.log('conegreso', this.egreso)
          let send = !this.egreso.id ? this.axios.post('cuentasmedicas/concurrencia/egreso', this.egreso) : this.axios.put('cuentasmedicas/concurrencia/egreso/' + this.egreso.id, this.egreso)
          send.then(response => {
            if (this.egreso.id) {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
            } else {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
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
