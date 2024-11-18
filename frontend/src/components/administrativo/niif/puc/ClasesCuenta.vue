<template>
  <div>

    <v-dialog v-model="dialog" max-width="500px" :hide-overlay="false">

      <form ref="form">
        <v-card>
          <v-card-title class="grey lighten-3">
            <span class="headline">{{ modalTitulo }}</span>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm12 md12>
                  <v-text-field :disabled="claseCuenta.id !== ''" v-model="claseCuenta.codigo"
                                label="Código" key="codigo"
                                name="codigo" required v-validate="'required|max:5|not_in:' + codigosUsados"
                                :error-messages="errors.collect('codigo')"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-text-field v-model="claseCuenta.nombre"
                                label="Nombre" key="nombre"
                                name="nombre" required v-validate="'required|max:150'"
                                :error-messages="errors.collect('nombre')"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-select
                    :items="tiposCuenta"
                    v-model="claseCuenta.tipo"
                    label="Tipo"
                    name="tipo"
                    :error-messages="errors.collect('tipo')"
                    required v-validate="'required'"
                  ></v-select>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-select
                    :items="natuCuenta"
                    v-model="claseCuenta.naturaleza"
                    label="Naturaleza"
                    name="naturaleza"
                    :error-messages="errors.collect('naturaleza')"
                    required v-validate="'required'"
                  ></v-select>
                </v-flex>
                <v-flex xs12 sm6 md6>
                  <v-switch  color="success"  label="Patrimonio"
                             v-model="claseCuenta.patrimonio"  :true-value="1" :false-value="0"></v-switch>
                </v-flex>
                <v-flex xs12 sm6 md6>
                  <v-switch  color="success" :label="claseCuenta.estado"
                             v-model="claseCuenta.estado"  true-value="Activo" false-value="Inactivo"></v-switch>
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
          hide-details
          flat
        >
        </v-text-field>
        <v-spacer></v-spacer>
        <v-btn v-if="infoComponent && clasesCuenta.length < 9 ? infoComponent.permisos.agregar : false" fab small color="accent" @click.native="dialog = true">
          <v-icon>add</v-icon>
        </v-btn>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="clasesCuenta"
        :loading="tableLoading"
        :pagination.sync="pagination"
        hide-actions
        class="elevation-1"
        :search="search" rows-per-page-text="Registros por página" :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
        <template slot="items" slot-scope="props">
          <td>{{ props.item.codigo }}</td>
          <td>{{ props.item.nombre }}</td>
          <td>{{ props.item.estado }}</td>
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
    <v-card-actions v-if="clasesCuenta.length > 5" class="text-xs-center pt-2 d-block">
      <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
    </v-card-actions>
  </div>
</template>

<script>
    import {SNACK_SHOW} from '@/store/modules/general/snackbar'
    export default {
      name: 'ClasesCuenta',
      data () {
        return {
          clasesCuenta: [],
          dialog: false,
          claseCuenta: {},
          claseCuentaEdicion: '',
          tableLoading: true,
          codigosUsados: '',
          search: '',
          pagination: {},
          tiposCuenta: ['Presupuesto', 'Balance', 'Resultado', 'Orden'],
          natuCuenta: ['Débito', 'Crédito'],
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
              text: 'Estado',
              align: 'left',
              sortable: false,
              value: 'estado'
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
      watch: {
        dialog (value) {
          if (value === false) {
            this.close()
          }
        }
      },
      mounted () {
        const dict = {
          custom: {
            codigo: {
              not_in: 'Este código ya esta registrado.'
            }
          }
        }

        this.$validator.localize('es', dict)
      },
      beforeMount () {
        this.formReset()
      },
      beforeCreate () {
        this.axios.get('clascuentas')
          .then((response) => {
            this.clasesCuenta = response.data.data
            this.pagination.totalItems = this.clasesCuenta.length
            this.tableLoading = false
            // console.log(this.centrosCosto)
          })
          .catch(e => {
            console.log('Hay un error. ' + e)
            return false
          })
      },
      computed: {
        infoComponent () {
          return this.$store.getters.getInfoComponent('clasesCuenta')
        },
        modalTitulo () {
          return !this.claseCuenta.id ? 'Nueva Clase Cuenta' : 'Edición de la Clase Cuenta: ' + this.claseCuenta.nombre
        },
        pages () {
          if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
          return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
        }
      },
      methods: {
        formReset () {
          this.claseCuenta = {
            id: '',
            codigo: '',
            nombre: '',
            tipo: '',
            naturaleza: '',
            patrimonio: 0,
            estado: 'Activo'
          }
          this.codigosUsados = this.getCodigosUsados()
        },
        close () {
          this.dialog = false
          this.formReset()
          this.$validator.reset()
        },
        getCodigosUsados (item) {
          if (item === '' || this.claseCuenta.id === '') {
            let codigos = this.clasesCuenta.map(cod => {
              return cod.codigo
            })
            return codigos
          }
        },
        edit (item) {
          this.dialog = true
          this.claseCuentaEdicion = item
          this.claseCuenta = JSON.parse(JSON.stringify(item))
          this.codigosUsados = this.getCodigosUsados(item)
        },
        save () {
          // this.$validator.validateAll()
          this.$validator.validateAll().then(result => {
            if (result) {
              var send = !this.claseCuenta.id ? this.axios.post('clascuentas', this.claseCuenta) : this.axios.put('clascuentas/' + this.claseCuenta.id, this.claseCuenta)
              send.then(response => {
                // console.log(response)
                if (this.claseCuenta.id) {
                  this.$store.commit(SNACK_SHOW, {msg: 'La clase cuenta se actualizó correctamente', color: 'success'})
                  this.clasesCuenta.splice(this.clasesCuenta.findIndex(claseCuenta => claseCuenta.id === response.data.data.id), 1, response.data.data)
                } else {
                  this.$store.commit(SNACK_SHOW, {msg: 'La clase cuenta se creó correctamente', color: 'success'})
                  this.clasesCuenta.splice(0, 0, response.data.data)
                }
                this.close()
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
            }
          })
        }
      }
    }
</script>

<style scoped>

</style>
