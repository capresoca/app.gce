<template>
  <div>
    <v-dialog v-model="dialog" width="550px" persistent>
      <v-form data-vv-scope="formProveedor">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <v-container fluid grid-list-xl>
            <v-layout row wrap>
              <v-flex xs12 sm12 md12>
                <postulador
                  nodata="Busqueda por nombre o número de documento."
                  required
                  hint="identificacion_completa"
                  itemtext="nombre_completo"
                  datatitle="identificacion"
                  datasubtitle="nombre_completo"
                  filter="identificacion_completa,nombre_completo"
                  :label="proveedor.gn_tercero_id != null ? 'Tercero ' : 'tercero '"
                  scope="formProveedor"
                  ref="postulaTercero"
                  entidad="terceros"
                  preicon="person"
                  @change="val => proveedor.gn_tercero_id = val"
                  @objectReturn="val => terceroSeleccionado(val)"
                  :value="proveedor.tercero"
                  clearable/>
                <v-layout row wrap v-if="proveedor.tercero">
                    <v-flex xs12 class="pb-1">
                      <v-slide-y-transition>
                        <tarjeta-detalle
                          :telefono="proveedor.tercero.telefono_fijo"
                          :celular="proveedor.tercero.celular"
                          :nombre="(proveedor.tercero.razon_social === null || proveedor.tercero.razon_social === '') ? proveedor.tercero.nombre_completo : proveedor.tercero.razon_social"
                          :identificacion="proveedor.tercero.identificacion_completa"
                        >
                        </tarjeta-detalle>
                      </v-slide-y-transition>
                    </v-flex>
                </v-layout>
              </v-flex>
              <v-flex xs12 sm12 md12>
                <postulador
                  nodata="Busqueda por código o nombre."
                  required
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  :label="proveedor.nf_niif_id != null ? 'Cuenta' : 'cuenta '"
                  lite
                  scope="formProveedor"
                  ref="buscarCuenta"
                  entidad="niifs"
                  routeparams="nivel:auxiliar=1"
                  :preicon="'fas fa-wallet'"
                  @change="val => proveedor.nf_niif_id = val"
                  @objectReturn="val => proveedor.niif = val"
                  :value="proveedor.niif"
                  clearable
                ></postulador>
              </v-flex>
              <v-flex xs12 sm12 md12 xl12 class="pb-1">
                <v-select
                  :items="complementos.tiposProveedor"
                  v-model="proveedor.tipo_proveedor"
                  label="Tipo Proveedor"
                  name="tipo proveedor"
                  key="tipo proveedor"
                  v-validate="'required'"
                  required
                  multiple
                  :error-messages="errors.collect('tipo proveedor')"
                ></v-select>
              </v-flex>
              <v-flex xs12 sm12 md12 xl12 class="pt-1">
                <v-text-field type="number" v-model.number="proveedor.plazo"
                              label="Plazo" key="plazo"
                              name="plazo" v-validate="'required|max:11'" :suffix="proveedor.plazo ? (proveedor.plazo !== 1 ? 'días' : 'día' ) : null" required
                              :error-messages="errors.collect('plazo')"></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close(parametros ? (parametros.key !== null ? 0 : null) : null)">Cancelar</v-btn>
            <v-btn color="primary" @click="save">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" btnplustruncate @click.native="dialog = true" btnplus btnplustitle="Crear Proveedor" title="Proveedores" subtitle="Registro"/>
      <data-table
        v-model="dataTableProveedor"
        @resetOption="item => resetOptions(item)"
        @editar="item => edit(item)"
      />
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import store from '../../../store/complementos/index'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {PG_PROVEEDOR_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'Proveedores',
    props: ['parametros'],
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable'),
      TarjetaDetalle: () => import('@/components/general/TajetaDetalle'),
      Postulador: () => import('@/components/general/Postulador')
    },
    data () {
      return {
        dataTableProveedor: {
          nameItemState: 'itemPgProveedor',
          route: 'pg_proveedores',
          optionsPerPage: [
            {
              text: 15,
              value: 15
            },
            {
              text: 50,
              value: 50
            },
            {
              text: 100,
              value: 100
            }
          ],
          makeHeaders: [
            {
              text: 'Proveedor',
              align: 'left',
              sortable: false,
              value: 'tercero.nombre_completo',
              component: {
                component: {
                  template: `
                    <div>
                      <span><v-icon color="#375d7b" size="18" class="pr-1" v-text="value.tercero ? 'fas fa-id-card-alt': ''"></v-icon>{{ value.tercero ? (value.tercero.razon_social ? value.tercero.razon_social : value.tercero.nombre_completo) :  '' }}</span>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Cuenta',
              align: 'left',
              sortable: false,
              value: 'niif.nombre',
              component: {
                component: {
                  template: `
                    <div>
                      <span v-text="value.niif ? value.niif.nombre : ''"></span>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Tipo',
              align: 'left',
              sortable: false,
              value: 'tipo_proveedor',
              component: {
                component: {
                  template: `
                    <div>
                       <span v-for="(item, index) in value.tipo_proveedor" v-text="item + ((value.tipo_proveedor[index + 1]) ? ', ' : ' ')"></span>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Plazo',
              align: 'left',
              sortable: false,
              value: 'plazo',
              component: {
                component: {
                  template: `
                    <div>
                      <span v-text="value.plazo ? (value.plazo !== 1 ? value.plazo + ' días' : value.plazo + ' día') : ''"></span>
                    </div>
                  `,
                  props: ['value']
                }
              },
              width: '100px'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'text-xs-center',
              singlesActions: true
            }
          ]
        },
        proveedores: [],
        proveedor: {},
        search: '',
        dialog: false,
        tableLoading: false
      }
    },
    mounted () {
      if (this.parametros ? this.parametros.key !== null : '') this.dialog = true
    },
    beforeMount () {
      this.formReset()
    },
    watch: {
      'proveedor.tercero' (val) {
        if (val === null) {
          return val != null ? this.proveedor.tercero : null
        }
      },
      '$store.state.nav.currentItem' (val) {
        if (val) {
          this.dialog = false
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('pg_proveedores')
      },
      modalTitulo () {
        return !this.proveedor.id ? 'Nuevo Proveedor' : 'Edición del Proveedor: ' + (this.proveedor.tercero ? (this.proveedor.tercero.nombre1 ? this.proveedor.tercero.nombre1 : this.proveedor.tercero.razon_social) : 'Pro...')
      },
      complementos () {
        return JSON.parse(JSON.stringify(store.getters.complementosFormProveedor))
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        return item
      },
      formReset () {
        this.proveedor = {
          id: '',
          gn_tercero_id: null,
          nf_niif_id: null,
          tipo_proveedor: null,
          plazo: null,
          tercero: null,
          niif: null
        }
      },
      terceroSeleccionado (val) {
        this.proveedor.tercero = val
        if (val !== null && this.dialog === false) {
          this.dialog = true
        }
      },
      edit (item) {
        this.dialog = true
        this.proveedor = JSON.parse(JSON.stringify(item))
      },
      close (val) {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
        this.$refs.buscarCuenta.reset()
        this.$refs.postulaTercero.reset()
        if ((this.parametros ? this.parametros.key !== null : '') && (val === 0)) this.optionsParametros()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        let errorCuenta = await this.$refs.buscarCuenta.validate()
        let errorTercero = await this.$refs.postulaTercero.validate()
        if (await this.validador('formProveedor') && errorCuenta && errorTercero) {
          let typeRequest = this.proveedor.id ? 'editar' : 'crear'
          var send = !this.proveedor.id ? this.axios.post('pg_proveedores', this.proveedor) : this.axios.put('pg_proveedores/' + this.proveedor.id, this.proveedor)
          send.then(response => {
            this.$store.commit(SNACK_SHOW, {msg: `El proveedor se ${this.proveedor.id ? 'actualizó' : 'creó'} correctamente.`, color: 'success'})
            this.$store.commit(PG_PROVEEDOR_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: null})
            if (this.parametros ? this.parametros.key !== null : '') this.optionsParametros(response)
            this.close(1)
          }).catch((e) => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro.' + e.response, color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      optionsParametros (response) {
        this.$store.commit(PG_PROVEEDOR_RELOAD_ITEM, {item: response ? response.data.data : null, estado: 'reload', key: (this.parametros.key !== null ? this.parametros.key : null)})
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
      }
    }
  }
</script>

<style scoped>

</style>
