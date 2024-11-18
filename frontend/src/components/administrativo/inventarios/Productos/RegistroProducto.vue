<template>
  <div>
    <v-card ref="loaderRef">
      <v-card-title class="headline grey lighten-2" primary-title v-if="!ordenEdit">
        Registrar Producto
      </v-card-title>
      <v-card-title class="headline grey lighten-2" primary-title v-else>
        Editar Producto
      </v-card-title>
      <v-card-text>
        <v-form data-vv-scope="formProducto">
          <v-container fluid grid-list-xl>
            <v-layout row wrap>
              <v-flex xs12 sm12 md4>
                <v-text-field label="Código"
                              v-model="producto.codigo"
                              key="codigo"
                              v-validate="'required|max:16'"
                              name="Codigo"
                              prepend-icon="subtitles"
                              :error-messages="errors.collect('Codigo')"
                              required>
                </v-text-field>
              </v-flex>
            </v-layout>
            <v-layout row wrap>
              <v-flex xs12 sm12 md10 lg10>
                <v-textarea
                  outline
                  name="descripcion"
                  label="Descripcion del producto"
                  v-model="producto.descripcion"
                ></v-textarea>
              </v-flex>
              <v-flex xs12 sm12 md4 lg4>
                <v-autocomplete
                  label="Grupo"
                  v-model="producto.in_grupo_id"
                  :items="grupos"
                  item-value="id"
                  item-text="nombre"
                  name="grupos"
                  persistent-hint
                  prepend-icon="collections_bookmark"
                  no-data-text="No existen datos"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('grupos')"
                  clearable>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                        <v-list-tile-sub-title v-html="'Código: ' + data.item.codigo"></v-list-tile-sub-title>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-flex xs12 sm12 md4 lg4>
                <v-autocomplete
                  label="Subgrupo"
                  v-model="producto.in_subgrupo_id"
                  :items="subGrupos"
                  item-value="id"
                  item-text="nombre"
                  name="subgrupos"
                  persistent-hint
                  prepend-icon="collections_bookmark"
                  no-data-text="No existen datos"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('subgrupos')"
                  clearable>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                        <v-list-tile-sub-title v-html="'Código: ' + data.item.codigo"></v-list-tile-sub-title>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-flex xs12 sm12 md4 lg4>
                <v-autocomplete
                  label="Unidad de medida"
                  v-model="producto.in_unidad_medida_id"
                  :items="unidadesDeMedida"
                  item-value="id"
                  item-text="nombre"
                  name="unidad de medida"
                  persistent-hint
                  prepend-icon="timeline"
                  no-data-text="No existen datos"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('unidad de medida')"
                  clearable>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                        <v-list-tile-sub-title v-html="'Código: ' + data.item.codigo"></v-list-tile-sub-title>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-flex xs12 sm12 md3 lg3>
                <v-text-field label="Costo del producto" v-model="producto.costo_producto" 
                  prepend-icon="attach_money"
                  key="costoProducto"
                  name="Costo del producto"
                  v-validate="'required'"
                  :error-messages="errors.collect('Costo del producto')"                  
                  required>
                </v-text-field>
              </v-flex>

              <v-flex xs12 sm12 md3 lg3>
                <v-text-field label="Codigo IVA" v-model="producto.codigo_iva" 
                  key="codigoIva"
                  name="Costo IVA"
                  prepend-icon="description"
                  v-validate="'required'"
                  :error-messages="errors.collect('Costo IVA')"                  
                  required>
                </v-text-field>
              </v-flex>

              <v-flex xs12 sm12 md3 lg3>
                <v-dialog
                  ref="pickerFechaIngreso"
                  v-model="pickerFechaIngreso"
                  :return-value.sync="producto.fecha_creacion"
                  persistent
                  lazy
                  full-width
                  width="290px">
                  <v-text-field
                    slot="activator"
                    v-model="producto.fecha_creacion"
                    label="Fecha de creacion"
                    prepend-icon="event"
                    readonly>
                  </v-text-field>
                  <v-date-picker v-model="producto.fecha_creacion" locale="es-co" :first-day-of-week="1" scrollable>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="pickerFechaIngreso = false">Cancelar</v-btn>
                    <v-btn flat color="primary" @click="$refs.pickerFechaIngreso.save(producto.fecha_creacion)">OK</v-btn>
                  </v-date-picker>
                </v-dialog>
              </v-flex>

              <v-flex xs12 sm12 md3 lg3>
                <v-select
                  :items="complementos.estado"
                  label="Estado"
                  v-model="producto.estado"
                  prepend-icon="list"
                ></v-select>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" flat @click="submit" :loading="loadingSubmit">Guardar</v-btn>
      </v-card-actions>
    </v-card>
    <v-dialog v-model="dialogCodigo" persistent max-width="290">      
      <v-card>
        <v-card-title class="headline">Codigo del Producto</v-card-title>
        <v-card-text>El codigo del Producto ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click="editarByCodigo">Cargar datos</v-btn>
          <v-btn color="green darken-1" flat @click="cerrarByCodigo">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {PRODUCTO_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroProducto',
    props: ['parametros'],
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    data () {
      return {
        grupos: [],
        subGrupos: [],
        unidadesDeMedida: [],
        loadingSubmit: false,
        dialogSave: false,
        dialogCodigo: false,
        buscandoCodigo: false,
        ordenEdit: false,
        pickerFechaIngreso: false,
        param: {},
        producto: {
          id: null,
          tercero: {
            nombre_completo: '',
            identificacion_completa: ''
          }
        },
        complementos: {
          estado: ['Activo', 'Inactivo']
        }
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
      this.getGrupos()
      this.getSubGrupos()
      this.getUnidadesDeMedida()
    },
    methods: {
      getGrupos () {
        this.axios.get('in_grupos')
          .then((response) => {
            if (response.data !== '') {
              this.grupos = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
          })
      },
      getSubGrupos () {
        this.axios.get('in_subgrupos')
          .then((response) => {
            if (response.data !== '') {
              this.subGrupos = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
          })
      },
      getUnidadesDeMedida () {
        this.axios.get('in_unidades_medida')
          .then((response) => {
            if (response.data !== '') {
              this.unidadesDeMedida = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
          })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formProducto')) {
          this.loadingSubmit = true
          if (this.producto.id) {
            this.axios.put('in_productos/' + this.producto.id, this.producto)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: response.data.message, color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(PRODUCTO_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('in_productos', this.producto)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El Producto se creo correctamente', color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(PRODUCTO_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      getRegistro (id) {
        let loader = this.$loading.show({
          container: this.$refs.loaderRef.$el
        })
        this.axios.get('in_productos/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el Producto. ', error: e})
          })
      },
      edit (item) {
        this.ordenEdit = true
        this.producto = item
      },
      editarByCodigo () {
        this.dialogCodigo = false
        this.ordenEdit = true
      },
      cerrarByCodigo () {
        this.formReset()
        this.dialogCodigo = false
        this.ordenEdit = false
      },
      formReset () {
        this.dialogCodigo = false
        this.ordenEdit = false
        this.producto = {}
      },
      terceroSeleccionado (val) {
        if (this.producto.id != null) {
          return
        }
        if (val != null) {
          this.producto.tercero = val
          this.producto.gn_tercero_id = val.id
          this.producto.nombre = val.razon_social
        }
      }
    }
  }
</script>
