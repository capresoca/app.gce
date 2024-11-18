<template>
  <div>
    <v-card>
      <loading v-model="loading" />
      <v-toolbar dense class="elevation-0">
        <v-icon>receipt</v-icon>
        <v-toolbar-title>Minuta contrato {{contrato && contrato.numero_contrato ? `No. ${contrato.numero_contrato}` : 'No definido'}}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-tooltip top>
          <v-btn
            slot="activator"
            icon
            large
            :loading="loadingPrint"
            @click="imprimirMinuta"
          >
            <v-icon
              medium
              color="success"
            >
              far fa-file-pdf
            </v-icon>
          </v-btn>
          <span>Vista previa</span>
        </v-tooltip>
      </v-toolbar>
      <registro-minuta ref="registroMinuta" @cancelar="cancelar" v-if="contrato" v-model="contrato"></registro-minuta>
    </v-card>
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import RegistroMinuta from '@/components/administrativo/contratacionEstatal/procesosContractuales/contratos/registroContrato/RegistroMinuta'
  export default {
    name: 'MinutaContrato',
    props: ['parametros'],
    components: {
      Loading,
      RegistroMinuta
    },
    data: () => ({
      loading: false,
      loadingPrint: false,
      contrato: null
    }),
    created () {
      this.getRegistro()
    },
    methods: {
      cancelar () {
        this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: this.parametros.tabOrigin })
      },
      async imprimirMinuta () {
        this.loadingPrint = true
        if (await this.$refs.registroMinuta.save(false)) this.filePrint(`minuta-pdf&id=${this.contrato.id}`)
        this.loadingPrint = false
      },
      getRegistro () {
        if (this.parametros.entidad && this.parametros.entidad.id) {
          this.loading = true
          this.axios.get(`minutas/${this.parametros.entidad.id}`)
            .then(response => {
              if (response.data !== '') {
                response.data.data.fecha_contrato = response.data.data.fecha_contrato ? this.moment(response.data.data.fecha_contrato).format('YYYY-MM-DD') : null
                response.data.data.fecha_acta_inicio = response.data.data.fecha_acta_inicio ? this.moment(response.data.data.fecha_acta_inicio).format('YYYY-MM-DD') : null
                this.contrato = response.data.data
                this.loading = false
              }
            })
            .catch(e => {
              this.loading = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer el contrato. ', error: e})
            })
        } else {
          this.$store.commit('SNACK_ERROR_LIST', {expression: ' Ésta petición no viene de un contrato valido.'})
          this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: this.parametros.tabOrigin })
        }
      }
    }
  }
</script>

<style scoped>

</style>
