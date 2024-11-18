<template>
  <div>
    <v-dialog v-model="destroyFila" max-width="290">
      <v-card>
        <!--<v-card-title class="headline">¿Está seguro/a de eliminar el campo?</v-card-title>-->
        <v-card-text color="blue-grey darken-1">¿Está seguro/a de eliminar el rango del registro RTF <span v-text="conRtf.nombre + '?'"></span> </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat="flat" @click.native="restoreDetalle()">No</v-btn>
          <v-btn color="primary" flat="flat" @click="eliminarDe(detalle)">Si</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-divider></v-divider>
    <v-card>
        <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
          <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
        </v-toolbar>
        <v-container grid-list-md style="max-width: inherit">
          <v-layout row wrap>
            <v-flex xs12 sm12 md12 lg12>
              <v-form data-vv-scope="formPrincipal">
                <v-container fluid grid-list-md grid-list-xl>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md4>
                      <v-text-field v-model="conRtf.codigo"
                                    label="Código" key="codigo"
                                    name="codigo" required v-validate="'required|max:5|not_in:' + codigosUsados"
                                    :error-messages="errors.collect('codigo')" :disabled="conRtf.id ? true : false">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm12 md4>
                      <v-text-field v-model="conRtf.nombre"
                                    label="Nombre" key="nombre"
                                    name="nombre" required v-validate="'required'"
                                    :error-messages="errors.collect('nombre')">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm12 md4>
                      <v-select
                        :items="manejos"
                        v-model="conRtf.manejo"
                        label="Manejo"
                        name="manejo"
                        :error-messages="errors.collect('manejo')"
                        required v-validate="'required'"
                      ></v-select>
                    </v-flex>
                    <v-slide-x-transition>
                      <v-flex xs12 sm12 md4  v-if="conRtf.manejo === 'Base' || conRtf.manejo === 'Variable'" >
                        <v-text-field prefix="$" type="number" :value="0.00" v-model.number="conRtf.base_minima" key="base mínima"
                                      name="base mínima" required v-validate="'required'"
                                      :error-messages="errors.collect('base mínima')" label="Base Mínima"></v-text-field>
                      </v-flex>
                    </v-slide-x-transition>
                    <v-slide-x-transition>
                      <v-flex xs12 sm12 md4 transition="slide-y-transition" v-if="conRtf.manejo === 'Base'">
                        <v-text-field prefix="%" type="number" v-model.number="conRtf.porcentaje" key="porcentaje"
                                      name="porcentaje" required v-validate="'required'"
                                      :error-messages="errors.collect('porcentaje')" label="Porcentaje"></v-text-field>
                      </v-flex>
                    </v-slide-x-transition>
                  </v-layout>
                </v-container>
              </v-form>
            </v-flex>
            <v-flex xs12 sm12 md12 v-if="conRtf.manejo === 'Rangos'">
              <template>
                <v-card>
                  <v-card-title class="grey lighten-3">
                    <h3>Agregar Rangos</h3>
                  </v-card-title>
                  <v-divider></v-divider>
                  <v-form data-vv-scope="formRango">
                    <v-container fluid grid-list-lg>
                      <v-layout row wrap>
                        <v-flex xs12 sm4 md2 lg2>
                          <v-text-field :step="0.1" ref="vIncial" type="number" v-model.number="detalle.valor_inicial" key="valor inicial"
                                        name="valor inicial" required v-validate="'required'"
                                        :error-messages="valor_value != '' ? valor_value : errors.collect('valor inicial')" label="Valor Inicial" @input="validaMensaje" :min="1" :max="detalle.valor_final"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm4 md2 lg2>
                          <v-text-field type="number" v-model.number="detalle.valor_final" key="valor final"
                                        name="valor final" required v-validate="'required'"
                                        :error-messages="valor_value2 != '' ? valor_value2 : errors.collect('valor final')" label="Valor Final" @input="validaMensaje" :min="detalle.valor_inicial"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm4 md2 lg2>
                          <v-text-field suffix="%" type="number" v-model.number="detalle.porcentaje" key="porcentaje"
                                        name="porcentaje" required v-validate="'required'"
                                        :error-messages="errors.collect('porcentaje')" label="Porcentaje" min="0"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm4 md2 lg2>
                          <v-text-field suffix="-" type="number" v-model.number="detalle.deducido" key="deducido"
                                        name="deducido" required v-validate="'required'"
                                        :error-messages="errors.collect('deducido')" label="Deducido" min="0"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm4 md2 lg2>
                          <v-text-field suffix="+" type="number" v-model.number="detalle.incremento" key="incremento"
                                        name="incremento" required v-validate="'required'"
                                        :error-messages="errors.collect('incremento')" label="Incremento" min="0"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm4 md2 lg2 class="text-sm-center pt-2">
                          <div sm10>
                            <v-btn color="primary" @click.prevent="agregarDetalle(detalle)"><v-icon>add</v-icon> Agregar</v-btn>
                          </div>
                        </v-flex>
                      </v-layout>
                    </v-container>
                  </v-form>
                  <v-alert class="py-1" v-if="valorEnd !== ''" v-model="valorEnd" color="error" type="error" @input="validaMensaje">
                    {{ validarDetalles(detalle).mensaje_error_rango }}
                  </v-alert>
                  <v-divider></v-divider>
                  <v-data-table v-if="conRtf.detalles.length > 0"
                                :headers="headers"
                                :items="conRtf.detalles"
                                :loading="tableLoading"
                                :pagination.sync="pagination"
                                hide-actions rows-per-page-text="Registros por página"
                                :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                    <template slot="items" slot-scope="props">
                      <td>{{ props.item.valor_inicial }}</td>
                      <td>{{ props.item.valor_final }}</td>
                      <td>{{ props.item.porcentaje }}</td>
                      <td>{{ props.item.deducido }}</td>
                      <td>{{ props.item.incremento }}</td>
                      <td class="text-xs-center">
                        <v-btn icon class="mx-0" fab small @click="preguntarEliminarDetalle(props.item)"> <v-icon color="accent">delete</v-icon> </v-btn>
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
            <v-flex xs12 sm12 md12>
              <v-switch  color="success" :label="conRtf.estado"
                         v-model="conRtf.estado"  true-value="Activo" false-value="Inactivo"></v-switch>
            </v-flex>
          </v-layout>
        </v-container>
        <v-divider></v-divider>
        <v-card-actions class="grey lighten-4">
          <v-layout row wrap>
            <v-flex xs6 class="text-xs-left">
              <v-btn @click="refresh(null)">Limpiar</v-btn>
            </v-flex>
            <v-flex xs6 class="text-xs-right">
              <v-btn @click="close()">Cancelar</v-btn>
              <v-btn color="primary" @click.prevent="save">Guardar</v-btn>
            </v-flex>
          </v-layout>
        </v-card-actions>
      </v-card>
  </div>
