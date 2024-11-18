<template>
  <v-dialog v-model="show" persistent max-width="600">
    <v-card>
      <toolbar-list
        info
        :title="valor === 'dos' ? ModalTitulo : ModalTituloDos"
        subtitle="Registro y gestión"
      ></toolbar-list>
      <v-form data-vv-scope="formExtras">
        <v-container fluid grid-list-sm>
          <v-layout row wrap v-if="valor === 'uno' || valor === 'dos'">
            <v-flex xs12 sm12 md12>
              <v-select
                :items="complementosExtras ? complementosExtras.extras_laborales : []"
                v-model="data.extra_laboral"
                item-value="consecutivo_extra_laboral"
                item-text="descripcion"
                name="extra laboral"
                label="Extra Laboral"
                :error-messages="errors.collect('extra laboral')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md6>
              <input-date
                v-model="data.fecha_inicio"
                label="Fecha Inicio (Año-Mes-Día)"
                format="YYYY-MM-DD"
                :min="minDate"
                name="fecha inicio"
                v-validate="'required|fechaValida:YYYY-MM-DD'"
                :error-messages="errors.collect('fecha inicio')"
              />
            </v-flex>
            <v-flex xs12 sm6 md6>
              <input-date
                :disabled="!data.fecha_inicio"
                v-model="data.fecha_fin"
                label="Fecha Fin (Año-Mes-Día)"
                format="YYYY-MM-DD"
                :min="data.fecha_inicio"
                name="fecha fin"
                v-validate="'fechaValida:YYYY-MM-DD'"
                :error-messages="errors.collect('fecha fin')"
              />
            </v-flex>
            <v-flex xs12 sm4 md6>
              <v-select
                :items="estadosS"
                v-model="data.estado"
                item-value="id"
                item-text="nombre"
                name="estado"
                label="Estado"
                :error-messages="errors.collect('estado')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm4 md6>
              <v-select
                :items="complementosExtras ? complementosExtras.causacionesextras : []"
                v-model="data.causacion"
                item-value="id"
                item-text="nombre"
                name="causación"
                label="Causación"
                :error-messages="errors.collect('causación')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md6>
              <input-number
                label="Valor extra"
                key="valor extra"
                name="valor extra"
                prepend-icon="attach_money"
                :precision="0"
                v-model.number="data.valor_extra"
                v-validate="'required|min_value:0'"
                :error-messages="errors.collect('valor extra')"
              ></input-number>
            </v-flex>
            <v-flex xs12 sm6 md6>
              <input-number
                label="Porcentaje Salarial"
                key="porcentaje salarial"
                name="porcentaje salarial"
                prepend-icon="fas fa-percent"
                :precision="2"
                v-model.number="data.porcentaje_extra"
                v-validate="'required|min_value:0'"
                :error-messages="errors.collect('porcentaje salarial')"
              ></input-number>
            </v-flex>
          </v-layout>
          <v-layout row wrap v-if="valor === 'tres' || valor === 'cuatro'">
            <v-flex xs12 sm12 md12>
              <input-number
                label="Salario Base"
                key="salario base"
                name="salario base"
                prepend-icon="attach_money"
                :precision="0"
                v-model.number="dataDos.salario_base"
                v-validate="'required|min_value:0'"
                :error-messages="errors.collect('salario base')"
              ></input-number>
            </v-flex>
            <v-flex xs12 sm12 md12>
              <v-textarea
                label="Observaciones"
                v-model="dataDos.observacion"
                name="observaciones"
                v-validate="'required'"
                :error-messages="errors.collect('observaciones')"
                rows="1"
              ></v-textarea>
            </v-flex>
          </v-layout>
        </v-container>
      </v-form>
      <v-card-actions class="grey lighten-3">
        <v-spacer></v-spacer>
        <v-btn flat @click="show = false">Cancelar</v-btn>
        <v-btn color="primary" flat @click.prevent="save">Registrar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'RegistroExtraLaboral',
    components: {
      InputNumber: () => import('@/components/general/InputNumber')
    },
    data: () => ({
      show: false,
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      data: null,
      dataDos: null,
      datos: null,
      valor: null
    }),
    watch: {
      'show' (val) {
        if (!val) {
          setTimeout(() => {
            this.datos = null
            this.formReset()
            this.formResetAnd()
            this.$validator.reset()
          }, 500)
        }
      }
    },
    created () {
      this.formReset()
      this.formResetAnd()
    },
    computed: {
      complementosExtras () {
        return store.getters.complementoscontExtrasLaborales
      },
      ModalTitulo () {
        return this.data ? `${!this.data.contrato_empleado_extra ? 'Nuevo ' : 'Edición del '}Extra Laboral ${this.data.contrato_empleado_extra ? ('# ' + this.data.contrato_empleado_extra) : ''}` : null
      },
      ModalTituloDos () {
        return this.dataDos ? `${!this.dataDos.contrato_empleado_remuneracion ? 'Nueva ' : 'Edición de la '}Remuneración ${this.dataDos.contrato_empleado_remuneracion ? ('# ' + this.dataDos.contrato_empleado_remuneracion) : ''}` : ''
      },
      estadosS () {
        return [
          {
            id: 1,
            nombre: 'Vigente'
          },
          {
            id: 0,
            nombre: 'No Vigente'
          }
        ]
      }
    },
    methods: {
      assign (props, num = null) {
        console.log('asa', props)
        console.log('asass', num)
        this.valor = num
        switch (num) {
          case 'uno':
            this.datos = this.clone(props)
            this.data.contrato_empleado = this.datos.contrato_empleado
            break
          case 'dos':
            this.data = this.clone(props)
            break
          case 'tres':
            this.datos = this.clone(props)
            this.dataDos.contrato_empleado = this.datos.contrato_empleado
            break
          case 'cuatro':
            this.dataDos = this.clone(props)
            break
        }
        this.show = true
      },
      save () {
        this.$validator.validateAll().then(result => {
          if (result) {
            let dato = (this.valor === 'tres' || this.valor === 'cuatro') ? this.dataDos : this.data
            dato.valor = this.valor
            this.$emit('updateOrCreate', dato)
            this.show = false
          }
        })
      },
      formReset () {
        this.data = {
          contrato_empleado_extra: null,
          contrato_empleado: null,
          extra_laboral: null,
          fecha_inicio: null,
          fecha_fin: null,
          estado: null,
          causacion: null,
          valor_extra: 0,
          porcentaje_extra: 0
        }
      },
      formResetAnd () {
        this.dataDos = {
          contrato_empleado_remuneracion: null,
          contrato_empleado: null,
          salario_base: 0,
          observacion: null
        }
      }
    }
  }
</script>

<style scoped>

</style>
