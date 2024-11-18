<template>
  <div>
    <v-card :class="parent==='primary' ? 'pa-0' : ''">
      <loading v-model="loading" />
      <v-card-text>
        <ckeditor-document v-model="minuta"></ckeditor-document>
      </v-card-text>
      <v-divider/>
      <v-card-actions>
        <v-btn flat @click.prevent="cancel" :loading="loading">Cancelar</v-btn>
        <v-spacer/>
        <v-btn flat color="primary" @click.prevent="save" :loading="loading">Guardar</v-btn>
      </v-card-actions>
    </v-card>
    <confirmar
      :loading="dialogCancelar.loading"
      :value="dialogCancelar.visible"
      :content="dialogCancelar.content"
      @cancelar="dialogCancelar.visible = false"
      :requiere-motivo="false"
      @aceptar="aceptaCancelacion"
    />
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  export default {
    name: 'RegistroMinuta',
    props: ['value', 'parent'],
    components: {
      CkeditorDocument: () => import('@/components/general/CkeditorDocument'),
      Loading,
      Confirmar: () => import('@/components/general/Confirmar')
    },
    data: () => ({
      dialogCancelar: {
        loading: false,
        visible: false,
        content: null
      },
      loading: false,
      minutaOriginal: null,
      minuta: null,
      otro: ''
    }),
    created () {
      this.minuta = JSON.parse(JSON.stringify(this.value.minuta))
      this.minutaOriginal = JSON.parse(JSON.stringify(this.value.minuta))
    },
    watch: {
      'value' (val) {
        if (val) {
          this.minuta = JSON.parse(JSON.stringify(val.minuta))
          this.minutaOriginal = JSON.parse(JSON.stringify(val.minuta))
        }
      }
    },
    methods: {
      cancel () {
        if (JSON.stringify(this.minutaOriginal) === JSON.stringify(this.minuta)) {
          this.aceptaCancelacion()
        } else {
          this.dialogCancelar.content = `<strong>la Minuta del contrato No. ${this.value.numero_contrato} perderá los cambios realizados recientemente.</strong><br/> ¿Está seguro de continuar?`
          this.dialogCancelar.visible = true
        }
      },
      aceptaCancelacion () {
        this.dialogCancelar.loading = true
        this.minuta = JSON.parse(JSON.stringify(this.minutaOriginal))
        this.value.minuta = this.minuta
        this.axios.put(`minutas/${this.value.id}`, this.value)
          .then(response => {
            this.$emit('cancelar')
            this.dialogCancelar.loading = false
            this.dialogCancelar.visible = false
          })
          .catch(e => {
            this.dialogCancelar.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al restaurar el contenido de la minuta. ', error: e})
          })
      },
      async save (message = true) {
        return new Promise(resolve => {
          if (this.minuta) {
            this.loading = true
            this.value.minuta = this.minuta
            this.axios.put(`minutas/${this.value.id}`, this.value)
              .then(response => {
                this.$store.commit('CONTRATO_RELOAD_ITEM', {item: response.data.data, estado: 'reload', key: null})
                if (message) this.$store.commit('SNACK_SHOW', {msg: 'El contenido de la minuta de ha guardado correctamente. ', color: 'success'})
                this.loading = false
                resolve(true)
              })
              .catch(e => {
                this.loading = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: ' al guardar el contenido de la minuta. ', error: e})
                resolve(false)
              })
          } else {
            this.$store.commit('SNACK_SHOW', {msg: 'El contenido de la minuta es invalido. ', color: 'warning'})
            resolve(false)
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
