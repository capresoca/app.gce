<template>
  <v-card>
    <!--<toolbar-list class="elevation-1 grey lighten-3 text-uppercase" title=""/>-->
    <v-toolbar class="grey lighten-3 elevation-0">
      <v-toolbar-title class="title text-uppercase">Configuración de Parámetros</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon color="white" v-if="configuracionCartera.id" flat @click="isEditing = !isEditing">
        <v-icon color="accent" v-text="'edit'"></v-icon>
      </v-btn>
    </v-toolbar>
    <v-container fluid grid-list-xs grid-list-xl class="pt-3">
      <v-layout v-if="!configuracionCartera.id" align-center justify-center>
        <v-flex  xs12 md12 class="pt-3">
          <v-alert v-show="!configuracionCartera.id" :value="true" color="error" icon="warning" width="500px">
            {{ message }}
          </v-alert>
        </v-flex>
      </v-layout>
      <v-form data-vv-scope="formConfiguracionCartera">
        <v-layout row wrap>
          <v-flex xs12 sm12 md5 lg5>
            <v-layout column>
              <v-flex>
                <v-card>
                  <toolbar-list class="elevation-1 grey lighten-3" title="Cuentas"/>
                  <v-container fluid>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md12 lg12>
                        <postulador
                          nodata="Busqueda por código o nombre."
                          required
                          hint="nombre"
                          itemtext="codigo"
                          datatitle="nombre"
                          datasubtitle="codigo"
                          filter="codigo,nombre"
                          :label="configuracionCartera.nf_niif_cxc_id ? 'CXC' : 'cuenta por cobrar'"
                          lite
                          scope="formConfiguracionCartera"
                          ref="cuentaXcobrar"
                          entidad="niifs"
                          routeparams="nivel:auxiliar=1"
                          preicon="account_balance_wallet"
                          :disabled="!configuracionCartera.id ? false : !isEditing"
                          @change="val => configuracionCartera.nf_niif_cxc_id = val"
                          @objectReturn="val => configuracionCartera.niifcxc = val"
                          :value="configuracionCartera.niifcxc"
                          clearable
                        ></postulador>
                      </v-flex>
                      <v-flex xs12 sm12 md12 lg12>
                        <postulador
                          nodata="Busqueda por código o nombre."
                          required
                          hint="nombre"
                          itemtext="codigo"
                          datatitle="nombre"
                          datasubtitle="codigo"
                          filter="codigo,nombre"
                          :label="configuracionCartera.nf_niif_glosas_id ? 'Glosas' : 'cuenta de glosas'"
                          lite
                          scope="formConfiguracionCartera"
                          ref="cuentaXglosas"
                          entidad="niifs"
                          routeparams="nivel:auxiliar=1"
                          preicon="class"
                          :disabled="!configuracionCartera.id ? false : !isEditing"
                          @change="val => configuracionCartera.nf_niif_glosas_id = val"
                          @objectReturn="val => configuracionCartera.niifglosas = val"
                          :value="configuracionCartera.niifglosas"
                          clearable
                        ></postulador>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card>
              </v-flex>
              <v-flex>
                <v-card class="card--flex-toolbar">
                  <toolbar-list class="elevation-1 grey lighten-3" title="Edades"/>
                  <v-container fluid grid-list-xs>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md12 lg12>
                        <v-text-field label="Plazo para facturas sin vencer:"
                                      required type="number" v-model.number="configuracionCartera.edad_inicial"
                                      :suffix="parseInt(configuracionCartera.edad_inicial) !== 1 ? 'días' : 'día'"></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md12 lg12>
                        <v-text-field
                          :label="'De ' + (configuracionCartera.edad_inicial === '' ? '#' : (parseInt(configuracionCartera.edad_inicial)+1)) + ' Hasta'"
                          type="number" required v-model.number="configuracionCartera.edad_rango1"  :suffix="parseInt(configuracionCartera.edad_rango1) !== 1 ? 'días' : 'día'"
                          key="días 1" v-validate="'required|min_value:'+((configuracionCartera.edad_inicial)+1)"
                          name="días 1" :error-messages="errors.collect('días 1')"></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md12 lg12>
                        <v-text-field
                          :label="'De ' + (configuracionCartera.edad_rango1 === '' ? '#' : (parseInt(configuracionCartera.edad_rango1)+1)) + ' Hasta'"
                          type="number" v-model.number="configuracionCartera.edad_rango2"  :suffix="parseInt(configuracionCartera.edad_rango2) !== 1 ? 'días' : 'día'"
                          key="días 2" v-validate="'required|min_value:'+(parseInt(configuracionCartera.edad_rango1)+1)"
                          name="días 2" :error-messages="errors.collect('días 2')"></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md12 lg12>
                        <v-text-field
                          :label="'De ' + (configuracionCartera.edad_rango2 === '' ? '#' : (parseInt(configuracionCartera.edad_rango2)+1)) + ' Hasta'"
                          type="number" v-model.number="configuracionCartera.edad_rango3"  :suffix="parseInt(configuracionCartera.edad_rango3) !== 1 ? 'días' : 'día'"
                          key="días 3" v-validate="'required|min_value:'+(parseInt(configuracionCartera.edad_rango2)+1)"
                          name="días 3" :error-messages="errors.collect('días 3')"></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md12 lg12>
                        <v-text-field
                          :label="'De ' + (configuracionCartera.edad_rango3 === '' ? '#' : (parseInt(configuracionCartera.edad_rango3)+1)) + ' Hasta'"
                          type="number" v-model.number="configuracionCartera.edad_rango4"  :suffix="parseInt(configuracionCartera.edad_rango4) !== 1 ? 'días' : 'día'"
                          key="días 4" v-validate="'required|min_value:'+(parseInt(configuracionCartera.edad_rango3)+1)"
                          name="días 4" :error-messages="errors.collect('días 4')"></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md12 lg12>
                        <h6 class="body-2" v-text="'Mayor de: ' + configuracionCartera.edad_rango4 + ' días.'"></h6>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs12 sm12 md7 lg7>
            <v-card>
              <toolbar-list class="elevation-1 grey lighten-3" title="Tipo de Comprobante"/>
              <v-container fluid>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-autocomplete
                      label="Nota débito"
                      v-model="configuracionCartera.tipodebito"
                      :items="complementosFormComDiario.tipcomdiarios"
                      @change="configuracionCartera.nf_tipcomdiario_debito_id = configuracionCartera.tipodebito ? configuracionCartera.tipodebito.id : null"
                      item-value="id"
                      item-text="codigo"
                      persistent-hint
                      :hint="configuracionCartera.tipodebito ? configuracionCartera.tipodebito.nombre : null"
                      name="nota débito"
                      :disabled="!configuracionCartera.id ? false : !isEditing"
                      return-object
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('nota débito')"
                      clearable>
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
                  <v-flex xs12 sm12 md6 lg6>
                    <v-autocomplete
                      label="Nota crédito"
                      v-model="configuracionCartera.tipocredito"
                      :items="complementosFormComDiario.tipcomdiarios"
                      @change="configuracionCartera.nf_tipcomdiario_credito_id = configuracionCartera.tipocredito ? configuracionCartera.tipocredito.id : null"
                      item-value="id"
                      item-text="codigo"
                      persistent-hint
                      :hint="configuracionCartera.tipocredito ? configuracionCartera.tipocredito.nombre : null"
                      name="nota crédito"
                      :disabled="!configuracionCartera.id ? false : !isEditing"
                      return-object
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('nota crédito')"
                      clearable>
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
                  <v-flex xs12 sm12 md6 lg6>
                    <v-autocomplete
                      label="Traslados"
                      v-model="configuracionCartera.tipotraslados"
                      :items="complementosFormComDiario.tipcomdiarios"
                      @change="configuracionCartera.nf_tipcomdiario_traslados_id = configuracionCartera.tipotraslados ? configuracionCartera.tipotraslados.id : null"
                      item-value="id"
                      item-text="codigo"
                      persistent-hint
                      :hint="configuracionCartera.tipotraslados ? configuracionCartera.tipotraslados.nombre : null"
                      name="traslados"
                      :disabled="!configuracionCartera.id ? false : !isEditing"
                      return-object
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('traslados')"
                      clearable>
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
                  <v-flex xs12 sm12 md6 lg6>
                    <v-autocomplete
                      label="CXC"
                      v-model="configuracionCartera.tipocxc"
                      :items="complementosFormComDiario.tipcomdiarios"
                      @change="configuracionCartera.nf_tipcomdiario_cxc_id = configuracionCartera.tipocxc ? configuracionCartera.tipocxc.id : null"
                      item-value="id"
                      item-text="codigo"
                      persistent-hint
                      :hint="configuracionCartera.tipocxc ? configuracionCartera.tipocxc.nombre : null"
                      name="cxc"
                      :disabled="!configuracionCartera.id ? false : !isEditing"
                      return-object
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('cxc')"
                      clearable>
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
                  <v-flex xs12 sm12 md6 lg6>
                    <v-autocomplete
                      label="Radicación de Glosas"
                      v-model="configuracionCartera.tiporglosas"
                      :items="complementosFormComDiario.tipcomdiarios"
                      @change="configuracionCartera.nf_tipcomdiario_rglosas_id = configuracionCartera.tiporglosas ? configuracionCartera.tiporglosas.id : null"
                      item-value="id"
                      item-text="codigo"
                      persistent-hint
                      :hint="configuracionCartera.tiporglosas ? configuracionCartera.tiporglosas.nombre : null"
                      name="radicación de glosas"
                      :disabled="!configuracionCartera.id ? false : !isEditing"
                      return-object
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('radicación de glosas')"
                      clearable>
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
                  <v-flex xs12 sm12 md6 lg6>
                    <v-autocomplete
                      label="Contestación de Glosas"
                      v-model="configuracionCartera.tipocglosas"
                      :items="complementosFormComDiario.tipcomdiarios"
                      @change="configuracionCartera.nf_tipcomdiario_cglosas_id = configuracionCartera.tipocglosas ? configuracionCartera.tipocglosas.id : null"
                      item-value="id"
                      item-text="codigo"
                      persistent-hint
                      :hint="configuracionCartera.tipocglosas ? configuracionCartera.tipocglosas.nombre : null"
                      name="contestación de glosas"
                      :disabled="!configuracionCartera.id ? false : !isEditing"
                      return-object
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('contestación de glosas')"
                      clearable>
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
                  <v-flex xs12 sm12 md6 lg6>
                    <v-autocomplete
                      label="Provisión de Cartera"
                      v-model="configuracionCartera.tipopcartera"
                      :items="complementosFormComDiario.tipcomdiarios"
                      @change="configuracionCartera.nf_tipcomdiario_pcartera_id = configuracionCartera.tipopcartera ? configuracionCartera.tipopcartera.id : null"
                      item-value="id"
                      item-text="codigo"
                      persistent-hint
                      :hint="configuracionCartera.tipopcartera ? configuracionCartera.tipopcartera.nombre : null"
                      name="provisión de cartera"
                      :disabled="!configuracionCartera.id ? false : !isEditing"
                      return-object
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('provisión de cartera')"
                      clearable>
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
                  <v-flex xs12 sm12 md6 lg6>
                    <v-autocomplete
                      label="Responsabilidades"
                      v-model="configuracionCartera.tiporesponsabilidades"
                      :items="complementosFormComDiario.tipcomdiarios"
                      @change="configuracionCartera.nf_tipcomdiario_responsabilidades_id = configuracionCartera.tiporesponsabilidades ? configuracionCartera.tiporesponsabilidades.id : null"
                      item-value="id"
                      item-text="codigo"
                      persistent-hint
                      :hint="configuracionCartera.tiporesponsabilidades ? configuracionCartera.tiporesponsabilidades.nombre : null"
                      name="responsabilidades"
                      :disabled="!configuracionCartera.id ? false : !isEditing"
                      return-object
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('responsabilidades')"
                      clearable>
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
                  <v-flex xs12 sm12 md6 lg6>
                    <v-autocomplete
                      label="Clasificación Difícil Recaudo"
                      v-model="configuracionCartera.tipocdr"
                      :items="complementosFormComDiario.tipcomdiarios"
                      @change="configuracionCartera.nf_tipcomdiario_cdr_id = configuracionCartera.tipocdr ? configuracionCartera.tipocdr.id : null"
                      item-value="id"
                      item-text="codigo"
                      persistent-hint
                      :hint="configuracionCartera.tipocdr ? configuracionCartera.tipocdr.nombre : null"
                      name="cdr"
                      :disabled="!configuracionCartera.id ? false : !isEditing"
                      return-object
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('cdr')"
                      clearable>
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
                  <v-flex xs12 sm12 md6 lg6>
                    <v-autocomplete
                      label="Notas Modifica Presupuesto"
                      v-model="configuracionCartera.tiponmp"
                      :items="complementosFormComDiario.tipcomdiarios"
                      @change="configuracionCartera.nf_tipcomdiario_nmp_id = configuracionCartera.tiponmp ? configuracionCartera.tiponmp.id : null"
                      item-value="id"
                      item-text="codigo"
                      persistent-hint
                      :hint="configuracionCartera.tiponmp ? configuracionCartera.tiponmp.nombre : null"
                      name="notas de modificación presupuesto"
                      :disabled="!configuracionCartera.id ? false : !isEditing"
                      return-object
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('notas de modificación presupuesto')"
                      clearable>
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
                  <v-flex xs12 sm12 md12 lg12>
                    <v-autocomplete
                      label="Reclasifica Acuerdo de Pago"
                      v-model="configuracionCartera.tiporap"
                      :items="complementosFormComDiario.tipcomdiarios"
                      @change="configuracionCartera.nf_tipcomdiario_rap_id = configuracionCartera.tiporap ? configuracionCartera.tiporap.id : null"
                      item-value="id"
                      item-text="codigo"
                      persistent-hint
                      :hint="configuracionCartera.tiporap ? configuracionCartera.tiporap.nombre : null"
                      name="reclasifica acuerdo de pago"
                      :disabled="!configuracionCartera.id ? false : !isEditing"
                      return-object
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('reclasifica acuerdo de pago')"
                      clearable>
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
                </v-layout>
              </v-container>
            </v-card>
          </v-flex>
          <v-flex xs12 sm12 md12 lg12>
          </v-flex>
        </v-layout>
        <v-layout row wrap v-if="infoComponent ? infoComponent.permisos.agregar : false">
          <v-flex xs12 class="text-xs-right pr-0">
            <v-spacer></v-spacer>
            <v-slide-y-transition>
              <!--<v-btn v-if="isEditing == false">Cancelar</v-btn>-->
              <v-btn color="primary" v-if="isEditing == false && !configuracionCartera.id ? true : (isEditing == true && configuracionCartera.id ? true : false) " @click.prevent="submit" v-text="!configuracionCartera.id ? 'Registrar' : 'Actualizar'"></v-btn>
            </v-slide-y-transition>
          </v-flex>
        </v-layout>
      </v-form>
    </v-container>
  </v-card>
