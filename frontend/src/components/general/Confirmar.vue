<template>
  <v-layout row justify-center>
    <v-dialog key="dialogConfirm" v-model="value" persistent max-width="400">
      <loading v-model="loading" />
      <v-card>
        <v-card-text class="title font-weight-light text-xs-center" v-html="content"/>
        <v-container v-if="requiereMotivo" fluid grid-list-xs>
          <v-layout row wrap>
            <v-flex xs12>
              <v-textarea
                name="motivo"
                label="Motivo"
                v-model="motivo"
                hint="Motivo por el cual se anula el registro."
                persistent-hint
                required
                v-validate="'required'"
                :error-messages="errors.collect('motivo')"
              ></v-textarea>
            </v-flex>
          </v-layout>
        </v-container>
        <v-divider/>
        <v-card-actions>
          <v-btn flat @click.stop="$emit('cancelar')">Cancelar</v-btn>
          <v-spacer/>
          <v-btn color="primary" @click.stop="submit">Aceptar</v-btn>
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
          default: true
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
            this.$emit('aceptar', this.motivo)
          }
        }
      }
    }
</script>

<style scoped>

</style>
