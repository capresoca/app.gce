<template>
  <v-card>
    <v-toolbar dense>
      <v-icon>file_copy</v-icon>
      <v-toolbar-title v-html="(!afitramite.id ? 'Nuevo trámite de afiliación' : 'Edición de trámite de afiliación') + ': ' + (!afitramite.recien_nacido ? 'Régimen subsidiado' : 'Recién nacido')" />
      <v-spacer/>
      <v-toolbar-items v-if="!afitramite.id">
        <v-checkbox
          label="Recién nacido"
          v-model="afitramite.recien_nacido"
          class="mt-2"
          :false-value="0"
          :true-value="1"
        />
      </v-toolbar-items>
    </v-toolbar>
    <component :is="component" :afitramite="afitramite" :parametros="parametros" @change="val => afitramite = val" @endsubmit="val => gofinalizar(val)"/>
    <v-dialog v-model="dialogFinalizar.show" persistent max-width="400">
      <v-card>
        <v-card-title class="grey lighten-3">
          <span class="title">¿Qué desea hacer?</span>
        </v-card-title>
        <v-card-text>
          <v-container fluid class="pa-0">
            <v-layout row wrap>
              <v-flex xs6 v-if="!afitramiteGo.recien_nacido">
                <v-layout align-center>
                  <v-checkbox :class="dialogFinalizar.radicar ? 'pb-4' : ''" :disabled="afitramiteGo && afitramiteGo.recien_nacido === 1" :label="dialogFinalizar.radicar ? '': 'Radicar'" v-model="dialogFinalizar.radicar" hide-details class="shrink mr-2"/>
                  <v-menu
                    v-if="dialogFinalizar.radicar"
                    key="fechaRadicacionmenu"
                    :disabled="afitramiteGo.recien_nacido === 1"
                    ref="Fecha radicación"
                    :close-on-content-click="false"
                    v-model="dialogFinalizar.menuDatex"
                    :nudge-right="40"
                    :return-value.sync="dialogFinalizar.fechaRadicacion"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <v-text-field
                      key="fechaRadicaciontext"
                      slot="activator"
                      v-model="dialogFinalizar.fechaRadicacion"
                      label="Fecha radicación"
                      name="fecha radicación"
                      required
                      :max="maxDateAfiliacion"
                      :min="minDateAfiliacion"
                      v-validate="'required|date_format:DD/MM/YYYY' + (false ? '' : '|date_between:' + minDateAfiliacion + ',' + maxDateAfiliacion + ',true')"
                      :error-messages="errors.collect('fecha radicación')"
                      readonly
                    ></v-text-field>
                    <v-date-picker 
                    locale="es-co"
                      key="fechaRadicaciondatepicker"
                      v-model="dialogFinalizar.fechaRadicacion"
                      @input="$refs['Fecha radicación'].save(dialogFinalizar.fechaRadicacion)"
                      @change="() => {
                        let index = $validator.errors.items.findIndex(x => x.field === 'fecha radicación')
                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                        }"
                    ></v-date-picker>
                  </v-menu>
                </v-layout>
              </v-flex>
              <v-flex :xs6="!afitramiteGo.recien_nacido" :xs12="afitramiteGo.recien_nacido">
                <v-checkbox v-model="dialogFinalizar.imprimir" label="Imprimir" class="ml-2"/>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-divider/>
        <v-card-actions>
          <v-spacer/>
          <v-btn flat @click="dialogFinalizar.show = false" :loading="loadingEndBtn">Cancelar</v-btn>
          <v-btn color="primary" flat @click="finalizar" :loading="loadingEndBtn">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {AFITRAMITE_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'RegistroAfiliacion',
    props: ['parametros'],
    data () {
      return {
        loadingEndBtn: false,
        dialogFinalizar: {
          show: false,
          imprimir: false,
          radicar: false,
          fechaRadicacion: this.moment().format('DD/MM/YYYY'),
          menuDatex: false
        },
        component: null,
        ips: null,
        afitramite: {},
        afitramiteGo: {},
        maxDateAfiliacion: new Date().toISOString().substr(0, 10),
        minDateAfiliacion: '01/01/1910',
        makeAfitramite: {
          idAfiliadoValido: null,
          afiliado: null,
          anexos: [],
          nf_ciiu_id: null,
          as_afiliado_id: null,
          as_archivoreporte_id: null,
          as_clasecotizante_id: null,
          as_regimene_id: 2,
          as_tipafiliacione_id: null,
          as_tipafiliado_id: 2,
          codigo_entidad: null,
          declaraciones: [],
          estado: 'Registrado',
          fecha_anulacion: null,
          fecha_radicacion: null,
          ficha_sisben: null,
          gs_usuario_id: null,
          ibc: null,
          id: null,
          ips: null,
          no_radicado: null,
          nucleo_familiar: [],
          puntaje_sisben: null,
          recien_nacido: 0,
          rs_ips_id: null,
          usuanula_id: null,
          afiliado_pagador: {
            gn_municipio_id: null,
            arl: null,
            as_afiliado_id: null,
            as_arl_id: null,
            as_formtrasmov_id: null,
            as_pagadore_id: null,
            fecha_vinculacion: null,
            ibc: null,
            id: null,
            pagador: null
          },
          usuradica_id: null
        }
      }
    },
    watch: {
      'afitramite.recien_nacido' (val) {
        this.afitramite.recien_nacido = val
        this.dialogFinalizar.fechaRadicacion = this.moment().format('DD/MM/YYYY')
        this.afitramite.afiliado = null
        this.component = () => import('@/components/misional/aseguramiento/tramites/afiliaciones/RegistroAfiliacion' + (val ? 'RN' : 'S'))
      }
    },
    beforeMount () {
      this.refresh()
    },
    mounted () {
      if (this.parametros.entidad !== null && this.parametros.entidad.id !== null) this.getRegistro(this.parametros.entidad.id)
    },
    methods: {
      gofinalizar (afi) {
        this.afitramiteGo = afi
        if (this.afitramiteGo.recien_nacido) this.dialogFinalizar.radicar = true
        this.dialogFinalizar.show = true
      },
      async finalizar () {
        this.loadingEndBtn = true
        if (this.dialogFinalizar.radicar) {
          this.afitramiteGo.estado = 'Radicado'
          this.afitramiteGo.fecha_radicacion = this.dialogFinalizar.fechaRadicacion
        }
        if (await this.submitAfitramite()) {
          this.$store.commit(SNACK_SHOW, {msg: 'El trámite de afiliación se ha ' + (this.afitramiteGo.estado === 'Radicado' ? 'radicado' : 'guardado') + ' satisfactoriamente.', color: 'success'})
        }
        this.dialogFinalizar.show = false
        this.loadingEndBtn = false
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
        if (this.dialogFinalizar.imprimir) {
          this.axios.get('ruta-pdf-formafiliacion?id=' + this.value.id)
            .then(response => {
              if (response.data !== '') {
                window.open(response.data, '_blank')
              }
            })
            .catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al generar el documento. ', error: e})
            })
        }
      },
      submitAfitramite () {
        const typeRequest = this.afitramiteGo.id ? 'editar' : 'crear'
        return new Promise((resolve, reject) => {
          let request = typeRequest === 'editar' ? this.axios.put(`formafiliaciones/${this.afitramiteGo.id}`, this.afitramiteGo) : this.axios.post('formafiliaciones', this.afitramiteGo)
          request
            .then(response => {
              this.value = response.data.formafiliacion
              this.$store.commit(AFITRAMITE_RELOAD_ITEM, {item: response.data.formafiliacion_o, estado: typeRequest, key: this.parametros.key})
              this.$store.commit('reloadTable', 'tableItemAfilliatum')
              resolve(true)
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro del trámite de afiliación. ', error: e})
              resolve(false)
            })
        })
      },
      refresh () {
        this.afitramite = JSON.parse(JSON.stringify(this.makeAfitramite))
        this.$validator.reset()
      },
      getRegistro (id) {
        this.axios.get('formafiliaciones/' + id)
          .then(response => {
            if (response.data !== '') {
              response.data.data.idAfiliadoValido = response.data.data.afiliado.id
              this.afitramite = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el trámite de afiliación. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>
</style>
