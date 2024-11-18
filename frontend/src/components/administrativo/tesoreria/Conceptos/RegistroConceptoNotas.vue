<template>
  <v-card>
    <v-container fluid grid-list-md>
      <v-layout row wrap>
        <v-flex xs12 sm4 md4 lg4>
          <v-text-field label="Codigo"
                        key="codigo"
                        name="codigo"
                        :disabled="ordenEdit"
                        v-model="concepto.codigo"
                        v-validate="'required|max:10'"
                        @keyup.enter="findByCodigo"
                        @blur="findByCodigo"
                        :error-messages="errors.collect('codigo')"
                        persistent-hint
                        :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''"
                        required>
          </v-text-field>
        </v-flex>
        <v-flex xs12 sm4 md8 lg8>
          <v-text-field label="Nombre del concepto"
                        key="nombre"
                        name="nombre del concepto"
                        v-model="concepto.nombre"
                        v-validate="'required|max:255'"
                        :error-messages="errors.collect('nombre del concepto')"
                        required>
          </v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md8 lg8>
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
            ref="buscarCuenta"
            entidad="niifs"
            routeparams="nivel:auxiliar=1&with=clascuenta"
            preicon="person"
            @change="val => concepto.nf_niif_id = val"
            @objectReturn="val => niifSeleccionado(val)"
            :value="concepto.niif"
            clearable
          ></postulador>
        </v-flex>
        <v-flex xs12 sm12 md4 lg4>
          Naturaleza
          <v-radio-group
            v-model="concepto.naturaleza" :column="false">
            <v-radio
              key="1"
              label="Débito"
              value="Débito"
            ></v-radio>
            <v-radio
              key="2"
              label="Crédito"
              value="Crédito"
            ></v-radio>
          </v-radio-group>
        </v-flex>
        <v-flex xs12 sm12 md12 lg12>
          <v-text-field v-model="concepto.cod_retencion" label="Codigo de retencion" required></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md3 lg3>
          constitucion de CDT
          <v-radio-group
            v-model="concepto.constitucion_cdt">
            <v-radio
              key="facturacionSi"
              label="Si"
              value="Si"
            ></v-radio>
            <v-radio
              key="facturacionNo"
              label="No"
              value="No"
            ></v-radio>
          </v-radio-group>
        </v-flex>
        <v-flex xs12 sm12 md3 lg3>
          Redencion de CDT
          <v-radio-group
            v-model="concepto.retencion_cdt">
            <v-radio
              key="facturacionSi"
              label="Si"
              value="Si"
            ></v-radio>
            <v-radio
              key="facturacionNo"
              label="No"
              value="No"
            ></v-radio>
          </v-radio-group>
        </v-flex>
        <v-flex xs12 sm12 md3 lg3>
          Afecta acreeadores
          <v-radio-group
            v-model="concepto.acreedores">
            <v-radio
              key="facturacionSi"
              label="Si"
              value="Si"
            ></v-radio>
            <v-radio
              key="facturacionNo"
              label="No"
              value="No"
            ></v-radio>
          </v-radio-group>
        </v-flex>
        <v-flex xs12 sm12 md3 lg3>
          Afecta CXP
          <v-radio-group
            v-model="concepto.cxp">
            <v-radio
              key="facturacionSi"
              label="Si"
              value="Si"
            ></v-radio>
            <v-radio
              key="facturacionNo"
              label="No"
              value="No"
            ></v-radio>
          </v-radio-group>
        </v-flex>
      </v-layout>
      <v-layout row wrap>
        <v-flex xs12 sm12 md6 lg6>
          Presupuesto de ingresos
          <v-radio-group
            v-model="concepto.ingresos">
            <v-radio
              key="facturacionSi"
              label="Si"
              value="Si"
            ></v-radio>
            <v-radio
              key="facturacionNo"
              label="No"
              value="No"
            ></v-radio>
          </v-radio-group>
        </v-flex>
        <v-flex xs12 sm12 md6 lg6>
          Presupuesto de gastos
          <v-radio-group
            v-model="concepto.gastos">
            <v-radio
              key="facturacionSi"
              label="Si"
              value="Si"
            ></v-radio>
            <v-radio
              key="facturacionNo"
              label="No"
              value="No"
            ></v-radio>
          </v-radio-group>
        </v-flex>
        <v-flex xs12 sm12 md6 lg6>
          <postulador
            nodata="Busqueda por codigo de habilitacion o nombre."
            hint="cod_habilitacion"
            itemtext="tercero.razon_social"
            datatitle="tercero.razon_social"
            datasubtitle="cod_habilitacion"
            filter="identificacion,tercero.razon_social,cod_habilitacion"
            label="Unidad ejecura"
            lite
            ref="buscarUnidadEjecutora"
            entidad="entidades"
            preicon="person"
            routeparams="&with=tercero"
            @change="val => concepto.rs_entidad_id = val"
            @objectReturn="val => concepto.entidad = val"
            :value="concepto.entidad"
            clearable
          ></postulador>
        </v-flex>
        <v-flex xs12 sm12 md6 lg6>
          <postulador
            nodata="Busqueda por descripcion."
            required
            itemtext="descripcion"
            datatitle="descripcion"
            filter="descripcion"
            label="Rubro"
            lite
            ref="buscarRubroPresupuestal"
            entidad="pr_rubros"
            preicon="person"
            @change="val => concepto.pr_rubro_id = val"
            @objectReturn="val => concepto.rubro = val"
            :value="concepto.rubro"
            clearable
          ></postulador>
        </v-flex>
        <v-flex xs12 sm12 md6 lg6>
          <v-text-field v-model="concepto.cod_retencion" label="Tipo de ingreso o de gasto" required></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md6 lg6>
          Devolucion de recaudo
          <v-radio-group
            v-model="concepto.recaudo">
            <v-radio
              key="facturacionSi"
              label="Si"
              value="Si"
            ></v-radio>
            <v-radio
              key="facturacionNo"
              label="No"
              value="No"
            ></v-radio>
          </v-radio-group>
        </v-flex>
      </v-layout>
      <v-layout row wrap>
        <v-flex xs12 sm12 md12 lg12>
          <v-btn
            color="success"
            @click="submit"
          >
            Guardar
          </v-btn>
        </v-flex>
      </v-layout>
    </v-container>
    <loading v-model="loading" />
    <v-dialog v-model="dialogCodigo" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">Codigo del concepto de notas bancarias</v-card-title>
        <v-card-text>El codigo del concepto de notas bancarias ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro código</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click="editarByCodigo">Cargar datos</v-btn>
          <v-btn color="green darken-1" flat @click="cerrarByCodigo">Otro código</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>
