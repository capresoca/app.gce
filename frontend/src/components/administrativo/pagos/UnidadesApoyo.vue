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
                <v-text-field v-model="unidadApoyo.codigo"
                              label="Código" key="codigo"
                              name="codigo" v-validate="'required|max:5|not_in:' + codigosUsados" required
                              :error-messages="errors.collect('codigo')"></v-text-field>
                <!--name="codigo" v-validate="'required|max:5|' + (!conPago.id ? 'not_in:' : (conPago.id &&  (val => val.codigo !== codigosUsados) ? 'not_in:' : 'in:' ) )  + codigosUsados" required-->

              </v-flex>
              <v-flex xs12 sm12 md12>
                <v-text-field v-model="unidadApoyo.nombre"
                              label="Nombre" key="nombre"
                              name="nombre" v-validate="'required|max:150|not_in:' + nombresUsados" required
                              :error-messages="errors.collect('nombre')"></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md12>
                <v-autocomplete
                  label="Centro de Costo"
                  v-model="unidadApoyo.cencosto"
                  @change="val => unidadApoyo.nf_cencosto_id = val ? val.id : null"
                  :items="complementos.cencostos"
                  item-value="id"
                  item-text="nombre"
                  name="centro de costo"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('centro de costo')"
                  return-object
                  clearable>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm12 md12>
                <!--<postulador></postulador>-->

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
        <v-toolbar-title class="title">Unidades de Apoyo </v-toolbar-title>
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
        :items="unidadesApoyo"
        :loading="tableLoading"
        :pagination.sync="pagination"
        hide-actions
        class="elevation-1"
        :search="search" rows-per-page-text="Registros por página" :rows-per-page-items='[10,25,50,{"text":"Todos","value":-1}]'>
        <template slot="items" slot-scope="props">
          <td>{{ props.item.codigo }}</td>
          <td>{{ props.item.nombre }}</td>
          <td>{{ props.item.cencosto ? props.item.cencosto.nombre :  ''}}</td>
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
  import store from '../../../store/complementos/index'
  export default {
    name: 'UnidadesApoyo',
    data () {
      return {
        unidadesApoyo: [],
        unidadApoyo: {},
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
            text: 'Centro Costo',
            align: 'left',
            sortable: false,
            value: 'nf_cencosto_id'
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
      this.axios.get('pg_uniapoyos')
        .then((res) => {
          // console.log(res.data.data)
          this.unidadesApoyo = res.data.data
          this.pagination.totalItems = this.unidadesApoyo.length
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
      complementos () {
        return store.getters.complementosFormComDiario
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('conPagos')
      },
      modalTitulo () {
        return !this.unidadApoyo.id ? 'Nueva Unidad de Apoyo' : 'Edición de la Unidad de Apoyo: ' + this.unidadApoyo.nombre
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      formReset () {
        this.unidadApoyo = {
          id: '',
          codigo: null,
          nombre: null,
          nf_cencosto_id: null,
          cencosto: null
        }
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      edit (item) {
        console.log(item)
        this.dialog = true
        this.unidadApoyo = JSON.parse(JSON.stringify(item))
        console.log(this.unidadApoyo)
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      getCodigosUsados () {
        return this.unidadesApoyo.map(cod => { if (!(this.unidadApoyo.codigo != null && this.unidadApoyo.codigo === cod.codigo)) return cod.codigo })
      },
      getNombresUsados () {
        return this.unidadesApoyo.map(nom => { if (!(this.unidadApoyo.nombre != null && this.unidadApoyo.nombre === nom.nombre)) return nom.nombre })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formPago')) {
          // console.log('Guardando...')
          var send = !this.unidadApoyo.id ? this.axios.post('pg_uniapoyos', this.unidadApoyo) : this.axios.put('pg_uniapoyos/' + this.unidadApoyo.id, this.unidadApoyo)
          send.then(response => {
            // console.log(response)
            if (this.unidadApoyo.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'La unidad de apoyo se actualizó correctamente', color: 'success'})
              this.unidadesApoyo.splice(this.unidadesApoyo.findIndex(unidadApoyo => unidadApoyo.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'La unidad de apoyo se creó correctamente', color: 'success'})
              this.unidadesApoyo.splice(0, 0, response.data.data)
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
