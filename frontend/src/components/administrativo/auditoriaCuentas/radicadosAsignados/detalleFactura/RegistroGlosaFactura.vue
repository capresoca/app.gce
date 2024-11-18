<template>
  <v-dialog v-model="visible" persistent max-width="900">
    <v-card>
      <loading v-model="loading"/>
      <v-card-title class="grey lighten-3 py-2">
        <span class="font-weight-bold"> Nueva glosa de factura </span>
      </v-card-title>
      <v-divider></v-divider>
      <v-container
        class="pa-2"
        fluid
        grid-list-md
      >
        <v-layout row wrap>
          <v-flex xs12>
            <v-autocomplete
              label="Seleccionar glosa"
              :items="glosas"
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
          <v-flex xs12>
            <v-textarea
              rows="1"
              auto-grow
              label="Observaciones"
              v-model="glosa.observacion"
            ></v-textarea>
          </v-flex>
        </v-layout>
      </v-container>
      <v-divider/>
      <v-card-actions>
        <v-spacer/>
        <v-btn flat @click.stop="visible = false">Cancelar</v-btn>
        <v-btn flat color="success" @click.stop="registraGlosa">Registrar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'RegistroGlosaFactura',
    props: ['value', 'glosas'],
    components: {
      Loading: () => import('@/components/general/Loading')
    },
    data: () => ({
      visible: false,
      loading: false,
      filterGlosas (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.tipo + ' ' + item.glosa + ' ' + item.descripcion)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      glosa: null,
      makeGlosa: {
        id: null,
        cm_factura_id: null,
        cm_facservicio_id: null,
        cm_manglosa_id: null,
        manglosa: null,
        observacion: null,
        tipo: 'Valor total',
        valor_glosa: null
      }
    }),
    watch: {
      'glosa.manglosa' (val) {
        if (val) {
          this.glosa.cm_manglosa_id = val.id
          // if (val.tipo === 'Devoluciones') {
          //   this.glosa.tipo = 'Valor'
          //   this.glosa.valor_glosa = 0
          // } else {
          //   this.glosa.valor_glosa = null
          // }
        }// else {
        //   this.glosa.cm_manglosa_id = null
        //   this.glosa.tipo = 'Valor'
        //   this.glosa.valor_glosa = null
        // }
      }
    },
    created () {
      this.nuevaGlosa()
    },
    methods: {
      nuevaGlosa () {
        this.glosa = JSON.parse(JSON.stringify(this.makeGlosa))
        this.glosa.cm_factura_id = this.value.id
        this.$validator.reset()
      },
      registraGlosa () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            this.glosa.valor_glosa = this.value.valor_neto
            this.axios.post(`cm_facturas/${this.glosa.cm_factura_id}/glosa`, this.glosa)
              .then(response => {
                // this.value.glosas.splice(0, 0, response.data.data)
                this.$emit('refreshFactura')
                this.$store.commit('SNACK_SHOW', {msg: `La glosa de factura fue registrada correctamente.`, color: 'success'})
                this.loading = false
                this.close()
              })
              .catch(e => {
                this.loading = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: `al registrar la glosa de factura`, error: e})
              })
          }
        })
      },
      open () {
        this.nuevaGlosa()
        this.visible = true
      },
      close () {
        this.visible = false
      }
    }
  }
</script>

<style scoped>

</style>
