<template>
  <v-card-text>
    <v-layout row wrap>
      <v-flex xs12 sm6 md4>
        <v-select
          label="Tipo"
          v-model="value.ce_tipocontrato_id"
          :items="value && value.proceso_contractual && (value.proceso_contractual.servicios_salud !== null) && (typeof value.proceso_contractual.servicios_salud !== 'undefined') && complementos && complementos.tiposContrato ? complementos.tiposContrato.filter(x => x.servicios_salud === value.proceso_contractual.servicios_salud) : []"
          item-value="id"
          item-text="nombre"
          name="Tipo"
          v-validate="'required'"
          :error-messages="errors.collect('Tipo')"
          :readonly="!!value.actaInicio"
        >
          <template slot="no-data">
            <div class="px-3">
              <v-icon color="danger">warning</v-icon> El contrato no tiene proceso contractual
            </div>
          </template>
        </v-select>
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-text-field
          label="Número"
          v-model="value.numero_contrato"
          name="Número"
          v-validate="'required'"
          :error-messages="errors.collect('Número')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex xs12 sm6 md4>
        <input-date
          v-model="value.fecha_contrato"
          label="Fecha (Año-Mes-Día)"
          format="YYYY-MM-DD"
          name="Fecha"
          :min="minDate"
          :max="maxDate"
          v-validate="'required|fechaValida|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
          :error-messages="errors.collect('Fecha')"
          :readonly="!!value.actaInicio"
        ></input-date>
      </v-flex>
      <v-flex xs12 sm12 md12>
        <v-card class="pa-0">
          <v-form data-vv-scope="formCDP" v-if="value.estado === 'Registrado'">
            <v-card-text class="pb-0 pt-1">
              <v-layout row wrap>
                <v-flex xs12 sm8 md8>
                  <postulador-v2
                    no-data="Busqueda por consecutivo, dependencia o valor."
                    hint="objecto"
                    item-text="consecutivo"
                    data-title="dependencia.nombre"
                    data-subtitle="objecto"
                    route-params="estado_valor=Disponible,Comprometido Parcialmente"
                    label="CDP"
                    entidad="pr_cdps"
                    preicon="attach_money"
                    @changeid="val => itemCDP.pr_cdp_id = val"
                    v-model="itemCDP.cdp"
                    name="CDP"
                    scope="formCDP"
                    :error-messages="errors.collect('CDP')"
                    no-btn-create
                    no-btn-edit
                    :slot-append='{
                      template:`<span class="pt-2 caption">Disponible: {{currency(value.valor_cdp - value.suma_valores_ejecutados)}}</span>`,
                      props: [`value`]
                     }'
                    :slot-selection='{
                      template:`
                      <span>{{value.consecutivo}} - {{value.dependencia.nombre}}</span>
                      `,
                      props: [`value`]
                     }'
                    :slot-data='{
                      template:`
                      <v-list-tile>
                        <v-list-tile-content>
                          <v-list-tile-title>
                            <span>{{value.consecutivo}} - {{value.dependencia.nombre}}</span>
                            <v-chip
                              class="mt-0 mb-2"
                              color="indigo lighten-2"
                              label
                              small
                              >
                              Disponible: {{currency(value.valor_cdp - value.suma_valores_ejecutados)}}
                            </v-chip>
                          </v-list-tile-title>
                          <v-list-tile-sub-title class="caption">{{ value.objecto }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                      `,
                      props: [`value`]
                     }'
                    :readonly="!!value.actaInicio"
                  />
                </v-flex>
                <v-flex xs12 sm4 md4>
                  <input-number
                    label="Valor a Descontar"
                    prepend-icon="attach_money"
                    :precision="0"
                    v-model.number="itemCDP.valor_descontado"
                    name="valor a descontar"
                    v-validate="'required|min_value:0' + (value.valor === 0 ? '' : '|max_value:' + value.valor)"
                    :error-messages="errors.collect('valor a descontar')"
                    :readonly="!!value.actaInicio"
                    :disabled="!itemCDP.cdp"
                  />
                  <!-- :hint="value.prcdps.length > 0 ? `Disponible: ${currency(valorDisponibleCDP - value.valor)}` : 'Se requiere seleccionar un CDP'"-->
                </v-flex>
                <v-flex xs12 sm12 md12 class="text-xs-right pa-1 grey lighten-3">
                  <v-tooltip top>
                    <v-btn small
                           slot="activator"
                           dark color="pink"
                           @click="add(itemCDP)">
                      <span v-text="'Agregar'"></span>
                    </v-btn>
                    <span v-text="'Agregar CDP'"></span>
                  </v-tooltip>
                </v-flex>
              </v-layout>
            </v-card-text>
          </v-form>
          <v-slide-y-transition group>
            <v-data-table
              v-if="value.prcdps.length"
              :items="value.prcdps"
              class="elevation-1"
              :headers="headersCdps"
              hide-actions
              :key="'table'">
              <template slot="items" slot-scope="props">
                <tr :key="'tag-' + (!props.item.id ? (props.item.id = props.index) : props.index)">
                  <td class="text-xs-justify truncate">{{ props.item.cdp ? `${props.item.cdp.consecutivo} - ${props.item.cdp.objecto}` : null }}</td>
                  <td class="text-xs-right">{{ currency(props.item.valor_descontado) }}</td>
                  <td class="text-xs-right">
                    <v-list-tile>
                      <v-list-tile-content>
                        <v-list-tile-sub-title class="caption">Anterior: {{props.item.cdp ? currency((value.estado !== 'Registrado' ? props.item.valor_cdp_anterior : (props.item.cdp.valor_cdp - props.item.cdp.suma_valores_ejecutados))) : null }}</v-list-tile-sub-title>
                        <v-list-tile-sub-title class="caption" v-if="props.item.valor_descontado > 0">Actual: {{(props.item.cdp) ? currency((value.estado !== 'Registrado' ? props.item.valor_cdp_anterior : (props.item.cdp.valor_cdp - props.item.cdp.suma_valores_ejecutados)) - props.item.valor_descontado) : 0 }}</v-list-tile-sub-title>
                      </v-list-tile-content>
                    </v-list-tile>
                  </td>
                  <td class="text-xs-center">
                    <v-btn icon class="mx-0" :disabled="value.estado !== 'Registrado'" fab small @click="eliminarCDP(props.index, props.item)"><v-icon color="accent">delete</v-icon></v-btn>
                  </td>
                </tr>
              </template>
              <template slot="no-data" class="error">
                <div class="pa-1 pl-2 text--white text-xs-center">
                  Lo sentimos, no existen registros. <v-icon>sentiment_very_dissatisfied</v-icon>
                </div>
              </template>
            </v-data-table>

          </v-slide-y-transition>
        </v-card>
      </v-flex>
      <v-flex xs12 sm12 md12>
        <postulador-v2
          key="postuladorEntidad"
          v-if="value.proceso_contractual && value.proceso_contractual.servicios_salud"
          no-data="Busqueda por NIT, razon social u código de habilitación."
          hint="tercero.identificacion_completa"
          item-text="nombre"
          data-title="tercero.identificacion_completa"
          data-subtitle="nombre"
          filter="nombre,tercero.identificacion_completa,tercero.nombre_completo,cod_habilitacion"
          :detail="DetalleEntidad"
          label="Entidad"
          entidad="entidades"
          preicon="location_city"
          @changeid="val => value.rs_entidad_id = val"
          v-model="value.entidad"
          name="Entidad"
          rules="required"
          v-validate="'required'"
          :error-messages="errors.collect('Entidad')"
          no-btn-create
          no-btn-edit
          :min-characters-search="3"
          :readonly="!!value.actaInicio"
        />
        <postulador-v2
          v-else
          key="postuladorTercero"
          no-data="Busqueda por nombre o número de documento."
          hint="identificacion_completa"
          item-text="nombre_completo"
          data-title="identificacion_completa"
          data-subtitle="nombre_completo"
          label="Tercero"
          :detail="detalleTercero"
          entidad="terceros"
          @changeid="val => value.gn_tercero_id = val"
          v-model="value.tercero"
          name="Tercero"
          preicon="person"
          :rules="'required'"
          v-validate="'required'"
          :error-messages="errors.collect('Tercero')"
          :readonly="!!value.actaInicio"
          no-btn-create
          no-btn-edit
        ></postulador-v2>
      </v-flex>
      <v-flex xs12 sm6 md4>
        <input-number
          label="Valor"
          prepend-icon="attach_money"
          :precision="0"
          v-model.number="value.valor"
          name="Valor"
          v-validate="'required|min_value:0|max_value:' + valorDisponibleCDP"
          :error-messages="errors.collect('Valor')"
          :readonly="!!value.actaInicio"
          :disabled="!value.prcdps.length"
        />
        <!--:hint="value.prcdps.length > 0 ? `Total disponible: ${currency(valorDisponibleCDP - value.valor)}` : 'Se requiere seleccionar un CDP'"-->
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-text-field
          label="Plazo meses"
          min="0"
          type="number"
          name="Plazo meses"
          v-validate="'numeric|required|min_value:0'"
          v-model.number="value.plazo_meses"
          :error-messages="errors.collect('Plazo meses')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-text-field
          label="Plazo días"
          min="0"
          type="number"
          name="Plazo días"
          v-validate="'numeric|required|min_value:0'"
          v-model.number="value.plazo_dias"
          :error-messages="errors.collect('Plazo días')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
      <v-flex xs12 sm12 md12>
        <v-textarea
          v-model="value.objeto"
          auto-grow
          label="Objeto"
          rows="1"
          name="Objeto"
          v-validate="'required'"
          :error-messages="errors.collect('Objeto')"
          :readonly="!!value.actaInicio"
        />
      </v-flex>
    </v-layout>
  </v-card-text>
