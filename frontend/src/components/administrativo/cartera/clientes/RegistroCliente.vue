<template>
  <div>
    <v-card>
      <v-card-title class="headline grey lighten-2" primary-title v-if="!ordenEdit">
        Registrar cliente
      </v-card-title>
      <v-card-title class="headline grey lighten-2" primary-title v-else>
        Editar cliente
      </v-card-title>
      <v-card-text>
        <v-form data-vv-scope="formCliente">
          <v-container fluid grid-list-xl>
            <v-layout row wrap>
              <v-flex xs12 sm12 md2 lg2>
                <v-text-field label="Codigo del cliente" v-model="cliente.codigo" 
                  key="codigo"
                  name="codigo"
                  v-validate="'required|max:16'"
                  :error-messages="errors.collect('codigo')"
                  :disabled="ordenEdit"
                  :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''"
                  persistent-hint
                  @keyup.enter="clienteByCod"
                  @blur="clienteByCod"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md5 lg5>
                <postulador
                  nodata="Busqueda por nombre o número de documento."
                  required
                  hint="nombre_completo"
                  itemtext="identificacion"
                  datatitle="identificacion_completa"
                  datasubtitle="nombre_completo"
                  filter="identificacion_completa,nombre_completo"
                  label="Tercero"
                  scope="formPrincipal"
                  ref="postulaTercero"
                  entidad="terceros"
                  routeparams="with=municipio.departamento"
                  preicon="person"
                  @change="val => cliente.gn_tercero_id = val"
                  @objectReturn="val => terceroSeleccionado(val)"
                  :value="cliente.tercero"
                  clearable
                />
              </v-flex>
              <v-flex xs12 sm12 md5 lg5>
                <v-text-field label="Nombre del cliente" v-model="cliente.nombre"
                  key="nombre"
                  name="nombre"
                  v-validate="'required|max:255'"
                  required
                  :error-messages="errors.collect('nombre')">                      
                </v-text-field>
              </v-flex>
            </v-layout>
            <v-layout row wrap>
              <v-flex xs12 sm12 md12 lg12 v-if="cliente.tercero.id">
                <v-slide-y-transition>
                  <v-card class="grey lighten-4 d-flex cardProveedor">
                  <v-card-title class="grey lighten-2 pr-0">
                    <span class="title" v-text="'Datos Basicos Del Cliente: '"></span>
                  </v-card-title>
                  <v-container fluid grid-list-md class="pr-0">
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md12 xl12>
                        <v-card class="grey lighten-4 elevation-0" style="max-width: 100% !important;">
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md12 xl12>
                              <v-card-title primary-title class="pa-1 pt-1">
                                <div>
                                  <div>
                                    <v-icon size="20">near_me</v-icon>
                                    <span v-text="cliente.tercero.direccion + ' ' + cliente.tercero.municipio.nombre + ' - ' + cliente.tercero.municipio.departamento.nombre"></span>
                                  </div>
                                  <div>
                                    <v-icon size="20">phone</v-icon>
                                    <span v-text="cliente.tercero.telefono_fijo ? cliente.tercero.telefono_fijo : 'No registra.'"></span>
                                  </div>
                                  <div>
                                    <v-icon size="20">phone_android</v-icon>
                                    <span v-text="cliente.tercero.celular ? cliente.tercero.celular : 'No registra.'"></span>
                                  </div>
                                </div>
                              </v-card-title>
                            </v-flex>
                          </v-layout>
                        </v-card>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card>
                </v-slide-y-transition>
              </v-flex>
            </v-layout>
            <v-subheader>Datos de registro del cliente</v-subheader>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs11 sm11 md11 lg11>
                <postulador
                  nodata="Busqueda por codigo o nombre"
                  required
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="Vendedor"
                  scope="formPrincipal"
                  lite
                  ref="postulaVendedor"
                  entidad="vendedor"
                  preicon="person"
                  @change="val => cliente.cr_vendedor_id = val"
                  @objectReturn="val => cliente.vendedor = val"
                  :value="cliente.vendedor"
                  clearable
                />
              </v-flex>
              <v-flex xs1 sm1 md1 lg1>
                <v-tooltip top>
                  <v-btn
                    slot="activator"
                    flat
                    icon
                    @click="dialogSave = true">
                    <v-icon color="accent">add</v-icon>
                  </v-btn>
                  <span>Nuevo vendedor</span>
                </v-tooltip>
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
            <v-layout row wrap>
              <v-flex xs12 sm12 md2 lg2>
                <v-text-field label="Dias de plazo" type="number" v-model="cliente.plazo"
                  key="plazo"
                  name="plazo"
                  hint="Dias de plazo"
                  persistent-hint
                  v-validate="'numeric'"
                  :error-messages="errors.collect('plazo')" required>                      
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md2 lg2>
                <v-text-field label="Cupo maximo"  type="number" v-model="cliente.cupo"
                  key="cupo"
                  name="cupo"
                  hint="Cupo maximo"
                  persistent-hint
                  v-validate="'numeric'"
                  :error-messages="errors.collect('cupo')" required>                      
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md2 lg2>
                <v-text-field label="% de descuento" type="number" v-model="cliente.descuento"
                  key="descuento"
                  name="descuento"
                  hint="% de descuento"
                  persistent-hint
                  v-validate="'decimal:5'"
                  suffix="%"
                  :error-messages="errors.collect('descuento')" required>                      
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md6 lg6>
                <postulador
                  nodata="Busqueda por código o nombre."
                  required
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="cuenta auxiliar"
                  lite
                  scope="formDetalle"
                  ref="buscarCuenta"
                  entidad="niifs"
                  routeparams="nivel:auxiliar=1"
                  preicon="local_atm"
                  @change="val => cliente.nf_niif_id = val"
                  @objectReturn="val => cliente.niif = val"
                  :value="cliente.niif"
                  clearable>
                </postulador>
              </v-flex>
              <v-flex xs12 sm12 md4 lg4>
                Maneja Iva
                <v-radio-group v-model="cliente.maneja_iva"
                  :column="false">
                  <v-radio
                  key="1"
                  label="Si"
                  value="Si"
                  ></v-radio>
                  <v-radio
                  key="2"
                  label="No"
                  value="No"
                  ></v-radio>
                </v-radio-group>
              </v-flex>
              <v-flex xs12 sm12 md4 lg4>
                Tipo de cliente
                <v-radio-group v-model="cliente.tipo_cliente"
                  :column="false">
                  <v-radio
                  key="1"
                  label="Clase A"
                  value="Clase A"
                  ></v-radio>
                  <v-radio
                  key="2"
                  label="Clase B"
                  value="Clase B"
                  ></v-radio>
                  <v-radio
                  key="3"
                  label="Clase C"
                  value="Clase C"
                  ></v-radio>
                </v-radio-group>
              </v-flex>
              <v-flex xs12 sm12 md4 lg4>
                Estado
                <v-radio-group v-model="cliente.estado"
                  :column="false">
                  <v-radio
                  key="1"
                  label="Activo"
                  value="Activo"
                  ></v-radio>
                  <v-radio
                  key="2"
                  label="Inactivo"
                  value="inactivo"
                  ></v-radio>
                </v-radio-group>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
        color="primary"
        flat
        :disabled="errors.any()"
        @click="submit"
        >
          Guardar
        </v-btn>
        <v-btn
        color="primary"
        flat
        @click="formReset"
        >
          Cerrar
        </v-btn>
      </v-card-actions>
      <div>
        <v-dialog v-model="dialogCodigo" persistent max-width="290">      
          <v-card>
            <v-card-title class="headline">Codigo del cliente</v-card-title>
            <v-card-text>El codigo del cliente ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="green darken-1" flat @click="editarByCodigo">Cargar datos</v-btn>
              <v-btn color="green darken-1" flat @click="cerrarByCodigo">Cerrar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </div>
    </v-card>
    <div class="text-xs-center">
      <registro-vendedores :open="dialogSave" :parametros="param" @dialog="val => dialogSave = val" @objectReturn="val => returnDialogo(val)"></registro-vendedores>
    </div>
  </div>
