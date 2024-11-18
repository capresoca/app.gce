<template>
  <v-layout row wrap id="flag-top">
    <loading v-model="loading" />
    <v-flex xs12>
      <v-card>
        <v-toolbar dense class="elevation-0">
          <v-toolbar-title v-html="'Items de capacidad instalada'"/>
          <small class="mt-2 ml-1"> Registro y gestión por entidad</small>
          <v-spacer/>
          <v-tooltip top v-if="!showFormRegistro">
            <v-btn
              slot="activator"
              fab
              right
              small
              color="accent"
              @click="showFormRegistro = true"
            >
              <v-icon>add</v-icon>
            </v-btn>
            <span>Crear item</span>
          </v-tooltip>
        </v-toolbar>
        <v-scroll-y-transition>
          <div v-if="showFormRegistro">
            <v-card-text>
              <v-form>
                <v-layout row wrap>
                  <v-flex xs12 sm9 md10>
                    <v-autocomplete
                      v-model="item.rs_tipcapinstalada_id"
                      :hint="item.tipo ? 'item.tipo.description' : ''"
                      :items="tiposCapaciadad"
                      label="Tipo"
                      item-text="tipo"
                      item-value="id"
                      persistent-hint
                      name="Tipo"
                      v-validate="'required'"
                      :error-messages="errors.collect('Tipo')"
                      @keyup.enter="submitItem"
                    />
                  </v-flex>
                  <v-flex xs12 sm3 md2>
                    <v-text-field
                      label="Cantidad"
                      min="0"
                      type="number"
                      name="Cantidad"
                      v-validate="'required|numeric|min_value:0'"
                      v-model.number="item.cantidad"
                      :error-messages="errors.collect('Cantidad')"
                      @keyup.enter="submitItem"
                    />
                  </v-flex>
                </v-layout>
              </v-form>
            </v-card-text>
            <v-divider/>
            <v-card-actions>
              <v-spacer/>
              <v-btn flat @click.stop="showFormRegistro = false">Cancelar</v-btn>
              <v-btn color="primary" @click.stop="submitItem">Registrar</v-btn>
            </v-card-actions>
          </div>
        </v-scroll-y-transition>
      </v-card>
    </v-flex>
    <v-flex xs12 class="pa-0 ma-0">
      <v-card class="elevation-0">
        <v-card-title>
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="search"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="value.capinstaladas"
          :search="search"
          rows-per-page-text="Registros por página:"
        >
          <template slot="items" slot-scope="props">
            <td>{{ props.item.tipo.tipo }}</td>
            <td>{{ props.item.tipo.descripcion }}</td>
            <td class="text-xs-center">{{ props.item.cantidad }}</td>
            <td class="text-xs-center">
              <v-tooltip top>
                <v-btn
                  flat
                  icon
                  color="accent"
                  slot="activator"
                  @click="capacidadBorrar(props.item)"
                >
                  <v-icon>delete</v-icon>
                </v-btn>
                <span>Borrar</span>
              </v-tooltip>
              <v-tooltip top>
                <v-btn
                  flat
                  icon
                  color="accent"
                  slot="activator"
                  @click="capacidadEditar(props.item)"
                >
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
            </td>
          </template>
          <v-alert slot="no-results" :value="true" color="error" icon="warning">
            No hay registros para mostrar{{ search ? ' segun criterio de busqueda : ' + search : '.' }}
          </v-alert>
          <template slot="pageText" slot-scope="props">
            Mostrando registros del {{ props.pageStart }} al {{ props.pageStop }} de {{ props.itemsLength }}
          </template>
        </v-data-table>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroEntidadCapacidadInstalada',
    props: ['value'],
    components: {
      Loading
    },
    data: () => ({
      showFormRegistro: false,
      loading: false,
      item: null,
      makeItem: {
        id: null,
        rs_entidades_id: null,
        tipo: null,
        rs_tipcapinstalada_id: null,
        cantidad: null
      },
      tiposCapaciadad: [],
      search: null,
      headers: [
        {
          text: 'tipo',
          align: 'left',
          sortable: false,
          value: 'tipo.tipo'
        },
        {
          text: 'Descripción',
          align: 'left',
          sortable: false,
          value: 'tipo.descripcion'
        },
        {
          text: 'Cantidad',
          align: 'center',
          sortable: false,
          value: 'cantidad'
        },
        {
          text: 'Opciones',
          align: 'center',
          sortable: false,
          value: 'id'
        }
      ]
    }),
    watch: {
      'showFormRegistro' (val) {
        !val && this.refreshCapacidad()
      }
    },
    created () {
      this.getTiposCapacidad()
      this.refreshCapacidad()
    },
    methods: {
      getTiposCapacidad () {
        this.axios.get(`tipcapinstaladas`)
          .then((response) => {
            this.tiposCapaciadad = response.data.data
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al los tipos de items de capacidad instalada. ', error: e})
          })
      },
      capacidadEditar (item) {
        this.refreshCapacidad()
        this.item = JSON.parse(JSON.stringify(item))
        this.$vuetify.goTo('#flag-top',
          {
            selector: '#flag-top',
            duration: 300,
            offset: 0 - 120,
            easing: 'easeInOutQuad'
          }
        )
        this.showFormRegistro = true
      },
      capacidadBorrar (item) {
        this.loading = true
        this.showFormRegistro = false
        this.axios.post(`entidades/remove-capinstalada/${item.id}`)
          .then((response) => {
            let index = this.value.capinstaladas.findIndex(x => x.id === item.id)
            this.value.capinstaladas.splice(index > -1 ? index : 0, index > -1 ? 1 : 0)
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al borrar el registro. ', error: e})
          })
      },
      submitItem () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            this.item.rs_entidades_id = this.value.id
            this.axios.post(`entidades/add-capinstalada`, this.item)
              .then((response) => {
                let index = this.value.capinstaladas.findIndex(x => x.id === response.data.data.id)
                this.value.capinstaladas.splice(index > -1 ? index : 0, index > -1 ? 1 : 0, response.data.data)
                this.refreshCapacidad()
                this.loading = false
              })
              .catch(e => {
                this.loading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al realizar el registro. ', error: e})
              })
          }
        })
      },
      refreshCapacidad () {
        this.item = JSON.parse(JSON.stringify(this.makeItem))
        this.$validator.reset()
      }
    }
  }
</script>

<style scoped>
</style>