</template>

<script>
  import InputDate from '@/components/general/InputDate'
  import InputNumber from '@/components/general/InputNumber'
  import store from '@/store/complementos/index'
  export default {
    name: 'RegistroContratoDatosBasicos',
    props: ['value', 'original'],
    components: {
      InputDate,
      InputNumber,
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    data () {
      return {
        detalleTercero: () => import('@/components/administrativo/niif/terceros/DetalleTercero'),
        DetalleEntidad: () => import('@/components/misional/redservicios/entidades/DetalleEntidad'),
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10),
        okPoblacionObjetivo: true,
        itemCDP: null,
        cdps: [],
        headersCdps: [
          {
            text: 'CDP',
            align: 'left',
            sortable: false,
            value: 'objecto'
          },
          {
            text: 'Valor Descontado',
            align: 'left',
            sortable: false,
            value: 'valor_descontado'
          },
          {
            text: 'Disponible',
            align: 'left',
            sortable: false,
            value: 'valor_cdp'
          },
          {
            text: 'Eliminar',
            align: 'center',
            sortable: false,
            value: ''
          }
        ],
        totalDetalles: {
          totalDebito: 0,
          totalCredito: 0,
          sumasIguales: 0,
          clase: null
        }
      }
    },
    created () {
      this.formReset()
    },
    computed: {
      valorDisponibleCDP () {
        let suma = 0
        if (this.value && this.value.prcdps.length) {
          this.value.prcdps.forEach(x => {
            let valor = x.cdp ? (x.cdp.valor_cdp - x.cdp.suma_valores_ejecutados) : 0
            suma += valor
          })
        }
        // let valor = (this.value && this.value.cdp) ? (this.value.cdp.valor_cdp - this.value.cdp.suma_valores_ejecutados) : 0
        return suma < 0 ? 0 : suma
      },
      complementos () {
        return store.getters.complementosContratoFormDatosBasica
      }
    },
    methods: {
      validate () {
        return this.$validator.validateAll()
      },
      reset () {
        return this.$validator.reset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formReset () {
        this.itemCDP = {
          id: null,
          ce_proconminuta_id: null,
          pr_cdp_id: null,
          valor_descontado: 0,
          cdp: null,
          index: null
        }
      },
      add (cdp) {
        let item = this.clone(cdp)
        let value = this.value.prcdps.filter(x => x.cdp && (x.cdp.id === item.cdp.id)).length
        let suma = 0
        if (item.cdp) suma = (item.cdp.valor_cdp - item.cdp.suma_valores_ejecutados)
        let sumValorDescontado = 0
        this.value.prcdps.map(z => (sumValorDescontado += z.valor_descontado))
        console.log('ass', item, value, suma, sumValorDescontado)
        if ((item.cdp && item.pr_cdp_id && (item.valor_descontado > 0)) && (value === 0) && (suma !== 0) && (sumValorDescontado >= 0 || sumValorDescontado < this.value.valor)) {
          this.$emit('addCdp', cdp)
          this.formReset()
        } else {
          if (value === 1) {
            this.$store.commit('SNACK_SHOW', {msg: 'Ya existe registrado el CDP.', color: 'danger'})
          }
          if (suma === 0) {
            this.$store.commit('SNACK_SHOW', {msg: 'El CDP no cuenta con disponibilidad $.', color: 'danger'})
          }
          if ((sumValorDescontado < 0 || sumValorDescontado >= this.value.valor)) {
            this.$store.commit('SNACK_SHOW', {msg: 'El valor debe ser mayor a cero y no puede ser mayor al valor contratado.', color: 'danger'})
          }
          if ((!item.cdp && !item.pr_cdp_id) || (item.valor_descontado === 0)) {
            this.$store.commit('SNACK_SHOW', {msg: 'Existen campos vacios para registro del CDP.', color: 'danger'})
          }
        }
      },
      eliminarCDP (index, item) {
        this.$emit('deleteCDP', item)
      }
    }
  }
</script>

<style scoped>
</style>
