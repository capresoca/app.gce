<template>
  <v-form data-vv-scope="formTabGeneral">
    <v-card class="elevation-0 transparent dense">
      <v-container fluid grid-list-xl>
        <v-layout row wrap>
          <v-flex xs12>
            <v-card >
              <v-card-title class="grey lighten-4 subheading">
                Tesorería
              </v-card-title>
              <v-container fluid grid-list-xl>
                <v-layout row wrap>
                  <v-flex xs12 sm6 md2>
                    <v-subheader class="pa-0 ma-0">Maneja Caja</v-subheader>
                    <v-switch class="ma-0 pa-0" :disabled="!value.id ? false : !isEditing"
                              color="accent" :label="value.maneja_caja"
                              v-model="value.maneja_caja"  true-value="Si" false-value="No"></v-switch>
                  </v-flex>
                  <v-flex xs12 sm6 md4>
                    <v-subheader class="pa-0 ma-0">Permitir Modificar Fecha En Los Documentos</v-subheader>
                    <v-switch class="ma-0 pa-0" :disabled="!value.id ? false : !isEditing"
                              color="accent" :label="value.modificar_fecha_documentos"
                              v-model="value.modificar_fecha_documentos"  true-value="Si" false-value="No"></v-switch>
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-subheader class="pa-0 ma-0">Control De Consecutivos Del Cheque</v-subheader>
                    <v-switch class="ma-0 pa-0" :disabled="!value.id ? false : !isEditing"
                              color="accent" :label="value.consecutivos_cheques"
                              v-model="value.consecutivos_cheques"  true-value="Si" false-value="No"></v-switch>
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-subheader class="pa-0 ma-0">Consecutivos De Recibos De Caja</v-subheader>
                    <v-radio-group class="pt-0 mt-0"
                      v-model="value.concecutivos_recivos_caja" :column="false" :disabled="!value.id ? false : !isEditing">
                      <v-radio key="consecutivoRecCajaSi" label="General" value="General"></v-radio>
                      <v-radio key="consecutivoRecCajaNo" label="Por Caja" value="Por caja"></v-radio>
                    </v-radio-group>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field label="Mensaje De Caja" prepend-inner-icon="description" v-model="value.mensaje_caja"></v-text-field>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card>
          </v-flex>
          <v-flex xs12>
            <v-card class>
              <v-card-title class="grey lighten-4 subheading">
                Interfaces
              </v-card-title>
              <v-container fluid grid-list-xl>
                <v-layout row wrap>
                  <v-flex xs12>
                    <v-subheader class="pa-0 ma-0">Afectar Presupuestos Con Documentos De Tesorería</v-subheader>
                    <v-radio-group class="pt-0 mt-0" v-model="value.afecta_presupuesto_doc_tesoreria" :column="false">
                      <v-radio key="afectaPresupuestosSi" label="Si" value="Si"></v-radio>
                      <v-radio key="afectaPresupuestosNo" label="No" value="No"></v-radio>
                    </v-radio-group>
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
    name: 'TabParametrosGenerales',
    props: ['value', 'isEditing'],
    inject: {
      $validator: '$validator'
    },
    watch: {
      'errors.items' (val) {
        this.$emit('errors', !(val.filter(item => item.scope === 'formTabGeneral').length > 0))
      }
    },
    methods: {
      validate () {
        return this.$validator.validateAll('formTabGeneral').then(result => { return result })
      }
    }
  }
</script>

<style scoped>

</style>
