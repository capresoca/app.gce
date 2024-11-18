<template>
  <v-container grid-list-md style="max-width: inherit" ref="formAltoCosto">

    <v-form data-vv-scope="formAltoCostos">
      <v-container fluid grid-list-md>
        <v-layout row wrap>

          <v-flex xs12 sm4>
            <v-select
              :items="tipos"
              v-model="altocosto.tipo"
              label="Tipo"
              name="tipo"
              :error-messages="errors.collect('tipo')"
              v-validate="'required'" required
              prepend-icon="assignment"
              :disabled="concurrenciaObj.estado === 'Cerrada'"
            ></v-select>
          </v-flex>

          <v-flex xs12 sm8>
            <postulador-v2
              no-data="Busqueda por código, descripcion y genero"
              hint="codigo"
              item-text="descripcion"
              data-title="codigo"
              data-subtitle="descripcion"
              label="Diagnóstico"
              entidad="rs_cie10s"
              preicon="reorder"
              @changeid="val => altocosto.rs_cie10_id = val"
              v-model="altocosto.diagnostico"
              name="diagnóstico"
              rules="required"
              v-validate="'required'"
              :error-messages="errors.collect('diagnóstico')"
              no-btn-create
              :min-characters-search="3" clearable
              :disabled="concurrenciaObj.estado === 'Cerrada'"
            />
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider/>

    </v-form>
    <v-card-actions>

      <v-spacer/>
      <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit">Guardar</v-btn>
    </v-card-actions>
  </v-container>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'

  export default {
    name: 'ConAltoCosto',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    props: {
      concurrenciaId: {
        type: Number,
        default: 0
      },
      concurrenciaObj: {
        type: Object,
        defatul: {}
      }
    },
    data () {
      return {
        altocosto: {},
        tipos: ['Artritis', 'Cancer', 'Enfermedades Huerfanas', 'Enfermedad Renal Cronica', 'Hemofilia', 'VIH'],
        loadingSubmit: false
      }
    },
    mounted () {
      this.getRegisto()
    },
    methods: {
      getRegisto () {
        let loader = this.$loading.show({
          container: this.$refs.formAltoCosto.$el
        })
        this.axios.get('cuentasmedicas/concurrencia/altocosto/' + this.concurrenciaId)
          .then((response) => {
            if (response.data !== '') {
              this.altocosto = response.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el alto costo. ' + e.response, color: 'danger'})
          })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formAltoCostos')) {
          this.loadingSubmit = true
          this.altocosto.cm_concurrencia_id = this.concurrenciaId
          console.log('altocosto', this.altocosto)
          let send = !this.altocosto.id ? this.axios.post('cuentasmedicas/concurrencia/altocosto', this.altocosto) : this.axios.put('cuentasmedicas/concurrencia/altocosto/' + this.altocosto.id, this.altocosto)
          send.then(response => {
            if (this.altocosto.id) {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
            } else {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
            }
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro. ', error: e})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>
