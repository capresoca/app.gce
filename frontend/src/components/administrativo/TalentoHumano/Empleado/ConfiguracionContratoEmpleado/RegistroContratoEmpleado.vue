<template>
  <v-dialog v-model="show" persistent max-width="900">
    <v-card>
      <toolbar-list
        info
        :title="ModalTitulo"
        subtitle="Registro y gestión"
      ></toolbar-list>
      <v-form ref="formContratos">
        <v-container fluid grid-list-md>
          <v-layout row wrap>
            <v-flex xs12 sm6 md4 lg4>
              <input-date
                v-model="data.fecha_inicio"
                label="Fecha Inicio (Año-Mes-Día)"
                format="YYYY-MM-DD"
                :min="minDate"
                :max="maxDate"
                name="fecha inicio"
                v-validate="'required|fechaValida:YYYY-MM-DD'"
                :error-messages="errors.collect('fecha inicio')"
              />
            </v-flex>
            <v-flex xs12 sm6 md4 lg4>
              <input-date
                :disabled="!data.fecha_inicio"
                v-model="data.fecha_final"
                label="Fecha Fin (Año-Mes-Día)"
                format="YYYY-MM-DD"
                :min="data.fecha_inicio"
                name="fecha fin"
                v-validate="'fechaValida:YYYY-MM-DD'"
                :error-messages="errors.collect('fecha fin')"
              />
            </v-flex>
            <v-flex xs12 sm6 md4 lg4>
              <v-select
                :items="estados"
                v-model="data.estado"
                item-value="id"
                item-text="nombre"
                name="estado"
                label="Estado"
                :error-messages="errors.collect('estado')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md4 lg4>
              <v-select
                :items="complementos ? complementos.cargos : []"
                v-model="data.cargo"
                item-value="cargo"
                item-text="descripcion"
                name="cargo"
                label="Cargo"
                :error-messages="errors.collect('cargo')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md3 lg3>
              <v-switch
                class="mr-4"
                color="success"
                label="Salario Integral"
                v-model="data.sw_salario_integral"
              ></v-switch>
            </v-flex>
            <v-flex xs12 sm6 md5 lg5>
              <v-select
                :items="complementos ? complementos.configuracionsalarial : []"
                v-model="data.configuracion_salarial"
                item-value="configuracion_salarial"
                item-text="descripcion"
                name="configuración salarial"
                label="Configuración Salarial"
                :error-messages="errors.collect('configuración salarial')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md4 lg3>
              <v-select
                :items="complementos ? complementos.tiposcontratos : []"
                v-model="data.tipo_contrato"
                item-value="tipo_contrato"
                item-text="descripcion"
                name="tipo contrato"
                label="Tipo Contrato"
                :error-messages="errors.collect('tipo contrato')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md4 lg3>
              <v-select
                :items="complementoscontratos ? complementoscontratos.tipliquidaciones : []"
                v-model="data.tipo_liquidacion"
                item-value="id"
                item-text="nombre"
                name="tipo de liquidación"
                label="Tipo de Liquidación"
                :error-messages="errors.collect('tipo de liquidación')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md6 lg6>
              <v-select
                :items="complementoscontratos ? complementoscontratos.tippagos : []"
                v-model="data.tipo_pago"
                item-value="id"
                item-text="nombre"
                name="tipo pago"
                label="Tipo Pago"
                :error-messages="errors.collect('tipo pago')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md3 lg3>
              <v-select
                :items="complementos ? complementos.centroscostos : []"
                v-model="data.centro_trabajo"
                item-value="centro_costo"
                item-text="descripcion"
                name="centro de trabajo"
                label="Centro de trabajo"
                :error-messages="errors.collect('centro de trabajo')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md3 lg3>
              <v-select
                :disabled="!data.centro_trabajo"
                :items="complementos ? complementos.areas.filter(x => x.centro_costo === data.centro_trabajo) : []"
                v-model="data.area"
                item-value="area"
                item-text="descripcion"
                name="área"
                label="Área"
                :error-messages="errors.collect('área')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md6 lg6>
              <v-select
                :disabled="!data.area"
                :items="complementos ? complementos.dependencias.filter(x => x.area === data.area) : []"
                v-model="data.dependencia"
                item-value="dependencia"
                item-text="descripcion"
                name="dependencia"
                label="Dependencia"
                :error-messages="errors.collect('dependencia')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md4 lg3>
              <v-select
                :items="complementoscontratos ? complementoscontratos.jornadas : []"
                v-model="data.jornada_laboral"
                item-value="id"
                item-text="nombre"
                name="jornada"
                label="Jornada"
                :error-messages="errors.collect('Jornada')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md4 lg3>
              <v-select
                :items="complementoscontratos ? complementoscontratos.tiposcotizantes : []"
                v-model="data.tipo_cotizante"
                item-value="id"
                item-text="nombre"
                name="tipo cotizante"
                label="Tipo Cotizante"
                :error-messages="errors.collect('tipo cotizante')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md4 lg3>
              <v-select
                :disabled="!data.tipo_cotizante"
                :items="complementoscontratos ? complementoscontratos.subtipos_cotizantes : []"
                v-model="data.sub_tipo_cotizante"
                item-value="id"
                item-text="nombre"
                name="subtipo cotizante"
                label="Subtipo Cotizante"
                :error-messages="errors.collect('subtipo cotizante')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md4 lg3>
              <input-date
                v-model="data.fecha_afiliacion_seguro"
                label="Fecha Afiliación Seguro (Año-Mes-Día)"
                format="YYYY-MM-DD"
                :max="maxDate"
                name="fecha afiliación seguro"
                v-validate="'fechaValida:YYYY-MM-DD'"
                :error-messages="errors.collect('fecha afiliación seguro')"
              />
            </v-flex>
            <v-flex xs12 sm6 md4 lg3>
              <v-select
                :items="complementoscontratos ? complementoscontratos.formasdepagos : []"
                v-model="data.forma_pago"
                item-value="id"
                item-text="nombre"
                name="forma de pago"
                label="Forma de Pago"
                :error-messages="errors.collect('forma de pago')"
                v-validate="'required'"
                clearable
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md4 lg3>
              <v-switch
                class="mr-4"
                color="success"
                label="Régimen Antiguo"
                v-model="data.sw_antiguo_regimen"
              ></v-switch>
            </v-flex>
          </v-layout>
        </v-container>
      </v-form>
      <v-card-actions class="grey lighten-3">
        <v-spacer></v-spacer>
        <v-btn flat @click="show = false">Cancelar</v-btn>
        <v-btn color="primary" flat @click.prevent="save">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'RegistroContratoEmpleado',
    data: () => ({
      show: false,
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      data: null,
      datos: null,
      complementos: null,
      complementoscontratos: null,
      estados: null
      // makeData: null
    }),
    watch: {
      'show' (val) {
        if (!val) {
          setTimeout(() => {
            this.datos = null
            this.formReset()
            this.$validator.reset()
          }, 500)
        }
      }
    },
    created () {
      this.formReset()
      this.getComplementos()
    },
    computed: {
      ModalTitulo () {
        return this.data ? `${!this.data.contrato_empleado ? 'Nuevo ' : 'Edición del '} Contrato ${this.data.contrato_empleado ? '# ' + this.data.contrato_empleado : ''}` : ''
      }
    },
    methods: {
      getComplementos () {
        this.axios.get(`talentohumano/complementos`)
          .then(response => {
            console.log('DAD', response)
            this.complementos = response.data.data
            this.complementoscontratos = this.clone(this.complementos.complementoscontratos)
            this.estados = this.complementoscontratos.estados
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al cargar los complementos.', error: e})
          })
      },
      assign (props, val = null) {
        console.log('propss', props)
        // this.data = JSON.parse(JSON.stringify(this.makeData))
        switch (val) {
          case 'uno':
            this.datos = props
            this.data.empleado = this.datos.empleado
            break
          case 'dos':
            this.data = props
            break
        }
        this.show = true
      },
      save () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.$emit('updateOrCreate', this.data)
            this.show = false
          }
        })
      },
      formReset () {
        this.data = {
          contrato_empleado: null,
          area: null,
          base_prima: 0,
          base_vacaciones: 0,
          basico_actual: 0,
          cargo: null,
          causal_despido: 0,
          centro_trabajo: null,
          configuracion_salarial: null,
          dependencia: null,
          dias_no_laborados: 0,
          empleado: null,
          estado: null,
          fecha_afiliacion_seguro: null,
          fecha_final: null,
          fecha_inicio: null,
          fecha_liquidacion: null,
          forma_pago: null,
          indemnizacion_contra: 0,
          indemnizacion_favor: 0,
          jornada_laboral: null,
          sub_tipo_cotizante: null,
          sw_antiguo_regimen: 0,
          sw_salario_integral: 0,
          tipo_contrato: null,
          tipo_cotizante: null,
          tipo_liquidacion: null,
          tipo_pago: null
        }
      }
    }
  }
</script>

<style scoped>

</style>
