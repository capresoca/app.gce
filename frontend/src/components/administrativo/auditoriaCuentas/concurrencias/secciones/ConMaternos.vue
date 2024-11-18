<template>
  <v-container grid-list-md style="max-width: inherit" ref="formMaterno">

    <v-form data-vv-scope="formMaternos">
      <v-container fluid grid-list-md>
        <v-layout row wrap>

          <v-flex xs12 md4>
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
                label="Fecha Parto"
                prepend-icon="event"
                readonly
                name="fecha"
                v-validate="'required|date_format:YYYY/MM/DD'"
                :error-messages="errors.collect('fecha')"
                :disabled="concurrenciaObj.estado === 'Cerrada'"
              ></v-text-field>
              <v-date-picker
                v-model="materno.fecha_parto"
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

          <v-flex xs12 md4>
            <v-select
              :items="tiposParto"
              v-model="materno.tipo_parto"
              label="Tipo parto"
              name="tipo parto"
              :error-messages="errors.collect('tipo parto')"
              v-validate="'required'" required
              :disabled="concurrenciaObj.estado === 'Cerrada'"
            ></v-select>
          </v-flex>

          <v-flex xs12 sm4>
            <v-select
              :items="viasParto"
              v-model="materno.via_parto"
              label="Via parto"
              name="via parto"
              :error-messages="errors.collect('via parto')"
              v-validate="'required'" required
              :disabled="concurrenciaObj.estado === 'Cerrada'"
            ></v-select>
          </v-flex>

          <v-flex xs12 md4>
            <v-text-field v-model.number="materno.peso_recien_nacido"
                          label="Peso recién nacido" prepend-icon="assignment"
                          name="peso" v-validate="'numeric'"
                          :error-messages="errors.collect('peso')" :disabled="concurrenciaObj.estado === 'Cerrada'"></v-text-field>
          </v-flex>

          <v-flex xs12 md4>
            <v-text-field v-model.number="materno.perimetro_cefalico"
                          label="Perimetro cefálico" prepend-icon="assignment"
                          name="perimetro cefalico" v-validate="'numeric'"
                          :error-messages="errors.collect('perimetro cefalico')" :disabled="concurrenciaObj.estado === 'Cerrada'"></v-text-field>
          </v-flex>

          <v-flex xs12 md4>
            <v-text-field v-model.number="materno.perimetro_abdominal"
                          label="Perimetro abdominal" prepend-icon="assignment"
                          name="perimetro abdominal" v-validate="'numeric'"
                          :error-messages="errors.collect('perimetro abdominal')" :disabled="concurrenciaObj.estado === 'Cerrada'"></v-text-field>
          </v-flex>

          <v-flex xs12 md4>
            <v-text-field v-model.number="materno.apgar"
                          label="APGAR" prepend-icon="assignment"
                          name="APGAR"
                          :error-messages="errors.collect('APGAR')" :disabled="concurrenciaObj.estado === 'Cerrada'"></v-text-field>
          </v-flex>

          <v-flex xs12 md4>
            <v-text-field v-model.number="materno.rh"
                          label="RH" prepend-icon="assignment"
                          name="RH"
                          :error-messages="errors.collect('RH')" :disabled="concurrenciaObj.estado === 'Cerrada'"></v-text-field>
          </v-flex>

          <v-flex xs12 sm4>
            <v-select
              :items="condicionesDeEgreso"
              v-model="materno.condicion_egreso"
              label="Condición egreso"
              name="condicion egreso"
              :error-messages="errors.collect('condicion egreso')"
              v-validate="'required'" required
              :disabled="concurrenciaObj.estado === 'Cerrada'"
            ></v-select>
          </v-flex>

          <v-flex xs12 md4>
            <v-text-field v-model.number="materno.edad_gestacion"
                          label="Edad de gestación" key="edad de gestacion"
                          name="edad de gestacion" v-validate="'required|numeric'" required
                          prepend-icon="pregnant_woman"
                          :error-messages="errors.collect('edad de gestacion')"
            :disabled="concurrenciaObj.estado === 'Cerrada'"></v-text-field>
          </v-flex>

          <v-flex xs12>
            <v-autocomplete
                            :items="complicacionesParto"
                            v-model="materno.cm_complicacionparto_id"
                            item-text="nombre"
                            item-value="id"
                            label="Complicación parto"
                            no-data-text="No hay datos disponibles"
                            name="complicacion parto"
                            :error-messages="errors.collect('complicacion parto')"
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
              :items="complicacionesNeonato"
              v-model="materno.cm_complicacionneonato_id"
              item-text="nombre"
              item-value="id"
              label="Complicación neonato"
              no-data-text="No hay datos disponibles"
              name="complicacion neonato"
              :error-messages="errors.collect('complicacion neonato')"
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
            <postulador-v2
              no-data="Busqueda por código, descripcion y genero"
              hint="codigo"
              item-text="descripcion"
              data-title="codigo"
              data-subtitle="descripcion"
              label="Diagnóstico Principal"
              entidad="rs_cie10s"
              preicon="reorder"
              @changeid="val => materno.dx_nacimiento = val"
              v-model="materno.diagnostico_nacimiento"
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
              @changeid="val => materno.dx_relacionado = val ? val : ''"
              v-model="materno.diagnostico_relacionado"
              name="diagnóstico"
              no-btn-create
              :min-characters-search="3" clearable
              :disabled="concurrenciaObj.estado === 'Cerrada'"
            />
          </v-flex>

          <v-flex xs12 md6>
            <v-layout align-center fill-height>
              <v-flex d-flex xs10 sm8>
                <input-file
                  ref="archivoAdjunto"
                  label="Historia clínica"
                  v-model="materno.file"
                  :file-name="materno.historia_clinica ? materno.historia_clinica.nombre : null"
                  accept="application/pdf"
                  :hint="'Extenciones aceptadas: pdf'"
                  class="mb-3"
                  prepend-icon="attach_file"
                  :disabled="concurrenciaObj.estado === 'Cerrada'"
                />
              </v-flex>
              <v-flex d-flex xs2 sm4 v-if="materno.historia_clinica && materno.historia_clinica.url_signed">
                <v-tooltip bottom>
                  <v-btn
                    fab
                    color="success"
                    small
                    :href="materno.historia_clinica ? materno.historia_clinica.url_signed : ''"
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
    name: 'ConMaternos',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputFile: () => import('@/components/general/InputFile')
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
        materno: {},
        tiposParto: ['Único', 'Múltiple'],
        viasParto: ['Vaginal', 'Cesárea'],
        condicionesDeEgreso: ['VIVO', 'MUERTO', 'REMITIDO', 'PHD'],
        complicacionesParto: [],
        complicacionesNeonato: [],
        menuDate: false,
        loadingSubmit: false,
        payload: null
      }
    },
    mounted () {
      this.formReset()
      this.getRegisto()
      this.getComplicacionesPartos()
      this.getComplicacionesNeonatos()
    },
    computed: {
      computedDateFormatted () {
        return this.formDate(this.materno.fecha_parto)
      }
    },
    methods: {
      getRegisto () {
        let loader = this.$loading.show({
          container: this.$refs.formMaterno.$el
        })
        this.axios.get('cuentasmedicas/concurrencia/materno/' + this.concurrenciaId)
          .then((response) => {
            if (Object.getOwnPropertyNames(response.data).length !== 0) {
              this.materno = response.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      getComplicacionesPartos () {
        this.axios.get('cuentasmedicas/complicacionespartos')
          .then((response) => {
            if (response.data !== '') {
              this.complicacionesParto = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer las glosas. ' + e, color: 'danger'})
          })
      },
      getComplicacionesNeonatos () {
        this.axios.get('cuentasmedicas/complicacionesneonatos')
          .then((response) => {
            if (response.data !== '') {
              this.complicacionesNeonato = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer las glosas. ' + e, color: 'danger'})
          })
      },
      formReset () {
        this.materno = {
          id: null,
          cm_concurrencia_id: null,
          fecha_parto: null,
          tipo_parto: null,
          via_parto: null,
          peso_recien_nacido: null,
          perimetro_cefalico: null,
          perimetro_abdominal: null,
          apgar: null,
          rh: null,
          condicion_egreso: null,
          edad_gestacion: null,
          cm_complicacionparto_id: null,
          cm_complicacionneonato_id: null,
          dx_nacimiento: null,
          dx_relacionado: '',
          complicacion_neonato: null
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      addFormData () {
        let data = new FormData()
        data.append('id', this.materno.id)
        data.append('cm_concurrencia_id', this.concurrenciaId)
        data.append('fecha_parto', this.materno.fecha_parto)
        data.append('tipo_parto', this.materno.tipo_parto)
        data.append('via_parto', this.materno.via_parto)
        if (this.materno.peso_recien_nacido) data.append('peso_recien_nacido', this.materno.peso_recien_nacido)
        if (this.materno.perimetro_cefalico) data.append('perimetro_cefalico', this.materno.perimetro_cefalico)
        if (this.materno.perimetro_abdominal) data.append('perimetro_abdominal', this.materno.perimetro_abdominal)
        if (this.materno.apgar) data.append('apgar', this.materno.apgar)
        if (this.materno.rh) data.append('rh', this.materno.rh)
        if (this.materno.condicion_egreso) data.append('condicion_egreso', this.materno.condicion_egreso)
        data.append('edad_gestacion', this.materno.edad_gestacion)
        if (this.materno.cm_complicacionparto_id) data.append('cm_complicacionparto_id', this.materno.cm_complicacionparto_id)
        if (this.materno.cm_complicacionparto_id) data.append('cm_complicacionneonato_id', this.materno.cm_complicacionneonato_id)
        if (this.materno.dx_relacionado) data.append('dx_relacionado', this.materno.dx_relacionado)
        data.append('dx_nacimiento', this.materno.dx_nacimiento)
        data.append('complicacion_neonato', this.materno.complicacion_neonato)

        data.append('file', typeof this.materno.file === 'undefined' ? '' : this.materno.file)

        this.payload = data
      },
      async submit () {
        if (await this.validador('formMaternos')) {
          this.loadingSubmit = true
          this.addFormData()
          let send = !this.materno.id ? this.axios.post('cuentasmedicas/concurrencia/materno', this.payload) : this.axios.post('cuentasmedicas/concurrencia/materno/' + this.materno.id, this.payload)
          send.then(response => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {
              msg: `Se ${this.materno.id ? 'actualizaron los datos' : 'realizó el registro'} correctamente.`,
              color: 'success'
            })
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
