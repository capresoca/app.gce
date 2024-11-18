<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 sm12 md12>
                <v-text-field v-model="conPago.codigo"
                              label="Código" key="codigo"
                              name="codigo" v-validate="'required|max:5|not_in:' + codigosUsados" required
                              :error-messages="errors.collect('codigo')"></v-text-field>
                <!--name="codigo" v-validate="'required|max:5|' + (!conPago.id ? 'not_in:' : (conPago.id &&  (val => val.codigo !== codigosUsados) ? 'not_in:' : 'in:' ) )  + codigosUsados" required-->

              </v-flex>
              <v-flex xs12 sm12 md12>
                <v-text-field v-model="conPago.nombre"
                              label="Nombre" key="nombre"
                              name="nombre" v-validate="'required|max:150|not_in:' + nombresUsados" required
                              :error-messages="errors.collect('nombre')"></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md12>
                <v-select
                  :items="tiposConcepto"
                  v-model="conPago.tipo_concepto"
                  key="tipo concepto"
                  label="Tipo concepto"
                  name="tipo concepto"
                  :error-messages="errors.collect('tipo concepto')"
                  required v-validate="'required'"
                ></v-select>
              </v-flex>
              <v-flex xs12 sm12 md12>
                <!--<postulador></postulador>-->
                <postulador
                  nodata="Busqueda por código o nombre."
                  required
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  :label="conPago.nf_niif_id ? 'Cuenta' : 'cuenta'"
                  lite
                  scope="formPago"
                  ref="buscarCuenta"
                  entidad="niifs"
                  routeparams="nivel:auxiliar=1"
                  preicon="fas fa-wallet"
                  @change="val => conPago.nf_niif_id = val"
                  @objectReturn="val => conPago.niif = val"
                  :value="conPago.niif"
                  clearable
                ></postulador>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="save">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <v-toolbar class="elevation-0 toolbar--dense">
        <v-toolbar-title class="title">Conceptos de Pagos </v-toolbar-title>
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
          single-line=""
          hide-details
          flat
        >
        </v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="conPagos"
        :loading="tableLoading"
        :pagination.sync="pagination"
        hide-actions
        class="elevation-1"
        :search="search" rows-per-page-text="Registros por página" :rows-per-page-items='[10,25,50,{"text":"Todos","value":-1}]'>
        <template slot="items" slot-scope="props">
          <td>{{ props.item.codigo }}</td>
          <td>{{ props.item.nombre }}</td>
          <td>{{ props.item.tipo_concepto }}</td>
          <td>{{ props.item.niif ? props.item.niif.nombre :  ''}}</td>
          <td class="text-xs-center">
            <v-btn icon class="mx-0" @click="edit(props.item)" v-if="infoComponent ? infoComponent.permisos.agregar : false">
              <v-icon color="accent">edit</v-icon>
            </v-btn>
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
  export default {
    name: 'ConPagos',
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    data () {
      return {
        conPagos: [],
        conPago: {},
        search: '',
        dialog: false,
        tableLoading: false,
        pagination: {},
        codigosUsados: '',
        nombresUsados: '',
        tiposConcepto: ['CXP', 'Notas', 'Traslado de Valores', 'Saldos Iniciales'],
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
            text: 'Tipo Concepto',
            align: 'left',
            sortable: false,
            value: 'tipo_concepto'
          },
          {
            text: 'Cuenta',
            align: 'left',
            sortable: false,
            value: 'nf_niif_id'
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
    watch: {
      'dialog' (val) {
        if (val === false) {
          this.close()
        }
      }
    },
    beforeCreate () {
      this.tableLoading = true
      this.axios.get('pg_conpagos')
        .then((res) => {
          // console.log(res.data.data)
          this.conPagos = res.data.data
          this.pagination.totalItems = this.conPagos.length
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
        return this.$store.getters.getInfoComponent('conPagos')
      },
      modalTitulo () {
        return !this.conPago.id ? 'Nuevo Concepto de Pago' : 'Edición del Concepto de Pago: ' + this.conPago.nombre
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      formReset () {
        this.conPago = {
          id: '',
          codigo: null,
          nombre: null,
          tipo_concepto: null,
          nf_niif_id: null,
          niif: null
        }
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      edit (item) {
        // console.log(item)
        this.dialog = true
        this.conPago = JSON.parse(JSON.stringify(item))
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
        this.$refs.buscarCuenta.reset()
      },
      getCodigosUsados () {
        return this.conPagos.map(cod => { if (!(this.conPago.codigo != null && this.conPago.codigo === cod.codigo)) return cod.codigo })
      },
      getNombresUsados () {
        return this.conPagos.map(nom => { if (!(this.conPago.nombre != null && this.conPago.nombre === nom.nombre)) return nom.nombre })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        let errorCuenta = await this.$refs.buscarCuenta.validate()
        if (await this.validador('formPago') && errorCuenta) {
          // console.log('Guardando...')
          var send = !this.conPago.id ? this.axios.post('pg_conpagos', this.conPago) : this.axios.put('pg_conpagos/' + this.conPago.id, this.conPago)
          send.then(response => {
            // console.log(response)
            if (this.conPago.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'El concepto de pago actualizó correctamente', color: 'success'})
              this.conPagos.splice(this.conPagos.findIndex(conPago => conPago.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'El concepto de pago se creó correctamente', color: 'success'})
              this.conPagos.splice(0, 0, response.data.data)
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
