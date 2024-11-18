<template>
  <div>
    <v-dialog v-model="dialogConfirm" persistent max-width="290">
      <v-card>
        <v-card-title class="headline grey lighten-3">Alerta</v-card-title>
        <v-card-text>¿Está seguro que desea eliminar este registro?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click.native="dialogConfirm = false">Cancelar</v-btn>
          <v-btn color="primary" flat @click.native="eliminarDetalle">Aceptar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card ref="formCuentaBancaria">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12 sm12 md12 lg12>
            <v-form data-vv-scope="formCuentasBancarias">
              <v-container fluid grid-list-md>
                <v-layout row wrap>
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="cuentaBancaria.codigo"
                                  label="Código" key="codigo"
                                  name="codigo" v-validate="'required|max:5|not_in:' + codigosUsados" required
                                  :error-messages="errors.collect('codigo')" :disabled="cuentaBancaria.id ? true : false"></v-text-field>
                  </v-flex>

                  <v-flex xs12 sm4 md4>
                    <v-autocomplete
                      label="Entidad bancaria"
                      v-model="cuentaBancaria.ts_banco_id"
                      :items="complementosBanco.bancos"
                      item-value="id"
                      item-text="nombre"
                      name="Entidad bancaria"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Entidad bancaria')">

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

                  <v-flex xs12 sm4 md4>
                    <v-autocomplete
                      label="Ciudad Origen"
                      v-model="cuentaBancaria.gn_municipio_id"
                      :items="complementos.municipios"
                      item-value="id"
                      item-text="nombre"
                      name="Ciudad Origen"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Ciudad Origen')"
                      :filter="filterMunicipios">

                      <template slot="item" slot-scope="data">
                        <template>
                          <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.nombre"/>
                            <v-list-tile-sub-title v-html="data.item.nombre_departamento"/>
                          </v-list-tile-content>
                        </template>
                      </template>
                    </v-autocomplete>
                  </v-flex>

                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="cuentaBancaria.numero_cuenta"
                                  label="Número de cuenta" key="numeroDeCuenta"
                                  name="Numero de cuenta" v-validate="'required'" required
                                  :error-messages="errors.collect('Numero de cuenta')">

                    </v-text-field>
                  </v-flex>

                  <v-flex xs12 sm4 md4>
                    <v-menu
                      ref="menuDate"
                      :close-on-content-click="false"
                      v-model="menuDate"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                    >
                      <!--:return-value.sync="date"-->
                      <v-text-field
                        slot="activator"
                        v-model="computedDateFormatted"
                        label="Fecha Apertura"
                        prepend-icon="event"
                        readonly
                        name="fecha"
                        v-validate="'required|date_format:YYYY/MM/DD'"
                        :error-messages="errors.collect('fecha')"
                      ></v-text-field>
                      <v-date-picker
                        v-model="cuentaBancaria.fecha_apertura"
                        @input="menuDate = false"
                        @change="() => {
                          let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                          $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                        }"
                        locale='es'
                        no-title
                        :max="maxDate"
                      ></v-date-picker>
                    </v-menu>
                  </v-flex>

                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="cuentaBancaria.saldo_inicial"
                                  label="Saldo Inicial" key="saldoInicial"
                                  name="saldoInicial" v-validate="'required|numeric'" required
                                  :error-messages="errors.collect('saldoInicial')"></v-text-field>
                  </v-flex>

                  <v-flex xs12 sm4 md4>
                    <postulador
                      nodata="Busqueda por código o nombre."
                      required
                      hint="codigo"
                      itemtext="nombre"
                      datatitle="codigo"
                      datasubtitle="nombre"
                      filter="codigo,nombre"
                      label="cuenta auxiliar"
                      scope="formDetalle"
                      ref="buscarCuenta"
                      entidad="niifs"
                      routeparams="nivel:auxiliar=1"
                      preicon="person"
                      btnplustruncate
                      @click="openNuevaNiif()"
                      @change="val => cuentaBancaria.nf_niif_id = val"
                      @objectReturn="val => cuentaBancaria.niif = val"
                      :value="cuentaBancaria.niif"
                      clearable
                    ></postulador>
                  </v-flex>
                  <registro-niif :value="dialogFormNiif" :cue="cuentaNiff" @cancelar="dialogFormNiif = false" @crear="val => goNiif(val)"></registro-niif>

                  <v-flex xs12 sm4 md4>
                    <v-autocomplete
                      label="Centro de Costo"
                      v-model="cuentaBancaria.cencosto"
                      @change="val => cuentaBancaria.nf_cencosto_id = val ? val.id : null"
                      :items="complementosCentroDeCosto.cencostos"
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

                  <v-flex xs12 sm4 md4>
                    <v-select
                      :items="tiposCuenta"
                      v-model="cuentaBancaria.tipo_cuenta"
                      name="tipo de cuenta"
                      label="Tipo"
                      :error-messages="errors.collect('tipo de cuenta')"
                      v-validate="'required'" required
                    ></v-select>
                  </v-flex>

                  <v-flex xs12 sm4 md4 v-if="cuentaBancaria.id">
                    <v-subheader class="pa-0 ma-0">Estado</v-subheader>
                    <v-switch class="ma-0 pa-0"  color="accent" :label="cuentaBancaria.estado"
                              v-model="cuentaBancaria.estado"  true-value="Activo" false-value="Inactivo"></v-switch>
                  </v-flex>

                  <!--<v-flex xs12 sm4 md4 offset-sm4 offset-md4 v-if="cuentaBancaria.tipo_cuenta === 'Corriente'">
                    <v-select
                      :items="controlDeCheques"
                      v-model="cuentaBancaria.control_auto"
                      name="control de cheques"
                      label="Control automático"
                      :error-messages="errors.collect('control de cheques')"
                      v-validate="'required'" required
                    ></v-select>
                  </v-flex>

                  <v-slide-x-transition>
                    <v-flex xs12 sm12 md12 v-if="cuentaBancaria.tipo_cuenta === 'Corriente'">
                      <template>
                        <v-card>
                          <v-card-title class="grey lighten-3">
                            <h3>Consecutivos de cheques</h3>
                          </v-card-title>
                          <v-divider></v-divider>
                          <v-form data-vv-scope="formRango">
                            <v-container fluid grid-list-lg>
                              <v-layout row wrap>
                                <v-flex xs12 sm3 md3>
                                  <v-text-field  type="number" v-model.number="detalle.cheque_inicial" key="valor inicial"
                                                name="valor inicial" required v-validate="'required|min_value:1'"
                                                :error-messages="errors.collect('valor inicial')" label="Valor Inicial"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3 md3>
                                  <v-text-field type="number" v-model.number="detalle.cheque_final" key="valor final"
                                                name="valor final" required v-validate="'required|min_value:' + detalle.cheque_inicial"
                                                :error-messages="errors.collect('valor final')" label="Valor Final""></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3 md3>
                                  <v-select
                                    :items="estadosConsecutivoCheque"
                                    v-model="detalle.estado"
                                    name="estado cheque"
                                    label="Estado"
                                    :error-messages="errors.collect('estado cheque')"
                                    v-validate="'required'" required
                                  ></v-select>
                                </v-flex>

                                <v-flex xs12 sm3 md3 class="text-sm-center pt-2">
                                  <div sm10>
                                    <v-btn color="primary" @click.prevent="agregarDetalle(detalle)"><v-icon>add</v-icon> Agregar</v-btn>
                                  </div>
                                </v-flex>
                              </v-layout>
                            </v-container>
                          </v-form>
                          <v-divider></v-divider>
                          <v-data-table v-if="cuentaBancaria.detalles.length > 0"
                                        :headers="headers"
                                        :items="cuentaBancaria.detalles"
                                        :loading="tableLoading"
                                        hide-actions rows-per-page-text="Registros por página"
                                        :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                            <template slot="items" slot-scope="props">
                              <td>{{ props.item.cheque_inicial }}</td>
                              <td>{{ props.item.cheque_final }}</td>
                              <td>{{ props.item.estado }}</td>
                              <td class="text-xs-center">
                                <v-tooltip top>
                                  <v-btn icon class="mx-0" fab small slot="activator" @click="cargarDetalleAEliminar(props.index)">
                                    <v-icon color="accent">delete</v-icon>
                                  </v-btn>
                                  <span>Eliminar</span>
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
                          </v-data-table>
                        </v-card>
                      </template>
                    </v-flex>
                  </v-slide-x-transition>-->

                </v-layout>
              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="formReset" :loading="loadingSubmit">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit" :disabled="errors.any()">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CUENTA_BANCARIA_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import store from '../../../../store/complementos/index'
  export default {
    name: 'RegistroCuentaBancaria',
    components: {
      RegistroNiif: () => import('@/components/administrativo/niif/puc/RegistroNiif'),
      Postulador: () => import('@/components/general/Postulador')
    },
    props: ['parametros'],
    data () {
      return {
        cuentasBancarias: [],
        cuentaBancaria: {},
        detalle: {},
        detalleAEliminar: null,
        tiposCuenta: ['Ahorros', 'Corriente'],
        controlDeCheques: ['Si', 'No'],
        estadosConsecutivoCheque: ['Activa', 'Bloqueada', 'Terminada'],
        menuDate: false,
        maxDate: this.moment().format('YYYY-MM-DD'),
        codigosUsados: '',
        nombresUsados: '',
        dialogFormNiif: false,
        cuentaNiff: null,
        loadingSubmit: false,
        tableLoading: false,
        dialogConfirm: false,

        headers: [
          {
            text: 'Valor Inicial',
            align: 'left',
            sortable: false,
            value: 'valorInicial'
          },
          {
            text: 'Valor Final',
            align: 'left',
            sortable: false,
            value: 'valorFinal'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
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
      if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
    },
    beforeCreate () {
      this.axios.get('cuentas')
        .then((res) => {
          this.cuentasBancarias = res.data.data
          this.codigosUsados = this.getCodigosUsados()
          this.nombresUsados = this.getNombresUsados()
        })
        .catch(e => {
          console.log('Hay un error. ' + e)
          return false
        })
    },
    computed: {
      tapTitulo () {
        return !this.cuentaBancaria.id ? 'Nueva Cuenta' : 'Edición de la cuenta: ' + this.cuentaBancaria.numero_cuenta
      },
      complementos () {
        let beforeComplement = JSON.parse(JSON.stringify(store.getters.complementosTercerosFormBasicos))

        return beforeComplement
      },
      complementosCentroDeCosto () {
        return store.getters.complementosFormComDiario
      },
      complementosBanco () {
        return store.getters.complementosFormBancos
      },
      computedDateFormatted () {
        return this.formDate(this.cuentaBancaria.fecha_apertura)
      }
    },
    methods: {
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.formCuentaBancaria.$el
        })
        this.axios.get('cuentas/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.cuentaBancaria = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la Cuenta Bancaria. ' + e.response, color: 'danger'})
          })
      },
      formReset () {
        this.cuentaBancaria = {
          id: '',
          ts_banco_id: null,
          codigo: null,
          tipo_cuenta: null,
          estado: 'Inactivo',
          numero_cuenta: null,
          fecha_apertura: null,
          saldo_inicial: null,
          control_auto: null,
          nf_niif_id: null,
          nf_cencosto_id: null,
          gn_municipio_id: null,
          cheque_inicial: null,
          cheque_final: null,
          chequeEstado: null,
          detalles: []
        }
        this.$validator.reset()
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      formChequesReset () {
        this.detalle = {
          cheque_inicial: null,
          cheque_final: null,
          estado: null
        }
      },
      filterMunicipios (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      getCodigosUsados () {
        return this.cuentasBancarias.map(cod => { if (!(this.cuentaBancaria.codigo != null && this.cuentaBancaria.codigo === cod.codigo)) return cod.codigo })
      },
      getNombresUsados () {
        return this.cuentasBancarias.map(nom => { if (!(this.cuentaBancaria.nombre != null && this.cuentaBancaria.nombre === nom.nombre)) return nom.nombre })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      openNuevaNiif () {
        this.dialogFormNiif = true
      },
      goNiif (val) {
        this.cuentaBancaria.niif = val
        this.dialogFormNiif = false
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      cargarDetalleAEliminar (detalle) {
        this.dialogConfirm = true
        this.detalleAEliminar = detalle
      },
      eliminarDetalle () {
        this.cuentaBancaria.detalles.splice(this.detalleAEliminar, 1)
        this.dialogConfirm = false
      },
      async agregarDetalle (detalle) {
        if (await this.validador('formRango')) {
          this.cuentaBancaria.detalles.push(detalle)
          this.$validator.reset()
          this.formChequesReset()
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al registrar el rango', color: 'danger'})
        }
      },
      async submit () {
        if (await this.validador('formCuentasBancarias')) {
          this.loadingSubmit = true
          const typeRequest = this.cuentaBancaria.id ? 'editar' : 'crear'
          let send = !this.cuentaBancaria.id ? this.axios.post('cuentas', this.cuentaBancaria) : this.axios.put('cuentas/' + this.cuentaBancaria.id, this.cuentaBancaria)
          send.then(response => {
            if (this.cuentaBancaria.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'Cuenta actualizada satisfactoriamente', color: 'success'})
              this.$store.commit(CUENTA_BANCARIA_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Cuenta creada satisfactoriamente', color: 'success'})
              this.$store.commit(CUENTA_BANCARIA_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            }
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        }
      }
    }
  }
</script>

<style scoped>

</style>
