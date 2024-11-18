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
                <v-flex xs12 sm12 md12>
                  <v-text-field v-model="tipoComdiario.codigo"
                                label="Código" key="codigo"
                                name="codigo" v-validate="'required|max:5|not_in:' + codigosUsados" required
                                :error-messages="errors.collect('codigo')"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-text-field v-model="tipoComdiario.nombre"
                                label="Nombre " key="nombre"
                                name="nombre" v-validate="'required|max:150'" required
                                :error-messages="errors.collect('nombre')"></v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-switch  color="success" :label="tipoComdiario.estado"
                             v-model="tipoComdiario.estado"  true-value="Activo" false-value="Inactivo"></v-switch>
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

    <v-card >
      <v-toolbar class="elevation-0 toolbar--dense">
        <v-toolbar-title>Tipos de Comprobantes de Diario </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn fab small color="accent"
               @click.native="dialog = true"
               v-if="infoComponent ? infoComponent.permisos.agregar : false">
          <v-icon>add</v-icon>
        </v-btn>
      </v-toolbar>
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
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="tiposComdiario"
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
            <v-btn icon class="mx-0" @click="edit(props.item)" v-if="infoComponent ? infoComponent.permisos.agregar : false">
              <v-icon color="accent">edit</v-icon>
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
      <v-card-actions class="text-xs-center pt-2 d-block">
        <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'TiposComdiario',
    data () {
      return {
        tiposComdiario: [],
        dialog: false,
        tipoComdiario: {},
        tipoComdiarioEdicion: '',
        pagination: {},
        tableLoading: true,
        search: '',
        codigosUsados: '',
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
    mounted () {
      const dict = {
        custom: {
          codigo: {
            not_in: 'Este código ya fue usado'
          }
        }
      }

      this.$validator.localize('es', dict)
    },
    beforeMount () {
      this.formReset()
    },
    beforeCreate () {
      // this.tableLoading = true
      this.axios.get('tipcomdiarios')
        .then((response) => {
          this.tiposComdiario = response.data.data
          this.pagination.totalItems = this.tiposComdiario.length
          this.tableLoading = false
          this.codigosUsados = this.getCodigosUsados()
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
      infoComponent () {
        return this.$store.getters.getInfoComponent('tiposComdiario')
      },
      modalTitulo () {
        return !this.tipoComdiario.id ? 'Nuevo Tipo de Comprobante de Diario' : 'Edición del Tipo Comprobante de Diario: ' + this.tipoComdiario.nombre
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      formReset () {
        this.tipoComdiario = {
          id: '',
          codigo: '',
          nombre: '',
          estado: 'Activo'
        }
        this.codigosUsados = this.getCodigosUsados()
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      edit (item) {
        this.dialog = true
        this.tipoComdiarioEdicion = item
        this.tipoComdiario = JSON.parse(JSON.stringify(item))
        this.codigosUsados = this.getCodigosUsados()
      },
      getCodigosUsados () {
        return this.tiposComdiario.map(cod => { if (!(this.tipoComdiario.codigo != null && this.tipoComdiario.codigo === cod.codigo)) return cod.codigo })
      },
      save () {
        // this.$validator.validateAll()
        this.$validator.validateAll().then(result => {
          if (result) {
            var send = !this.tipoComdiario.id ? this.axios.post('tipcomdiarios', this.tipoComdiario) : this.axios.put('tipcomdiarios/' + this.tipoComdiario.id, this.tipoComdiario)
            send.then(response => {
              console.log(response)
              if (this.tipoComdiario.id) {
                this.$store.commit(SNACK_SHOW, {msg: 'Tipo de comprobante de diario actualizado correctamente', color: 'success'})
                this.tiposComdiario.splice(this.tiposComdiario.findIndex(tipoComdiario => tipoComdiario.id === response.data.data.id), 1, response.data.data)
              } else {
                this.$store.commit(SNACK_SHOW, {msg: 'Tipo de comprobante de diario creado correctamente', color: 'success'})
                this.tiposComdiario.splice(0, 0, response.data.data)
              }
              this.close()
            }).catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
            })
          } else {
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