</template>

<script>
    import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
    import {CONRTF_RELOAD_ITEM} from '../../../../store/modules/general/tables'
    import {SNACK_SHOW} from '@/store/modules/general/snackbar'
    export default {
      name: 'RegistroRtf',
      props: ['parametros'],
      data () {
        return {
          conRtf: {},
          conRtfEdicion: {
            id: null,
            codigo: null,
            nombre: null,
            manejo: null,
            base_minima: null,
            porcentaje: null,
            estado: 'Activo',
            detalles: []
          },
          valor_value: '',
          valor_value2: '',
          valorEnd: '',
          manejos: ['Base', 'Rangos', 'Variable'],
          detalle: {},
          tableLoading: false,
          destroyFila: false,
          codigosUsados: null,
          obtenerCodigos: [],
          pagination: {},
          headers: [
            {
              text: 'Valor Inicial',
              align: 'left',
              sortable: false,
              value: 'valor_inicial'
            },
            {
              text: 'Valor Final',
              align: 'left',
              sortable: false,
              value: 'valor_final'
            },
            {
              text: 'Procentaje',
              align: 'left',
              sortable: false,
              value: 'procentaje'
            },
            {
              text: 'Deducido',
              align: 'left',
              sortable: false,
              value: 'deducido'
            },
            {
              text: 'Incremento',
              align: 'left',
              sortable: false,
              value: 'incremento'
            },
            {
              text: '',
              align: 'center',
              sortable: false,
              value: 'id'
            }
          ],
          detallesValidos: []
        }
      },
      computed: {
        tapTitulo () {
          return !this.conRtf.id ? 'Nuevo Concepto de RTF' : 'Edición del Concepto RTF: ' + this.conRtf.nombre
        },
        pages () {
          if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
          return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
        }
      },
      beforeMount () {
        this.getCodigo()
        this.refresh()
        this.formReset()
        this.validarDetalles()
      },
      mounted () {
        const dict = {
          custom: {
            codigo: {
              not_in: 'Este código ya fue usado'
            }
          }
        }
        this.$validator.localize('es', dict)
        if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
      },
      methods: {
        getRegisto (id) {
          this.axios.get('conrtfs/' + id)
            .then((response) => {
              console.log(response)
              if (response.data !== '') {
                this.conRtf = response.data.data
              }
            })
            .catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el concepto RTF. ' + e.response, color: 'danger'})
            })
        },
        getCodigo () {
          // console.log('this.parametros')
          this.axios.get('conrtfs').then((response) => {
            this.obtenerCodigos = response.data.data
            this.codigosUsados = this.getCodigosUsados(this.obtenerCodigos)
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })
        },
        getCodigosUsados (item) {
          let codigos = this.obtenerCodigos.map((val) => {
            return val.codigo
          })
          return codigos
        },
        formReset () {
          this.detalle = {
            valor_inicial: '',
            valor_final: '',
            porcentaje: '',
            deducido: '',
            incremento: ''
          }
        },
        close () {
          this.formReset()
          this.$validator.reset()
          this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
        },
        refresh () {
          this.$validator.reset()
          this.conRtf = JSON.parse(JSON.stringify(this.conRtfEdicion))
        },
        async agregarDetalle (detalle) {
          // this.$validator.validateAll()
          if (await this.validador('formRango') && this.validarDetalles(detalle).libre && this.detalle.valor_inicial !== '' && this.detalle.valor_final !== '' &&
            this.detalle.porcentaje !== '' && this.detalle.deducido !== '' && this.detalle.incremento !== '') {
            this.conRtf.detalles.push(detalle)
            this.$validator.reset()
            this.ordenarDetalles()
            this.formReset()
            // this.$refs.vIncial.focus()
          } else {
            if (this.detalle.valor_inicial === '' && this.detalle.valor_final === '' &&
              this.detalle.porcentaje === '' && this.detalle.deducido === '' && this.detalle.incremento === '') {
              this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacíos. ', color: 'danger'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al registrar el rango, rectifica la información. ', color: 'danger'})
            }
          }
        },
        validaMensaje () {
          let mensinicial = this.validarDetalles(this.detalle).mensaje_inicial
          let mensfinal = this.validarDetalles(this.detalle).mensaje_final
          if (mensinicial && this.detalle.valor_inicial !== '') {
            this.valor_value = mensinicial
          } else {
            this.$validator.reset()
            this.valor_value = ''
          }
          if (mensfinal && this.detalle.valor_final !== '') {
            this.valor_value2 = mensfinal
          } else {
            this.$validator.reset()
            this.valor_value2 = ''
          }
          if (this.detalle.valor_inicial !== '' && this.detalle.valor_final !== '') {
            this.valorEnd = this.validarDetalles(this.detalle).mensaje_error_rango
          } else {
            this.$validator.reset()
            this.valorEnd = ''
          }
        },
        validarDetalles (detalleVerificar) {
          var respuesta = {
            libre: true,
            mensaje_inicial: '',
            mensaje_final: '',
            mensaje_error_rango: ''
          }
          if (this.conRtf.detalles.length) {
            if (detalleVerificar.valor_final <= detalleVerificar.valor_inicial) {
              respuesta.mensaje_error_rango = 'El valor final debe ser mayor al valor inicial.'
              respuesta.libre = false
            } else if (detalleVerificar.valor_inicial >= detalleVerificar.valor_final) {
              respuesta.mensaje_error_rango = 'El valor inicial debe ser menor al valor inicial.'
              respuesta.libre = false
            } else {
              this.conRtf.detalles.forEach((detalle) => {
                let vInicial = ((detalleVerificar.valor_inicial >= detalle.valor_inicial) && (detalleVerificar.valor_inicial <= detalle.valor_final))
                let vFinal = ((detalleVerificar.valor_final >= detalle.valor_inicial) && (detalleVerificar.valor_final <= detalle.valor_final))
                let val = vInicial || vFinal
                if (val) {
                  respuesta.libre = false
                  respuesta.mensaje_inicial = vInicial ? 'El valor inicial: ' + detalleVerificar.valor_inicial + ' esta entre el rango: ' + detalle.valor_inicial + ' - ' + detalle.valor_final + '.' : ''
                  respuesta.mensaje_final = vFinal ? 'El valor final: ' + detalleVerificar.valor_final + ' esta entre el rango: ' + detalle.valor_inicial + ' - ' + detalle.valor_final + '.' : ''
                  respuesta.mensaje_error_rango = val ? 'Se presenta conflicto con otro(s) rango(s).' : ''
                } else {
                  let vIniInverse = (detalle.valor_inicial >= detalleVerificar.valor_inicial && detalle.valor_inicial <= detalleVerificar.valor_final)
                  let vFinInverse = (detalle.valor_final <= detalleVerificar.valor_final && detalle.valor_final >= detalleVerificar.valor_inicial)
                  let varInFin = vIniInverse || vFinInverse
                  if (varInFin) {
                    respuesta.libre = false
                    respuesta.mensaje_inicial = vIniInverse ? 'El valor inicial: ' + detalle.valor_inicial + ' esta entre el rango: ' + detalleVerificar.valor_inicial + ' - ' + detalleVerificar.valor_final + '.' : ''
                    respuesta.mensaje_final = vFinInverse ? 'El valor final: ' + detalle.valor_final + ' esta entre el rango: ' + detalleVerificar.valor_inicial + ' - ' + detalleVerificar.valor_final + '.' : ''
                    respuesta.mensaje_error_rango = varInFin ? 'Se presenta conflicto con otro(s) rango(s).' : ''
                  }
                }
              })
            }
          }
          return respuesta
        },
        restoreDetalle () {
          this.destroyFila = false
          this.formReset()
          this.$validator.reset()
        },
        preguntarEliminarDetalle (item) {
          if (item === '') {
            this.detalle = ''
          } else {
            this.detalle = item
          }
          this.destroyFila = true
        },
        eliminarDe (detalle) {
          // this.destroyFila = false
          if (detalle.id) {
            this.axios.delete('conrtfs/detalles/' + detalle.id).then(response => {
              this.restoreDetalle()
              this.conRtf.detalles.splice(this.conRtf.detalles.indexOf(detalle), 1)
              this.$store.commit(SNACK_SHOW, {msg: 'Rango eliminado correctamente. ', color: 'success'})
            })
          } else {
            this.restoreDetalle()
            this.conRtf.detalles.splice(this.conRtf.detalles.indexOf(detalle), 1)
          }
        },
        ordenarDetalles () {
          this.conRtf.detalles.sort(function (a, b) {
            return (a.valor_inicial - b.valor_final)
          })
        },
        validador (scope) {
          return this.$validator.validateAll(scope).then(result => { return result })
        },
        async save () {
          // this.$validator.validateAll()
          if (await this.validador('formPrincipal')) {
            this.submit()
          } else if (await this.validador('formPrincipal') && this.conRtf.manejo === 'Rangos' && this.conRtf.detalles.length > 0) {
            this.submit()
          } else {
            if (this.conRtf.manejo === 'Rangos' && this.conRtf.detalles.length < 1) {
              // this.$validator.reset()
              this.$store.commit(SNACK_SHOW, {msg: 'Se debe agregar al menos un rango.', color: 'danger'})
            } else if (this.conRtf.manejo == null || this.conRtf.codigo == null || this.conRtf.nombre == null) {
              this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Se presenta un error al guardar el registro.', color: 'danger'})
            }
          }
        },
        submit () {
          if (this.conRtf.manejo !== 'Rangos') {
            this.conRtf.detalles = []
          } else if (this.conRtf.manejo !== 'Base') {
            this.conRtf.porcentaje = null
          } else if (this.conRtf.manejo === 'Rangos') {
            this.conRtf.base_minima = null
            this.conRtf.porcentaje = null
          }
          const typeRequest = this.conRtf.id ? 'editar' : 'crear'
          this.axios.post('conrtfs', this.conRtf).then(response => {
            if (this.conRtf.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'El concepto RTF se actualizó correctamente', color: 'success'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'El concepto RTF se creó correctamente', color: 'success'})
            }
            // this.$store.commit(SNACK_SHOW, {msg: 'Efectivo :)', color: 'success'})
            this.$store.commit(CONRTF_RELOAD_ITEM, {conRtf: response.data.data, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        }
      }
    }
</script>

<style scoped>

</style>
