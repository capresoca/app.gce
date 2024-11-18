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
                <v-flex xs12>
                  <v-text-field v-model="banco.codigo"
                                label="Código" key="codigo"
                                name="codigo" v-validate="'required|max:5|not_in:' + codigosUsados" required
                                :error-messages="errors.collect('codigo')"></v-text-field>


                </v-flex>
                <v-flex xs12>
                  <v-text-field v-model="banco.nombre"
                                label="Nombre" key="nombre"
                                name="nombre" v-validate="'required|max:150|not_in:'  + nombresUsados" required
                                :error-messages="errors.collect('nombre')"></v-text-field>
                </v-flex>
                <v-flex xs12>
                  <postulador
                    nodata="Busqueda por nombre o número de documento."
                    required
                    hint="identificacion"
                    itemtext="nombre_completo"
                    datatitle="identificacion"
                    datasubtitle="nombre_completo"
                    filter="identificacion_completa,nombre_completo"
                    :label="banco.gn_tercero_id != null ? 'Tercero seleccionado ' : 'tercero '"
                    scope="formPago"
                    ref="postulaTercero"
                    entidad="terceros"
                    preicon="person"
                    @change="val => banco.gn_tercero_id = val"
                    @objectReturn="val => terceroSeleccionado(val)"
                    :value="banco.tercero"
                    routeparams="gn_tipdocidentidad_id=12"
                    clearable/>
                </v-flex>
                <v-flex xs12>
                  <v-slide-y-transition>
                    <v-layout v-if="banco.tercero">
                      <v-card class="grey lighten-4 d-flex cardProveedor pt-1">
                        <v-card-title class="grey lighten-2 pr-0">
                          <span class="title" v-text="'Datos Básicos Proveedor: '"></span>
                        </v-card-title>
                        <v-container fluid grid-list-md class="pr-0">
                          <v-layout row wrap>
                            <v-flex xs12>
                              <v-card class="grey lighten-4 elevation-0" style="max-width: 100% !important;">
                                <v-layout row wrap>
                                  <v-flex xs6 sm6 md7 xl7>
                                    <v-card-title primary-title class="pa-1 pt-1">
                                      <div>
                                        <div>
                                          <v-icon size="20">portrait</v-icon>
                                          <span v-text="banco.tercero.nombre1 ? banco.tercero.nombre1 + ' ' + banco.tercero.apellido1 : banco.tercero.razon_social"></span>
                                          <!--<span v-else v-text="banco.tercero.razon_social"></span>-->
                                        </div>
                                        <div>
                                          <v-icon size="20">near_me</v-icon>
                                          <span v-text="banco.tercero.identificacion"></span>
                                        </div>
                                        <div>
                                          <v-icon size="20">phone</v-icon>
                                          <span v-text="banco.tercero.telefono_fijo ? banco.tercero.telefono_fijo : 'No registra.'"></span>
                                        </div>
                                        <div>
                                          <v-icon size="20">phone_android</v-icon>
                                          <span v-text="banco.tercero.celular ? banco.tercero.celular : 'No registra.'"></span>
                                        </div>
                                      </div>
                                    </v-card-title>
                                  </v-flex>
                                  <v-flex xs6 sm6 md5 xl5 class="pb-1">
                                    <div class="text-xs-center" style="max-width: 100%">
                                      <v-icon class="icone" size="86" color="#375d7b">store_mall_directory</v-icon>
                                    </div>
                                  </v-flex>
                                </v-layout>
                              </v-card>
                            </v-flex>
                          </v-layout>
                        </v-container>
                      </v-card>
                    </v-layout>
                  </v-slide-y-transition>
                </v-flex>
                <v-flex xs12 >
                  <v-text-field v-model="banco.codigo_ach"
                                label="Código ACH" key="codigoACH"
                                name="codigoACH" v-validate="'required|max:150|not_in:'" required
                                :error-messages="errors.collect('codigoACH')"></v-text-field>
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
        <toolbar-list :info="infoComponent" title="Bancos" subtitle="Registro y gestión" btnplus btnplustitle="Crear Banco" btnplustruncate @click="dialog = true"/>
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
          :items="bancos"
          :loading="tableLoading"
          :pagination.sync="pagination"
          hide-actions
          class="elevation-1"
          :search="search" rows-per-page-text="Registros por página" :rows-per-page-items='[10,25,50,{"text":"Todos","value":-1}]'>
          <template slot="items" slot-scope="props">
            <td>{{ props.item.codigo }}</td>
            <td>{{ props.item.nombre }}</td>
            <td>{{ props.item.tercero.nombre_completo}}</td>
            <td>{{ props.item.codigo_ach}}</td>
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

  export default {
    name: 'bancos',
    components: {
      Postulador: () => import('@/components/general/Postulador'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        bancos: [],
        banco: {},
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
            text: 'Tercero',
            align: 'left',
            sortable: false,
            value: 'tercero'
          },
          {
            text: 'Código ACH',
            align: 'left',
            sortable: false,
            value: 'codigoACH'
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
      'banco.tercero' (val) {
        if (val === null) {
          return val != null ? this.banco.tercero : null
        }
      }

    },
    beforeCreate () {
      this.tableLoading = true
      this.axios.get('bancos')
        .then((res) => {
          this.bancos = res.data.data
          this.pagination.totalItems = this.bancos.length
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
        return this.$store.getters.getInfoComponent('bancos')
      },
      modalTitulo () {
        return !this.banco.id ? 'Nuevo Banco' : 'Edición del banco: ' + this.banco.nombre
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      terceroSeleccionado (val) {
        this.banco.tercero = val
        if (val !== null && this.dialog === false) {
          this.dialog = true
        }
      },
      formReset () {
        this.banco = {
          id: '',
          codigo: null,
          nombre: null,
          tercero: null,
          gn_tercero_id: null,
          codigo_ach: null
        }
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      edit (item) {
        this.dialog = true
        this.banco = JSON.parse(JSON.stringify(item))
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      getCodigosUsados () {
        return this.bancos.map(cod => { if (!(this.banco.codigo != null && this.banco.codigo === cod.codigo)) return cod.codigo })
      },
      getNombresUsados () {
        return this.bancos.map(nom => { if (!(this.banco.nombre != null && this.banco.nombre === nom.nombre)) return nom.nombre })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formPago')) {
          var send = !this.banco.id ? this.axios.post('bancos', this.banco) : this.axios.put('bancos/' + this.banco.id, this.banco)
          send.then(response => {
            if (this.banco.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'El banco se actualizó correctamente', color: 'success'})
              this.bancos.splice(this.bancos.findIndex(banco => banco.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'El banco se creó correctamente', color: 'success'})
              this.bancos.splice(0, 0, response.data.data)
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
