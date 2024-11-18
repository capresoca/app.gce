<template>
  <v-dialog
    v-model="dialog"
    persistent
    width="720"
  >
    <template v-if="btnOpen" v-slot:activator="{ on }">
      <v-btn flat icon color="primary" v-on="on">
        <v-icon>add</v-icon>
      </v-btn>
    </template>

    <v-card>
      <loading v-model="loading"></loading>
      <v-toolbar dense>
        <v-avatar color="primary" size="40">
          <v-icon class="white--text">fas fa-user-md</v-icon>
        </v-avatar>
        <v-toolbar-title>Registro médico</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat icon @click="dialog = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-container fluid grid-list-sm>
        <v-layout row wrap>
          <v-flex xs12 sm4 md2>
            <v-text-field
              label="Código"
              name="código"
              v-model="medico.codigo"
              v-validate="'required'"
              :error-messages="errors.collect('código')"
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm8 md10>
            <v-text-field
              label="Nombre"
              name="nombre"
              v-model="medico.descripcion"
              v-validate="'required'"
              :error-messages="errors.collect('nombre')"
            ></v-text-field>
          </v-flex>
          <v-flex xs12>
            <v-autocomplete
              ref="autocompleteespecialidad"
              label="Especialidad"
              v-model="medico.au_especialidad_id"
              :items="complementos.especialidadesAutorizacion"
              item-value="id"
              name="especialidad"
              required
              v-validate="'required'"
              :error-messages="errors.collect('especialidad')"
              :filter="filterEspecialidad">
              <template slot="item" slot-scope="data">
                <v-list-tile-content class="truncate-content" style="width: 100% !important;">
                  <v-list-tile-title>{{data.item.descripcion}}</v-list-tile-title>
                  <v-list-tile-sub-title>Nivel: {{data.item.nivel}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </template>
              <template slot="selection" slot-scope="data">
                <v-list-tile class="content-v-list-tile-p0" style="width: 100% !important;">
                  <v-list-tile-content class="truncate-content">
                    <v-list-tile-title>{{data.item.descripcion}}</v-list-tile-title>
                    <v-list-tile-sub-title>Nivel: {{data.item.nivel}}</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
              </template>
            </v-autocomplete>
          </v-flex>
        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn flat @click="dialog = false">
          cancelar
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          flat
          @click="submit"
        >
          Registrar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'DialogRegistroMedico',
    props: {
      btnOpen: {
        type: Boolean,
        default: true
      }
    },
    data: () => ({
      dialog: false,
      loading: false,
      medico: null,
      makeMedico: {
        id: null,
        codigo: null,
        descripcion: null,
        au_especialidad_id: null
      },
      filterEspecialidad (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.descripcion)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      }
    }),
    watch: {
      'dialog' (val) {
        if (!val) {
          setTimeout(() => {
            this.refresh()
          }, 400)
        }
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosFormRegistroMedico
      }
    },
    created () {
      this.refresh()
    },
    methods: {
      submit () {
        this.loading = true
        this.axios.post(`autorizaciones/medicos`, this.medico)
          .then((response) => {
            this.$store.commit('SNACK_SHOW', {msg: 'El médico se registró correctamente. ', color: 'success'})
            this.loading = false
            this.$emit('created', response.data.data)
            this.dialog = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al consultar los servicios primarios. ', error: e})
          })
      },
      refresh () {
        this.medico = JSON.parse(JSON.stringify(this.makeMedico))
        this.$validator.reset()
        if (this.$refs && this.$refs.autocompleteespecialidad) this.$refs.autocompleteespecialidad.reset()
      },
      open () {
        this.dialog = true
      }
    }
  }
</script>

<style scoped>

</style>
