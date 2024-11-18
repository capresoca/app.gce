<template>  
  <div>
    <!-- inicio formulario modal -->
    <v-dialog v-model="dialog" width="500px">
      <v-form>
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar detalles</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12>
                <v-autocomplete
                  label="Tipo Soporte"
                  v-model="detalle.consecutivo_soporte"
                  :items="complementosTiposSoportes.tiposoporte"
                  item-value="consecutivo_soporte"
                  item-text="descripcion"
                  name="tipo soporte"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('tipo soporte')"
                  :filter="filterTipo"> 

                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.descripcion"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-flex xs12>
                <v-select
                  item-text="nombre"
                  item-value="nombre"
                  label="Activo"
                  :items="dicotomia"
                  name="activo"
                  v-validate="'required'"
                  :error-messages="errors.collect('activo')"
                  v-model="detalle.sw_activo"
                ></v-select>
              </v-flex>

              <v-flex xs12>
                <v-select
                  item-text="nombre"
                  item-value="nombre"
                  label="Obligatorio"
                  :items="dicotomia"
                  name="vivienda propia"
                  v-validate="'required'"
                  :error-messages="errors.collect('vivienda propia')"
                  v-model="detalle.sw_obligatorio"
                ></v-select>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeTable">Cancelar</v-btn>
            <v-btn color="primary" @click="saveDetalle" >Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    
    <!-- fin formulario modal -->
    <v-card ref="loaderRef">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ ordenEdit ? 'Edicion de tipo de soporte por incapacidad' : 'Registro de tipo de soporte por incapacidad' }} </v-toolbar-title>
      </v-toolbar>
      <v-card-text class="no-padding">
      <!-- start -->
      <v-container fluid grid-list-xl >
        <v-card flat>
          <v-card-text>
            <v-layout row wrap>
              <v-flex xs12> 
                <v-form data-vv-scope="formTipo">
                <v-card>
                  <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs12>

                          <v-select
                            item-text="nombre"
                            item-value="nombre"
                            label="Tipo Incapacidad"
                            :items="tipoIncapacidad"
                            name="tipo incapacidad"
                            v-validate="'required'"
                            :error-messages="errors.collect('tipo incapacidad')"
                            v-model="encabezado.proceso"
                          ></v-select>

                        </v-flex>

                        <v-flex xs12>
                          <v-text-field v-model="encabezado.descripcion"
                            label="Descripción" key="descripción"
                            name="descripción"
                            v-validate="'required|max:200'"
                            :error-messages="errors.collect('descripción')" ></v-text-field>
                        </v-flex>

                    </v-layout>
                  </v-card-text>
                </v-card>
                </v-form>
              </v-flex>
            </v-layout>

            <v-card flat v-show="!mostrarTabla">
              <v-card >
                <v-card-text>
                  <v-toolbar dense short flat>
                    Detalles
                    <v-spacer></v-spacer>
                    <v-btn icon>
                      <v-icon color="green" @click="dialog = true" title="agregar">add</v-icon>
                    </v-btn>
                  </v-toolbar>
                  <v-data-table
                    :headers="headers"
                    :items="dataDetalles.detalle"
                    :loading="tableLoading"
                    :total-items="dataDetalles.length"
                    hide-actions
                    class="elevation-1">
                    <template slot="items" slot-scope="props">
                      <td>{{ props.item.consecutivo_soporte | descripcionSoporte}}</td>
                      <td>{{ props.item.sw_activo}}</td>
                      <td>{{ props.item.sw_obligatorio}}</td>
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
                              @click="eliminarDetalle(props.index)"
                            >
                              <v-icon color="accent">delete</v-icon>
                            </v-btn>
                            <span>Eliminar</span>
                          </v-tooltip>
                          <v-tooltip top>
                            <v-btn
                              fab
                              dark
                              small
                              color="white"
                              slot="activator"
                              @click="editarDetalle(props.item, props.index)"
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
                        No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                      </v-alert>
                      <v-alert v-else :value="true" color="info" icon="info">
                        Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                      </v-alert>
                    </template>
                  </v-data-table>
                </v-card-text>
              </v-card>
            </v-card>

          <Detalles :tipodetalles="ID" v-show="mostrarTabla"></Detalles>
          </v-card-text>
        </v-card>
      </v-container>
      <!-- end --> 
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit" >Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {SOPORTEINCAPACIDAD_RELOAD_ITEM} from '@/store/modules/general/tables'
  // import moment from 'moment'
  export default {
    name: 'RegistroEmpleado',
    components: {
      Detalles: () => import('@/components/misional/atencionUsuario/tiposSoporte/Detalle')
    },
    props: ['parametros'],
    data () {
      return {
        ID: null,
        ordenEdit: false,
        tableLoading: false,
        loadingSubmit: false,
        mostrarTabla: false,
        i: null, // para editar un elemento en las tabla de detalles
        dialog: false,
        headers: [
          {
            text: 'Tipo Soporte',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Activo',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Obligatorio',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ],
        dicotomia: [
          {
            value: '1',
            nombre: 'Si'
          },
          {
            value: '0',
            nombre: 'No'
          }
        ],
        tipoIncapacidad: [
          {
            proceso: '2',
            nombre: 'Incapacidad Enfermedad General' // GENERAL_ILLNESS
          }
          // },
          // {
          //   proceso: '4',
          //   nombre: 'Incapacidad por Accidente de Trabajo' // WORK_ACCIDENT
          // },
          // {
          //   proceso: '5',
          //   nombre: 'Incapacidad por Accidente de Tránsito' // CAR_ACCIDENT
          // },
          // {
          //   proceso: '7',
          //   nombre: 'Licencia de Maternidad' // MATERNITY_LICENCE
          // },
          // {
          //   proceso: '8',
          //   nombre: 'Licencia de Paternidad' // PATERNITY_LICENCE
          // },
          // {
          //   proceso: '17',
          //   nombre: 'Licencia Fallecimiento Madre' // LICENCIA_FALLECIMIENTO_MADRE
          // }
        ],
        tabs: null,
        dataDetalles: { detalle: [] },
        encabezado: {},
        detalle: {},
        filterTipo (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.descripcion + ' ' + item.profesion)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        }
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.consecutivo_proceso)
    },
    created () {
      this.getID()
    },
    watch: {
    },
    computed: {
      complementos () {
        return store.getters.complementosTercerosFormComplementarios
      },
      complementosTiposSoportes () {
        return JSON.parse(JSON.stringify(store.getters.complementosTipoSoporte))
      }
    },
    methods: {
      getID () {
        this.ID = this.parametros.entidad.consecutivo_proceso
      },
      saveDetalle () {
        if (this.i !== '' && this.i !== null) {
          // this.dataDetalles.detalle[this.i] = Object.assign({}, this.detalle)
          this.dataDetalles.detalle[this.i].consecutivo_soporte = this.detalle.consecutivo_soporte
          this.dataDetalles.detalle[this.i].sw_obligatorio = this.detalle.sw_obligatorio
          this.dataDetalles.detalle[this.i].sw_activo = this.detalle.sw_activo
        } else {
          this.dataDetalles.detalle.push(this.detalle)
        }
        this.formTableReset()
      },
      eliminarDetalle (item) {
        this.dataDetalles.detalle.splice(item, 1)
      },
      editarDetalle (item, index) {
        this.dialog = true
        this.detalle = Object.assign({}, item)
        this.i = index
      },
      closeTable () {
        this.dialog = false
        this.formTableReset()
      },
      formTableReset () {
        this.i = null
        this.detalle = {
          consecutivo_soporte: null,
          sw_activo: null,
          sw_obligatorio: null
        }
        this.dialog = false
      },
      getRegistro (id) {
        let loader = this.$loading.show({
          container: this.$refs.loaderRef.$el
        })
        this.axios.get('soportexincapacidad/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.encabezado = response.data.data
              this.dataDetalles.detalle = response.data.detalle
              this.mostrarTabla = true

              for (let i = 0; i < this.tipoIncapacidad.length; i++) {
                const element = this.tipoIncapacidad[i]
                if (this.encabezado.proceso.toString() === element.proceso) this.encabezado.proceso = element.nombre
              }
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al hacer la busqueda de registros. ', error: e})
          })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formTipo')) {
          this.loadingSubmit = true
          this.encabezado = [].concat(this.encabezado, this.dataDetalles) // concatenar arrays
          if (this.encabezado[0].consecutivo_proceso) {
            this.axios.put('soportexincapacidad/actualizar/' + this.encabezado[0].consecutivo_proceso, this.encabezado)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El empleado se actualizo correctamente', color: 'success'})
                this.formReset()
                console.log('data en actualiza', response.data.data)
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(SOPORTEINCAPACIDAD_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('soportexincapacidad/crear', this.encabezado)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El soporte se creó correctamente', color: 'success'})
                this.formReset()
                console.log('data en crea', response.data.data)
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(SOPORTEINCAPACIDAD_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      formReset () {
        this.mostrarTabla = false
        this.ID = null
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      }
    },
    filters: {
      descripcionSoporte: function (value) {
        for (let i = 0; i < store.getters.complementosTipoSoporte.tiposoporte.length; i++) {
          const element = store.getters.complementosTipoSoporte.tiposoporte[i]
          if (value === element.consecutivo_soporte) {
            return element.descripcion
          }
        }
      }
    }
  }
</script>

<style scoped>
  .tipo {
    height: 10px;
    padding: 0;
  }
  .no-padding {
    padding: 0;
  }
</style>
