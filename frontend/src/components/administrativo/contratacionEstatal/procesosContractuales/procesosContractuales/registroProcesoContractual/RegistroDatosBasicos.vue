<template>
  <v-card>
    <loading v-model="loading" />
    <v-card-text>
      <form-datos-basicos v-model="procesoContractual" ref="sBasicos" />
    </v-card-text>
    <v-divider/>
    <v-card-actions>
      <v-spacer/>
      <v-btn color="primary" @click.prevent="save" :loading="loading">Guardar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {PROCESOCONTRACTUAL_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import FormDatosBasicos from '@/components/administrativo/contratacionEstatal/procesosContractuales/procesosContractuales/registroProcesoContractual/FormDatosBasicos'

  export default {
    name: 'RegistroDatosBasicos',
    components: {
      Loading,
      FormDatosBasicos
    },
    props: {
      value: {
        type: Object,
        default: null
      }
    },
    data () {
      return {
        procesoContractual: null,
        loading: false
      }
    },
    watch: {
      'value' (val) {
        this.procesoContractual = val
      }
    },
    created () {
      this.procesoContractual = this.value
    },
    methods: {
      async save () {
        if (await this.$refs.sBasicos.validate()) {
          this.loading = true
          const typeRequest = this.procesoContractual.id ? 'editar' : 'crear'
          const request = typeRequest === 'editar' ? this.axios.put('ce_procontractuales/' + this.procesoContractual.id, this.procesoContractual) : this.axios.post('ce_procontractuales', this.procesoContractual)
          request
            .then(response => {
              if (response.data.procontractuale_o.estudios_previos && !response.data.procontractuale_o.estudios_previos.proconminutageos) {
                response.data.procontractuale_o.estudios_previos.proconminutageos = []
              }
              this.$store.commit(SNACK_SHOW, {msg: 'Los datos básicos del proceso contractual se registraron correctamente ', color: 'success'})
              this.$store.commit(PROCESOCONTRACTUAL_RELOAD_ITEM, {item: response.data.procontractuale, estado: typeRequest, key: null})
              this.procesoContractual = response.data.procontractuale_o
              this.$emit('input', this.procesoContractual)
              this.loading = false
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar los datos básicos del proceso contractual. ', error: e})
              this.loading = false
            })
        }
      }
    }
  }
</script>

<style scoped>

</style>
