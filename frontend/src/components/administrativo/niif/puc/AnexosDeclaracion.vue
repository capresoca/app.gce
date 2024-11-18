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
                <v-text-field v-model="anexoDeclaracion.codigo"
                              label="Código" key="codigo"
                              name="codigo" v-validate="'required|max:5|not_in:' + codigosUsados" required
                              :error-messages="errors.collect('codigo')"></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md12>
                <v-text-field v-model="anexoDeclaracion.nombre"
                              label="Nombre" key="nombre"
                              name="nombre" v-validate="'required|max:150'" required
                              :error-messages="errors.collect('nombre')"></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md12>
                <v-switch  color="success" :label="anexoDeclaracion.estado"
                           v-model="anexoDeclaracion.estado"  true-value="Activo" false-value="Inactivo"></v-switch>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click="close">Cancelar</v-btn>
          <v-btn color="blue darken-1" @click="save()" flat>Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </form>
  </v-dialog>
  <v-dialog v-model="destroyFila" max-width="290">
    <v-card>
      <!--<v-card-title class="headline">¿Está seguro/a de eliminar el campo?</v-card-title>-->
      <v-card-text color="blue-grey darken-1">¿Está seguro/a de eliminar el campo <span v-text="anexoDeclaracion.nombre + '?'"></span> </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn flat="flat" @click.native="destroyFila = false">No</v-btn>
        <v-btn color="primary" flat="flat" @click="eliminar(anexoDeclaracion)">Si</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <v-card>
    <v-toolbar class="elevation-0 toolbar--dense">
      <v-toolbar-title>Anexos de Declaración </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn fab small color="accent" @click.native="dialog = true" v-if="infoComponent ? infoComponent.permisos.agregar : false">
        <v-icon>add</v-icon>
      </v-btn>
    </v-toolbar>
    <v-card-title>
      <v-text-field
        v-model="search"
        append-icon="search"
        label="Buscar"
        hide-details
        flat>
      </v-text-field>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="anexosDeclaracion"
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
          <v-layout row wrap class="pa-1">
            <v-flex xs6>
              <v-btn icon @click="edit(props.item)">
                <v-icon small color="accent">edit</v-icon>
              </v-btn>
            </v-flex>
            <v-flex xs6>
              <v-btn icon v-if="props.item.removable" @click="pregutar(props.item)">
                <v-icon small color="accent">delete</v-icon>
              </v-btn>
            </v-flex>
          </v-layout>
        </td>
      </template>
      <template slot="no-data" style="margin: 0 auto; top: 50% !important;">
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
  <!--v-if="anexosDeclaracion.length > 5"-->
</div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'AnexosDeclaracion',
    data () {
      return {
        anexosDeclaracion: [],
        dialog: false,
        anexoDeclaracion: {},
        anexoDeclaracionEdicion: '',
        tableLoading: true,
        destroyFila: false,
        pagination: {},
        codigosUsados: '',
        persistent: false,
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
      this.axios.get('anedeclaraciones')
        .then((response) => {
          this.anexosDeclaracion = response.data.data
          this.pagination.totalItems = this.anexosDeclaracion.length
          this.tableLoading = false
          // console.log(this.anexosDeclaracion)
        })
        .catch(e => {
          console.log('Hay un error. ' + e)
          return false
        })
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('anexosDeclaracion')
      },
      modalTitulo () {
        return !this.anexoDeclaracion.id ? 'Nuevo Anexo Declaración' : 'Edición del Anexo Declaración: ' + this.anexoDeclaracion.nombre
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      formReset () {
        this.anexoDeclaracion = {
          id: '',
          codigo: '',
          nombre: '',
          estado: 'Activo'
        }
        this.codigosUsados = this.getCodigosUsados()
      },
      getCodigosUsados (item) {
        if (item === '' || this.anexoDeclaracion.id === '') {
          let codigos = this.anexosDeclaracion.map(cod => {
            return cod.codigo
          })
          return codigos
        }
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      pregutar (item) {
        this.anexoDeclaracion = item
        this.destroyFila = true
      },
      eliminar (item) {
        this.destroyFila = false
        this.axios.delete('anedeclaraciones/' + item.id).then(response => {
          console.log('Eliminado')
          this.anexosDeclaracion.splice(this.anexosDeclaracion.indexOf(item), 1)
          this.$store.commit(SNACK_SHOW, {msg: 'Anexo eliminado correctamente. ', color: 'success'})
        })
      },
      edit (item) {
        this.dialog = true
        this.anexoDeclaracionEdicion = item
        this.anexoDeclaracion = JSON.parse(JSON.stringify(item))
        this.codigosUsados = this.getCodigosUsados(item)
      },
      save () {
        // this.$validator.validateAll()
        this.$validator.validateAll().then(result => {
          if (result) {
            var send = !this.anexoDeclaracion.id ? this.axios.post('anedeclaraciones', this.anexoDeclaracion) : this.axios.put('anedeclaraciones/' + this.anexoDeclaracion.id, this.anexoDeclaracion)
            send.then(response => {
              console.log(response)
              if (this.anexoDeclaracion.id) {
                this.$store.commit(SNACK_SHOW, {msg: 'Anexo actualizado correctamente', color: 'success'})
                this.anexosDeclaracion.splice(this.anexosDeclaracion.findIndex(anexoDeclaracion => anexoDeclaracion.id === response.data.data.id), 1, response.data.data)
              } else {
                this.$store.commit(SNACK_SHOW, {msg: 'Anexo creado correctamente', color: 'success'})
                this.anexosDeclaracion.splice(0, 0, response.data.data)
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
