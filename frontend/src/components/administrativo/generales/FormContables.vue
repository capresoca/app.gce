<template>
    <v-form data-vv-scope="formTabCon">
      <registro-niif :value="dialogFormNiif" :cue="cuentaNiff" @cancelar="dialogFormNiif = false" @crear="val => goNiif(val)"></registro-niif>
      <v-card>
        <v-container fluid grid-list-xl>
          <v-layout row wrap>
            <v-flex xs12 sm6>
              <postulador-v2
                no-data="Busqueda por código o nombre."
                hint="nombre"
                item-text="codigo"
                data-title="codigo"
                data-subtitle="nombre"
                scope="formTabCon"
                :label="!value.nf_niifdeficit_id ? 'cuenta de deficit' : 'Cuenta de Deficit'"
                entidad="niifs"
                route-params="nivel:auxiliar=1"
                preicon="business_center"
                @changeid="val => value.nf_niifdeficit_id = val"
                v-model="value.deficit"
                v-validate="'required'"
                name="cuenta de deficit"
                :error-messages="errors.collect('cuenta de deficit')"
                :disabled="!value.id ? false : !isEditing"
                btnCreateTruncate
                @create="openNuevaNiif(1)"
                :min-characters-search="2"
                clearable/>
<!--              <postulador-->
<!--                nodata="Busqueda por código o nombre."-->
<!--                required-->
<!--                hint="nombre"-->
<!--                itemtext="codigo"-->
<!--                datatitle="codigo"-->
<!--                datasubtitle="nombre"-->
<!--                filter="codigo,nombre"-->
<!--                :label="!value.nf_niifdeficit_id ? 'cuenta de deficit' : 'Cuenta de Deficit'"-->
<!--                scope="formTabCon"-->
<!--                ref="buscarCuentadeficit"-->
<!--                entidad="niifs"-->
<!--                preicon="business_center"-->
<!--                routeparams="nivel:auxiliar=1"-->
<!--                @change="val => value.nf_niifdeficit_id = val"-->
<!--                @objectReturn="val => value.deficit = val"-->
<!--                :value="value.deficit"-->
<!--                clearable-->
<!--                btnplustruncate-->
<!--                :disabled="!value.id ? false : !isEditing"-->
<!--                @click="openNuevaNiif(1)"-->
<!--              ></postulador>-->
            </v-flex>
            <v-flex xs12 sm6>
              <postulador-v2
                no-data="Busqueda por código o nombre."
                hint="nombre"
                item-text="codigo"
                data-title="codigo"
                data-subtitle="nombre"
                scope="formTabCon"
                :label="!value.nf_niifsuperavit_id ? 'cuenta de superavit' : 'Cuenta de Superavit'"
                entidad="niifs"
                route-params="nivel:auxiliar=1"
                preicon="multiline_chart"
                @changeid="val => value.nf_niifsuperavit_id = val"
                v-model="value.superavit"
                v-validate="'required'"
                name="cuenta de superavit"
                :error-messages="errors.collect('cuenta de superavit')"
                :disabled="!value.id ? false : !isEditing"
                btnCreateTruncate
                @create="openNuevaNiif(3)"
                :min-characters-search="2"
                clearable/>
<!--              <postulador-->
<!--                nodata="Busqueda por código o nombre."-->
<!--                required-->
<!--                hint="nombre"-->
<!--                itemtext="codigo"-->
<!--                datatitle="codigo"-->
<!--                datasubtitle="nombre"-->
<!--                filter="codigo,nombre"-->
<!--                :label="!value.nf_niifsuperavit_id ? 'cuenta de superavit' : 'Cuenta de Superavit'"-->
<!--                scope="formTabCon"-->
<!--                ref="buscarCuentasuperavit"-->
<!--                entidad="niifs"-->
<!--                preicon="multiline_chart"-->
<!--                routeparams="nivel:auxiliar=1"-->
<!--                @change="val => value.nf_niifsuperavit_id = val"-->
<!--                @objectReturn="val => value.superavit = val"-->
<!--                :value="value.superavit"-->
<!--                clearable-->
<!--                btnplustruncate-->
<!--                :disabled="!value.id ? false : !isEditing"-->
<!--                @click="openNuevaNiif(3)"-->
<!--              ></postulador>-->
            </v-flex>
            <v-flex xs12 sm6>
              <postulador-v2
                no-data="Busqueda por código o nombre."
                hint="nombre"
                item-text="codigo"
                data-title="codigo"
                data-subtitle="nombre"
                scope="formTabCon"
                :label="!value.nf_niifganacias_id ? 'cuenta de ganancias' : 'Cuenta de Ganancias'"
                entidad="niifs"
                route-params="nivel:auxiliar=1"
                preicon="local_atm"
                @changeid="val => value.nf_niifganacias_id = val"
                v-model="value.ganancias"
                v-validate="'required'"
                name="cuenta de ganancias"
                :error-messages="errors.collect('cuenta de ganancias')"
                :disabled="!value.id ? false : !isEditing"
                btnCreateTruncate
                @create="openNuevaNiif(2)"
                :min-characters-search="2"
                clearable/>
