<template>
    <div>
      <!--height="442px"-->
      <v-card>
        <v-card-title class="headline grey lighten-2" primary-title>
          Repetir Comprobantes de diario
        </v-card-title>
        <v-card-text>
          <v-container fluid grid-list-xl >
            <v-form data-vv-scope="formRepetirComprobanteDiario">
              <v-layout row wrap>
                <v-flex xs12 sm12 md10 lg10>
                  <postulador
                    nodata="Busqueda por consecutivo"
                    required
                    itemtext="consecutivo"
                    datatitle="consecutivo"
                    filter="consecutivo"
                    label="Comprobante de origen"
                    scope="formRepetirComprobanteDiario"
                    ref="postulaComprobanteOrigen"
                    entidad="comdiarios"
                    preicon="assignment_turned_in"
                    @change="val => comprobante.nf_comdiario_origen_id = val"
                    @objectReturn="val => comprobante.comprobanteorigen = val"
                    :value="comprobante.comprobanteorigen"
                    clearable
                  />
                </v-flex>
                <v-flex xs12 sm12 md2 lg2>
                <v-text-field label="Comprobante destino" type="number" v-model="comprobante.comdiariodestino" :readonly="true"> </v-text-field>   
                </v-flex>
                <v-flex xs12 sm12 md12 lg12>
                  <v-flex xs12 sm12 md12 lg12 class="pa-1">
                    <v-dialog
                      ref="fechaComprobanteDestino"
                      v-model="fechaComprobanteDestino"
                      :return-value.sync="comprobante.fecha"
                      persistent
                      lazy
                      full-width
                      width="290px">
                      <v-text-field
                        slot="activator"
                        v-model="comprobante.fecha"
                        label="Fecha"
                        prepend-icon="event"
                        key="fecha actual"
                        v-validate="'required|date_format:YYYY-MM-DD'"
                        name="fecha actual"
                        :error-messages="errors.collect('fecha actual')"
                        readonly>
                      </v-text-field>
                      <v-date-picker
                        v-model="comprobante.fecha"
                        @change="() => {
                          let index = $validator.errors.items.findIndex(x => x.field === 'fecha actual')
                          $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                        }"
                        :min="fechaActual"
                        locale='es'
                        scrollable
                      >
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="fechaComprobanteDestino = false">Cancelar</v-btn>
                        <v-btn flat color="primary" @click="$refs.fechaComprobanteDestino.save(comprobante.fecha)">OK</v-btn>
                      </v-date-picker>
                    </v-dialog>
                  </v-flex>
                </v-flex>
              </v-layout>
            </v-form>
          </v-container>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
          color="primary"
          flat
          :disabled="errors.any()"
          @click="submit"
          >
            Guardar
          </v-btn>
          <v-btn
          color="primary"
          flat
          @click="formReset"
          >
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import moment from 'moment'
  export default {
    name: 'RepetirComprobanteDiario',
    data () {
      return {
        fechaActual: '2018-01-01',
        fechaComprobanteDestino: false,
        mensaje: 'asda',
        comprobante: {
          fecha: null,
          comprobanteorigen: null
        }
      }
    },
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    methods: {
      async submit () {
        if (await this.validador('formRepetirComprobanteDiario')) {
          this.axios.post('repetir-comprobante-diario', this.comprobante)
            .then(response => {
              console.log(response)
              this.$store.commit(SNACK_SHOW, {msg: 'El comprobante de diario se creo correctamente', color: 'success'})
              this.formReset()
            }).catch(e => {
              console.log(e)
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response.data.errors, color: 'danger'})
            })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formReset () {
        this.comprobante = {
          fecha: null,
          comprobanteorigen: null,
          comdiariodestino: ''
        }
        this.axios.get('consecutivo-comdiario')
          .then(response => {
            console.log(response)
            this.comprobante.comdiariodestino = response.data.consecutivo
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al obtener el consecutivo ' + e.response, color: 'danger'})
          })
      }
    },
    mounted () {
      this.axios.get('consecutivo-comdiario')
        .then(response => {
          this.comprobante.comdiariodestino = response.data.consecutivo
        }).catch(e => {
          this.$store.commit(SNACK_SHOW, {msg: 'Error al obtener el consecutivo ' + e.response, color: 'danger'})
        })
      this.fechaActual = moment().format('YYYY-MM') + '-01'
    }
  }
</script>
