<template>
  <v-form data-vv-scope="tabFormConsecutivo">
    <v-card class="transparent elevation-0 dense">
      <v-container fluid grid-list-xl>
        <v-layout row wrap>
          <v-flex xs12>
            <v-card>
              <v-card-title class="grey lighten-4 subheading">
                Aprovechamiento
              </v-card-title>
              <v-container fluid grid-list-xl>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-text-field label="Recivos de caja"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-text-field label="Transacciones electronicas"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-text-field label="Comprobantes de egreso"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-text-field label="Venta de titulos"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-text-field label="Consignaciones"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-text-field label="Relaciones de giro"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-text-field label="Notas"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-text-field label="Cancelacion de honorarios"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-text-field label="Compra de titulos"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg6>
                <v-text-field label="Acreedores varios"></v-text-field>
              </v-flex>
                </v-layout>
              </v-container>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
  </v-form>
</template>

<script>
  export default {
    name: 'TabParametrosConsecutivos',
    props: ['value', 'isEditing'],
    inject: {
      $validator: '$validator'
    },
    watch: {
      'errors.items' (val) {
        this.$emit('errors', !(val.filter(item => item.scope === 'tabFormConsecutivo').length > 0))
      }
    },
    methods: {
      validate () {
        return this.$validator.validateAll('tabFormConsecutivo').then(result => { return result })
      }
    }
  }
</script>

<style scoped>

</style>