<!--              <postulador-->
<!--                nodata="Busqueda por código o nombre."-->
<!--                required-->
<!--                hint="nombre"-->
<!--                itemtext="codigo"-->
<!--                datatitle="codigo"-->
<!--                datasubtitle="nombre"-->
<!--                filter="codigo,nombre"-->
<!--                :label="!value.nf_niifganacias_id ? 'cuenta de ganancias' : 'Cuenta de Ganancias'"-->
<!--                scope="formTabCon"-->
<!--                ref="buscarCuentaganancias"-->
<!--                entidad="niifs"-->
<!--                preicon="local_atm"-->
<!--                routeparams="nivel:auxiliar=1"-->
<!--                @change="val => value.nf_niifganacias_id = val"-->
<!--                @objectReturn="val => value.ganancias = val"-->
<!--                :value="value.ganancias"-->
<!--                clearable-->
<!--                btnplustruncate-->
<!--                :disabled="!value.id ? false : !isEditing"-->
<!--                @click="openNuevaNiif(2)"-->
<!--              ></postulador>-->
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                label="Comprobante de Ajustes"
                v-model="value.com_ajuste"
                @change="val => value.nf_tipcomdiajustes_id = val ? val.id : null"
                :items="complementos.tipcomdiarios"
                item-value="id"
                item-text="codigo"
                persistent-hint
                :hint="value.nf_tipcomdiajustes_id ? value.com_ajuste.nombre : null"
                name="comprobante de ajustes"
                return-object
                required
                v-validate="'required'"
                :disabled="!value.id ? false : !isEditing"
                :error-messages="errors.collect('comprobante de ajustes')"
                clearable
              >
                <!--autocomplete-->
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="data.item.codigo"/>
                      <v-list-tile-sub-title v-html="data.item.nombre"/>
                    </v-list-tile-content>
                  </template>
                </template>
                <template slot="no-data">
                  <div class="pa-2">
                    <span>No se encuentra el comprobante.</span>
                  </div>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                label="Comprobante de Cierre"
                v-model="value.com_cierre"
                @change="val => value.nf_tipcomdiacierre_id = val ? val.id : null"
                :items="complementos.tipcomdiarios"
                item-value="id"
                item-text="codigo"
                persistent-hint
                :hint="value.nf_tipcomdiacierre_id ? value.com_cierre.nombre : null"
                name="comprobante de cierre"
                return-object
                required
                v-validate="'required'"
                :disabled="!value.id ? false : !isEditing"
                :error-messages="errors.collect('comprobante de cierre')"
                clearable
              >
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="data.item.codigo"/>
                      <v-list-tile-sub-title v-html="data.item.nombre"/>
                    </v-list-tile-content>
                  </template>
                </template>
                <template slot="no-data">
                  <div class="pa-2">
                    <span>No se encuentra el comprobante.</span>
                  </div>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                label="Comprobante de Traslado."
                v-model="value.com_traslado"
                @change="val => value.nf_tipcomdiatraslado_id = val ? val.id : null"
                :items="complementos.tipcomdiarios"
                item-value="id"
                item-text="codigo"
                persistent-hint
                :hint="value.nf_tipcomdiatraslado_id ? value.com_traslado.nombre : null"
                name="comprobante de traslado"
                return-object
                required
                v-validate="'required'"
                :disabled="!value.id ? false : !isEditing"
                :error-messages="errors.collect('comprobante de traslado')"
                clearable
              >
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="data.item.codigo"/>
                      <v-list-tile-sub-title v-html="data.item.nombre"/>
                    </v-list-tile-content>
                  </template>
                </template>
                <template slot="no-data">
                  <div class="pa-2">
                    <span>No se encuentra el comprobante.</span>
                  </div>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                label="Comprobante de Homologación."
                v-model="value.com_homologacion"
                @change="val => value.nf_tipcomdiahomologacion_id = val ? val.id : null"
                :items="complementos.tipcomdiarios"
                item-value="id"
                item-text="codigo"
                persistent-hint
                :hint="value.nf_tipcomdiahomologacion_id ? value.com_homologacion.nombre : null"
                name="comprobante de homologación"
                return-object
                required
                v-validate="'required'"
                :disabled="!value.id ? false : !isEditing"
                :error-messages="errors.collect('comprobante de homologación')"
                clearable
              >
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="data.item.codigo"/>
                      <v-list-tile-sub-title v-html="data.item.nombre"/>
                    </v-list-tile-content>
                  </template>
                </template>
                <template slot="no-data">
                  <div class="pa-2">
                    <span>No se encuentra el comprobante.</span>
                  </div>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                label="Comprobante de Distribución."
                v-model="value.com_distribucion"
                @change="val => value.nf_tipcomdiadistribucion_id = val ? val.id : null"
                :items="complementos.tipcomdiarios"
                item-value="id"
                item-text="codigo"
                persistent-hint
                :hint="value.nf_tipcomdiadistribucion_id ? value.com_distribucion.nombre : null"
                name="comprobante de distribución"
                return-object
                required
                v-validate="'required'"
                :disabled="!value.id ? false : !isEditing"
                :error-messages="errors.collect('comprobante de distribución')"
                clearable
              >
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="data.item.codigo"/>
                      <v-list-tile-sub-title v-html="data.item.nombre"/>
                    </v-list-tile-content>
                  </template>
                </template>
                <template slot="no-data">
                  <div class="pa-2">
                    <span>No se encuentra el comprobante.</span>
                  </div>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12><v-divider></v-divider></v-flex>
            <v-flex xs12 sm6>
              <postulador-v2
                no-data="Busqueda por código o nombre."
                hint="identificacion_completa"
                item-text="nombre_completo"
                data-title="identificacion_completa"
                data-subtitle="nombre_completo"
                scope="formTabCon"
                :label="!value.nit_dian ? 'dian' : 'Dian'"
                entidad="terceros"
                preicon="person"
                @changeid="val => value.nit_dian = val"
                v-model="value.dian"
                v-validate="'required'"
                name="dian"
                :error-messages="errors.collect('dian')"
                :disabled="!value.id ? false : !isEditing"
                no-btn-create
                :min-characters-search="2"
                clearable/>
