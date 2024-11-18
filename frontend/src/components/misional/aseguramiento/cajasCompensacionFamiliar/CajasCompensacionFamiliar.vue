<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formCcfs">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 sm12 md12>
                <v-text-field v-model="cajaCompensacion.cod_habilitacion"
                              label="Código" key="codigo"
                              name="codigo" v-validate="'required|not_in:' + codigosUsados" required
                              :error-messages="errors.collect('codigo')"></v-text-field>


              </v-flex>

              <v-flex xs12 sm12 md12>
                <v-text-field v-model="cajaCompensacion.nombre"
                              label="Nombre" key="nombre"
                              name="nombre" v-validate="'required|max:150|not_in:'  + nombresUsados" required
                              :error-messages="errors.collect('nombre')"></v-text-field>
              </v-flex>

              <v-flex xs12 sm12 md12>
                <v-select
                  :items="tiposEstadoCaja"
                  v-model="cajaCompensacion.estado"
                  name="estado de caja"
                  label="Estado"
                  :error-messages="errors.collect('estado de caja')"
                  v-validate="'required'" required
                ></v-select>
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
      <toolbar-list :info="infoComponent" title="Cajas de compensación familiar" subtitle="Registro y gestión" btnplus btnplustitle="Crear Item" btnplustruncate @click="dialog = true"/>
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
        :items="cajasCompensacion"
        :loading="tableLoading"
        :pagination.sync="pagination"
        hide-actions
        class="elevation-1"
        :search="search" rows-per-page-text="Registros por página" :rows-per-page-items='[10,25,50,{"text":"Todos","value":-1}]'>
        <template slot="items" slot-scope="props">
          <td>{{ props.item.cod_habilitacion }}</td>
          <td>{{ props.item.nombre }}</td>
          <td>{{ props.item.estado }}</td>
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
    name: 'CajasCompensacionFamiliar',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        cajasCompensacion: [],
        cajaCompensacion: {},
        tiposEstadoCaja: ['Activo', 'Inactivo'],
        search: '',
        dialog: false,
        tableLoading: false,
        pagination: {},
        codigosUsados: '',
        nombresUsados: '',

        headers: [
          {
            text: 'Código Habilitación',
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
      this.axios.get('as_ccfs')
        .then((res) => {
          this.cajasCompensacion = res.data.data
          this.pagination.totalItems = this.cajasCompensacion.length
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
        return this.$store.getters.getInfoComponent('cajasCompensacionFamiliar')
      },
      modalTitulo () {
        return !this.cajaCompensacion.id ? 'Nuevo item' : 'Edición del item: ' + this.cajaCompensacion.nombre
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      formReset () {
        this.cajaCompensacion = {
          id: '',
          cod_habilitacion: null,
          nombre: null,
          estado: null
        }
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      edit (item) {
        this.dialog = true
        this.cajaCompensacion = JSON.parse(JSON.stringify(item))
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      getCodigosUsados () {
        return this.cajasCompensacion.map(cod => { if (!(this.cajaCompensacion.codigo != null && this.cajaCompensacion.codigo === cod.codigo)) return cod.codigo })
      },
      getNombresUsados () {
        return this.cajasCompensacion.map(nom => { if (!(this.cajaCompensacion.nombre != null && this.cajaCompensacion.nombre === nom.nombre)) return nom.nombre })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formCcfs')) {
          var send = !this.cajaCompensacion.id ? this.axios.post('as_ccfs', this.cajaCompensacion) : this.axios.put('as_ccfs/' + this.cajaCompensacion.id, this.cajaCompensacion)
          send.then(response => {
            if (this.cajaCompensacion.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'El item se actualizó correctamente', color: 'success'})
              this.cajasCompensacion.splice(this.cajasCompensacion.findIndex(cajaCompensacion => cajaCompensacion.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'El item se creó correctamente', color: 'success'})
              this.cajasCompensacion.splice(0, 0, response.data.data)
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
