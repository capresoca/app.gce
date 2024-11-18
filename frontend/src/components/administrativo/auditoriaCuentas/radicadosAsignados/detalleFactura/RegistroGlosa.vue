<template>
  <v-card>
    <loading v-model="loading"/>
    <v-card-title class="grey lighten-3 py-2">
      <span class="font-weight-bold"> Nueva glosa </span>
    </v-card-title>
    <v-divider></v-divider>
    <v-container
      fluid
      grid-list-md
    >
      <v-layout row wrap>
        <v-flex xs12>
          <v-autocomplete
            label="Seleccionar glosa"
            :items="glosas.filter(x => x.capita === (capitado ? '1' : '0'))"
            v-model="glosa.manglosa"
            item-value="id"
            item-text="descripcion"
            :filter="filterGlosas"
            name="glosa"
            v-validate="'required'"
            :error-messages="errors.collect('glosa')"
            return-object
          >
            <template slot="selection" slot-scope="data">
              <div style="width: 100% !important;">
                <v-list-tile class="content-v-list-tile-p0">
                  <v-list-tile-content class="truncate-content">
                    <span class="ma-0 body-2">{{data.item.glosa}}: {{data.item.descripcion}}</span>
                    <span class="ma-0 body-2">Tipo: {{data.item.tipo}}</span>
                  </v-list-tile-content>
                </v-list-tile>
                <v-divider></v-divider>
              </div>
            </template>
            <template slot="item" slot-scope="data">
              <div style="width: 100% !important;">
                <v-list-tile class="content-v-list-tile-p0">
                  <v-list-tile-content class="truncate-content">
                    <span class="ma-0 body-1">{{data.item.glosa}}: {{data.item.descripcion}}</span>
                    <span class="ma-0 body-1">Tipo: {{data.item.tipo}}</span>
                  </v-list-tile-content>
                </v-list-tile>
              </div>
            </template>
          </v-autocomplete>
        </v-flex>
        <template v-if="glosa.manglosa">
          <template v-if="glosa.manglosa.tipo !== 'Devoluciones'">
            <v-flex xs12 sm12 :md8="glosa.tipo === 'Valor total' || glosa.tipo === null" :md4="glosa.tipo !== null && glosa.tipo !== 'Valor total'">
              <v-select
                label="Tipo"
                v-model="glosa.tipo"
                item-value="value"
                item-text="text"
                :items="[{value: 'Valor total', text: 'Valor total'}, {value: 'Valor', text: 'Valor parcial'}, {value: 'Porcentaje', text: 'Porcentaje'}]"
                :name="`Tipo`"
                v-validate="'required'"
                :error-messages="errors.collect(`Tipo`)"
                persistent-hint
                :hint="glosa.tipo === 'Valor total' && servicios.length === 1 ? currency(glosa.valor_glosa) : ''"
                :readonly="capitado"
              ></v-select>
            </v-flex>
            <v-flex xs12 sm8 md4 v-if="glosa.tipo && glosa.tipo !== 'Valor total'">
              <input-number
                v-if="glosa.tipo === 'Valor'"
                :key="'inputValor'"
                name="valor"
                prepend-icon="attach_money"
                v-model.number="glosa.valor_glosa"
                v-validate="`required|min_value:0` + (servicios.length === 1 ? `|max_value:${servicios[0].valor_total}` : '') "
                :error-messages="errors.collect(`valor`)"
              ></input-number>
              <v-text-field
                v-if="glosa.tipo === 'Porcentaje'"
                :key="'inputporcentaje'"
                label="Porcentaje"
                prefix="%"
                v-model.number="glosa.valor_glosa"
                type="number"
                min="0"
                max="100"
                name="porcentaje"
                v-validate="`required|min_value:0|max_value:100`"
                :error-messages="errors.collect(`porcentaje`)"
                :hint="servicios.length === 1 ? (glosa.valor_glosa ? currency((servicios[0].valor_total * glosa.valor_glosa) / 100) : '') : ''"
                persistent-hint
              ></v-text-field>
            </v-flex>
            <v-flex xs12 sm4 md4>
              <v-switch
                v-model="glosa.of_facservicio"
                label="Sumar"
                :true-value="null"
                :false-value="2"
                hide-details
              ></v-switch>
            </v-flex>
          </template>
          <v-flex xs12>
            <v-textarea
              rows="1"
              auto-grow
              label="Observaciones"
              v-model="glosa.observacion"
            ></v-textarea>
          </v-flex>
        </template>
      </v-layout>
    </v-container>
    <v-divider/>
    <v-card-actions>
      <v-spacer/>
      <span class="caption error--text mr-3"><strong>{{errorSobreValorado}}</strong></span>
      <v-btn flat @click.stop="$emit('cancelar')">Cancelar</v-btn>
      <v-btn flat color="success" @click.stop="registraGlosa">Registrar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    name: 'RegistroGlosa',
    props: ['servicios', 'glosas', 'window', 'capitado', 'facturaid'],
    components: {
      InputNumber: () => import('@/components/general/InputNumber'),
      Loading: () => import('@/components/general/Loading')
    },
    data: () => ({
      loading: false,
      filterGlosas (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.tipo + ' ' + item.glosa + ' ' + item.descripcion)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      glosa: null,
      errorSobreValorado: null,
      makeGlosa: {
        id: null,
        cm_facservicio_id: null,
        cm_manglosa_id: null,
        manglosa: null,
        observacion: null,
        tipo: null,
        valor_glosa: null,
        of_facservicio: null
      }
    }),
    watch: {
      'servicios.length' (val) {
        this.glosa.cm_facservicio_id = val === 1 ? this.servicios[0].id : null
        this.reloadHintValorGlosa()
        this.nuevaGlosa()
      },
      'glosa.tipo' (val) {
        if (val) {
          this.reloadHintValorGlosa()
        }
      },
      'glosa.manglosa' (val) {
        if (val) {
          this.glosa.cm_manglosa_id = val.id
        }
      },
      'window' () {
        this.nuevaGlosa()
      },
      'glosa.valor_glosa' () {
        this.validacionValorSuperado()
      },
      'glosa.of_facservicio' () {
        this.validacionValorSuperado()
      },
      'glosa' (val) {
        if (val && this.capitado && this.glosa) {
          this.glosa.tipo = 'Porcentaje'
        } else {
          this.glosa.tipo = null
        }
      }
    },
    created () {
      this.nuevaGlosa()
    },
    methods: {
      reloadHintValorGlosa () {
        if (this.servicios.length === 1) {
          this.glosa.valor_glosa = this.glosa.tipo === 'Valor total' ? this.servicios[0].valor_total : null
        } else {
          this.glosa.valor_glosa = null
        }
      },
      nuevaGlosa () {
        this.glosa = JSON.parse(JSON.stringify(this.makeGlosa))
        this.glosa.cm_facservicio_id = this.servicios.length === 1 ? this.servicios[0].id : null
        this.$validator.reset()
      },
      validacionValorSuperado () {
        this.errorSobreValorado = ''
        if (this.servicios.length && this.glosa && this.glosa.valor_glosa) {
          this.servicios.some((x, index) => {
            let valorGlosado = 0
            if ((this.glosa.tipo !== 'Porcentaje' ? this.glosa.valor_glosa : ((x.valor_total * this.glosa.valor_glosa) / 100)) > x.valor_total) {
              this.errorSobreValorado = `El valor de la glosa no puede ser mayor al valor del servicio, ITEM ${index + 1}`
              return true
            }
            if (x.glosas.length && !this.glosa.of_facservicio) {
              valorGlosado = this.lodash.sumBy(x.glosas.filter(j => !j.of_facservicio), 'valor_glosa')
            }
            valorGlosado = valorGlosado + (this.glosa.tipo !== 'Porcentaje' ? this.glosa.valor_glosa : ((x.valor_total * this.glosa.valor_glosa) / 100))
            this.errorSobreValorado = valorGlosado > x.valor_total ? `La suma de ésta glosa hace superar el valor glosado del item facturado, ITEM ${index + 1}` : ''
            if (this.errorSobreValorado) return true
          })
        }
        return this.errorSobreValorado === ''
      },
      async validate () {
        let validateForm = await this.$validator.validateAll()
        let validateValorSuperado = await this.validacionValorSuperado()
        return validateForm && validateValorSuperado
      },
      async registraGlosa () {
        if (await this.validate()) {
          this.loading = true
          console.log('this.servicios', this.servicios)
          let request = this.axios.post(this.servicios.length === 1 ? `facservicios/${this.glosa.cm_facservicio_id}/glosa` : `facservicios/masivos`, this.servicios.length === 1 ? this.glosa : {facservicios: this.servicios.map(x => x.id), item: 'glosa', glosa: this.glosa})
          request
            .then(response => {
              if (this.servicios.length === 1) this.servicios[0].glosas.splice(0, 0, response.data.data)
              this.loading = false
              this.$emit('success')
              this.$store.commit('SNACK_SHOW', {msg: `La glosa fue registrada correctamente.`, color: 'success'})
            })
            .catch(e => {
              this.loading = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: `al registrar la glosa`, error: e})
            })
        }
      }
    }
  }
</script>

<style scoped>

</style>
