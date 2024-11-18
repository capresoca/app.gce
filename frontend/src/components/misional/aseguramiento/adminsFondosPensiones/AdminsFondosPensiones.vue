<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formAfps">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 sm12 md12>
                <v-text-field v-model="adminFondoPension.codigo"
                              label="Código" key="codigo"
                              name="codigo" v-validate="'required|not_in:' + codigosUsados" required
                              :error-messages="errors.collect('codigo')"></v-text-field>


              </v-flex>
              <v-flex xs12 sm12 md12>
                <v-text-field v-model="adminFondoPension.nombre"
                              label="Nombre" key="nombre"
                              name="nombre" v-validate="'required|max:150|not_in:'  + nombresUsados" required
                              :error-messages="errors.collect('nombre')"></v-text-field>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="save" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" title="Administradoras  de Fondos de Pensiones" subtitle="Registro y gestión" btnplus btnplustitle="Crear Item" btnplustruncate @click="dialog = true"/>
      <loading v-model="tableLoading" />
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
        :items="adminsFondosPensiones"
        :loading="tableLoading"
        :pagination.sync="pagination"
        hide-actions
        class="elevation-1"
        :search="search" rows-per-page-text="Registros por página" :rows-per-page-items='[10,25,50,{"text":"Todos","value":-1}]'>
        <template slot="items" slot-scope="props">
          <td>{{ props.item.codigo }}</td>
          <td>{{ props.item.nombre }}</td>
          <td class="text-xs-center">
            <v-tooltip top>
              <v-btn icon class="mx-0" slot="activator" @click="edit(props.item)">
                <v-icon color="accent">edit</v-icon>
              </v-btn>
              <span>Editar</span>
            </v-tooltip>
          </td>
        </template>
        <template slot="no-data">
          <v-alert  v-if="tableLoading" :value="true" color="error" icon="warning">
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
  import Loading from '@/components/general/Loading'

  export default {
    name: 'AdminsFondosPensiones',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        adminsFondosPensiones: [],
        adminFondoPension: {},
        search: '',
        dialog: false,
        tableLoading: false,
        pagination: {},
        codigosUsados: '',
        nombresUsados: '',

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
            text: 'Opciones',
            align: 'center',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      const dict = {
        custom: {
          codigo: {
            not_in: 'Este código ya fue registrado.'
          },
          nombre: {
            not_in: 'Este nombre ya fue registrado.'
          }
        }
      }

      this.$validator.localize('es', dict)
    },
    beforeCreate () {
      this.tableLoading = true
      this.axios.get('as_afps')
        .then((res) => {
          this.adminsFondosPensiones = res.data.data
          this.pagination.totalItems = this.adminsFondosPensiones.length
          this.tableLoading = false
          this.codigosUsados = this.getCodigosUsados()
          this.nombresUsados = this.getNombresUsados()
        })
        .catch(e => {
          console.log('Hay un error. ' + e)
          return false
        })
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('adminsFondosPensiones')
      },
      modalTitulo () {
        return !this.adminFondoPension.id ? 'Nuevo item' : 'Edición del item: ' + this.adminFondoPension.nombre
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      formReset () {
        this.adminFondoPension = {
          id: '',
          codigo: null,
          nombre: null
        }
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      edit (item) {
        this.dialog = true
        this.adminFondoPension = JSON.parse(JSON.stringify(item))
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      getCodigosUsados () {
        return this.adminsFondosPensiones.map(cod => { if (!(this.adminFondoPension.codigo != null && this.adminFondoPension.codigo === cod.codigo)) return cod.codigo })
      },
      getNombresUsados () {
        return this.adminsFondosPensiones.map(nom => { if (!(this.adminFondoPension.nombre != null && this.adminFondoPension.nombre === nom.nombre)) return nom.nombre })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formAspf')) {
          var send = !this.adminFondoPension.id ? this.axios.post('as_afps', this.adminFondoPension) : this.axios.put('as_afps/' + this.adminFondoPension.id, this.adminFondoPension)
          send.then(response => {
            if (this.adminFondoPension.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'El item se actualizó correctamente', color: 'success'})
              this.adminsFondosPensiones.splice(this.adminsFondosPensiones.findIndex(adminFondoPension => adminFondoPension.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'El item se creó correctamente', color: 'success'})
              this.adminsFondosPensiones.splice(0, 0, response.data.data)
            }
            this.dialog = false
            this.close()
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
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
