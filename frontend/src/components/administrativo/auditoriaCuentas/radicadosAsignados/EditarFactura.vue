<template>
  <v-dialog v-model="dialog" persistent max-width="480">
    <v-card>
      <v-card-title class="headline">Actualización de Factura {{factura && factura.consecutivo ? factura.consecutivo : ''}} </v-card-title>
      <loading v-model="loading"></loading>
      <v-card-text
        v-if="modelo"
      >
        <v-text-field
          label="Consecutivo Factura"
          name="consecutivo factura"
          v-validate="'required'"
          :error-messages="errors.collect('consecutivo factura')"
          v-model="modelo.factura.consecutivo"
        >
        </v-text-field>
        <v-autocomplete
          label="Plan de cobertura"
          name="plan cobertura"
          v-model="planCobertura"
          :items="planes"
          required
          v-validate="'required'"
          item-value="id"
          return-object
          :error-messages="errors.collect('plan cobertura')"
          :filter="filterPlanCobertura"
          :hint="planCobertura ? 'Contrato:' + planCobertura.contrato.numero_contrato : ''"
          persistent-hint
          @change="val => modelo.rs_contrato_id = val.id"
        >
          <template slot="item" slot-scope="data">
            <v-list-tile-content class="truncate-content" style="width: 100% !important;">
              <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
              <v-list-tile-sub-title>Contrato: {{data.item.contrato.numero_contrato}}</v-list-tile-sub-title>
            </v-list-tile-content>
          </template>
          <template slot="selection" slot-scope="data">
            <span>{{data.item.nombre}}</span>
          </template>
        </v-autocomplete>
        <input-number
          label="Valor Neto"
          name="valor neto"
          prepend-icon="attach_money"
          v-model.number="modelo.factura.valor_neto"
          v-validate="`required|min_value:0`"
          :error-messages="errors.collect(`valor neto`)"
        ></input-number>
        <input-number
          label="Valor Compartido"
          name="valor compartido"
          prepend-icon="attach_money"
          v-model.number="modelo.factura.valor_compartido"
          v-validate="`required|min_value:0`"
          :error-messages="errors.collect(`valor compartido`)"
        ></input-number>
        <input-number
          label="Valor Descuentos"
          name="valor descuentos"
          prepend-icon="attach_money"
          v-model.number="modelo.factura.valor_descuentos"
          v-validate="`required|min_value:0`"
          :error-messages="errors.collect(`valor descuentos`)"
        ></input-number>
        <input-number
          label="Valor Comisión"
          name="valor comisión"
          prepend-icon="attach_money"
          v-model.number="modelo.factura.valor_comision"
          v-validate="`required|min_value:0`"
          :error-messages="errors.collect(`valor comisión`)"
        ></input-number>
      </v-card-text>
      <v-card-actions>
        <v-btn flat @click="dialog = false">Cancelar</v-btn>
        <v-spacer></v-spacer>
        <v-btn color="primary" flat @click="submit">Actualizar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'EditarFactura',
    data: () => ({
      dialog: false,
      loading: false,
      factura: null,
      radicado: null,
      modelo: null,
      planCobertura: null,
      planes: [],
      filterPlanCobertura (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.contrato.numero_contrato + ' ' + item.nombre)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      }
    }),
    methods: {
      submit () {
        this.loading = true
        this.axios.put(`cmcapitadas/${this.modelo.factura.id}`, this.modelo)
          .then(response => {
            this.$store.commit('reloadTable', 'tableFacturasRadicadas')
            this.$store.commit('SNACK_SHOW', {msg: `La factura ${this.factura.consecutivo} se actualizó.`, color: 'success'})
            this.dialog = false
            this.loading = false
          }).catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: 'Hay un error al enviar los datos.', error: e})
          })
      },
      assign (item) {
        this.planCobertura = null
        this.planes = []
        this.dialog = true
        this.loading = true
        this.axios.get(`radicado_capitados/${item.id}`)
          .then(response => {
            this.factura = response.data.factura
            this.radicado = response.data.radicado
            this.modelo = {
              id_radicado: this.factura.cm_radicado_id,
              rs_contrato_id: this.radicado.rs_contrato_id,
              factura: {
                id: this.factura.id,
                consecutivo: this.factura.consecutivo,
                valor_neto: this.factura.valor_neto,
                valor_compartido: this.factura.valor_compartido,
                valor_descuentos: this.factura.valor_descuentos,
                valor_comision: this.factura.valor_comision
              }
            }
            this.axios.get(`radicadas/contratos_ips/${this.radicado.rs_entidad_id}`)
              .then(response => {
                this.planes = response.data.data
                if (this.radicado.rs_contrato_id && this.planes.find(x => x.id === this.radicado.rs_contrato_id)) {
                  this.planCobertura = this.planes.find(y => y.id === this.radicado.rs_contrato_id)
                }
                this.loading = false
              }).catch(() => {
                this.loading = false
                this.$store.commit('SNACK_SHOW', {msg: `Error al recuperar los contratos de la entidad: ${this.factura.entidad.nombre}.`, color: 'danger'})
              })
          }).catch(() => {
            this.loading = false
            this.$store.commit('SNACK_SHOW', {msg: `Error al recuperar la información de la factura ${item.no_factura}.`, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
