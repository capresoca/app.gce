<template>
  <div>

    <v-dialog v-model="dialog" max-width="500px">

      <form ref="form">
        <v-card>
          <v-card-title class="grey lighten-3">
            <span class="headline">{{ modalTitulo }}</span>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm4 md3>
                  <v-text-field v-model="nivelCuenta.codigo" :disabled="nivelCuenta.id === '' || nivelCuenta.id !== ''"
                                label="Código"></v-text-field>
                </v-flex>
                <v-flex xs12 sm8 md9>
                  <v-text-field v-model="nivelCuenta.nombre"
                                label="Nombre" key="nombre"
                                name="nombre" required v-validate="'required|max:150|not_in:' + nombresUsados"
                                :error-messages="errors.collect('nombre')"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-text-field type="number" v-model.number="nivelCuenta.digitos"
                                label="Digitos" key="digitos del nivel cuenta"
                                name="digitos del nivel cuenta" required v-validate="'required|max:11|numeric'"
                                :error-messages="errors.collect('digitos del nivel cuenta')"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-text-field
                    label="Descripción digitos"
                    v-model="nivelCuenta.desc_digitos"
                    key="descripción digitos"
                    name="descripción digitos" v-validate="'required|max:45'" required
                    :error-messages="errors.collect('descripción digitos')">
                  </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md6>
                  <v-switch  color="success" :label="nivelCuenta.auxiliar" label="Auxiliar"
                             v-model="nivelCuenta.auxiliar"  :true-value="1" :false-value="0"></v-switch>
                </v-flex>
                <v-flex xs12 sm6 md6>
                  <v-switch  color="success" :label="nivelCuenta.estado"
                             v-model="nivelCuenta.estado"  true-value="Activo" false-value="Inactivo"></v-switch>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat @click="close">Cancelar</v-btn>
            <v-btn color="blue darken-1" @click.prevent="save()" flat>Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </form>
    </v-dialog>

    <v-card>
      <v-card-title>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line=""
          hint="Buscar por el codigo de la cuenta"
          hide-details
          flat
        >
        </v-text-field>
        <v-spacer></v-spacer>
        <v-btn fab small color="accent" @click.native="dialog = true" v-if="infoComponent ? infoComponent.permisos.agregar : false">
          <v-icon>add</v-icon>
        </v-btn>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="nivelesCuenta"
        :loading="tableLoading"
        class="elevation-1"
        :pagination.sync="pagination"
        hide-actions
        :search="search" rows-per-page-text="Registros por página" :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
        <template slot="items" slot-scope="props">
          <td>{{ props.item.codigo }}</td>
          <td>{{ props.item.nombre }}</td>
          <td>{{ props.item.desc_digitos }}</td>
          <td class="text-xs-center">
            <v-btn icon class="mx-0" @click="edit(props.item)">
              <v-icon small color="accent">edit</v-icon>
            </v-btn>
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
        <template slot="pageText" slot-scope="props">
          {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
        </template>
        <v-alert slot="no-results" :value="true" color="error" icon="warning">
          No se encontraron resultados de "{{ search }}"
        </v-alert>
      </v-data-table>
    </v-card>
    <v-card-actions v-if="nivelesCuenta.length > 5" class="text-xs-center pt-2 d-block">
      <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
    </v-card-actions>
  </div>
</template>

<script>
    import {SNACK_SHOW} from '@/store/modules/general/snackbar'
    export default {
      name: 'NivelesCuenta',
      data () {
        return {
          nivelesCuenta: [],
          nivelCuentaEdicion: '',
          nivelCuenta: {},
          tableLoading: true,
          dialog: false,
          pagination: {},
          nombresUsados: '',
          search: '',
          headers: [
            {
              text: 'Código',
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
              text: 'Descripción',
              align: 'left',
              sortable: false,
              value: 'desc_digitos'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              value: 'id'
            }
          ]

        }
      },
      mounted () {
        const dict = {
          custom: {
            nombre: {
              not_in: 'Este nombre ya esta en el registro.'
            }
          }
        }

        this.$validator.localize('es', dict)
      },
      beforeMount () {
        this.formReset()
      },
      beforeCreate () {
        this.axios.get('nivcuentas')
          .then((response) => {
            this.nivelesCuenta = response.data.data
            this.pagination.totalItems = this.nivelesCuenta.length
            this.tableLoading = false
            // console.log(this.centrosCosto)
          })
          .catch(e => {
            console.log('Hay un error. ' + e)
            return false
          })
      },
      watch: {
        dialog (value) {
          if (value === false) {
            this.close()
          }
        }
      },
      computed: {
        modalTitulo () {
          return !this.nivelCuenta.id ? 'Nuevo Nivel Cuenta' : 'Edición del Nivel Cuenta: ' + this.nivelCuenta.nombre
        },
        pages () {
          if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
          return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
        },
        infoComponent () {
          return this.$store.getters.getInfoComponent('nivelesCuenta')
        }
      },
      methods: {
        formReset () {
          this.nivelCuenta = {
            id: '',
            codigo: '',
            nombre: '',
            auxiliar: 0,
            digitos: '',
            desc_digitos: '',
            estado: 'Activo'
          }
          this.nombresUsados = this.getNombresUsados()
        },
        getNombresUsados (item) {
          if (item === '' || this.nivelCuenta.id === '') {
            let nombres = this.nivelesCuenta.map(name => {
              return name.nombre
            })
            return nombres
          }
        },
        close () {
          this.dialog = false
          this.formReset()
          this.$validator.reset()
        },
        edit (item) {
          this.dialog = true
          this.nivelCuentaEdicion = item
          this.nivelCuenta = JSON.parse(JSON.stringify(item))
          this.nombresUsados = this.getNombresUsados(item)
        },
        save () {
          // this.$validator.validateAll()
          this.$validator.validateAll().then(result => {
            if (result) {
              var send = !this.nivelCuenta.id ? this.axios.post('nivcuentas', this.nivelCuenta) : this.axios.put('nivcuentas/' + this.nivelCuenta.id, this.nivelCuenta)
              send.then(response => {
                // console.log(response)
                if (this.nivelCuenta.id) {
                  this.$store.commit(SNACK_SHOW, {msg: 'El nivel cuenta se actualizó correctamente', color: 'success'})
                  this.nivelesCuenta.splice(this.nivelesCuenta.findIndex(nivelCuenta => nivelCuenta.id === response.data.data.id), 1, response.data.data)
                } else {
                  this.$store.commit(SNACK_SHOW, {msg: 'El nivle cuenta se creó correctamente', color: 'success'})
                  this.nivelesCuenta.push(response.data.data)
                }
                this.close()
              }).catch(e => {
                // this.$store.commit(SNACK_SHOW, {msg: 'Registra la información, los campos no deben estar vacíos.', color: 'danger'})
                this.$store.commit(SNACK_SHOW, {
                  msg: 'Hay un error al guardar el registro. ' + e.response,
                  color: 'danger'
                })
              })
            }
          })
        }
      }
    }
</script>

<style scoped>

</style>
