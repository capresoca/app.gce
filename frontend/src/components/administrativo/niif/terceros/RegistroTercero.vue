<template>
  <div>
    <v-toolbar>
      <v-icon>{{!tercero.id ? 'person_add' : 'person'}}</v-icon>
      <v-toolbar-title>{{!tercero.id ? 'Nuevo tercero' : 'Edición de tercero' }}</v-toolbar-title>
    </v-toolbar>
    <tercero ref="cTercero" @update="val => tercero = val"/>
    <v-layout row wrap>
      <v-flex xs6 class="text-xs-left">
        <v-btn @click="refresh(null)" :loading="loadingSubmit">Limpiar</v-btn>
      </v-flex>
      <v-flex xs6 class="text-xs-right">
        <v-btn @click="submit" color="primary" :loading="loadingSubmit">Guardar</v-btn>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
  import Tercero from '@/components/administrativo/niif/terceros/FormTercero'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {TERCERO_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'RegistroTercero',
    props: ['parametros'],
    inject: {
      $validator: '$validator'
    },
    components: {
      Tercero
    },
    data () {
      return {
        tercero: {},
        loadingSubmit: false
      }
    },
    mounted () {
      this.refresh()
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
    },
    methods: {
      refresh () {
        this.$refs.cTercero.stepActual = 1
        this.$refs.cTercero.assign(this.$store.getters.newTercero)
        this.$validator.reset()
      },
      getRegistro (id) {
        this.axios.get('terceros/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.$refs.cTercero.assign(response.data.data)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el tercero. ', error: e})
          })
      },
      async submit () {
        if (await this.$refs.cTercero.validate()) {
          this.loadingSubmit = true
          const typeRequest = this.tercero.id ? 'editar' : 'crear'
          this.axios.post('terceros', this.tercero).then(response => {
            this.$store.commit(SNACK_SHOW, {msg: response.data.message, color: 'success'})
            this.$store.commit(TERCERO_RELOAD_ITEM, {item: response.data.tercero, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro. ', error: e})
          })
        }
      }
    }
  }
</script>

<style scoped>

</style>
