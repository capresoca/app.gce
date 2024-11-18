<template>
  <div id="container-parametros">
    <v-card class="transparent elevation-0">
      <v-toolbar class="grey lighten-3 elevation-1 dense">
        <v-toolbar-title class="title" v-text="'Parámetros De Tesorería'"></v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon color="white" v-if="configuracion.id" flat @click="isEditingP = !isEditingP">
          <v-icon color="accent" v-text="'edit'"></v-icon>
        </v-btn>
      </v-toolbar>
      <v-container fluid class="pa-0 pb-2 pt-1">
        <v-layout row wrap>
          <v-flex v-if="addParametros === false" xs12 md12>
            <v-alert v-show="!configuracion.id" :value="true" color="error" icon="warning" width="500px">
              <v-layout row wrap>
                <v-flex xs8 class="text-xs-left pt-3">
                  {{ message }}
                </v-flex>
                <v-flex xs4 class="text-xs-right">
                  <v-btn
                    color="primary"
                    dark
                    @click="addParametros = !addParametros"
                    v-text="'Agregar'"
                  >
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-alert>
          </v-flex>
          <v-flex xs12 v-if="configuracion.id || addParametros">
            <v-tabs color="cyan" v-model="currentItem" grow dark icons-and-text>
              <v-tabs-slider color="accent"></v-tabs-slider>
              <v-tab v-for="(bite, item) in tabParametros" :href="bite.hrefId" :key="`${item}`">
                <span v-text="bite.name"></span>
                <v-icon  color="white darken-2" v-text="bite.icon"></v-icon>
              </v-tab>
            </v-tabs>
            <v-tabs-items v-model="currentItem">
              <v-tab-item value="tab-generales" key="0">
                <tab-parametros-generales ref="general" v-model="configuracion" @errors="val => validTabGeneral = val" :is-editing="isEditingP"></tab-parametros-generales>
              </v-tab-item>
              <v-tab-item value="tab-contable" key="1">
                <tab-parametros-contables ref="contable" v-model="configuracion" @errors="val => validTabContable = val" :is-editing="isEditingP"></tab-parametros-contables>
              </v-tab-item>
              <v-tab-item value="tab-consecutivos" key="2">
                <tab-parametros-consecutivos ref="consecutivo" v-model="configuracion" @errors="val => validTabConsecutivo = val" :is-editing="isEditingP"></tab-parametros-consecutivos>
              </v-tab-item>
            </v-tabs-items>
          </v-flex>
        </v-layout>
      </v-container>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="close()">Cancelar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-slide-y-transition>
              <v-btn color="primary" v-if="isEditingP == false && !configuracion.id ? true : (isEditingP == true && configuracion.id ? true : false) " @click.prevent="addParametros ? submit() : 'Nada'"  v-text="!configuracion.id ? 'Registrar' : 'Actualizar'"></v-btn>
            </v-slide-y-transition>
            <!--<v-btn color="primary" @click.prevent="save()" v-text="(!isEditingP && !empresa.id)  ? 'Guardar' : 'Actualizar' " ></v-btn>-->
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>
<script>
  import store from '../../../store/complementos/index'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  // import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  export default {
    name: 'ParametrosTesoreria',
    inject: {
      $validator: '$validator'
    },
    data () {
      return {
        active: null,
        addParametros: false,
        currentItem: 'tab-generales',
        isEditingP: false,
        message: '',
        tabParametros: [
          {
            name: 'Datos Generales',
            icon: 'domain',
            hrefId: '#tab-generales'
          },
          {
            name: 'Datos Contables',
            icon: 'settings',
            hrefId: '#tab-contable'
          },
          {
            name: 'Consecutivos',
            icon: 'layers',
            hrefId: '#tab-consecutivos'
          }
        ],
        configuracion: {
          id: null,
          maneja_caja: 'No',
          modificar_fecha_documentos: 'No',
          consecutivos_cheques: 'No',
          concecutivos_recivos_caja: null,
          mensaje_caja: null,
          afecta_presupuesto_doc_tesoreria: null,
          contabiliacion_tasa: null,
          tipo_tasa: null,
          tasa: null,
          tipocaja: null,
          tipoegreso: null,
          tipoconsignacion: null,
          tiponotas: null,
          tipoinversiones: null,
          tipoaprovecha: null
        },
        complementosFormParametros: {}
      }
    },
    components: {
      TabParametrosConsecutivos: () => import('./TabParametrosConsecutivos'),
      TabParametrosContables: () => import('./TabParametrosContables'),
      TabParametrosGenerales: () => import('./TabParametrosGenerales'),
      Postulador: () => import('@/components/general/Postulador')
    },
    computed: {
      complementosFormComDiario () {
        return store.getters.complementosFormComDiario
      }
    },
    methods: {
      complementos () {
      },
      selectComprobanteCaja (val) {
        let comprobante = this.complementosFormComDiario.tipcomdiarios.find(x => x.id === val)
        this.configuracion.tipocaja = comprobante
      },
      selectComprobanteEgreso (val) {
        let comprobante = this.complementosFormComDiario.tipcomdiarios.find(x => x.id === val)
        this.configuracion.tipoegreso = comprobante
      },
      selectComprobanteConsignacion (val) {
        let comprobante = this.complementosFormComDiario.tipcomdiarios.find(x => x.id === val)
        this.configuracion.tipoconsignacion = comprobante
      },
      selectComprobanteNotas (val) {
        let comprobante = this.complementosFormComDiario.tipcomdiarios.find(x => x.id === val)
        this.configuracion.tiponotas = comprobante
      },
      selectComprobanteInversiones (val) {
        let comprobante = this.complementosFormComDiario.tipcomdiarios.find(x => x.id === val)
        this.configuracion.tipoinversiones = comprobante
      },
      selectComprobanteAprovecha (val) {
        let comprobante = this.complementosFormComDiario.tipcomdiarios.find(x => x.id === val)
        this.configuracion.tipoaprovecha = comprobante
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.$refs.general.validate()) {
          if (await this.$refs.contable.validate()) {
            if (await this.$refs.consecutivo.validate()) {
              this.send()
            } else {
              this.currentItem = 'tab-consecutivos'
              this.$store.commit(SNACK_SHOW, {msg: 'Existen campos vacíos. ', color: 'danger'})
              return false
            }
          } else {
            this.currentItem = 'tab-contable'
            this.$store.commit(SNACK_SHOW, {msg: 'Existen campos vacíos. ', color: 'danger'})
            return false
          }
        } else {
          this.currentItem = 'tab-generales'
          this.$store.commit(SNACK_SHOW, {msg: 'Existen campos vacíos. ', color: 'danger'})
          return false
        }
      },
      send () {
        let send = !this.configuracion.id ? this.axios.post('ts_parametros', this.configuracion) : this.axios.put('ts_parametros/' + this.configuracion.id, this.configuracion)
        send.then((res) => {
          this.$store.commit(SNACK_SHOW, {
            msg: `Se ${this.configuracion.id
              ? 'actualizaron los datos'
              : 'realizó el registro'} de los parametros correctamente.`,
            color: 'success'
          })
          this.configuracion = res.data.data
          this.isEditingP = false
          this.currentItem = 'tab-generales'
        }).catch(e => {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
        })
      },
      close () {
        if (!this.configuracion.id) this.addParametros = false
        // this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
      }

    },
    mounted () {
      this.axios.get('cajas')
        .then((res) => {
          this.complementosFormParametros.cajas = res.data.data
        })
        .catch(e => {
          return false
        })
      this.axios.get('ts_parametros')
        .then((res) => {
          console.log(res)
          if (res.data.exists) {
            this.addParametros = true
            this.configuracion = res.data.data
          } else {
            this.addParametros = false
            this.message = res.data.message
          }
        })
        .catch(e => {
          return false
        })
    }
  }
</script>