</template>

<script>
  import store from '../../../store/complementos/index'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'Configuracion',
    data () {
      return {
        active: null,
        configuracionCartera: {
          id: null,
          nf_niif_cxc_id: null,
          nf_niif_glosas_id: null,
          nf_tipcomdiario_debito_id: null,
          nf_tipcomdiario_credito_id: null,
          nf_tipcomdiario_traslados_id: null,
          nf_tipcomdiario_cxc_id: null,
          nf_tipcomdiario_rglosas_id: null,
          nf_tipcomdiario_cglosas_id: null,
          nf_tipcomdiario_pcartera_id: null,
          nf_tipcomdiario_responsabilidades_id: null,
          nf_tipcomdiario_cdr_id: null,
          nf_tipcomdiario_nmp_id: null,
          nf_tipcomdiario_rap_id: null,
          edad_inicial: 0,
          edad_rango1: 0,
          edad_rango2: 0,
          edad_rango3: 0,
          edad_rango4: 0,
          // Objects
          niifcxc: null,
          niifglosas: null,
          tipodebito: null,
          tipocredito: null,
          tipotraslados: null,
          tipocxc: null,
          tiporglosas: null,
          tipocglosas: null,
          tipopcartera: null,
          tiporesponsabilidades: null,
          tipocdr: null,
          tiponmp: null,
          tiporap: null
        },
        isEditing: false,
        message: null
      }
    },
    components: {
      Postulador: () => import('@/components/general/Postulador'),
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    beforeMount () {
      this.configuracionFirst()
    },
    computed: {
      complementosFormComDiario () {
        return store.getters.complementosFormComDiario
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('configuracionCartera')
      }
    },
    methods: {
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        let cxc = await this.$refs.cuentaXcobrar.validate()
        let cxg = await this.$refs.cuentaXglosas.validate()
        if (await this.validador('formConfiguracionCartera') && cxc && cxg) {
          let send = !this.configuracionCartera.id ? this.axios.post('cr_configuraciones', this.configuracionCartera) : this.axios.put('cr_configuraciones/' + this.configuracionCartera.id, this.configuracionCartera)
          send.then((res) => {
            this.$store.commit(SNACK_SHOW, {
              msg: `Se ${this.configuracionCartera.id
                ? 'actualizaron los datos'
                : 'realizó el registro'} correctamente.`,
              color: 'success'
            })
            this.configuracionCartera = res.data.data
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ', color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay datos obligatorios verifique el formulario. ', color: 'danger'})
        }
      },
      configuracionFirst () {
        this.axios.get('cr_configuraciones').then((response) => {
          if (response.data.data) {
            this.configuracionCartera = response.data.data
            // console.log(this.configuracionCartera)
          } else {
            this.message = response.data.message
            // console.log(this.message)
          }
        }).catch(e => {
          this.$store.commit(SNACK_SHOW, {msg: e.data.message, color: 'danger'})
        })
      }
    }
  }
</script>

<style scoped>

</style>