<!--              <postulador-->
<!--                nodata="Busqueda por código o nombre."-->
<!--                required-->
<!--                hint="identificacion_completa"-->
<!--                itemtext="nombre_completo"-->
<!--                datatitle="identificacion"-->
<!--                datasubtitle="nombre_completo"-->
<!--                filter="identificacion_completa,nombre_completo"-->
<!--                :label="!value.nit_dian ? 'dian' : 'Dian'"-->
<!--                scope="formTabCon"-->
<!--                ref="buscarNitdian"-->
<!--                entidad="terceros"-->
<!--                preicon="person"-->
<!--                :disabled="!value.id ? false : !isEditing"-->
<!--                @change="val => value.nit_dian = val"-->
<!--                @objectReturn="val => value.dian = val"-->
<!--                :value="value.dian"-->
<!--                clearable-->
<!--                btnplustruncate-->
<!--              ></postulador>-->
            </v-flex>
            <v-flex xs12 sm6>
              <postulador-v2
                no-data="Busqueda por código o nombre."
                hint="identificacion_completa"
                item-text="nombre_completo"
                data-title="identificacion_completa"
                data-subtitle="nombre_completo"
                scope="formTabCon"
                :label="!value.tesoreria_distrital ? 'tesorería distrital' : 'Tesorería Distrital'"
                entidad="terceros"
                preicon="home"
                @changeid="val => value.tesoreria_distrital = val"
                v-model="value.tesoreria"
                v-validate="'required'"
                name="tesorería distrital"
                :error-messages="errors.collect('tesorería distrital')"
                :disabled="!value.id ? false : !isEditing"
                no-btn-create
                :min-characters-search="2"
                clearable/>
