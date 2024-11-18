<template>
  <div>
    <v-card class="pa-0">
      <loading v-model="loading" />
      <v-card-text>
        <template v-if="value && value.rp">
          <v-list three-line>
            <v-list-tile>
              <v-list-tile-avatar color="purple" class="white--text">RP</v-list-tile-avatar>
              <v-list-tile-content>
                <v-list-tile-title>Registro presupuestal No. {{value.rp.consecutivo}} de {{value.rp.fecha}}</v-list-tile-title>
                <v-list-tile-sub-title>valor: {{ currency(value.rp.valor_compromiso)}}</v-list-tile-sub-title>
                <v-list-tile-sub-title>{{value.rp.observaciones}}</v-list-tile-sub-title>
              </v-list-tile-content>
              <v-list-tile-action>
                <v-tooltip top>
                  <v-btn flat icon color="green" slot="activator" @click.stop="filePrint(`reporte-pr-rp&id=${value.rp.id}`)">
                    <v-icon>far fa-file-pdf</v-icon>
                  </v-btn>
                  <span>Vista documento</span>
                </v-tooltip>
              </v-list-tile-action>
            </v-list-tile>
          </v-list>
          <v-card class="mb-2">
            <v-card-title><strong>Minuta del contrato</strong></v-card-title>
            <v-data-table
              :items="[value]"
              class="elevation-1"
              hide-actions
              hide-headers
            >
              <template slot="items" slot-scope="props">
                <td>
                  <v-avatar
                    color="primary"
                    size="40"
                    class="white--text"
                  >
                    <v-icon color="white">receipt</v-icon>
                  </v-avatar>
                </td>
                <td><strong>Minuta contrato No. {{ props.item.numero_contrato }}</strong></td>
                <td width="50%">
                  <input-file-v2
                    class="mb-2"
                    :name="'minuta' + props.index"
                    v-validate="'required'"
                    :error-messages="errors.collect('minuta' + props.index)"
                    data-vv-as="Minuta"
                    label="Archivo"
                    v-model="props.item.archivo_minuta"
                    :file-name="props.item.archivo_minuta ? props.item.archivo_minuta.nombre : null"
                    required
                    accept="application/pdf"
                    :hint="'Extenciones aceptadas: pdf'"
                    prepend-icon="description"
                    @input="enviominuta(props.item)"
                  />
                </td>
              </template>
            </v-data-table>
          </v-card>
          <v-card>
            <v-card-title><strong>Garantías</strong></v-card-title>
            <v-data-table
              v-if="value && value.proceso_contractual && value.proceso_contractual.estudios_previos && value.proceso_contractual.estudios_previos.garantias && value.proceso_contractual.estudios_previos.garantias.length"
              :items="value.proceso_contractual.estudios_previos.garantias"
              class="elevation-1"
              hide-actions
              hide-headers
            >
              <template slot="items" slot-scope="props">
                <td>
                  <v-avatar
                    color="teal"
                    size="30"
                    class="white--text"
                  >
                    {{props.index + 1}}
                  </v-avatar>
                </td>
                <td><strong>{{ props.item.garantia.nombre }}</strong></td>
                <td width="50%">
                  <input-file-v2
                    class="mb-2"
                    :name="'garantia' + props.index"
                    v-validate="'required'"
                    :error-messages="errors.collect('garantia' + props.index)"
                    data-vv-as="garantía"
                    label="Archivo"
                    v-model="props.item.archivo"
                    :file-name="props.item.archivo ? props.item.archivo.nombre : null"
                    required
                    accept="application/pdf"
                    :hint="'Extenciones aceptadas: pdf'"
                    prepend-icon="description"
                    @input="envioGarantia(props.item)"
                  />
                </td>
              </template>
            </v-data-table>
            <div v-else class="title grey--text text-xs-center font-weight-light pa-2">
              El proceso contractual no registra requerimientos de garantias.
            </div>
          </v-card>
        </template>
        <div v-else class="title grey--text text-xs-center font-weight-light pa-2">
          El contrato no tiene registro presupuestal. <v-icon>sentiment_very_dissatisfied</v-icon>
        </div>
      </v-card-text>
      <v-divider/>
      <v-card-actions v-if="value.estado === 'Confirmado'">
        <v-spacer/>
        <v-btn color="orange" @click.prevent="confirm" :loading="loading">Perfeccionar contrato</v-btn>
      </v-card-actions>
    </v-card>
    <confirmar
      :loading="dialogA.loading"
      :value="dialogA.visible"
      :content="dialogA.content"
      @cancelar="cancelaConfirmacion"
      :requiere-motivo="false"
      @aceptar="aceptaConfirmacion"
    />
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import InputFileV2 from '@/components/general/InputFileV2'
  export default {
    name: 'RegistroPerfeccionamiento',
    props: ['value', 'parent'],
    components: {
      Loading,
      InputFileV2,
      Confirmar: () => import('@/components/general/Confirmar')
    },
    data: () => ({
      dialogA: {
        loading: false,
        visible: false,
        content: null
      },
      loading: false
    }),
    created () {

    },
    watch: {
    },
    methods: {
      enviominuta (item) {
        this.loading = true
        let data = new FormData()
        data.append('archivo_minuta', item.archivo_minuta)
        data.append('minuta_firmada', item.minuta_firmada)
        this.axios.post(`minuta-firmada/${item.id}`, data)
          .then(response => {
            this.$store.commit('CONTRATO_RELOAD_ITEM', {item: {}, estado: 'reload', key: null})
            this.$store.commit('SNACK_SHOW', {msg: 'La minuta del contrato se ha cargado correctamente. ', color: 'success'})
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$nextTick(() => {
              item.archivo_minuta = null
            })
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al guardar la minuta del contrato. ', error: e})
          })
      },
      envioGarantia (item) {
        this.loading = true
        let data = new FormData()
        data.append('archivo', item.archivo)
        data.append('gn_archivo', item.gn_archivo)
        this.axios.post(`estpregarantias/${item.id}`, data)
          .then(response => {
            this.$store.commit('CONTRATO_RELOAD_ITEM', {item: {}, estado: 'reload', key: null})
            this.$store.commit('SNACK_SHOW', {msg: 'La garantía se ha cargado correctamente. ', color: 'success'})
            this.loading = false
          })
          .catch(e => {
            console.log('entra')
            this.loading = false
            this.$nextTick(() => {
              item.archivo = null
            })
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al guardar la garantía. ', error: e})
          })
      },
      async confirm () {
        if (await this.$validator.validateAll()) {
          this.dialogA.content = `El contrato será perfeccionado, si decide continuar, los datos no se podran editar nuevamente.<br/> <strong>¿Está seguro de confirmar los datos?</strong>`
          this.dialogA.visible = true
        }
      },
      cancelaConfirmacion () {
        this.dialogA.loading = false
        this.dialogA.visible = false
        this.dialogA.content = null
      },
      aceptaConfirmacion () {
        this.dialogA.loading = true
        this.value.estado = 'Legalizado'
        this.submit()
      },
      submit () {
        this.loading = true
        this.axios.put(`minutas/${this.value.id}`, this.value)
          .then(response => {
            this.$store.commit('CONTRATO_RELOAD_ITEM', {item: response.data.data, estado: 'reload', key: null})
            this.$store.commit('SNACK_SHOW', {msg: 'El contrato se ha perfeccionado correctamente. ', color: 'success'})
            this.$emit('input', response.data.data)
            this.loading = false
            this.cancelaConfirmacion()
          })
          .catch(e => {
            this.loading = false
            this.dialogA.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al perfeccionar el contrato. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
