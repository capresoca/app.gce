<template>
  <div>
    <v-card>
      <toolbar-list class="elevation-1 grey lighten-4" :title="cardTitle"/>
      <v-card-text>
        <v-form data-vv-scope="formConceptos">
          <v-container fluid grid-list-xl>
            <v-layout row wrap>
              <v-flex xs12 sm12 md3 lg3>
                <v-select :items="tiposConcepto"
                  v-model="conceptoCartera.tipo_concepto"
                  label="Tipo de Concepto"
                  name="tipo de concepto"
                  key="tipo de concepto"
                  :error-messages="errors.collect('tipo de concepto')"
                  v-validate="'required'"
                ></v-select>
              </v-flex>
              <v-flex xs12 sm12 md3 lg3>
                <v-container class="pa-0">
                  <v-layout row wrap>
                    <v-flex xs9 sm10 md9 class="pr-0">
                      <v-text-field label="Código" v-model="conceptoCartera.codigo" key="código"
                                    name="código" v-validate="'required|max:5'" :error-messages="errors.first('código')"
                                    :disabled="ordenEdit" required></v-text-field>
                    </v-flex>
                    <v-flex xs3 sm2 md3 class="text-xs-right pl-0">
                      <v-tooltip bottom v-show="!ordenEdit">
                        <v-btn slot="activator" color="transparent" fab dark @click="conceptoByCod" icon>
                          <v-icon color="accent">done</v-icon>
                        </v-btn>
                        <span>Verificar Código</span>
                      </v-tooltip>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-flex>
              <v-flex xs12 sm12 md6 lg6>
                <v-text-field label="Nombre del Concepto" v-model="conceptoCartera.nombre"
                  key="nombre"
                  name="nombre"
                  v-validate="'required|max:150'"
                  :error-messages="errors.collect('nombre')"
                  required></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md6 lg6>
                <v-container fluid class="pa-1">
                  <v-radio-group label="El Concepto Aplica A:" :disabled="(conceptoCartera.tipo_concepto !== 'Cuenta por Cobrar')" v-model="conceptoCartera.tipo_aplicacion" row>
                    <v-radio key="1" label="Nota debito" value="Debito"></v-radio>
                    <v-radio key="2" label="Nota credito" value="Credito"></v-radio>
                  </v-radio-group>
                </v-container>
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
                  preicon="person"
                  @change="val => conceptoCartera.nf_niif_id = val"
                  @objectReturn="val => conceptoCartera.niif = val"
                  :value="conceptoCartera.niif"
                  clearable
                ></postulador>
              </v-flex>
              <v-flex xs12 sm6 md3 lg3>
                <!--Maneja tercero-->
                <v-switch  color="success" :label="'Maneja Tercero'"
                           v-model="conceptoCartera.maneja_tercero"  true-value="Si" false-value="No"></v-switch>
              </v-flex>
              <v-flex xs12 sm6 md3 lg3>
                <v-switch  color="success" :label="'Afecta Cartera'"
                           v-model="conceptoCartera.afecta_cartera"  true-value="Si" false-value="No"></v-switch>
              </v-flex>
              <v-flex xs12 sm6 md3 lg3>
                <v-switch  color="success" :label="'Afecta Bancos'"
                           v-model="conceptoCartera.afecta_bancos"  true-value="Si" false-value="No"></v-switch>
              </v-flex>
              <v-flex xs12 sm6 md3 lg3>
                <v-switch  color="success" :label="'Concepto para Glosas'"
                           v-model="conceptoCartera.glosas"  true-value="Si" false-value="No"></v-switch>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="refresh(null)">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="submit">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
    <v-dialog v-model="dialogCodigo" persistent max-width="290">      
      <v-card>
        <v-card-title class="headline">Código del Concepto</v-card-title>
        <v-card-text class="text-xs-justify">El código del concepto ya esta registrado, desea cargar los datos para su actualización o utilizar otro código.</v-card-text>
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
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CONCEPTOSCARTERA_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'RegistrarConcepto',
    props: ['parametros'],
    components: {
      Postulador: () => import('@/components/general/Postulador'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        dialogSave: false,
        dialogCodigo: false,
        ordenEdit: false,
        tiposConcepto: ['Cuenta por Cobrar', 'Notas'],
        conceptoCartera: {},
        headers: [
          {
            text: 'Codigo',
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
            text: 'Tipo de concepto',
            align: 'left',
            sortable: false,
            value: 'tipo_concepto'
          },
          {
            text: 'Cuenta',
            align: 'left',
            sortable: false,
            value: 'niif.codigo'
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
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
    },
    computed: {
      cardTitle () {
        return !this.conceptoCartera.id ? 'Nuevo Concepto de Cartera' : 'Edición del Concepto de Cartera: ' + this.conceptoCartera.nombre
      }
    },
    methods: {
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async conceptoByCod () {
        if (this.conceptoCartera.codigo) {
          if (this.conceptoCartera.tipo_concepto) {
            this.axios.get('conceptoscartera/codigo/' + this.conceptoCartera.codigo + '/' + this.conceptoCartera.tipo_concepto)
              .then((response) => {
                if (response.data.exists) {
                  this.conceptoCartera = response.data.concepto
                  this.dialogCodigo = true
                } else {
                  var codigo = this.conceptoCartera.codigo
                  var tipoConcepto = this.conceptoCartera.tipo_concepto
                  this.$store.commit(SNACK_SHOW, {msg: 'Codigo validado', color: 'success'})
                  this.formReset()
                  this.dialogSave = true
                  this.conceptoCartera.codigo = codigo
                  this.conceptoCartera.tipo_concepto = tipoConcepto
                }
              }).catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
              })
          } else {
            this.$store.commit(SNACK_SHOW, {msg: 'Seleccione un tipo de concepto para validar el codigo', color: 'danger'})
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Digite el codigo', color: 'danger'})
        }
      },
      async submit () {
        let errorCuenta = await this.$refs.buscarCuenta.validate()
        if (await this.validador('formConceptos') && errorCuenta) {
          const typeRequest = this.conceptoCartera.id ? 'editar' : 'crear'
          let send = !this.conceptoCartera.id ? this.axios.post('conceptoscartera', this.conceptoCartera) : this.axios.put('conceptoscartera/' + this.conceptoCartera.id, this.conceptoCartera)
          send.then(response => {
            this.$store.commit(SNACK_SHOW, {
              msg: `Se ${this.conceptoCartera.id
                ? 'actualizaron los datos'
                : 'realizó el registro'} correctamente del Concepto Cartera ${response.data.data.nombre}.`,
              color: 'success'
            })
            this.$store.commit(CONCEPTOSCARTERA_RELOAD_ITEM, {conceptoCartera: response.data.data, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          }).catch(() => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ', color: 'danger'})
          })
        }
      },
      getRegistro (id) {
        this.axios.get('conceptoscartera/' + id)
          .then((response) => {
            this.conceptoCartera = response.data.data
            this.ordenEdit = true
            this.dialogSave = true
            // if (response.data.exists) this.edit(response.data.data)
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' Error al traer el concepto. ', error: e})
          })
      },
      edit (item) {
        this.dialogSave = true
        this.ordenEdit = true
        this.conceptoCartera = item
      },
      editarByCodigo () {
        this.dialogCodigo = false
        this.dialogSave = true
        this.ordenEdit = true
      },
      cerrarByCodigo () {
        this.formReset()
        this.dialogCodigo = false
        this.dialogSave = true
        this.ordenEdit = false
      },
      formReset () {
        this.conceptoCartera = {
          id: null,
          nombre: null,
          codigo: null,
          glosas: 'No',
          tipo_concepto: null,
          nf_niif_id: null,
          maneja_tercero: 'No',
          afecta_bancos: 'No',
          afecta_cartera: 'No',
          tipo_aplicacion: 'Debito',
          niif: null
        }
      },
      refresh () {
        this.ordenEdit = false
        this.$validator.reset()
        this.formReset()
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      }
    }
  }
</script>

<style scoped>

</style>
