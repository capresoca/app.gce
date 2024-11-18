<template>
  <v-layout row justify-center>
    <v-dialog key="dialogConfirm" v-model="value" persistent max-width="400">
      <loading v-model="loading" />
      <v-card>
        <v-card-text class="title font-weight-light text-xs-center" v-html="content"/>
        <v-divider/>
        <v-card-actions>
          <v-btn flat @click.stop="$emit('cancelar')">Cancelar</v-btn>
          <v-spacer/>
          <v-btn color="primary" @click.stop="submit" :loading="loadingSubmit" >Aceptar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
    import Loading from '@/components/general/Loading'
    export default {
      name: 'Confirmar',
      props: {
        value: {
          type: Boolean,
          default: false
        },
        content: {
          type: String
        },
        requiereMotivo: {
          type: Boolean,
          default: false
        },
        loading: {
          type: Boolean,
          default: false
        }
      },
      components: {
        Loading
      },
      data () {
        return {
          loadingSubmit: false,
          motivo: null
        }
      },
      watch: {
        'value' (val) {
          if (!val) {
            this.motivo = null
            this.$validator.reset()
          }
        }
      },
      methods: {
        async submit () {
          if (await this.$validator.validateAll()) {
            this.loadingSubmit = true
            this.$emit('aceptar', this.motivo)
          }
        },
        pararCarga () {
          this.loadingSubmit = false
        }
      }
    }
</script>

<style scoped>

</style>
