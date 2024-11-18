<template>
    <v-card>
      <v-toolbar dense>
        <v-avatar size="40" color="primary">
          <v-icon class="white--text">{{infoComponent ? infoComponent.icono : ''}}</v-icon>
        </v-avatar>
        <v-toolbar-title>Proceso de asignación masiva de grupos de prestadores</v-toolbar-title>
      </v-toolbar>
      <v-container
        fluid
        grid-list-sm
        class="pb-0"
      >
        <loading v-model="loading"></loading>
        <v-layout row wrap>
          <v-flex xs12>
            <input-file-v2
              style="width: 100% !important;"
              label="Archivo"
              v-model="file"
              accept="text/plain"
              :hint="'Extenciones aceptadas: txt'"
              prepend-icon="description"
              name="archivo"
              v-validate="'required'"
              :error-messages="errors.collect('archivo')"
            ></input-file-v2>
          </v-flex>
          <v-flex xs12>
            <v-textarea
              rows="3"
              auto-grow
              label="Observaciones"
              v-model="observacion"
            ></v-textarea>
          </v-flex>
        </v-layout>
      </v-container>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn class="shrink ml-2" color="success" @click="submit" :loading="loading">Cargar archivo</v-btn>
      </v-card-actions>
      <v-slide-y-transition group>
        <template v-if="errores.length">
          <v-divider key="vdividerxx"></v-divider>
          <v-card key="cardErrores">
            <v-subheader class="title">
              <v-icon left color="error">fas fa-exclamation-triangle</v-icon>
              Errores en el archivo
            </v-subheader>
            <v-data-table key="datatableErrors"
                          :headers="headersErrores"
                          :items="errores"
                          class="elevation-1"
            >
              <template v-slot:items="props">
                <td>{{ props.item.tipo }}</td>
                <td>{{ props.item.titulo }}</td>
                <td>
                  <a v-if="props.item.id_afiliado" @click="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: {id: props.item.id_afiliado}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})">{{ props.item.afiliado }}</a>
                  <span v-else>{{props.item.afiliado}}</span>
                </td>
              </template>
            </v-data-table>
          </v-card>
        </template>
      </v-slide-y-transition>
    </v-card>
</template>

<script>
  export default {
    name: 'RegistroAsignacionMasiva',
    props: ['parametros'],
    components: {
      InputFileV2: () => import('@/components/general/InputFileV2')
    },
    data: () => ({
      loading: false,
      file: null,
      observacion: null,
      errores: [],
      headersErrores: [
        {
          text: 'Tipo',
          align: 'left',
          sortable: true,
          value: 'tipo'
        },
        {
          text: 'Descripción',
          align: 'left',
          sortable: false,
          value: 'titulo'
        },
        {
          text: 'Afiliado',
          align: 'left',
          sortable: true,
          value: 'afiliado'
        }
      ]
    }),
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('asignacionmasiva')
      }
    },
    watch: {
    },
    created () {
    },
    methods: {
      submit () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.errores = []
            this.loading = true
            let data = new FormData()
            data.append('file', this.file)
            data.append('observacion', this.observacion)
            data.append('tipo', 'Asignación')
            this.axios.post('rs_servicios/masivos', data)
              .then(response => {
                if (response.data.errors.length) {
                  this.errores = response.data.errors
                  this.$store.commit('SNACK_SHOW', {msg: 'El archivo contiene errores de validación. ', color: 'orange'})
                } else {
                  this.$store.commit('SNACK_SHOW', {msg: 'El archivo se ha cargado correctamente. ', color: 'success'})
                  this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: null })
                  this.$store.commit('reloadTable', 'tableAsignacionMasiva')
                }
                this.loading = false
              })
              .catch(e => {
                this.loading = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: ' al cargar el archivo de asignación masiva de grupos de prestadores. ', error: e})
              })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
