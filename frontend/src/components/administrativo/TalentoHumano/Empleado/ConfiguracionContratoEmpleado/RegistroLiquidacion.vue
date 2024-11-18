<template>
  <v-dialog v-model="show" persistent max-width="500">
    <v-card>
      <toolbar-list
        info
        :title="ModalTitulo"
      ></toolbar-list>
      <v-form ref="formContratos">
        <v-container fluid grid-list-sm>
          <v-layout row wrap>
            <v-flex xs12 sm12 md12>
              <input-date
                v-model="data.fecha_liquidacion"
                label="Fecha Liquidación (Año-Mes-Día)"
                format="YYYY-MM-DD"
                :min="data.fecha_inicio"
                name="fecha liquidación"
                v-validate="'fechaValida:YYYY-MM-DD'"
                :error-messages="errors.collect('fecha liquidación')"
              />
            </v-flex>
            <v-flex xs12 sm12 md12>
              <v-select
                :items="complementoscontratos ? complementoscontratos.causales_despido : []"
                v-model="data.causal_despido"
                item-value="id"
                item-text="nombre"
                name="causal"
                label="Causal"
                :error-messages="errors.collect('causal')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm12 md12>
              <input-number
                label="Días No Laborados"
                key="días no laborados"
                name="días no laborados"
                prepend-icon="attach_money"
                :precision="0"
                v-model.number="data.dias_no_laborados"
                v-validate="'required|min_value:0'"
                :error-messages="errors.collect('días no laborados')"
              ></input-number>
            </v-flex>
            <v-flex xs12 sm12 md12>
              <input-number
                label="Indemnización Favor"
                key="indemnización favor"
                name="indemnización favor"
                prepend-icon="attach_money"
                :precision="2"
                v-model.number="data.indemnizacion_favor"
                v-validate="'required|min_value:0'"
                :error-messages="errors.collect('indemnización favor')"
              ></input-number>
            </v-flex>
            <v-flex xs12 sm12 md12>
              <input-number
                label="Indemnización Contra"
                key="indemnización contra"
                name="indemnización contra"
                prepend-icon="attach_money"
                :precision="2"
                v-model.number="data.indemnizacion_contra"
                v-validate="'required|min_value:0'"
                :error-messages="errors.collect('indemnización contra')"
              ></input-number>
            </v-flex>
            <v-flex xs12 sm12 md12>
              <input-number
                label="Base Prima"
                key="base prima"
                name="base prima"
                prepend-icon="attach_money"
                :precision="2"
                v-model.number="data.base_prima"
                v-validate="'required|min_value:0'"
                :error-messages="errors.collect('base prima')"
              ></input-number>
            </v-flex>
            <v-flex xs12 sm12 md12>
              <input-number
                label="Base Vacaciones"
                key="base vacaciones"
                name="base vacaciones"
                prepend-icon="attach_money"
                :precision="2"
                v-model.number="data.base_vacaciones"
                v-validate="'required|min_value:0'"
                :error-messages="errors.collect('base vacaciones')"
              ></input-number>
            </v-flex>
          </v-layout>
        </v-container>
      </v-form>
      <v-card-actions class="grey lighten-3">
        <v-spacer></v-spacer>
        <v-btn flat @click="show = false">Cancelar</v-btn>
        <v-btn color="primary" flat @click.prevent="save">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'RegistroLiquidacion',
    data: () => ({
      show: false,
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      data: null,
      datos: null,
      complementoscontratos: null,
      estados: null
      // makeData: null
    }),
    components: {
      InputNumber: () => import('@/components/general/InputNumber')
    },
    watch: {
      'show' (val) {
        if (!val) {
          setTimeout(() => {
            this.datos = null
            this.formReset()
            this.$validator.reset()
          }, 500)
        }
      }
    },
    created () {
      this.formReset()
      this.getComplementos()
    },
    computed: {
      ModalTitulo () {
        return this.data ? `Liquidación del contrato ${this.data.contrato_empleado ? '# ' + this.data.contrato_empleado : ''}` : ''
      }
    },
    methods: {
      getComplementos () {
        this.axios.get(`talentohumano/complementos`)
          .then(response => {
            console.log('DAD', response)
            let complementos = response.data.data
            this.complementoscontratos = this.clone(complementos.complementoscontratos)
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al cargar los complementos.', error: e})
          })
      },
      assign (props) {
        console.log('propss', props)
        this.data = props
        this.show = true
      },
      save () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.$emit('updated', this.data)
            this.show = false
          }
        })
      },
      formReset () {
        this.data = {
          contrato_empleado: null,
          base_prima: 0,
          base_vacaciones: 0,
          basico_actual: 0,
          causal_despido: null,
          dias_no_laborados: 0,
          empleado: null,
          estado: null,
          fecha_liquidacion: null,
          fecha_inicio: null,
          indemnizacion_favor: 0,
          indemnizacion_contra: 0
        }
      }
    }
  }
</script>

<style scoped>

</style>
