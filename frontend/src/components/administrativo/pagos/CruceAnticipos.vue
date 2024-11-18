<template>
  <div>
    <v-card ref="formAnticipos">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> Cruce Anticipos </v-toolbar-title>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12>
            <v-form data-vv-scope="formAnticipos">
              <v-container fluid grid-list-md>

                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm6>

                          </v-flex>


                        </v-layout>

                      </v-card-text>
                    </v-card>
                  </v-flex>


                </v-layout>
              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 class="text-xs-right">
            <v-btn>Cancelar</v-btn>
            <v-btn color="primary"  :loading="loadingSubmit">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import { SNACK_ERROR_LIST } from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'CruceAnticipos',
    props: ['parametros'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        anticipo: {},
        loadingSubmit: false
      }
    },
    mounted () {
      console.log('parametros', this.parametros.entidad)
      this.getCxPTercero()
    },
    methods: {
      getCxPTercero () {
        let loader = this.$loading.show({
          container: this.$refs.formAnticipos.$el
        })
        this.axios.get('cxps/tercero/' + this.parametros.entidad.tercero.id)
          .then(response => {
            console.log('response', response)
            this.anticipo = response.data.facturas
            loader.hide()
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al hacer la busqueda de registros. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
