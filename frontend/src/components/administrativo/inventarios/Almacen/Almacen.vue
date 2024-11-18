<template>
  <div>
    <v-dialog v-model="dialog" width="720px">
      <v-form data-vv-scope="form">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12 sm6>
                <v-text-field label="Codigo"
                              v-model="almacen.codigo"
                              key="codigo"
                              v-validate="'required'"
                              name="Codigo"
                              :error-messages="errors.collect('codigo')"
                              required>
                </v-text-field>
              </v-flex>

              <v-flex xs12 sm6>
                <v-text-field label="Nombre"
                              v-model="almacen.nombre"
                              key="nombre"
                              v-validate="'required'"
                              name="Nombre"
                              :error-messages="errors.collect('nombre')"
                              required>
                </v-text-field>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="save" :loading="loadingSubmit" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" title="Almacen de inventarios" subtitle="Registro y gestión" btnplus btnplustitle="Crear almacen" btnplustruncate @click="dialog = true"/>

      <loading v-model="loading" />
      <v-data-table
        :headers="headers"
        :items="almacenes"
        :loading="tableLoading"
        rows-per-page-text="Registros por página"
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <td>{{ props.item.codigo}}</td>
          <td>{{ props.item.nombre}}</td>
          <td>
            <v-speed-dial
              v-model="props.item.show"
              direction="left"
              open-on-hover
              transition="slide-x-transition"
            >
              <v-btn
                slot="activator"
                v-model="props.item.show"
                color="blue darken-2"
                dark
                fab
                small
              >
                <v-icon>chevron_left</v-icon>
                <v-icon>close</v-icon>
              </v-btn>
              <v-tooltip top>
                <v-btn
                  fab
                  dark
                  small
                  color="white"
                  slot="activator"
                  @click="edit(props.item)"
                >
                  <v-icon color="accent">mode_edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
            </v-speed-dial>
          </td>
        </template>
        <template slot="no-data">
          <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
            Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
          </v-alert>
          <v-alert v-else :value="true" color="info" icon="info">
            Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
          </v-alert>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>
<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'Almacen',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        almacenes: [],
        almacen: {},
        dialog: false,
        loading: false,
        tableLoading: false,
        loadingSubmit: false,

        headers: [
          {
            text: 'Codigo',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            actions: true,
            classData: 'justify-center layout px-0'
          }
        ]
      }
    },
    mounted () {
      this.getData()
      this.formReset()
    },
    computed: {
      modalTitulo () {
        return !this.almacen.id ? 'Nuevo almacen' : 'Edición'
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('almaceninventarios')
      }
    },
    methods: {
      getData () {
        this.loading = true
        this.tableLoading = true
        this.axios.get(`in_almacen`)
          .then((response) => {
            if (response.data !== '') {
              this.almacenes = response.data.data
              this.loading = false
              this.tableLoading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Error al traer los datos.' + e, color: 'danger'})
            console.log('error', e)
          })
      },
      formReset () {
        this.almacen = {
          id: ''
        }
      },
      edit (item) {
        this.dialog = true
        this.almacen = JSON.parse(JSON.stringify(item))
      },
      close () {
        this.dialog = false
        this.loadingSubmit = false
        this.formReset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('form')) {
          this.loadingSubmit = true

          let send = !this.almacen.id ? this.axios.post('in_almacen', this.almacen) : this.axios.put('in_almacen/' + this.almacen.id, this.almacen)
          send.then(response => {
            if (this.almacen.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'La contestación se actualizó correctamente', color: 'success'})
              this.almacenes.splice(this.almacenes.findIndex(almacen => almacen.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'La contestación se creó correctamente', color: 'success'})
              this.almacenes.splice(0, 0, response.data.data)
            }
            this.close()
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {msg: 'Hay un error al guardar el registro. ' + e, color: 'danger'})
            this.close()
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }

    }
  }
</script>