</template>

<script>
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CLIENTE_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import RegistroVendedores from '@/components/administrativo/cartera/vendedores/RegistroVendedores'
  export default {
    name: 'RegistroCliente',
    props: ['parametros'],
    data () {
      return {
        dialogSave: false,
        dialogCodigo: false,
        validCliente: false,
        validNitCliente: false,
        buscandoCodigo: false,
        ordenEdit: false,
        param: {},
        cliente: {
          tercero: {
            identificacion: '',
            nombre_completo: '',
            municipio: {
              nombre: '',
              departamento: {
                nombre: ''
              }
            }
          },
          vendedor: {
            codigo: '',
            nombre: ''
          },
          niif: {
            codigo: '',
            disponibilidad: '',
            estado: '',
            id: null,
            maneja_ccosto: null,
            maneja_tercero: null,
            nf_anedeclaracione_id: null,
            nf_clascuenta_id: null,
            nf_nivcuenta_id: null,
            nf_padre_id: null,
            nombre: '',
            tipo: '',
            tipo_retencion: null
          }
        }
      }
    },
    components: {
      Postulador: () => import('@/components/general/Postulador'),
      RegistroVendedores
    },
    watch: {
      'cliente.tercero' (val) {
        this.cliente.nombre = val.nombre_completo
      }
    },
    methods: {
      buscar () {
      },
      terceroSeleccionado (val) {
        if (val !== null) {
          if (val.id !== null && val.id !== '' && val.id !== undefined) {
            if (val.id !== this.cliente.tercero.id) {
              this.axios.get('clientes/tercero/' + val.id)
                .then((response) => {
                  if (response.data.exists) {
                    this.cliente.tercero = response.data.tercero
                  } else {
                    this.$store.commit(SNACK_SHOW, {msg: 'No podemos recuperar los datos del tercero. Intente de nuevo', color: 'danger'})
                  }
                }).catch(e => {
                  this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
                })
            }
          }
        } else {
          if (val !== null) {
            this.cliente.tercero = val
          } else {
            this.cliente.tercero = {
              identificacion: '',
              nombre_completo: '',
              municipio: {
                nombre: '',
                departamento: {
                  nombre: ''
                }
              }
            }
          }
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async clienteByCod () {
        if (this.cliente.codigo) {
          this.buscandoCodigo = true
          this.axios.get('clientes/codigo/' + this.cliente.codigo)
            .then((response) => {
              if (response.data.exists) {
                this.cliente = response.data.cliente
                this.dialogCodigo = true
              } else {
                var codigo = this.cliente.codigo
                this.cliente = {
                  id: '',
                  codigo: codigo,
                  tercero: {
                    identificacion: '',
                    nombre_completo: '',
                    municipio: {
                      nombre: '',
                      departamento: {
                        nombre: ''
                      }
                    }
                  },
                  vendedor: {
                    codigo: '',
                    nombre: ''
                  },
                  niif: {
                    codigo: '',
                    disponibilidad: '',
                    estado: '',
                    id: null,
                    maneja_ccosto: null,
                    maneja_tercero: null,
                    nf_anedeclaracione_id: null,
                    nf_clascuenta_id: null,
                    nf_nivcuenta_id: null,
                    nf_padre_id: null,
                    nombre: '',
                    tipo: '',
                    tipo_retencion: null
                  }
                }
                this.dialogCodigo = false
              }
              this.buscandoCodigo = false
              this.validCliente = true
              this.validNitCliente = true
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
            })
        }
      },
      async submit () {
        if (await this.validador('formCliente')) {
          if (this.cliente.id) {
            this.axios.put('clientes/' + this.cliente.id, this.cliente)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: response.data.message, color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(CLIENTE_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('clientes', this.cliente)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El cliente se creo correctamente', color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(CLIENTE_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      getRegistro (id) {
        this.axios.get('clientes/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el cliente. ', error: e})
          })
      },
      returnDialogo (val) {
        this.cliente.vendedor = val.data
      },
      edit (item) {
        this.ordenEdit = true
        this.validNitCliente = true
        this.cliente = item
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
        this.validCliente = false
        this.validNitCliente = false
        this.ordenEdit = false
        this.cliente = {
          tercero: {
            identificacion: '',
            nombre_completo: '',
            municipio: {
              nombre: '',
              departamento: {
                nombre: ''
              }
            }
          },
          vendedor: {
            codigo: '',
            nombre: ''
          },
          niif: {
            codigo: '',
            disponibilidad: '',
            estado: '',
            id: null,
            maneja_ccosto: null,
            maneja_tercero: null,
            nf_anedeclaracione_id: null,
            nf_clascuenta_id: null,
            nf_nivcuenta_id: null,
            nf_padre_id: null,
            nombre: '',
            tipo: '',
            tipo_retencion: null
          }
        }
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
    }
  }
</script>

<style scoped>

</style>
