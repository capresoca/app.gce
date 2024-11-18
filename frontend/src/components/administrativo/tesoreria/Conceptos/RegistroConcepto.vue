<template>
  <div>
    <v-toolbar dense>
      <v-icon>{{infoComponent ? infoComponent.icono : ''}}</v-icon>
      <v-toolbar-title>{{concepto.id ? 'Edición de' : 'Nuevo'}} concepto de tesorería</v-toolbar-title>
      <v-spacer/>
      <v-toolbar-items>
        <v-select
          v-if="!concepto.id"
          label="Tipo de concepto"
          :items="tiposConcepto"
          v-model="tipoConcepto"
          hide-details
        >
        </v-select>
        <input-detail-flex
          v-else
          label="Tipo de concepto"
          :text="tiposConcepto.find(x => x.value === tipoConcepto).text"
        ></input-detail-flex>
      </v-toolbar-items>
    </v-toolbar>
    <div v-if="!tipoConcepto" class="title grey--text text-xs-center font-weight-light pa-5">
      Seleccione un tipo de concepto
    </div>
    <template v-else>
      <registro-concepto-recibos @input="val => concepto = val" v-if="tipoConcepto === 1" :configuracion="configuracion" :parametros="concepto"></registro-concepto-recibos>
      <registro-concepto-egreso v-if="tipoConcepto === 2" :configuracion="configuracion" :parametros="concepto"></registro-concepto-egreso>
      <registro-concepto-notas v-if="tipoConcepto === 3" :configuracion="configuracion" :parametros="concepto"></registro-concepto-notas>
    </template>
  </div>
</template>
<script>
  import RegistroConceptoRecibos from '@/components/administrativo/tesoreria/Conceptos/RegistroConceptosRecibosCaja'
  import RegistroConceptoEgreso from '@/components/administrativo/tesoreria/Conceptos/RegistroConceptoEgreso'
  import RegistroConceptoNotas from '@/components/administrativo/tesoreria/Conceptos/RegistroConceptoNotas'
  export default {
    name: 'RegistroConceptosTesoreria',
    props: ['parametros'],
    components: {
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      RegistroConceptoRecibos,
      RegistroConceptoEgreso,
      RegistroConceptoNotas
    },
    data () {
      return {
        concepto: {},
        tiposConcepto: [{value: 1, text: 'Recibo de caja'}, {value: 2, text: 'Comprobante de egreso'}, {value: 3, text: 'Nota bancaria'}],
        tipoConcepto: null,
        configuracion: null
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('conceptosTesoreria')
      }
    },
    created () {
      this.getParametros()
      if (this.parametros.entidad !== null) {
        this.concepto = this.parametros.entidad
        this.tipoConcepto = this.parametros.tipo === 'caja' ? 1 : this.parametros.tipo === 'egreso' ? 2 : 3
      }
    },
    methods: {
      async getParametros () {
        this.axios.get(`ts_parametros`)
          .then(response => {
            console.log('response', response)
            if (response.data.data) {
              this.configuracion = response.data.data
            } else {
              this.$store.commit('SNACK_SHOW', {msg: 'No se ha realizado la parametrización de tesorería.', color: 'error'})
            }
          })
          .catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los parametros de tesorería. ', error: e})
          })
      }
    }
  }
</script>
