<template>
  <div class="container">
    <v-container>
      <v-card flat icon>
      <v-card-title class="headline grey lighten-2" primary-title>
        Registro Periodo validador 4505
      </v-card-title>
        <v-card-text>
          <v-container>
            <v-layout row wrap>
              <v-flex xs12 sm12 md6 lg3>
                <v-select
                  v-model="periodo.year"
                  :disabled="periodo.id != ''"
                  :items="years"
                  placeholder="Seleccione un año"
                  label="Año"
                ></v-select>
              </v-flex>
              <v-flex xs12 sm12 md6 lg3>
                <v-select
                  v-model="periodo.periodo"
                  :disabled="periodo.id != ''"
                  :items="periodos"
                  placeholder="Seleccione un periodo"
                  label="Periodo"
                ></v-select>
              </v-flex>
                  <v-flex xs12 sm12 md6 lg3>
                    <v-dialog
                      ref="pickerFechaInicioValidacion"
                      v-model="pickerFechaInicioValidacion"
                      :return-value.sync="periodo.fecha_inicio_validacion"
                      persistent
                      lazy
                      full-width
                      width="290px">
                      <v-text-field
                        slot="activator"
                        v-model="periodo.fecha_inicio_validacion"
                        label="Fecha Inicial validador"
                        prepend-icon="event"
                        readonly>
                      </v-text-field>
                      <v-date-picker v-model="periodo.fecha_inicio_validacion" scrollable>
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="pickerFechaInicioValidacion = false">Cancelar</v-btn>
                        <v-btn flat color="primary" @click="$refs.pickerFechaInicioValidacion.save(periodo.fecha_inicio_validacion)">OK</v-btn>
                      </v-date-picker>
                    </v-dialog>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg3>
                    <v-dialog
                      ref="pickerFechaFinValidacion"
                      v-model="pickerFechaFinValidacion"
                      :return-value.sync="periodo.fecha_fin_validacion"
                      persistent
                      lazy
                      full-width
                      width="290px">
                      <v-text-field
                        slot="activator"
                        v-model="periodo.fecha_fin_validacion"
                        label="Fecha final de validador"
                        prepend-icon="event"
                        readonly>
                      </v-text-field>
                      <v-date-picker v-model="periodo.fecha_fin_validacion" scrollable>
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="pickerFechaFinValidacion = false">Cancelar</v-btn>
                        <v-btn flat color="primary" @click="$refs.pickerFechaFinValidacion.save(periodo.fecha_fin_validacion)">OK</v-btn>
                      </v-date-picker>
                    </v-dialog>
                  </v-flex>
            </v-layout>
            <v-layout row wrap>
              <v-flex xs6 class="text-xs-left">
              </v-flex>
              <v-flex xs6 class="text-xs-right">
                <v-btn @click="submit" color="primary">Guardar</v-btn>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {PERIODOS4505_RELOAD_ITEM} from '@/store/modules/general/tables'
  import moment from 'moment'
  export default {
    name: 'RegistroPeriodos4505',
    props: ['parametros'],
    data () {
      return {
        fab: false,
        pickerFechaInicioValidacion: false,
        pickerFechaFinValidacion: false,
        periodo: {
          id: '',
          year: '',
          periodo: '',
          fecha_inicio_validacion: '',
          fecha_fin_validacion: ''
        },
        years: [],
        periodos: []
      }
    },
    methods: {
      submit () {
        if (!moment(this.parametros.fecha_fin_validacion, 'YYYY-MM-DD').isSameOrAfter(moment(this.parametros.fecha_inicio_validacion, 'YYYY-MM-DD'))) {
        }
        if (this.periodo.id) {
          this.axios.put('periodos4505/' + this.periodo.id, this.periodo)
            .then(response => {
              if (response.data.state === 'validador') {
                this.$store.commit(SNACK_SHOW, {msg: 'Existen datos que son obligatorios, verifique el formulario', color: 'danger'})
              } else {
                if (response.data.state === 'exist') {
                  this.$store.commit(SNACK_SHOW, {msg: response.data.message, color: 'danger'})
                  this.periodo = response.data.data
                } else {
                  if (response.data.state === 'save') {
                    this.$store.commit(SNACK_SHOW, {msg: 'El periodo de validacion se actualizo correctamente', color: 'success'})
                    this.formReset()
                    this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                    this.$store.commit(PERIODOS4505_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
                  } else {
                    this.$store.commit(SNACK_SHOW, {msg: 'Error al guardar sin retorno de mensaje ' + response.data.message, color: 'danger'})
                  }
                }
              }
            }).catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response.data.message, color: 'danger'})
            })
        } else {
          this.axios.post('periodos4505', this.periodo)
            .then(response => {
              if (response.data.state === 'validador') {
                this.$store.commit(SNACK_SHOW, {msg: 'Existen datos que son obligatorios, verifique el formulario', color: 'danger'})
              } else {
                if (response.data.state === 'exist') {
                  this.$store.commit(SNACK_SHOW, {msg: response.data.message, color: 'danger'})
                } else {
                  if (response.data.state === 'save') {
                    this.$store.commit(SNACK_SHOW, {msg: 'El periodo de validacion se creó correctamente', color: 'success'})
                    this.formReset()
                    this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                    this.$store.commit(PERIODOS4505_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
                  } else {
                    this.$store.commit(SNACK_SHOW, {msg: 'Error al guardar sin retorno de mensaje ' + response.data.message, color: 'danger'})
                  }
                }
              }
            }).catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response.data.message, color: 'danger'})
            })
        }
      },
      getRegistro (id) {
        this.axios.get('periodos4505/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.periodo = response.data.data
            } else {
              this.$store.commit('SNACK_SHOW', {msg: 'El identificador del periodo no existe en la base de datos ', color: 'error'})
            }
          })
          .catch(e => {
            this.$store.commit('SNACK_SHOW', {msg: 'Status: ' + e.response.status + ' | ' + e.response.data.message, color: 'error'})
          })
      },
      formReset () {
        this.pickerFechaInicioValidacion = false
        this.pickerFechaFinValidacion = false
        this.periodo = {
          id: '',
          year: '',
          periodo: '',
          fecha_inicio_validacion: '',
          fecha_fin_validacion: ''
        }
      },
      complementos () {
        this.axios.get('complementos-unificador-archivos-4505').then(response => {
          this.periodos = response.data.periodos
        }).catch(e => {
          this.$store.commit('SNACK_SHOW', {msg: 'Status: ' + e.response.status + ' | ' + e.response.data.message, color: 'error'})
        })
      },
      loadYears () {
        for (var i = moment().subtract('3', 'y').year(); i < moment().add('3', 'y').year(); i++) {
          this.years.push(moment(i + '-01-01', 'Y').add(1, 'y').year())
        }
      }
    },
    mounted () {
      this.complementos()
      this.loadYears()
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
    }
  }
</script>