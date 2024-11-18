<template>
  <v-card>
    <toolbar-list
      :title="titulo"
      subtitle="Registro y gestión"
    ></toolbar-list>
    <v-form data-vv-scope="formNominales">
      <v-container fluid grid-list-sm>
        <v-layout row wrap>
          <v-flex xs12 sm6 md4>
            <input-date
              v-model="nominal.fecha"
              label="Fecha (Año-Mes-Día)"
              format="YYYY-MM-DD"
              :min="minDate"
              :max="maxDate"
              name="fecha"
              v-validate="'required|fechaValida:YYYY-MM-DD'"
              :error-messages="errors.collect('fecha')"
            />
          </v-flex>
          <v-flex xs12 sm6 md4>
            <input-date
              v-model="nominal.fecha_inicio_prima"
              label="Fecha Inicio (Año-Mes-Día)"
              format="YYYY-MM-DD"
              :min="minDate"
              :max="nominal.fecha_fin_prima"
              name="fecha inicio"
              v-validate="'required|fechaValida:YYYY-MM-DD'"
              :error-messages="errors.collect('fecha inicio')"
            />
          </v-flex>
          <v-flex xs12 sm6 md4>
            <input-date
              v-model="nominal.fecha_fin_prima"
              label="Fecha Fin (Año-Mes-Día)"
              format="YYYY-MM-DD"
              :min="nominal.fecha_inicio_prima"
              name="fecha fin"
              v-validate="'fechaValida:YYYY-MM-DD'"
              :error-messages="errors.collect('fecha fin')"
            />
          </v-flex>
          <v-flex xs12 sm6 md6>
            <v-text-field v-model="nominal.descripcion"
                          label="Descripción"
                          key="descripción"
                          name="descripción"
                          v-validate="'required|max:200'"
                          required
                          :error-messages="errors.collect('descripción')"></v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md2>
            <v-text-field v-model="nominal.ano"
                          label="Año"
                          key="año"
                          name="año"
                          v-validate="'required'"
                          required
                          :error-messages="errors.collect('año')"></v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md2>
            <v-select
              :items="complementosNominales.cbomeses"
              v-model="nominal.mes"
              item-value="id"
              item-text="nombre"
              name="mes"
              label="Mes"
              :error-messages="errors.collect('mes')"
              v-validate="'required'"
              clearable
            ></v-select>
          </v-flex>
          <v-flex xs12 sm6 md2>
            <v-select
              :items="complementosNominales.periodosnominales"
              v-model="nominal.periodo"
              item-value="id"
              item-text="nombre"
              name="periodo"
              label="Periodo"
              :error-messages="errors.collect('periodo')"
              v-validate="'required'"
              clearable
            ></v-select>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-select
              :items="complementosNominales.cencostos"
              v-model="nominal.centro_costo"
              item-value="centro_costo"
              item-text="descripcion"
              name="centro costo"
              label="Centro Costo"
              :error-messages="errors.collect('centro costo')"
              v-validate="'required'"
              clearable
            ></v-select>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-select
              :disabled="!nominal.centro_costo"
              :items="complementosNominales.areas.filter(x => x.centro_costo === nominal.centro_costo)"
              v-model="nominal.area"
              item-value="area"
              item-text="descripcion"
              name="área"
              label="Área"
              :error-messages="errors.collect('área')"
              v-validate="'required'"
              clearable
            ></v-select>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-select
              :disabled="!nominal.area"
              :items="complementosNominales.dependencias.filter(x => x.area === nominal.area)"
              v-model="nominal.dependencia"
              item-value="dependencia"
              item-text="descripcion"
              name="dependencia"
              label="Dependencia"
              :error-messages="errors.collect('dependencia')"
              v-validate="'required'"
              clearable
            ></v-select>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-select
              :items="complementosNominales.estadosnominales"
              v-model="nominal.estado"
              item-value="id"
              item-text="nombre"
              name="estado"
              label="Estado"
              :error-messages="errors.collect('estado')"
              v-validate="'required'"
              clearable
            ></v-select>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-switch
              class="mr-4"
              color="success"
              label="Liquida Prima"
              v-model="nominal.sw_prima"
            ></v-switch>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-switch
              class="mr-4"
              color="success"
              label="Liquida Interés Cesantías"
              v-model="nominal.sw_interes_cesantia"
            ></v-switch>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-select
              :items="tiposnominas"
              v-model="nominal.tipo_nomina"
              item-value="id"
              item-text="nombre"
              name="tipo nómina"
              label="Tipo Nómina"
              :error-messages="errors.collect('tipo nómina')"
              v-validate="'required'"
              clearable
            ></v-select>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-select
              :items="complementosNominales.operadoresliquidacion"
              v-model="nominal.liquidacion_operador"
              item-value="id"
              item-text="nombre"
              name="operador liquidación"
              label="Operador Liquidación"
              :error-messages="errors.collect('operador liquidación')"
              v-validate="'required'"
              clearable
            ></v-select>
          </v-flex>
          <v-flex xs12 sm12 md12>
            <v-textarea
              label="Observaciones"
              v-model="nominal.observacion"
              name="observaciones"
              v-validate="'required'"
              :error-messages="errors.collect('observaciones')"
              rows="1"
            ></v-textarea>
          </v-flex>
        </v-layout>
      </v-container>
    </v-form>
    <v-card-actions class="grey lighten-3">
      <v-spacer></v-spacer>
      <v-btn flat @click="cancelar">Cancelar</v-btn>
      <v-btn color="primary" flat @click.prevent="save">Guardar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'RegistroNominalLiquidacion',
    props: ['parametros'],
    data: () => ({
      nominal: null,
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      makeData: {
        descripcion: null,
        observacion: null,
        ano: null,
        mes: null,
        periodo: null,
        centro_costo: null,
        area: null,
        dependencia: null,
        fecha: null,
        estado: null,
        empresa: 0,
        usuario: null,
        liquidacion_operador: null,
        sw_prima: 0,
        sw_interes_cesantia: 0,
        consecutivo_saldo: 0,
        documento_nota: null,
        cuenta_nomina: null,
        valor_nomina: 0,
        tipo_nomina: null,
        fecha_inicio_prima: null,
        fecha_fin_prima: null
      }
    }),
    created () {
      this.nominal = this.clone(this.makeData)
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad)
    },
    computed: {
      complementosNominales () {
        return store.getters.complementosNominales
      },
      titulo () {
        return `${!this.nominal.liquidacion_nomina ? 'Nueva ' : 'En Edición de la '} Nómina ${!this.nominal.liquidacion_nomina ? '' : '#' + this.nominal.liquidacion_nomina}`
      },
      tiposnominas () {
        return [
          {
            id: 1,
            nombre: 'Normal'
          }
        ]
      }
    },
    methods: {
      getRegistro (entidad) {
        this.axios.get(`talentohumano/nominaliquidaciones/${entidad.liquidacion_nomina}`)
          .then(response => {
            this.nominal = response.data.data
            // this.$store.commit('SNACK_SHOW', {msg: `Se registro el extra labora éxitosamente`, color: 'success'})
            // this.$store.commit('reloadTable', 'tableScNominalLiquidacion')
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' Error al cargar el registro nominal. ', error: e})
          })
      },
      cancelar () {
        this.$validator.reset()
        this.nominal = this.clone(this.makeData)
        this.$store.commit('reloadTable', 'tableScNominalLiquidacion')
      },
      save () {
        this.$validator.validateAll().then(result => {
          if (result) {
            let typeRequest = !this.nominal.liquidacion_nomina ? this.axios.post(`talentohumano/nominaliquidaciones`, this.nominal) : this.axios.put(`talentohumano/nominaliquidaciones/${this.nominal.liquidacion_nomina}`, this.nominal)
            typeRequest.then(() => {
              // this.$store.commit('reloadTable', 'tableScNominalLiquidacion')
              // this.nominal = this.clone(response.data.data)
              this.$store.commit('SNACK_SHOW', {
                msg: `Se ${!this.nominal.liquidacion_nomina ? 'registro' : 'actualizo'}  la nómina éxitosamente`,
                color: 'success'
              })
              this.cancelar()
            }).catch(e => {
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' Error al guardar el registro. ', error: e})
            })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