<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CONCEPTO_NOTAS_TESORERIA_RELOAD_ITEM} from '@/store/modules/general/tables'
  import Loading from '@/components/general/Loading'
  export default {
    name: 'RegistroConceptoNotas',
    components: {
      Postulador: () => import('@/components/general/Postulador'),
      Loading
    },
    props: ['parametros'],
    data () {
      return {
        loading: false,
        buscandoCodigo: false,
        dialogCodigo: false,
        ordenEdit: false,
        concepto: {
          bancos: '',
          naturaleza: ''
        }
      }
    },
    watch: {
      'parametros' (val) {
        if (val.nuevo) {
          this.formReset()
        }
        if (val.entidad) {
          if (val.entidad !== null) {
            this.getRegistro(val.entidad.id)
          }
        }
      }
    },
    methods: {
      validador () {
        return this.$validator.validateAll().then(result => { return result })
      },
      async submit () {
        if (await this.validador()) {
          if (this.concepto.nf_niif_id) {
            if (this.concepto.id) {
              this.axios.put('ts_concepto_notas/' + this.concepto.id, this.concepto)
                .then(response => {
                  this.$store.commit(SNACK_SHOW, {msg: 'El concepto se actualizo correctamente', color: 'success'})
                  this.formReset()
                  this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                  this.$store.commit(CONCEPTO_NOTAS_TESORERIA_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
                }).catch(e => {
                  this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
                })
            } else {
              this.axios.post('ts_concepto_notas', this.concepto)
                .then(response => {
                  this.$store.commit(SNACK_SHOW, {msg: 'El concepto se creó correctamente', color: 'success'})
                  this.formReset()
                  this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                  this.$store.commit(CONCEPTO_NOTAS_TESORERIA_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
                }).catch(e => {
                  this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
                })
            }
          } else {
            this.$store.commit(SNACK_SHOW, {msg: 'Seleccione una cuenta auxiliar.', color: 'danger'})
          }
        } else {
          console.log('Error')
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      findByCodigo () {
        if (this.concepto.codigo) {
          this.buscandoCodigo = true
          this.axios.get('ts_concepto_notas/codigo/' + this.concepto.codigo)
            .then(response => {
              if (response.data.exists) {
                this.$store.commit(SNACK_SHOW, {msg: 'El concepto existe', color: 'success'})
                this.formReset()
                this.concepto = response.data.data
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              this.buscandoCodigo = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
            })
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
        this.concepto = {
          naturaleza: '',
          facturacion: '',
          anticipos: '',
          pacientes: ''
        }
        this.dialogCodigo = false
        this.ordenEdit = false
      },
      niifSeleccionado (val) {
        if (val !== null) {
          this.concepto.niif = val
          this.concepto.naturaleza = val.clascuenta.naturaleza
        }
      },
      getRegistro (id) {
        this.axios.get('ts_concepto_notas/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'No existe el concepto', color: 'danger'})
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el concepto. ', error: e})
          })
      },
      edit (item) {
        this.ordenEdit = true
        this.concepto = item
      }
    },
    mounted () {
      this.$validator.localize('es')
    }
  }
</script>
