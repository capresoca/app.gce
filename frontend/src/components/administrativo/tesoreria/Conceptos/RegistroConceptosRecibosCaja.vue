<template>
  <v-card>
    <v-container fluid grid-list-md>
      <v-layout row wrap>
        <v-flex xs12 sm4 md4>
          <v-text-field
            label="Código"
            name="código"
            v-model="concepto.codigo"
            :disabled="!!concepto.id"
            v-validate="'required|max:10'"
            :error-messages="errors.collect('código')"
            @keyup.enter="findByCodigo"
            @blur="findByCodigo"
            :loading="buscandoCodigo"
          >
          </v-text-field>
        </v-flex>
        <v-flex xs12 sm4 md8>
          <v-text-field
            label="Nombre del concepto"
            name="nombre del concepto"
            v-model="concepto.nombre"
            v-validate="'required|max:255'"
            :error-messages="errors.collect('nombre del concepto')"
          >
          </v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md8>
          <postulador-v2
            ref="validadorNiif"
            no-data="Busqueda por código o nombre."
            hint="codigo"
            item-text="nombre"
            data-title="codigo"
            data-subtitle="nombre"
            label="Cuenta auxiliar"
            entidad="niifs"
            @changeid="val => concepto.nf_niif_id = val"
            v-model="concepto.niif"
            route-params="nivel:auxiliar=1&with=clascuenta"
            name="cuenta auxiliar"
            rules="required"
            v-validate="'required'"
            :error-messages="errors.collect('cuenta auxiliar')"
            no-btn-create
            :min-characters-search="3"
            clearable
          ></postulador-v2>
        </v-flex>
        <v-flex xs12 sm12 md4>
          <span class="v-label caption theme--light">Naturaleza</span>
          <v-radio-group
            class="mt-0"
            v-model="concepto.naturaleza"
            row
          >
            <v-radio
              label="Débito"
              value="Débito"
            ></v-radio>
            <v-radio
              label="Crédito"
              value="Crédito"
            ></v-radio>
          </v-radio-group>
        </v-flex>
        <v-flex xs12 sm12 md12 v-if="concepto.niif && concepto.niif.tipo === 'Retencion'">
          <v-autocomplete
            label="Concepto de retención"
            v-model="concepto.nf_conrtf_id"
            item-value="id"
            item-text="codigo_nombre"
            :items="conrtfs"
            :loading="loadingConrtfs"
            placehoder="Buscar por código o nombre"
            no-data-text="No hay resultados para mostrar"
            name="concepto de retención"
            v-validate="'required'"
            :error-messages="errors.collect('concepto de retención')"
          >
          </v-autocomplete>
        </v-flex>
        <template v-if="configuracion && configuracion.afecta_presupuesto_doc_tesoreria">
          <v-flex xs12 sm12 md6>
            <v-select
              label="Entidad resolución"
              v-model="concepto.pr_entidad_resolucion_id"
              item-value="id"
              item-text="nombre"
              :items="entidadesResolucion"
              name="entidad resolución"
              v-validate="'required'"
              :error-messages="errors.collect('entidad resolución')"
              persistent-hint
              :hint="concepto.pr_entidad_resolucion_id && !!entidadesResolucion.find(x => x.id === concepto.pr_entidad_resolucion_id) ? 'Periodo: ' + entidadesResolucion.find(x => x.id === concepto.pr_entidad_resolucion_id).periodo : ''"
            >
              <template slot="selection" slot-scope="data">
                <div style="width: 100% !important;">
                  <v-list-tile class="content-v-list-tile-p0">
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title class=caption>Código: {{data.item.codigo}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </div>
              </template>
              <template slot="item" slot-scope="data">
                <div style="width: 100% !important;">
                  <v-list-tile class="content-v-list-tile-p0">
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title class=caption>Código: {{data.item.codigo}} - Periodo: {{ data.item.periodo }}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </div>
              </template>
            </v-select>
          </v-flex>
          <v-flex xs12 sm12 md6>
            <v-autocomplete
              label="Rubro de ingreso"
              v-model="concepto.pr_detstringreso_id"
              :items="stringresos"
              item-value="id"
              item-text="id"
              :filter="filterStringresos"
              placehoder="Busqueda por código o nombre."
              no-data-text="No hay resultados para mostrar"
              name="rubro de ingreso"
              v-validate="'required'"
              :error-messages="errors.collect('rubro de ingreso')"
              persistent-hint
              :hint="concepto.pr_detstringreso_id && !!stringresos.find(x => x.id === concepto.pr_detstringreso_id) ? 'Tipo: ' + stringresos.find(x => x.id === concepto.pr_detstringreso_id).tipo_ingreso.nombre : ''"
            >
              <template slot="selection" slot-scope="data">
                <div style="width: 100% !important;">
                  <v-list-tile class="content-v-list-tile-p0">
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.rubro.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title class=caption>Código: {{data.item.rubro.codigo}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </div>
              </template>
              <template slot="item" slot-scope="data">
                <div style="width: 100% !important;">
                  <v-list-tile class="content-v-list-tile-p0">
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.rubro.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title class=caption>Código: {{data.item.rubro.codigo}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </div>
              </template>
            </v-autocomplete>
          </v-flex>
        </template>
        <v-flex d-flex>
          <v-switch
            label="Facturación"
            v-model="concepto.facturacion"
            :true-value="1"
            :false-value="0"
          >
          </v-switch>
        </v-flex>
        <v-flex d-flex>
          <v-switch
            :disabled="!concepto.facturacion"
            label="Anticipos"
            v-model="concepto.anticipos"
            :true-value="1"
            :false-value="0"
          >
          </v-switch>
        </v-flex>
        <v-flex d-flex>
          <v-switch
            :disabled="!concepto.facturacion || !concepto.anticipos"
            label="Pacientes / Afiliados"
            v-model="concepto.pacientes"
            :true-value="1"
            :false-value="0"
          >
          </v-switch>
        </v-flex>
      </v-layout>
    </v-container>
    <template v-if="configuracion">
      <v-divider></v-divider>
      <v-card-actions>
        <v-tooltip top>
          <v-btn
            slot="activator"
            @click="formReset(true)"
          >
            <v-icon left>fas fa-eraser</v-icon>
            Limpiar
          </v-btn>
          <span>Limpiar formulario</span>
        </v-tooltip>
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          @click="submit"
        >
          Registrar
        </v-btn>
      </v-card-actions>
    </template>
    <loading v-model="loading" />
    <v-dialog v-model="dialogCodigo" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">Codigo del concepto de recibo de caja</v-card-title>
        <v-card-text>El codigo del concepto de recibo de caja ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
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
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CONCEPTO_RECIBO_CAJA_TESORERIA_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'RegistroConceptosRecibosCaja',
    components: {
      Loading,
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    props: ['parametros', 'configuracion'],
    data () {
      return {
        loadingConrtfs: true,
        loading: true,
        dialogCodigo: false,
        buscandoCodigo: false,
        concepto: {},
        temporal: null,
        conrtfs: [],
        entidadesResolucion: [],
        stringresos: [],
        filterStringresos (item, queryText) {
          const hasValue = val => val != null ? val : ''
          return hasValue(item.rubro.codigo + ' ' + item.rubro.nombre).toString().toLowerCase().indexOf(hasValue(queryText).toString().toLowerCase()) > -1
        }
      }
    },
    watch: {
      'concepto.pr_entidad_resolucion_id' (val) {
        if (val) {
          this.getDetStringresos(val)
        } else {
          this.concepto.pr_detstringreso_id = null
        }
      },
      'configuracion.afecta_presupuesto_doc_tesoreria' (val) {
        if (!val) {
          this.concepto.pr_entidad_resolucion_id = null
          this.concepto.pr_detstringreso_id = null
        }
      },
      'concepto.niif' (val) {
        if (val) {
          this.concepto.naturaleza = val.clascuenta.naturaleza
          if (val.tipo === 'Retencion') {
            if (!this.conrtfs.length) this.getConrtfs()
          } else {
            this.concepto.nf_conrtf_id = null
          }
        }
      },
      'concepto.facturacion' (val, prev) {
        if (!val && typeof prev !== 'undefined') {
          this.concepto.anticipos = 0
          this.concepto.pacientes = 0
        }
      },
      'concepto.anticipos' (val, prev) {
        if (!val && typeof prev !== 'undefined') {
          this.concepto.pacientes = 0
        }
      }
    },
    created () {
      this.formReset()
      if (this.parametros.id) {
        this.getRegistro(this.parametros.id)
      } else {
        this.loading = false
      }
      this.getEntidadesResolucion()
    },
    methods: {
      submit () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            const typeRequest = this.concepto.id ? 'editar' : 'crear'
            const request = typeRequest === 'crear' ? this.axios.post(`ts_concepto_recibos`, this.concepto) : this.axios.put(`ts_concepto_recibos/${this.concepto.id}`, this.concepto)
            request
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: `El concepto se ${typeRequest === 'editar' ? 'actualizó' : 'creó'} correctamente.`, color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(CONCEPTO_RECIBO_CAJA_TESORERIA_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
                this.loading = false
              }).catch(e => {
                this.loading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar el concepto. ', error: e})
              })
          }
        })
      },
      findByCodigo () {
        if (this.concepto.codigo) {
          this.temporal = null
          this.buscandoCodigo = true
          this.axios.get(`ts_concepto_recibos/codigo/${this.concepto.codigo}`)
            .then(response => {
              if (response.data.data) {
                this.temporal = response.data.data
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              this.buscandoCodigo = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al validar el código. ', error: e})
            })
        }
      },
      editarByCodigo () {
        this.concepto = JSON.parse(JSON.stringify(this.temporal))
        this.dialogCodigo = false
      },
      cerrarByCodigo () {
        this.concepto.codigo = null
        this.dialogCodigo = false
      },
      formReset (input = false) {
        this.concepto = {
          id: null,
          nombre: null,
          nf_niif_id: null,
          naturaleza: null,
          facturacion: 0,
          anticipos: 0,
          pacientes: 0,
          nf_conrtf_id: null,
          pr_entidad_resolucion_id: null,
          pr_detstringreso_id: null,
          // objetos
          niif: null,
          rubro_ingreso: null
        }
        this.dialogCodigo = false
        this.$validator.reset()
        if (this.$refs.validadorNiif) {
          setTimeout(() => {
            this.$refs.validadorNiif.reset()
          }, 100)
        }
        if (input) this.$emit('input', this.concepto)
      },
      getRegistro (id) {
        this.loading = true
        this.axios.get(`ts_concepto_recibos/${id}`)
          .then(response => {
            this.concepto = response.data.data
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el concepto. ', error: e})
          })
      },
      getDetStringresos (id) {
        this.axios.get(`pr_stringresos?pr_entidad_resolucion=${id}`)
          .then(response => {
            if (response.data.data.length) this.stringresos = response.data.data[0].detalles
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los rubros de ingreso. ', error: e})
          })
      },
      getConrtfs () {
        this.loadingConrtfs = true
        this.axios.get(`conrtfs`)
          .then(response => {
            this.conrtfs = response.data.data
            this.loadingConrtfs = false
          })
          .catch(e => {
            this.loadingConrtfs = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los conceptos de retención. ', error: e})
          })
      },
      getEntidadesResolucion () {
        this.axios.get(`pr_entidad_resolucion`)
          .then(response => {
            this.entidadesResolucion = response.data.data
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer las entidades resolución. ', error: e})
          })
      }
    }
  }
</script>
