<template>
  <v-container grid-list-md style="max-width: inherit" ref="formIngreso">

    <v-form data-vv-scope="formIngresos">
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
              v-model="ingreso.via_ingreso"
              label="Via de ingreso"
              name="via de ingreso"
              prepend-icon="input"
              :error-messages="errors.collect('via de ingreso')"
              v-validate="'required'" required
            ></v-select>
          </v-flex>

          <v-flex xs12 v-if="ingreso.via_ingreso === 'Remitido'">
            <postulador-v2
              no-data="Busqueda por NIT, razon social u código de habilitación."
              hint="tercero.identificacion_completa"
              item-text="tercero.nombre_completo"
              data-title="tercero.identificacion_completa"
              data-subtitle="tercero.nombre_completo"
              label="IPS Origen"
              entidad="entidades"
              preicon="location_city"
              @changeid="val => ingreso.rs_entidad_id = val"
              v-model="ingreso.entidad_origen"
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
              @changeid="val => ingreso.dx_ingreso = val"
              v-model="ingreso.diagnostico_ingreso"
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
              @changeid="val => ingreso.dx_relacionado = val"
              v-model="ingreso.diagnostico_relacionado"
              name="diagnóstico"
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
              label="Diagnóstico Relacionado 2 (opcional)"
              entidad="rs_cie10s"
              preicon="reorder"
              @changeid="val => ingreso.dx_relacionado2 = val"
              v-model="ingreso.diagnostico_relacionado2"
              name="diagnóstico"
              no-btn-create
              :min-characters-search="3" clearable
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
    name: 'ConIngresos',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    props: {
      concurrenciaId: {
        type: Number,
        default: 0
      }
    },
    data () {
      return {
        ingreso: {
          cm_concurrencia_id: this.concurrenciaId,
          entidad_origen: null,
          diagnostico_ingreso: null
        },
        tiposViaIngreso: ['Consulta Externa', 'Urgencias', 'Remitido'],
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
    computed: {
      computedDateFormatted () {
        return this.formDate(this.fechaRecepcion)
      }
    },
    methods: {
      getRegisto () {
        let loader = this.$loading.show({
          container: this.$refs.formIngreso.$el
        })
        this.axios.get('cuentasmedicas/concurrencia/ingreso/' + this.concurrenciaId)
          .then((response) => {
            if (response.data !== '') {
              this.ingreso = response.data
              this.splitFechaRecepcionYHora()
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el ingreso. ' + e.response, color: 'danger'})
          })
      },
      splitFechaRecepcionYHora () {
        let fechaHoraDivididos = this.ingreso.fecha_ingreso.split(' ')
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
        if (await this.validador('formIngresos')) {
          this.loadingSubmit = true
          this.ingreso.fecha_ingreso = `${this.fechaRecepcion} ${this.horaRecepcion}`
          let send = !this.ingreso.id ? this.axios.post('cuentasmedicas/concurrencia/ingreso', this.ingreso) : this.axios.put('cuentasmedicas/concurrencia/ingreso/' + this.ingreso.id, this.ingreso)
          send.then(response => {
            if (this.ingreso.id) {
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
