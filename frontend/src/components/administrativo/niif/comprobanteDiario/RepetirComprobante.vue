<template>
    <v-dialog v-model="dialog" width="600px" persistent>
      <v-card>
        <toolbar-list :info="infoComponent"  title="Repetir Comprobante de Diario" subtitle="Registro"/>
        <v-form data-vv-scope="formRepetirComprobante">
          <v-container fluid grid-list-xl>
            <v-layout row wrap>
              <v-flex xs12 class="pt-0">
                <v-list>
                  <v-list-tile-content>
                    <v-list-tile-title v-html="`<b>Comprobante de diario N°: </b> ${value ? value.consecutivo : ''}`"></v-list-tile-title>
                    <v-list-tile-title v-html="`<b>Tipo Comprobante: </b> ${value ? (value.codigoTipo + ' - ' + value.tipo) : ''}`"></v-list-tile-title>
                    <v-list-tile-title v-html="`<b>Comprobante Destino: </b> ${comprobante.comdiariodestino}`"></v-list-tile-title>
                  </v-list-tile-content>
                </v-list>
              </v-flex>
              <v-flex xs12 sm8 class="pt-0">
                <v-autocomplete
                  label="Nuevo Tipo de Comprobante"
                  v-model="comprobante.nf_tipcomdiario_id"
                  :items="complementosFormComDiario.tipcomdiarios"
                  item-value="id"
                  item-text="nombre"
                  name="tipo de comprobante"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('tipo de comprobante')"
                  autocomplete
                >
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm4 class="pt-0">
                <input-date
                  v-model="comprobante.fecha"
                  label="Fecha"
                  placeholder="YYYY-MM-DD"
                  format="YYYY-MM-DD"
                  name="fecha"
                  key="fecha"
                  v-validate="'required|fechaValida|date_format:YYYY-MM-DD'"
                  :error-messages="errors.collect('fecha')"
                ></input-date>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
        <v-card-actions class="grey lighten-4">
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            flat
            small
            :disabled="errors.any()"
            @click="submit"
          >
            Guardar
          </v-btn>
          <v-btn
            color="grey"
            flat
            small
            @click="$emit('cancelar')"
          >
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>

<script>
  import {COMDIARIO_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import store from '../../../../store/complementos/index'
  export default {
    name: 'RepetirComprobante',
    props: ['value', 'dialog'],
    components: {
      InputDate: () => import('@/components/general/InputDate'),
      Postulador: () => import('@/components/general/Postulador'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        fechaActual: '2018-01-01',
        fechaComprobanteDestino: false,
        mensaje: 'asda',
        comprobante: null
      }
    },
    watch: {
      'dialog' (value) {
        if (value === false) {
          this.$validator.reset()
          this.formReset()
          // this.comprobante.nf_comdiario_origen_id = value.id
        }
      },
      'value' (value) {
        if (value) {
          this.comprobante.nf_comdiario_origen_id = value.id
        }
        // console.log('vale', value)
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('comdiarios')
      },
      complementosFormComDiario () {
        return store.getters.complementosFormComDiario
      }
    },
    methods: {
      async submit () {
        if (await this.validador('formRepetirComprobante')) {
          this.axios.post('repetir-comprobante-diario', this.comprobante)
            .then(response => {
              this.$store.commit(SNACK_SHOW, {msg: 'El comprobante de diario se creo correctamente', color: 'success'})
              this.$store.commit(COMDIARIO_RELOAD_ITEM, {
                item: response.data,
                estado: 'reload',
                key: null
              })
              this.$emit('cancelar')
              // this.formReset()
            }).catch(e => {
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
          comdiariodestino: '',
          nf_comdiario_origen_id: null,
          nf_tipcomdiario_id: null
        }
        this.obtenerComprobanteDestino()
        // this.axios.get('consecutivo-comdiario')
        //   .then(response => {
        //     console.log(response)
        //     this.comprobante.comdiariodestino = response.data.consecutivo
        //   }).catch(e => {
        //     this.$store.commit(SNACK_SHOW, {msg: 'Error al obtener el consecutivo ' + e.response, color: 'danger'})
        //   })
      },
      obtenerComprobanteDestino () {
        this.axios.get('consecutivo-comdiario')
          .then(response => {
            this.comprobante.comdiariodestino = response.data.consecutivo
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al obtener el consecutivo ' + e.response, color: 'danger'})
          })
      }
    },
    created () {
      this.formReset()
    },
    mounted () {
      this.obtenerComprobanteDestino()
      this.fechaActual = this.moment().format('YYYY-MM') + '-01'
    }
  }
</script>

<style scoped>

</style>
