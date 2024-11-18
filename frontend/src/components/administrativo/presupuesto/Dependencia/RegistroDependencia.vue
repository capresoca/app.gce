<template>
  <div>
    <v-card>
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
      </v-toolbar>
      <!--<v-card-title class="headline grey lighten-2" primary-title>-->
      <!--{{ }}-->
      <!--</v-card-title>-->
      <v-card-text>
        <v-form data-vv-scope="formRegistroDependencia">
          <v-container fluid grid-list-xl >
            <v-layout row wrap>
              <v-flex xs12 sm12 md4 lg4>
                <v-text-field label="Codigo"
                  v-model="dependencia.codigo"
                  key="codigo"
                  v-validate="'required|max:16'"
                  name="codigo"
                  :error-messages="errors.collect('codigo')"
                  :disabled="ordenEdit"
                  @keyup.enter="findByCodigo"
                  @blur="findByCodigo"
                  persistent-hint
                  :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''">
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md8 lg8>
                <v-text-field label="Nombre"
                  v-model="dependencia.nombre"
                  key="nombre"
                  v-validate="'required'"
                  name="nombre"
                  :error-messages="errors.collect('nombre')"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md12 lg12 v-if="abrir">
                <v-autocomplete
                  label="Centro de Costo"
                  persistent-hint
                  v-model="dependencia.nf_cencosto_id"
                  @change="val => dependencia.nf_cencosto_id = val"
                  :items="complementosFormCxc.cencostos"
                  item-value="id"
                  item-text="nombre"
                  name="centro de costo"
                  required
                  clearable>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                        <v-list-tile-sub-title v-html="data.item.codigo"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm12 md112 lg12 v-if="abrir">
                <postulador
                  nodata="Busqueda por nombre o número de documento."
                  itemtext="identificacion"
                  datatitle="identificacion_completa"
                  datasubtitle="nombre_completo"
                  filter="identificacion_completa,nombre_completo"
                  label="NIT"
                  scope="formRegistroDependencia"
                  ref="postulaTercero"
                  entidad="terceros"
                  preicon="person"
                  @change="val => dependencia.gn_tercero_id = val"
                  @objectReturn="val => seleccionTercero(val)"
                  :value="dependencia.tercero"
                  clearable
                />
              </v-flex>
              <v-flex xs12 sm12 md12 lg12 v-if="abrir">
                <v-text-field label="Representante" :value="dependencia.tercero ? dependencia.tercero.nombre_completo : null" readonly></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md12 lg12 v-if="abrir">
                <v-text-field label="Ciudad" :value="dependencia.tercero ? dependencia.tercero.municipio.nombre + ' - ' + dependencia.tercero.municipio.nombre_departamento : null" readonly></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-layout row wrap>
          <v-flex xs12 sm12 md12 lg12 class="text-xs-right">
            <v-btn
              color="primary"
              @click="submit"
            >
              Guardar
            </v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
    <v-dialog v-model="dialogCodigo" persistent max-width="290">      
      <v-card>
        <v-card-title class="subheading text-xs-center grey lighten-3 font-weight-bold" v-text="'CÓDIGO DE LA DEPENDENCIA'"></v-card-title>
        <v-card-text class="text-xs-justify">El código de la dependencia ya esta siendo utilizado ¿Desea cargar los datos para su actualización o utilizar otro código?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary darken-1" flat @click="editarByCodigo">Cargar datos</v-btn>
          <v-btn color="grey darken-1" flat @click="cerrarByCodigo">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import store from '../../../../store/complementos/index'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {DEPENDENCIA_PR_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroDependencia',
    props: ['parametros'],
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    data () {
      return {
        buscandoCodigo: false,
        dialogCodigo: false,
        ordenEdit: false,
        dependencia: {},
        abrir: false
      }
    },
    computed: {
      complementosFormCxc () {
        return store.getters.complementosFormComDiario
      },
      tapTitulo () {
        return `${this.dependencia.id ? 'Edicion' : 'Registro'} de Dependencia`
      }
    },
    methods: {
      findByCodigo () {
        if (this.dependencia.codigo) {
          this.buscandoCodigo = true
          this.axios.get('pr_dependencias/codigo/' + this.dependencia.codigo)
            .then(response => {
              if (response.data.exists) {
                this.$store.commit(SNACK_SHOW, {msg: 'El codigo de la dependencia existe', color: 'success'})
                this.formReset()
                this.dependencia = response.data.data
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              console.log(e)
              this.buscandoCodigo = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al consultar el codigo. ' + e.response, color: 'danger'})
            })
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formRegistroDependencia')) {
          if (this.dependencia.id) {
            this.axios.put('pr_dependencias/' + this.dependencia.id, this.dependencia)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El registro de la dependencia se actualizo correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(DEPENDENCIA_PR_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('pr_dependencias', this.dependencia)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El registro de la dependencia se creó correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(DEPENDENCIA_PR_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      editarByCodigo () {
        this.dialogCodigo = false
        this.ordenEdit = true
      },
      cerrarByCodigo () {
        this.formReset()
        this.ordenEdit = false
        this.dialogCodigo = false
      },
      formReset () {
        this.dependencia = {
          id: null,
          tercero: null
        }
        this.buscandoCodigo = false
        this.dialogCodigo = false
        this.ordenEdit = false
      },
      seleccionTercero (val) {
        if (val !== null) {
          if (val.id !== null && val.id !== '' && val.id !== undefined) {
            if (val.id !== this.dependencia.tercero.id) {
              this.axios.get('empleado/tercero/' + val.id)
                .then((response) => {
                  if (response.data.exists) {
                    this.procesarTercero(response.data.tercero)
                  } else {
                    this.$store.commit(SNACK_SHOW, {msg: 'No podemos recuperar los datos del tercero. Intente de nuevo', color: 'danger'})
                  }
                }).catch(e => {
                  this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
                })
            }
          }
        }
      },
      procesarTercero (val) {
        if (val.municipio === null) val.municipio = {nombre: '', nombre_departamento: ''}
        // this.dependencia.nombre = val.nombre_completo
        this.dependencia.tercero = val
        this.dependencia.gn_tercero_id = val.id
      },
      getRegistro (id) {
        this.axios.get('pr_dependencias/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el subgrupo. ', error: e})
          })
      },
      edit (item) {
        this.ordenEdit = true
        this.dependencia = item
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
    }
  }
</script>
