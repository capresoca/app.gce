<template>
  <div>
    <v-layout row wrap>
      <v-flex xs12 sm6 md4>
        <v-select
          label="Tipo"
          v-model="value.tipo"
          :items="complementos.tiposContrato"
          name="Tipo"
          v-validate="'required'"
          :error-messages="errors.collect('Tipo')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-text-field
          label="Número"
          v-model="value.numero_contrato"
          name="Número"
          v-validate="'required'"
          :error-messages="errors.collect('Número')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-menu
          ref="Fecha"
          v-model="menuDate"
          :close-on-content-click="false"
          :nudge-right="40"
          lazy
          transition="scale-transition"
          offset-y
          full-width
          min-width="290px"
          :readonly="!!value.actaInicio"
        >
          <v-text-field
            slot="activator"
            label="Fecha"
            v-model="value.fecha_contrato"
            name="Fecha"
            prepend-icon="event"
            v-validate="'required|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
            :error-messages="errors.collect('Fecha')"
            readonly
            :readonly="!!value.actaInicio"
          />
          <v-date-picker
            ref="picker"
            v-model="value.fecha_contrato"
            locale="es-co"
            :max="maxDate"
            :min="minDate"
            @input="$refs['Fecha'].save(value.fecha_contrato)"
            @change="() => {
          let index = $validator.errors.items.findIndex(x => x.field === 'Fecha')
          $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
          }"
            :readonly="!!value.actaInicio"
          />
        </v-menu>
      </v-flex>
      <v-flex xs12 sm12 md12>
        <postulador-v2
          no-data="Busqueda por NIT, razon social u código de habilitación."
          hint="tercero.identificacion_completa"
          item-text="nombre"
          data-title="tercero.identificacion_completa"
          data-subtitle="nombre"
          :detail="DetalleEntidad"
          label="Entidad"
          entidad="entidades"
          preicon="location_city"
          @changeid="val => value.rs_entidad_id = val"
          v-model="value.entidad"
          name="Entidad"
          rules="required"
          v-validate="'required'"
          :error-messages="errors.collect('Entidad')"
          no-btn-create
          no-btn-edit
          :min-characters-search="3"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-text-field
          prepend-icon="attach_money"
          label="Valor"
          name="Valor"
          v-validate="'required|min_value:0'"
          min="0"
          type="number"
          v-model.number="value.valor"
          :error-messages="errors.collect('Valor')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-text-field
          label="Plazo meses"
          min="0"
          type="number"
          name="Plazo meses"
          v-validate="'numeric|required|min_value:0'"
          v-model.number="value.plazo_meses"
          :error-messages="errors.collect('Plazo meses')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-text-field
          label="Plazo días"
          min="0"
          type="number"
          name="Plazo días"
          v-validate="'numeric|required|min_value:0'"
          v-model.number="value.plazo_dias"
          :error-messages="errors.collect('Plazo días')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex xs12 sm12 md12>
        <v-textarea
          v-model="value.objeto"
          auto-grow
          label="Objeto"
          rows="1"
          name="Objeto"
          v-validate="'required'"
          :error-messages="errors.collect('Objeto')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
    </v-layout>
    <v-layout row wrap>
      <v-flex xs12 class="pb-0 subheading grey--text text--darken-2">
        Población objetivo
        <v-divider/>
      </v-flex>
      <v-flex dflex>
        <v-checkbox
          label="Contributivo"
          v-model="value.contributivo"
          :false-value="0"
          :true-value="1"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex dflex>
        <v-checkbox
          label="Subsidiado"
          v-model="value.subsidiado"
          :false-value="0"
          :true-value="1"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex dflex>
        <v-checkbox
          label="Portabilidad"
          v-model="value.portabilidad"
          :false-value="0"
          :true-value="1"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-slide-x-reverse-transition>
        <v-flex v-if="!okPoblacionObjetivo" xs12 class="pt-0 caption text-xs-center red--text text--darken-2">
          Se debe seleccionar al menos una de las opciones de población objetivo.
        </v-flex>
      </v-slide-x-reverse-transition>
    </v-layout>
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'RegistroContratoDatosBasicos',
    props: ['value'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    data () {
      return {
        DetalleEntidad: () => import('@/components/misional/redservicios/entidades/DetalleEntidad'),
        menuDate: false,
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10),
        okPoblacionObjetivo: true
      }
    },
    watch: {
      'value.contributivo' (val) {
        this.okPoblacionObjetivo = this.validaPoblacionObjetivo()
      },
      'value.subsidiado' (val) {
        this.okPoblacionObjetivo = this.validaPoblacionObjetivo()
      },
      'value.portabilidad' (val) {
        this.okPoblacionObjetivo = this.validaPoblacionObjetivo()
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosContratoFormDatosBasica
      }
    },
    methods: {
      validaPoblacionObjetivo () {
        return (this.value.contributivo || this.value.subsidiado || this.value.portabilidad)
      },
      validate () {
        this.okPoblacionObjetivo = this.validaPoblacionObjetivo()
        let okValidacion = this.$validator.validateAll()
        return (this.okPoblacionObjetivo && okValidacion)
      },
      reset () {
        return this.$validator.reset()
      }
    }
  }
</script>

<style scoped>
</style>