<!--              <postulador-->
<!--                nodata="Busqueda por código o nombre."-->
<!--                required-->
<!--                hint="identificacion_completa"-->
<!--                itemtext="nombre_completo"-->
<!--                datatitle="identificacion"-->
<!--                datasubtitle="nombre_completo"-->
<!--                filter="identificacion_completa,nombre_completo"-->
<!--                :disabled="!value.id ? false : !isEditing"-->
<!--                :label="!value.tesoreria_distrital ? 'tesorería distrital' : 'Tesorería Distrital'"-->
<!--                scope="formTabCon"-->
<!--                ref="buscarTesoreria"-->
<!--                entidad="terceros"-->
<!--                @change="val => value.tesoreria_distrital = val"-->
<!--                @objectReturn="val => value.tesoreria = val"-->
<!--                :value="value.tesoreria"-->
<!--                clearable-->
<!--                btnplustruncate-->
<!--              ></postulador>-->
            </v-flex>
            <v-flex xs12>
              <v-spacer></v-spacer>
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
                <v-text-field
                  slot="activator"
                  v-model="computedDateFormatted"
                  label="Fecha Inicial"
                  prepend-icon="event"
                  readonly
                  name="fecha inicial"
                  :disabled="!value.id ? false : !isEditing"
                  v-validate="'required|date_format:YYYY/MM/DD'"
                  :error-messages="errors.collect('fecha inicial')"
                ></v-text-field>
                <v-date-picker
                  v-model="value.fecha_inicial"
                  @input="menuDate = false"
                  @change="() => {
                    let index = $validator.errors.items.findIndex(x => x.field === 'fecha inicial')
                    $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                  }"
                  locale='es'
                  no-title
                ></v-date-picker>
              </v-menu>
            </v-flex>
            <v-flex xs12 sm7>
              <v-text-field v-upper="'value.firma_certificados'" v-model="value.firma_certificados" name="firma certificados"
                            label="Firma Certificados" key="firma certificados"
                            v-validate="'required'" required
                            :disabled="!value.id ? false : !isEditing"
                            :error-messages="errors.collect('firma certificados')"></v-text-field>
            </v-flex>
            <v-flex xs12 sm5>
              <v-autocomplete
                label="Cargo"
                v-model="value.cargo"
                @change="val => value.gn_cargo_id = val ? val.id : null"
                :items="complementos.cargosEmpleado"
                item-value="id"
                item-text="nombre"
                name="cargo"
                return-object
                required
                v-validate="'required'"
                :error-messages="errors.collect('cargo')"
                :disabled="!value.id ? false : !isEditing"
                clearable
              >
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="data.item.codigo"/>
                      <v-list-tile-sub-title v-html="data.item.nombre"/>
                    </v-list-tile-content>
                  </template>
                </template>
                <template slot="no-data">
                  <div class="pa-2">
                    <span>No se encuentra el cargo.</span>
                  </div>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm7>
              <v-text-field v-upper="'value.revisor_fiscal'" v-model="value.revisor_fiscal" name="revisor fiscal"
                            label="Revisor Fiscal" key="revisor fiscal"
                            v-validate="'required'" required
                            :disabled="!value.id ? false : !isEditing"
                            :error-messages="errors.collect('revisor fiscal')"></v-text-field>
            </v-flex>
            <v-flex xs12 sm5>
              <v-text-field v-model="value.tarjeta_profesional" name="tarjeta profesional"
                            :disabled="!value.id ? false : !isEditing"
                            label="Tarjeta Profesional" key="tarjeta profesional" v-validate="'required'"
                            :error-messages="errors.collect('tarjeta profesional')"></v-text-field>
            </v-flex>
          </v-layout>
        </v-container>
        <!--<v-card-text>Contables</v-card-text> @change="configuracionPago.nf_tipcomcxp_id = configuracionPago.tipcomp_cxp ? configuracionPago.tipcomp_cxp.id : null"-->
      </v-card>
    </v-form>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'FormContables',
    props: ['value', 'isEditing'],
    inject: {
      $validator: '$validator'
    },
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      // Postulador: () => import('@/components/general/Postulador'),
      RegistroNiif: () => import('@/components/administrativo/niif/puc/RegistroNiif')
    },
    data () {
      return {
        niifNueva: null,
        menuDate: false,
        dialogFormNiif: null,
        cuentaNiff: null
      }
    },
    watch: {
      'errors.items' (val) {
        this.$emit('errors', !(val.filter(item => item.scope === 'formTabCon').length > 0))
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosEmpresa
      },
      computedDateFormatted () {
        return this.formDate(this.value.fecha_inicial)
      }
    },
    methods: {
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      goNiif (val) {
        console.log('GoVal', val)
        console.log('Niif', this.niifNueva)
        if (this.niifNueva === 1) {
          this.value.deficit = val
        } else if (this.niifNueva === 2) {
          this.value.ganancias = val
        } else if (this.niifNueva === 3) {
          this.value.superavit = val
        }
        this.dialogFormNiif = false
      },
      openNuevaNiif (val) {
        this.niifNueva = val
        this.dialogFormNiif = true
      },
      validate () {
        return this.$validator.validateAll('formTabCon').then(result => { return result })
      }
    }
  }
</script>

<style scoped>

</style>
