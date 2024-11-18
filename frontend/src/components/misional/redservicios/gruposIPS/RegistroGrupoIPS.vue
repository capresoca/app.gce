<template>
  <v-dialog v-model="dialog" persistent max-width="920">
    <v-card>
      <loading v-model="loading"></loading>
      <v-toolbar dense>
        <v-toolbar-title>
          <v-avatar color="primary" size="38">
            <v-icon class="white--text">{{icon}}</v-icon>
          </v-avatar>
          {{grupo && grupo.id ? 'Nuevo ' : 'Edición de '}}Grupo de IPS
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat icon @click.stop="dialog = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-container
        fluid
        grid-list-md
      >
        <v-layout row wrap>
          <v-flex xs12 sm12 md6>
            <v-autocomplete
              label="Departamento"
              v-model="grupo.departamento_id"
              :items="complementos.departamentos"
              item-value="id"
              item-text="nombre"
              name="departamento"
              required
              v-validate="'required'"
              :error-messages="errors.collect('departamento')"
              :filter="filterMunicipios"
            >
            </v-autocomplete>
          </v-flex>
          <v-flex xs12 sm12 md6>
            <v-autocomplete
              :disabled="!grupo.departamento_id"
              label="Municipio"
              v-model="grupo.municipio_id"
              :items="grupo.departamento_id ? complementos.municipios.filter(x => x.gn_departamento_id === grupo.departamento_id) : []"
              item-value="id"
              item-text="nombre"
              name="municipio"
              required
              v-validate="'required'"
              :error-messages="errors.collect('municipio')"
              :filter="filterMunicipios"
            >
            </v-autocomplete>
          </v-flex>
          <v-flex xs12 sm8>
            <v-textarea
              label="Descripción"
              v-model="grupo.descrip"
              name="descripción"
              v-validate="'required'"
              :error-messages="errors.collect('descripción')"
              rows="1"
              auto-grow
            ></v-textarea>
          </v-flex>
          <v-flex xs12 sm4>
            <v-switch
              v-model="grupo.portable"
              label="Portabilidad"
              :true-value="1"
              :false-value="0"
              primary
              hide-details
              @change="getPrestadores"
            ></v-switch>
          </v-flex>
          <v-flex xs12>
            <v-card>
              <v-toolbar dense>
                <v-toolbar-title>Prestadores asignados</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <v-data-table
                  :headers="headersPrestadores"
                  :items="grupo.prestadores"
                  hide-actions
                >
                  <template v-slot:items="props">
                    <tr :active="props.item.primaria">
                      <td>
                        <v-checkbox
                          v-model="props.item.primaria"
                          :true-value="1"
                          :false-value="0"
                          primary
                          hide-details
                          @change="reloadPrimaria(props)"
                        ></v-checkbox>
                      </td>
                      <td>
                        <v-list-tile-content style="width: 100%">
                          <v-list-tile-title>{{props.item.entidad.nombre}}</v-list-tile-title>
                          <v-list-tile-sub-title class="caption"><v-icon size="small">fas fa-phone-alt</v-icon> {{props.item.entidad.telefono_sede}} <v-icon size="small">fas fa-map-signs</v-icon> {{props.item.entidad.direccion_sede}}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </td>
                      <td class="text-xs-center">
                        <v-tooltip top>
                          <v-btn color="danger" slot="activator" flat icon @click.stop="grupo.prestadores.splice(props.index, 1)">
                            <v-icon>delete</v-icon>
                          </v-btn>
                          <span>Borrar Registro</span>
                        </v-tooltip>
                      </td>
                    </tr>
                  </template>
                </v-data-table>
              </v-card-text>
              <v-divider></v-divider>
              <v-card-actions>
                <v-autocomplete
                  label="Seleccionar Prestador"
                  v-model="prestadorSeleccionado"
                  :items="prestadoresASeleccionar"
                  item-value="id"
                  item-text="nombre"
                  return-object
                  :filter="filterIPSs"
                  no-data-text="No hay prestadores para seleccionar"
                  :loading="loadingPrestadores"
                  @input="pushPrestador"
                  prepend-icon="search"
                >
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                        <v-list-tile-sub-title class="caption"><v-icon size="small">fas fa-phone-alt</v-icon> {{data.item.telefono_sede}} <v-icon size="small">fas fa-map-signs</v-icon> {{data.item.direccion_sede}}</v-list-tile-sub-title>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn flat @click="dialog = false">Cancelar</v-btn>
        <v-spacer></v-spacer>
        <span class="caption error--text mr-3"><strong>{{errorButton}}</strong></span>
        <v-btn color="primary darken-1" flat @click="submit">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'RegistroGrupoIPS',
    props: {
      icon: {
        type: String,
        default: ''
      }
    },
    data: () => ({
      dialog: false,
      loading: false,
      grupo: null,
      makeGrupo: {
        id: null,
        municipio_id: null,
        departamento_id: null,
        descrip: null,
        portable: 0,
        prestadores: []
      },
      serviciosPrimarios: [],
      prestadoresASeleccionar: [],
      loadingPrestadores: false,
      prestadorSeleccionado: null,
      servicio_id: null,
      headersPrestadores: [
        {
          text: 'Primaria',
          align: 'left',
          sortable: false
        },
        {
          text: 'Entidad',
          align: 'left',
          sortable: false
        },
        {
          text: '',
          align: 'center',
          sortable: false
        }
      ],
      filterIPSs (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.cod_habilitacion + ' ' + item.nombre)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      filterMunicipios (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      errorButton: null
    }),
    watch: {
      dialog (val) {
        !val && this && this.closer()
      },
      'grupo.municipio_id': {
        handler (val) {
          val && this.getPrestadores()
        },
        immediate: true
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosGruposIPS
      }
    },
    created () {
      this.grupo = {...this.makeGrupo}
      this.getServiciosPrimarios()
    },
    methods: {
      async submit () {
        if (await this.validate()) {
          this.loading = true
          let data = {...this.grupo}
          data.prestadores = data.prestadores.map(x => { return {entidad_id: x.entidad.id, primaria: x.primaria} })
          let request = data.id
            ? this.axios.put(`rs_servicios/grupos_ips/${data.id}`, data)
            : this.axios.post(`rs_servicios/grupos_ips`, data)
          request
            .then(() => {
              this.$store.commit('reloadTable', 'tableGruposIPS')
              this.$store.commit('SNACK_SHOW', {msg: `El grupo de IPS se registró correctamente.`, color: 'success'})
              this.dialog = false
              this.loading = false
            })
            .catch(e => {
              this.loading = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: `al registrar el grupo de IPS.`, error: e})
            })
        }
      },
      async validate () {
        if (!(await this.$validator.validateAll())) {
          this.errorButton = 'Hay campos por diligenciar en el formulario.'
        } else if (!this.grupo.prestadores.length) {
          this.errorButton = 'Se debe seleccionar al menos un prestador.'
        } else if (this.grupo.prestadores.length && !this.grupo.prestadores.find(x => x.primaria)) {
          this.errorButton = 'Se debe seleccionar un prestador como IPS primaria.'
        }
        if (this.errorButton) {
          setTimeout(() => {
            this.errorButton = null
          }, 8000)
          return false
        }
        return true
      },
      pushPrestador () {
        if (!this.grupo.prestadores.find(x => x.entidad.id === this.prestadorSeleccionado.id)) {
          this.grupo.prestadores.push({
            id: null,
            primaria: 0,
            entidad: {...this.prestadorSeleccionado}
          })
        }
        setTimeout(() => {
          this.prestadorSeleccionado = null
        }, 20)
      },
      reloadPrimaria (props) {
        if (props.item.primaria) {
          this.grupo.prestadores.forEach((x, index) => {
            if (index !== props.index) x.primaria = 0
          })
        }
      },
      getGrupo (grupo) {
        this.loading = true
        this.axios.get(`rs_servicios/grupos_ips/${grupo.id}`)
          .then(response => {
            this.grupo = response.data.data
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: `al traer el registro del grupo`, error: e})
          })
      },
      getPrestadores () {
        this.loadingPrestadores = true
        this.axios.get(`rs_servicios/prestadores/${this.grupo.municipio_id}`)
          .then(response => {
            this.prestadoresASeleccionar = response.data.data
            this.loadingPrestadores = false
          })
          .catch(e => {
            this.loadingPrestadores = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: `al traer los prestadores`, error: e})
          })
      },
      getServiciosPrimarios () {
        this.axios.get(`servicios/primarios`)
          .then((response) => {
            this.serviciosPrimarios = response.data.data
          })
          .catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los servicios primarios. ', error: e})
          })
      },
      closer () {
        setTimeout(() => {
          this.resetModel()
        }, 300)
      },
      open (grupo = null) {
        if (grupo) {
          this.getGrupo(grupo)
        } else {
          this.resetModel()
        }
        this.dialog = true
      },
      resetModel () {
        this.makeGrupo.prestadores = []
        this.grupo = {...this.makeGrupo}
        this.$validator.reset()
        this.prestadoresASeleccionar = []
        this.errorButton = null
        this.prestadorSeleccionado = null
      }
    }
  }
</script>

<style scoped>

</style>
